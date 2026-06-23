<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use App\Services\OrderService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
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

            Action::make('cancel_order')
                ->label('Siparişi İptal Et')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->form([
                    Textarea::make('reason')
                        ->label('İptal Sebebi (isteğe bağlı)')
                        ->rows(3),
                ])
                ->modalHeading('Siparişi İptal Et')
                ->modalDescription('Bu işlem geri alınamaz. Stok kalemleri otomatik olarak iade edilecektir.')
                ->modalSubmitActionLabel('İptal Et')
                ->modalIcon('heroicon-o-x-circle')
                ->visible(fn () => ! in_array(
                    $this->getRecord()?->status,
                    ['iptal_edildi', 'kargoya_verildi', 'teslim_edildi', 'iade_edildi'],
                ))
                ->action(function (array $data): void {
                    $record = $this->getRecord();
                    try {
                        app(OrderService::class)->cancelOrder($record, $data['reason'] ?? '');
                        Notification::make()
                            ->title('Sipariş #' . $record->order_number . ' iptal edildi.')
                            ->success()
                            ->send();
                        $this->redirect(OrderResource::getUrl('edit', ['record' => $record->id]));
                    } catch (\LogicException $e) {
                        Notification::make()
                            ->title('İptal edilemedi')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
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
