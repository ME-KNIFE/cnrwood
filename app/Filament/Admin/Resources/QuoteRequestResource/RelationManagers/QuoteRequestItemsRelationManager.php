<?php

namespace App\Filament\Admin\Resources\QuoteRequestResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Read-only items list for a quote request.
 * Items are submitted by the customer; admins only review them here.
 */
class QuoteRequestItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Talep Edilen Ürünler';

    protected static ?string $modelLabel = 'Kalem';

    protected static ?string $pluralModelLabel = 'Kalemler';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function form(Schema $schema): Schema
    {
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

                TextColumn::make('quantity')
                    ->label('Adet')
                    ->default('—')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('notes')
                    ->label('Not')
                    ->default('—')
                    ->wrap()
                    ->limit(120),

                TextColumn::make('created_at')
                    ->label('Eklenme')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'asc')
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
