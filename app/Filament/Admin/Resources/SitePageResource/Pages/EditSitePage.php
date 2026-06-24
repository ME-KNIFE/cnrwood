<?php

namespace App\Filament\Admin\Resources\SitePageResource\Pages;

use App\Filament\Admin\Resources\SitePageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSitePage extends EditRecord
{
    protected static string $resource = SitePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn () => SitePageResource::canDelete($this->record)),
        ];
    }
}
