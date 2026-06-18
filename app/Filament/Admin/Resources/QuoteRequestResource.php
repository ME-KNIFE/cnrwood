<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\QuoteRequestResource\Pages;
use App\Filament\Admin\Resources\QuoteRequestResource\RelationManagers\QuoteRequestItemsRelationManager;
use App\Filament\Concerns\AuthorizesByRole;
use App\Models\AdminUser;
use App\Models\QuoteRequest;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuoteRequestResource extends Resource
{
    use AuthorizesByRole;

    protected static ?string $model = QuoteRequest::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Teklif Talepleri';
    protected static ?string $modelLabel = 'Teklif Talebi';
    protected static ?string $pluralModelLabel = 'Teklif Talepleri';
    protected static string | \UnitEnum | null $navigationGroup = 'Satış';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'reference_number';

    // ── RBAC ─────────────────────────────────────────────────────────────────
    // Created by customers via the public site; admin only triages.
    // canCreate() is hard overridden so that even super_admin cannot
    // trigger a non-existent /create route from the UI.
    protected static array $viewRoles   = ['sales_manager', 'support'];
    protected static array $createRoles = [];
    protected static array $editRoles   = ['sales_manager', 'support'];
    protected static array $deleteRoles = [];

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('İletişim Bilgileri')
                ->schema([
                    Placeholder::make('reference_number')->label('Referans No')
                        ->content(fn ($record) => $record?->reference_number ?? '—'),
                    Placeholder::make('type')->label('Tip')
                        ->content(fn ($record) => match ($record?->type) {
                            'sandik'  => 'Sandık Hesaplama',
                            'product' => 'Ürün Teklifi',
                            'general' => 'Genel Talep',
                            default   => $record?->type ?? '—',
                        }),
                    Placeholder::make('contact_name')->label('Ad Soyad')
                        ->content(fn ($record) => $record?->contact_name ?? '—'),
                    Placeholder::make('contact_email')->label('E-posta')
                        ->content(fn ($record) => $record?->contact_email ?? '—'),
                    Placeholder::make('contact_phone')->label('Telefon')
                        ->content(fn ($record) => $record?->contact_phone ?? '—'),
                    Placeholder::make('company_name')->label('Firma')
                        ->content(fn ($record) => $record?->company_name ?? '—'),
                    Placeholder::make('message')->label('Mesaj')
                        ->content(fn ($record) => $record?->message ?? '—')
                        ->columnSpanFull(),
                ])->columns(3),
            Section::make('Sandık Hesaplama Bilgileri')
                ->visible(fn ($record) => $record?->type === 'sandik' && $record?->sandikCalculation !== null)
                ->schema([
                    Placeholder::make('sandik_dimensions')->label('Ölçüler (U×G×Y / Ağırlık)')
                        ->content(fn ($record) => $record?->sandikCalculation
                            ? $record->sandikCalculation->getDimensionsSummary()
                            : '—'),
                    Placeholder::make('sandik_crate_type')->label('Sandık Tipi')
                        ->content(fn ($record) => $record?->sandikCalculation?->getCrateTypeLabel() ?? '—'),
                    Placeholder::make('sandik_quantity')->label('Adet')
                        ->content(fn ($record) => $record?->sandikCalculation?->quantity ?? '—'),
                    Placeholder::make('sandik_material')->label('Malzeme')
                        ->content(fn ($record) => $record?->sandikCalculation?->material ?? '—'),
                    Placeholder::make('sandik_shipping_type')->label('Sevkiyat')
                        ->content(fn ($record) => match ($record?->sandikCalculation?->shipping_type) {
                            'ic'      => 'İç (Yurtiçi)',
                            'ihracat' => 'İhracat',
                            default   => '—',
                        }),
                    Placeholder::make('sandik_destination')->label('Varış')
                        ->content(fn ($record) => trim(implode(' / ', array_filter([
                            $record?->sandikCalculation?->destination_city,
                            $record?->sandikCalculation?->destination_country,
                        ]))) ?: '—'),
                    Placeholder::make('sandik_requirements')->label('Teknik Gereksinimler')
                        ->content(function ($record) {
                            if (! $record?->sandikCalculation) {
                                return '—';
                            }
                            $flags = [];
                            if ($record->sandikCalculation->requires_ispm15) {
                                $flags[] = 'ISPM-15';
                            }
                            if ($record->sandikCalculation->requires_forklift) {
                                $flags[] = 'Forklift';
                            }
                            if ($record->sandikCalculation->requires_crane) {
                                $flags[] = 'Vinç';
                            }
                            return $flags ? implode(', ', $flags) : 'Yok';
                        }),
                    Placeholder::make('sandik_notes')->label('Sandık Notu')
                        ->content(fn ($record) => $record?->sandikCalculation?->notes ?: '—')
                        ->columnSpanFull(),
                ])->columns(3),
            Section::make('Durum & Atama')
                ->schema([
                    Select::make('status')->label('Durum')
                        ->options([
                            'yeni'              => 'Yeni',
                            'inceleniyor'       => 'İnceleniyor',
                            'teklif_gonderildi' => 'Teklif Gönderildi',
                            'kazanildi'         => 'Kazanıldı',
                            'kaybedildi'        => 'Kaybedildi',
                        ])->required(),
                    Select::make('assigned_to')->label('Atanan Kişi')
                        ->options(fn () => AdminUser::where('is_active', true)
                            ->whereIn('role', ['super_admin', 'sales_manager', 'support'])
                            ->get()
                            ->mapWithKeys(fn ($u) => [$u->id => $u->name . ' (' . $u->role . ')'])
                            ->toArray()
                        )
                        ->nullable()->searchable(),
                    Textarea::make('admin_notes')->label('İç Not')->rows(3)->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference_number')->label('Ref No')->searchable()->sortable(),
                TextColumn::make('contact_name')->label('Ad Soyad')->searchable()->sortable(),
                TextColumn::make('contact_email')->label('E-posta')->searchable()
                    ->toggleable(),
                TextColumn::make('contact_phone')->label('Telefon')
                    ->toggleable()
                    ->default('—'),
                BadgeColumn::make('type')->label('Tip')
                    ->colors(['info' => 'sandik', 'warning' => 'product', 'gray' => 'general'])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'sandik'  => 'Sandık',
                        'product' => 'Ürün',
                        'general' => 'Genel',
                        default   => $state,
                    }),
                BadgeColumn::make('status')->label('Durum')
                    ->colors([
                        'warning' => 'yeni',
                        'info'    => 'inceleniyor',
                        'primary' => 'teklif_gonderildi',
                        'success' => 'kazanildi',
                        'danger'  => 'kaybedildi',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'yeni'              => 'Yeni',
                        'inceleniyor'       => 'İnceleniyor',
                        'teklif_gonderildi' => 'Teklif Gönderildi',
                        'kazanildi'         => 'Kazanıldı',
                        'kaybedildi'        => 'Kaybedildi',
                        default             => $state,
                    }),
                TextColumn::make('assignedTo.name')->label('Atanan')->default('—'),
                TextColumn::make('created_at')->label('Tarih')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->label('Durum')
                    ->options([
                        'yeni'              => 'Yeni',
                        'inceleniyor'       => 'İnceleniyor',
                        'teklif_gonderildi' => 'Teklif Gönderildi',
                        'kazanildi'         => 'Kazanıldı',
                        'kaybedildi'        => 'Kaybedildi',
                    ]),
                SelectFilter::make('type')->label('Tip')
                    ->options(['sandik' => 'Sandık', 'product' => 'Ürün', 'general' => 'Genel']),
            ])
            ->actions([EditAction::make()->label('Yönet')])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array
    {
        return [
            QuoteRequestItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuoteRequests::route('/'),
            'edit'  => Pages\EditQuoteRequest::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
