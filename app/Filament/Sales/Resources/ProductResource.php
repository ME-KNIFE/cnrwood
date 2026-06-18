<?php

namespace App\Filament\Sales\Resources;

use App\Filament\Sales\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
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

    public static function form(Schema $schema): Schema { return $schema->components([]); }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')->label('SKU')->searchable()->sortable(),
                TextColumn::make('name')
                    ->label('İsim')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['tr'] ?? '-') : ($state ?? '-'))
                    ->searchable(query: fn (Builder $query, string $search): Builder =>
                        $query->where('name', 'like', "%{$search}%")
                    ),
                BadgeColumn::make('product_type')->label('Tip')
                    ->colors(['success' => 'buyable', 'warning' => 'quote_only'])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'buyable'    => 'Satılık',
                        'quote_only' => 'Sadece Teklif',
                        default      => $state,
                    }),
                TextColumn::make('price')->label('Fiyat')->money('TRY')->default('—'),
                TextColumn::make('stock_quantity')->label('Stok')->default('—'),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->filters([
                SelectFilter::make('product_type')->label('Tip')
                    ->options(['buyable' => 'Satılık', 'quote_only' => 'Sadece Teklif']),
            ]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return ['index' => Pages\ListProducts::route('/')];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->where('is_active', true);
    }

    public static function canCreate(): bool { return false; }
}
