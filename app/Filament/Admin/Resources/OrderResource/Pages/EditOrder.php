<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use App\Services\OrderService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected ?string $pendingStatus = null;
    protected ?string $pendingPaymentStatus = null;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('invoice')
                ->label('Fatura İndir')
                ->icon('heroicon-o-document-arrow-down')
                ->color('gray')
                ->url(fn () => route('admin.orders.invoice', $this->getRecord()))
                ->openUrlInNewTab(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->pendingStatus        = $data['status'] ?? null;
        $this->pendingPaymentStatus = $data['payment_status'] ?? null;
        unset($data['status'], $data['payment_status']);
        return $data;
    }

    protected function afterSave(): void
    {
        $record  = $this->getRecord();
        $service = app(OrderService::class);

        if ($this->pendingStatus !== null && $this->pendingStatus !== $record->status) {
            try {
                $service->transitionStatus($record, $this->pendingStatus);
                $record->refresh();
            } catch (\LogicException $e) {
                Notification::make()
                    ->title('Geçersiz Durum Geçişi')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }
        }

        if ($this->pendingPaymentStatus !== null && $this->pendingPaymentStatus !== $record->payment_status) {
            try {
                $service->transitionPaymentStatus($record, $this->pendingPaymentStatus);
                $record->refresh();
            } catch (\LogicException $e) {
                Notification::make()
                    ->title('Geçersiz Ödeme Durumu Geçişi')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }
        }
    }
}
