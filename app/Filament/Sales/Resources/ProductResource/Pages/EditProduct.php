<?php

namespace App\Filament\Sales\Resources\ProductResource\Pages;

use App\Filament\Sales\Resources\ProductResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Ürünlere Dön')
                ->icon('heroicon-o-arrow-left')
                ->url(ProductResource::getUrl('index'))
                ->color('gray'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Ürün güncellendi';
    }
}
