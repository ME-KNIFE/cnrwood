<?php

namespace App\Filament\Sales\Resources\ProductCategoryResource\Pages;

use App\Filament\Sales\Resources\ProductCategoryResource;
use Filament\Resources\Pages\ListRecords;

class ListProductCategories extends ListRecords
{
    protected static string $resource = ProductCategoryResource::class;
    protected function getHeaderActions(): array { return []; }
}
