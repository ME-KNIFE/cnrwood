<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CouponResource\Pages;
use App\Filament\Concerns\AuthorizesByRole;
use App\Models\Coupon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    use AuthorizesByRole;

    protected static ?string $model = Coupon::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Kuponlar';
    protected static ?string $modelLabel = 'Kupon';
    protected static ?string $pluralModelLabel = 'Kuponlar';
    protected static string|\UnitEnum|null $navigationGroup = 'Pazarlama';
    protected static ?int $navigationSort = 1;

    protected static array $viewRoles   = ['admin', 'sales_manager'];
    protected static array $createRoles = ['admin', 'sales_manager'];
    protected static array $editRoles   = ['admin', 'sales_manager'];
    protected static array $deleteRoles = ['admin'];

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Kupon Bilgileri')
                ->schema([
                    TextInput::make('code')
                        ->label('Kupon Kodu')
                        ->required()
                        ->maxLength(50)
                        ->unique(table: 'coupons', column: 'code', ignoreRecord: true)
                        ->extraInputAttributes(['style' => 'text-transform:uppercase'])
                        ->dehydrateStateUsing(fn (string $state) => strtoupper(trim($state))),
                    Select::make('type')
                        ->label('İndirim Tipi')
                        ->options(['percentage' => 'Yüzde (%)', 'fixed' => 'Sabit Tutar (TL)'])
                        ->required(),
                    TextInput::make('value')
                        ->label('İndirim Değeri')
                        ->numeric()
                        ->minValue(0.01)
                        ->required(),
                    TextInput::make('min_order_amount')
                        ->label('Minimum Sipariş Tutarı (TL)')
                        ->numeric()
                        ->minValue(0)
                        ->nullable(),
                    TextInput::make('max_uses')
                        ->label('Maksimum Kullanım')
                        ->numeric()
                        ->integer()
                        ->minValue(1)
                        ->nullable()
                        ->helperText('Boş bırakırsanız sınırsız.'),
                    TextInput::make('used_count')
                        ->label('Kullanım Sayısı')
                        ->numeric()
                        ->integer()
                        ->default(0)
                        ->disabled(fn ($context) => $context === 'create'),
                    DateTimePicker::make('expires_at')
                        ->label('Son Kullanma Tarihi')
                        ->nullable(),
                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kupon Kodu')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('type')
                    ->label('Tip')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => $state === 'percentage' ? 'Yüzde' : 'Sabit'),
                TextColumn::make('value')
                    ->label('Değer')
                    ->formatStateUsing(function (Coupon $record): string {
                        return $record->type === 'percentage'
                            ? '%' . number_format((float) $record->value, 0)
                            : number_format((float) $record->value, 2, ',', '.') . ' TL';
                    }),
                TextColumn::make('min_order_amount')
                    ->label('Min. Tutar')
                    ->money('TRY')
                    ->placeholder('—'),
                TextColumn::make('used_count')
                    ->label('Kullanım')
                    ->sortable(),
                TextColumn::make('max_uses')
                    ->label('Max. Kullanım')
                    ->placeholder('Sınırsız'),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('expires_at')
                    ->label('Son Kullanma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder('—'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_active')->label('Aktif'),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit'   => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
