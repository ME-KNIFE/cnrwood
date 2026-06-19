<?php

namespace App\Filament\Sales\Resources\OrderResource\Pages;

use App\Filament\Sales\Resources\OrderResource;
use App\Services\OrderService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected ?string $pendingStatus = null;

    protected function getHeaderActions(): array { return []; }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->pendingStatus = $data['status'] ?? null;
        unset($data['status']);
        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->getRecord();

        if ($this->pendingStatus === null || $this->pendingStatus === $record->status) {
            return;
        }

        try {
            app(OrderService::class)->transitionStatus($record, $this->pendingStatus);
            $record->refresh();
        } catch (\LogicException $e) {
            Notification::make()
                ->title('Geçersiz Durum Geçişi')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
