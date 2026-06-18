<?php

namespace App\Filament\Admin\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Read-only relation manager for order_items.
 * Order items are immutable snapshots — never edit or delete here.
 */
class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Sipariş Kalemleri';

    protected static ?string $modelLabel = 'Kalem';

    protected static ?string $pluralModelLabel = 'Kalemler';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function form(Schema $schema): Schema
    {
        // No form — this RM is display only.
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_name')
            ->columns([
                TextColumn::make('product_name')
                    ->label('Ürün')
                    ->wrap()
                    ->searchable(),

                TextColumn::make('product_sku')
                    ->label('SKU')
                    ->toggleable(),

                TextColumn::make('variant_name')
                    ->label('Varyant')
                    ->default('—')
                    ->toggleable(),

                TextColumn::make('quantity')
                    ->label('Adet')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('unit_price')
                    ->label('Birim Fiyat')
                    ->money('TRY')
                    ->sortable(),

                TextColumn::make('total_price')
                    ->label('Tutar')
                    ->money('TRY')
                    ->sortable(),
            ])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
