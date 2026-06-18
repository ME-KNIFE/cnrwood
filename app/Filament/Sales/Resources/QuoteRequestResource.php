<?php

namespace App\Filament\Sales\Resources;

use App\Filament\Sales\Resources\QuoteRequestResource\Pages;
use App\Models\QuoteRequest;
use Filament\Actions\BulkActionGroup;
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
    protected static ?string $model = QuoteRequest::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Teklif Talepleri';
    protected static ?string $modelLabel = 'Teklif Talebi';
    protected static ?string $pluralModelLabel = 'Teklif Talepleri';
    protected static string | \UnitEnum | null $navigationGroup = 'Satış';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'reference_number';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('İletişim Bilgileri')
                ->schema([
                    Placeholder::make('reference_number')->label('Ref No')
                        ->content(fn ($record) => $record?->reference_number ?? '—'),
                    Placeholder::make('contact_name')->label('Ad Soyad')
                        ->content(fn ($record) => $record?->contact_name ?? '—'),
                    Placeholder::make('contact_email')->label('E-posta')
                        ->content(fn ($record) => $record?->contact_email ?? '—'),
                    Placeholder::make('message')->label('Mesaj')
                        ->content(fn ($record) => $record?->message ?? '—')
                        ->columnSpanFull(),
                ])->columns(3),
            Section::make('Durum Güncelle')
                ->schema([
                    Select::make('status')->label('Durum')
                        ->options([
                            'yeni'              => 'Yeni',
                            'inceleniyor'       => 'İnceleniyor',
                            'teklif_gonderildi' => 'Teklif Gönderildi',
                            'kazanildi'         => 'Kazanıldı',
                            'kaybedildi'        => 'Kaybedildi',
                        ])->required(),
                    Textarea::make('admin_notes')->label('Not')->rows(2)->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference_number')->label('Ref No')->searchable()->sortable(),
                TextColumn::make('contact_name')->label('Ad Soyad')->searchable(),
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
                TextColumn::make('created_at')->label('Tarih')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->label('Durum')
                    ->options([
                        'yeni'              => 'Yeni',
                        'inceleniyor'       => 'İnceleniyor',
                        'teklif_gonderildi' => 'Teklif Gönderildi',
                    ]),
            ])
            ->actions([EditAction::make()->label('Yönet')])
            ->bulkActions([BulkActionGroup::make([])]);
    }

    public static function getRelations(): array { return []; }

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

    public static function canCreate(): bool { return false; }
}
