<?php

namespace App\Filament\Sales\Resources\QuoteRequestResource\Pages;

use App\Filament\Sales\Resources\QuoteRequestResource;
use Filament\Resources\Pages\EditRecord;

class EditQuoteRequest extends EditRecord
{
    protected static string $resource = QuoteRequestResource::class;
    protected function getHeaderActions(): array { return []; }
}
