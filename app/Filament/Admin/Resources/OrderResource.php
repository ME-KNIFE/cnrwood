<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Filament\Admin\Resources\OrderResource\RelationManagers\OrderItemsRelationManager;
use App\Filament\Admin\Resources\OrderResource\RelationManagers\OrderPaymentsRelationManager;
use App\Filament\Admin\Resources\OrderResource\RelationManagers\OrderShipmentsRelationManager;
use App\Filament\Concerns\AuthorizesByRole;
use App\Models\Order;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class OrderResource extends Resource
{
    use AuthorizesByRole;

    protected static ?string $model = Order::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Siparişler';
    protected static ?string $modelLabel = 'Sipariş';
    protected static ?string $pluralModelLabel = 'Siparişler';
    protected static string | \UnitEnum | null $navigationGroup = 'Satış';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'order_number';

    // ── RBAC ─────────────────────────────────────────────────────────────────
    // Manual order creation is intentionally disabled (orders are created
    // by the checkout flow). $createRoles is empty AND canCreate() is hard
    // overridden so that even super_admin cannot trigger a non-existent
    // /create route from the UI.
    protected static array $viewRoles   = ['sales_manager'];
    protected static array $createRoles = [];
    protected static array $editRoles   = ['sales_manager'];
    protected static array $deleteRoles = [];

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Sipariş Bilgileri')
                ->schema([
                    Placeholder::make('order_number')->label('Sipariş No')
                        ->content(fn ($record) => $record?->order_number ?? '—'),
                    Placeholder::make('customer_name')->label('Müşteri Adı')
                        ->content(fn ($record) => $record?->customer_name ?? '—'),
                    Placeholder::make('customer_email')->label('E-posta')
                        ->content(fn ($record) => $record?->customer_email ?? '—'),
                    Placeholder::make('customer_phone')->label('Telefon')
                        ->content(fn ($record) => $record?->customer_phone ?? '—'),
                    Placeholder::make('payment_method')->label('Ödeme Yöntemi')
                        ->content(fn ($record) => match ($record?->payment_method) {
                            'havale_eft'  => 'Havale / EFT',
                            'kredi_karti' => 'Kredi Kartı',
                            default       => $record?->payment_method ?? '—',
                        }),
                    Placeholder::make('created_at')->label('Sipariş Tarihi')
                        ->content(fn ($record) => $record?->created_at?->format('d.m.Y H:i') ?? '—'),
                ])->columns(3),

            Section::make('Teslimat Adresi')
                ->schema([
                    Placeholder::make('shipping_address_display')
                        ->label('Teslimat Adresi')
                        ->content(function ($record) {
                            if (! $record?->shipping_address) {
                                return '—';
                            }
                            $a     = $record->shipping_address;
                            $lines = array_filter([
                                $a['full_name'] ?? null,
                                $a['phone'] ?? null,
                                $a['address_line1'] ?? null,
                                $a['address_line2'] ?? null,
                                trim(($a['district'] ?? '') . ' ' . ($a['city'] ?? '')),
                                isset($a['postal_code']) ? 'Posta Kodu: ' . $a['postal_code'] : null,
                                $a['country'] ?? 'Türkiye',
                            ]);
                            return new HtmlString(implode('<br>', array_values($lines)));
                        })
                        ->columnSpanFull(),
                ]),

            Section::make('Finansal Detaylar')
                ->schema([
                    Placeholder::make('subtotal')->label('Ara Toplam')
                        ->content(fn ($record) => $record ? '₺' . number_format($record->subtotal, 2) : '—'),
                    Placeholder::make('discount_amount')->label('İndirim')
                        ->content(fn ($record) => $record ? '−₺' . number_format($record->discount_amount, 2) : '—'),
                    Placeholder::make('coupon_code')->label('Kupon Kodu')
                        ->content(fn ($record) => $record?->coupon_code ?? '—'),
                    Placeholder::make('shipping_cost')->label('Kargo Ücreti')
                        ->content(fn ($record) => $record ? '₺' . number_format($record->shipping_cost, 2) : '—'),
                    Placeholder::make('total')->label('Genel Toplam')
                        ->content(fn ($record) => $record ? '₺' . number_format($record->total, 2) : '—'),
                ])->columns(3),

            Section::make('Durum Güncelle')
                ->schema([
                    Select::make('status')->label('Sipariş Durumu')
                        ->options(function ($record) {
                            $all = [
                                'beklemede'        => 'Beklemede',
                                'odeme_bekleniyor' => 'Ödeme Bekleniyor',
                                'islemde'          => 'İşlemde',
                                'kargoya_verildi'  => 'Kargoya Verildi',
                                'teslim_edildi'    => 'Teslim Edildi',
                                'iptal_edildi'     => 'İptal Edildi',
                                'iade_edildi'      => 'İade Edildi',
                            ];
                            if (! $record) {
                                return $all;
                            }
                            $transitions = [
                                'beklemede'        => ['beklemede', 'odeme_bekleniyor', 'islemde', 'iptal_edildi'],
                                'odeme_bekleniyor' => ['odeme_bekleniyor', 'islemde', 'iptal_edildi'],
                                'islemde'          => ['islemde', 'kargoya_verildi', 'iptal_edildi'],
                                'kargoya_verildi'  => ['kargoya_verildi', 'teslim_edildi'],
                                'teslim_edildi'    => ['teslim_edildi', 'iade_edildi'],
                                'iptal_edildi'     => ['iptal_edildi'],
                                'iade_edildi'      => ['iade_edildi'],
                            ];
                            $allowed = $transitions[$record->status] ?? array_keys($all);
                            return array_intersect_key($all, array_flip($allowed));
                        })
                        ->required(),
                    Select::make('payment_status')->label('Ödeme Durumu')
                        ->options([
                            'beklemede'        => 'Beklemede',
                            'odeme_bekleniyor' => 'Ödeme Bekleniyor',
                            'odendi'           => 'Ödendi',
                            'basarisiz'        => 'Başarısız',
                            'iptal_edildi'     => 'İptal Edildi',
                            'iade_edildi'      => 'İade Edildi',
                        ])->required(),
                    Textarea::make('admin_notes')->label('Yönetici Notu')->rows(3)->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')->label('Sipariş No')->searchable()->sortable(),
                TextColumn::make('customer_name')->label('Müşteri')->searchable()->sortable(),
                TextColumn::make('customer_email')->label('E-posta')->searchable()
                    ->toggleable(),
                TextColumn::make('customer_phone')->label('Telefon')
                    ->toggleable()
                    ->default('—'),
                BadgeColumn::make('status')->label('Durum')
                    ->colors([
                        'gray'    => 'beklemede',
                        'warning' => ['odeme_bekleniyor', 'islemde'],
                        'info'    => 'kargoya_verildi',
                        'success' => 'teslim_edildi',
                        'danger'  => ['iptal_edildi', 'iade_edildi'],
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'beklemede'        => 'Beklemede',
                        'odeme_bekleniyor' => 'Ödeme Bekleniyor',
                        'islemde'          => 'İşlemde',
                        'kargoya_verildi'  => 'Kargoya Verildi',
                        'teslim_edildi'    => 'Teslim Edildi',
                        'iptal_edildi'     => 'İptal Edildi',
                        'iade_edildi'      => 'İade Edildi',
                        default            => $state,
                    }),
                BadgeColumn::make('payment_status')->label('Ödeme')
                    ->colors(['gray' => 'beklemede', 'success' => 'odendi', 'danger' => ['basarisiz', 'iade_edildi']])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'beklemede'   => 'Beklemede',
                        'odendi'      => 'Ödendi',
                        'basarisiz'   => 'Başarısız',
                        'iade_edildi' => 'İade Edildi',
                        default       => $state,
                    }),
                TextColumn::make('total')->label('Toplam')->money('TRY')->sortable(),
                TextColumn::make('payment_method')->label('Ödeme Yöntemi')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'havale_eft'  => 'Havale/EFT',
                        'kredi_karti' => 'Kredi Kartı',
                        default       => $state,
                    })
                    ->toggleable(),
                TextColumn::make('created_at')->label('Tarih')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->label('Durum')
                    ->options([
                        'beklemede'        => 'Beklemede',
                        'odeme_bekleniyor' => 'Ödeme Bekleniyor',
                        'islemde'          => 'İşlemde',
                        'kargoya_verildi'  => 'Kargoya Verildi',
                        'teslim_edildi'    => 'Teslim Edildi',
                        'iptal_edildi'     => 'İptal Edildi',
                        'iade_edildi'      => 'İade Edildi',
                    ]),
                SelectFilter::make('payment_status')->label('Ödeme Durumu')
                    ->options([
                        'beklemede'        => 'Beklemede',
                        'odeme_bekleniyor' => 'Ödeme Bekleniyor',
                        'odendi'           => 'Ödendi',
                        'basarisiz'        => 'Başarısız',
                        'iptal_edildi'     => 'İptal Edildi',
                        'iade_edildi'      => 'İade Edildi',
                    ]),
                Filter::make('created_at')
                    ->label('Tarih Aralığı')
                    ->form([
                        DatePicker::make('created_from')->label('Başlangıç Tarihi'),
                        DatePicker::make('created_until')->label('Bitiş Tarihi'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
                            ->when($data['created_until'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '<=', $v));
                    }),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([])]);
    }

    public static function getRelations(): array
    {
        return [
            OrderItemsRelationManager::class,
            OrderPaymentsRelationManager::class,
            OrderShipmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit'  => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
