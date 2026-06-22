<?php

namespace App\Filament\Admin\Resources\FairResource\Pages;

use App\Filament\Admin\Resources\FairResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFairs extends ListRecords
{
    protected static string $resource = FairResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
