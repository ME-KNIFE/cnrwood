<?php

namespace App\Filament\Admin\Resources\FairResource\Pages;

use App\Filament\Admin\Resources\FairResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFair extends EditRecord
{
    protected static string $resource = FairResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
