<?php

namespace App\Filament\Admin\Resources\QuoteRequestResource\Pages;

use App\Filament\Admin\Resources\QuoteRequestResource;
use Filament\Resources\Pages\EditRecord;

class EditQuoteRequest extends EditRecord
{
    protected static string $resource = QuoteRequestResource::class;
    protected function getHeaderActions(): array { return []; }
}
