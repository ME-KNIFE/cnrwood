<?php

namespace App\Filament\Sales\Resources\ProductResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Ürün Görselleri';

    protected static ?string $modelLabel = 'Görsel';

    protected static ?string $pluralModelLabel = 'Görseller';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            FileUpload::make('url')
                ->label('Görsel Dosyası')
                ->image()
                ->disk('public')
                ->directory('product-images')
                ->visibility('public')
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->maxSize(5120)
                ->imagePreviewHeight('120')
                ->required()
                ->helperText('JPG, PNG veya WebP. Maks. 5 MB.'),

            TextInput::make('alt_text.tr')
                ->label('Alt Metin (TR)')
                ->maxLength(255),

            TextInput::make('alt_text.en')
                ->label('Alt Text (EN)')
                ->maxLength(255),

            Toggle::make('is_primary')
                ->label('Birincil Görsel')
                ->default(false)
                ->helperText('Listelerde ilk gösterilecek görsel.'),

            Toggle::make('is_active')
                ->label('Yayında')
                ->default(true)
                ->helperText('Pasif görseller sitede gösterilmez, silinmez.'),

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
            ->recordTitleAttribute('url')
            ->columns([
                ImageColumn::make('url')
                    ->label('Görsel')
                    ->disk('public')
                    ->height(60)
                    ->width(60)
                    ->square(),

                TextColumn::make('alt_text')
                    ->label('Alt (TR)')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['tr'] ?? '-') : ($state ?? '-'))
                    ->limit(40),

                IconColumn::make('is_primary')
                    ->label('Birincil')
                    ->boolean(),

                IconColumn::make('is_active')
                    ->label('Yayında')
                    ->boolean(),

                TextColumn::make('sort_order')
                    ->label('Sıra')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order')
            ->headerActions([
                CreateAction::make()->label('Görsel Ekle'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
        // Note: Sales role cannot delete images — use admin panel for deletion.
    }
}
