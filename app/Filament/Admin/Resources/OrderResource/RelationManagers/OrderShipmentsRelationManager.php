<?php

namespace App\Filament\Admin\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Read-only shipments list.
 * Shipment records are managed by warehouse workflows (not yet implemented).
 */
class OrderShipmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'shipments';

    protected static ?string $title = 'Kargo / Sevkiyat';

    protected static ?string $modelLabel = 'Sevkiyat';

    protected static ?string $pluralModelLabel = 'Sevkiyatlar';

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
            ->recordTitleAttribute('tracking_number')
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('cargo_company')
                    ->label('Kargo Firması')
                    ->default('—'),

                TextColumn::make('tracking_number')
                    ->label('Takip No')
                    ->default('—')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'hazirlanıyor'    => 'gray',
                        'kargoya_verildi' => 'info',
                        'teslim_edildi'   => 'success',
                        'iade'            => 'danger',
                        default           => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'hazirlanıyor'    => 'Hazırlanıyor',
                        'kargoya_verildi' => 'Kargoya Verildi',
                        'teslim_edildi'   => 'Teslim Edildi',
                        'iade'            => 'İade',
                        default           => $state,
                    }),

                TextColumn::make('shipped_at')
                    ->label('Gönderim')
                    ->dateTime('d.m.Y H:i')
                    ->default('—')
                    ->sortable(),

                TextColumn::make('estimated_delivery')
                    ->label('Tahmini Teslim')
                    ->date('d.m.Y')
                    ->default('—')
                    ->toggleable(),

                TextColumn::make('delivered_at')
                    ->label('Teslim')
                    ->dateTime('d.m.Y H:i')
                    ->default('—')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
