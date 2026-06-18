<?php

namespace App\Filament\Admin\Resources\ContactMessageResource\Pages;

use App\Filament\Admin\Resources\ContactMessageResource;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);
        if (! $this->record->is_read) {
            $this->record->markAsRead();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Listeye Dön')
                ->url(static::getResource()::getUrl())
                ->color('gray'),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('name')->label('Ad Soyad'),
            TextEntry::make('email')->label('E-posta'),
            TextEntry::make('phone')->label('Telefon')->default('—'),
            TextEntry::make('subject')->label('Konu')->default('—'),
            TextEntry::make('message')->label('Mesaj')->columnSpanFull(),
            TextEntry::make('created_at')->label('Tarih')->dateTime('d.m.Y H:i'),
        ]);
    }
}
