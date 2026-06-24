<?php

namespace App\Filament\Sales\Resources;

use App\Filament\Sales\Resources\ProductResource\Pages;
use App\Filament\Sales\Resources\ProductResource\RelationManagers\ProductImagesRelationManager;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Ürünler';
    protected static ?string $modelLabel = 'Ürün';
    protected static ?string $pluralModelLabel = 'Ürünler';
    protected static string | \UnitEnum | null $navigationGroup = 'Ürün Yönetimi';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Temel Bilgiler')
                ->schema([
                    TextInput::make('name.tr')
                        ->label('Ürün Adı (TR)')
                        ->required(),
                    TextInput::make('sku')
                        ->label('SKU')
                        ->disabled(),
                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ])->columns(2),

            Section::make('Fiyat & Stok')
                ->schema([
                    TextInput::make('price')
                        ->label('Fiyat (TL)')
                        ->numeric()
                        ->minValue(0)
                        ->required(),
                    TextInput::make('stock_quantity')
                        ->label('Stok Adedi')
                        ->numeric()
                        ->minValue(0)
                        ->required(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Isim')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['tr'] ?? '-') : ($state ?? '-'))
                    ->searchable(query: fn (Builder $query, string $search): Builder =>
                        $query->where('name', 'like', "%{$search}%")
                    ),
                TextColumn::make('price')
                    ->label('Fiyat')
                    ->money('TRY')
                    ->default('-'),
                TextColumn::make('stock_quantity')
                    ->label('Stok')
                    ->default('-')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->actions([
                EditAction::make(),
                Action::make('update_stock')
                    ->label('Stok')
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->form([
                        TextInput::make('stock_quantity')
                            ->label('Yeni Stok Adedi')
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ])
                    ->fillForm(fn (Product $record): array => [
                        'stock_quantity' => $record->stock_quantity,
                    ])
                    ->action(function (Product $record, array $data): void {
                        $record->update(['stock_quantity' => (int) $data['stock_quantity']]);
                        Notification::make()
                            ->title('Stok guncellendi')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProductImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'edit'  => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->where('product_type', 'buyable')
            ->where('is_active', true);
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
