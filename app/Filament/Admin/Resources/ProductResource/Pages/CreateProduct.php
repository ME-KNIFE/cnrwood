<?php

namespace App\Filament\Admin\Resources\ProductResource\Pages;

use App\Filament\Admin\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->nullifyQuoteOnlyPriceStock($data);
    }

    private function nullifyQuoteOnlyPriceStock(array $data): array
    {
        if (($data['product_type'] ?? null) === 'quote_only') {
            $data['price']               = null;
            $data['compare_at_price']    = null;
            $data['stock_quantity']      = null;
            $data['low_stock_threshold'] = null;
            $data['track_stock']         = false;
        }
        return $data;
    }
}
