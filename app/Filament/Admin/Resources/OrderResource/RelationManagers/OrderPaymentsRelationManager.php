<?php

namespace App\Filament\Admin\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Read-only payments transaction log for an order.
 * Payments are written by payment gateways / EFT confirmation flows,
 * never created or edited manually from the admin panel.
 */
class OrderPaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Ödemeler';

    protected static ?string $modelLabel = 'Ödeme';

    protected static ?string $pluralModelLabel = 'Ödemeler';

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
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('method')
                    ->label('Yöntem')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'havale_eft'  => 'Havale/EFT',
                        'kredi_karti' => 'Kredi Kartı',
                        default       => $state,
                    }),

                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'paid'                   => 'success',
                        'pending',
                        'awaiting_bank_transfer' => 'warning',
                        'failed',
                        'cancelled',
                        'refunded'               => 'danger',
                        default                  => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending'                => 'Beklemede',
                        'awaiting_bank_transfer' => 'Havale Bekleniyor',
                        'paid'                   => 'Ödendi',
                        'failed'                 => 'Başarısız',
                        'cancelled'              => 'İptal Edildi',
                        'refunded'               => 'İade Edildi',
                        default                  => $state,
                    }),

                TextColumn::make('amount')
                    ->label('Tutar')
                    ->money('TRY')
                    ->sortable(),

                TextColumn::make('provider')
                    ->label('Sağlayıcı')
                    ->default('—')
                    ->toggleable(),

                TextColumn::make('provider_ref')
                    ->label('Referans')
                    ->default('—')
                    ->toggleable()
                    ->limit(30),

                TextColumn::make('bank_sender_name')
                    ->label('Gönderen')
                    ->default('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('paid_at')
                    ->label('Ödendi')
                    ->dateTime('d.m.Y H:i')
                    ->default('—')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
