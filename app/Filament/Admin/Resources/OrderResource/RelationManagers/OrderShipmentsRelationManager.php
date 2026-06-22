<?php

namespace App\Filament\Admin\Resources\OrderResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderShipmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'shipments';

    protected static ?string $title = 'Kargo / Sevkiyat';

    protected static ?string $modelLabel = 'Sevkiyat';

    protected static ?string $pluralModelLabel = 'Sevkiyatlar';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('status')
                ->label('Durum')
                ->options([
                    'hazirlanıyor'    => 'Hazırlanıyor',
                    'kargoya_verildi' => 'Kargoya Verildi',
                    'teslim_edildi'   => 'Teslim Edildi',
                    'iade'            => 'İade',
                ])
                ->default('hazirlanıyor')
                ->required(),

            TextInput::make('cargo_company')
                ->label('Kargo Firması')
                ->placeholder('Aras, Yurtiçi, MNG, Sürat…')
                ->maxLength(100)
                ->nullable(),

            TextInput::make('tracking_number')
                ->label('Takip Numarası')
                ->maxLength(100)
                ->nullable(),

            TextInput::make('tracking_url')
                ->label('Takip URL')
                ->url()
                ->maxLength(500)
                ->nullable()
                ->placeholder('https://kargotakip.example.com/...')
                ->columnSpanFull(),

            DateTimePicker::make('shipped_at')
                ->label('Gönderim Tarihi')
                ->seconds(false)
                ->nullable(),

            DatePicker::make('estimated_delivery')
                ->label('Tahmini Teslim')
                ->nullable(),

            DateTimePicker::make('delivered_at')
                ->label('Teslim Tarihi')
                ->seconds(false)
                ->nullable(),

            Textarea::make('notes')
                ->label('Notlar')
                ->rows(2)
                ->nullable()
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tracking_number')
            ->columns([
                TextColumn::make('cargo_company')
                    ->label('Kargo Firması')
                    ->default('—'),

                TextColumn::make('tracking_number')
                    ->label('Takip No')
                    ->default('—')
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
            ->headerActions([
                CreateAction::make()->label('Sevkiyat Ekle'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
