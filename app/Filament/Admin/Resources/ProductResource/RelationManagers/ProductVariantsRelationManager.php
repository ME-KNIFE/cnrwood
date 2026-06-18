<?php

namespace App\Filament\Admin\Resources\ProductResource\RelationManagers;

use App\Models\ProductVariant;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class ProductVariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $title = 'Ürün Varyantları';

    protected static ?string $modelLabel = 'Varyant';

    protected static ?string $pluralModelLabel = 'Varyantlar';

    /**
     * Override to bypass the `where('is_active', true)` filter on
     * Product::variants() so admins can see/edit inactive variants too.
     * No model changes required.
     */
    public function getRelationship(): Relation|Builder
    {
        return $this->getOwnerRecord()->hasMany(ProductVariant::class);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name.tr')
                ->label('Varyant Adı (TR)')
                ->required()
                ->maxLength(255),

            TextInput::make('name.en')
                ->label('Variant Name (EN)')
                ->maxLength(255),

            TextInput::make('sku')
                ->label('SKU')
                ->maxLength(255),

            TextInput::make('price_modifier')
                ->label('Fiyat Farkı (₺)')
                ->numeric()
                ->default(0)
                ->helperText('Ana ürün fiyatına eklenir. Sadece teklif tipindeki ürünlerde dikkate alınmaz.'),

            TextInput::make('stock_quantity')
                ->label('Stok Adedi')
                ->numeric()
                ->minValue(0)
                ->default(0),

            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),

            TextInput::make('sort_order')
                ->label('Sıralama')
                ->numeric()
                ->minValue(0)
                ->default(0),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('sku')
            ->columns([
                TextColumn::make('name')
                    ->label('Varyant')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['tr'] ?? '-') : ($state ?? '-'))
                    ->searchable(query: fn (Builder $query, string $search): Builder =>
                        $query->where('name', 'like', "%{$search}%")
                    ),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('price_modifier')
                    ->label('Fiyat Farkı')
                    ->money('TRY')
                    ->sortable(),

                TextColumn::make('stock_quantity')
                    ->label('Stok')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                TextColumn::make('sort_order')
                    ->label('Sıralama')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order')
            ->headerActions([
                CreateAction::make()->label('Varyant Ekle'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
