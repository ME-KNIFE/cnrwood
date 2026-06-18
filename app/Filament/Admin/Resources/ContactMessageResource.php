<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ContactMessageResource\Pages;
use App\Filament\Concerns\AuthorizesByRole;
use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    use AuthorizesByRole;

    protected static ?string $model = ContactMessage::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'İletişim Mesajları';
    protected static ?string $modelLabel = 'Mesaj';
    protected static ?string $pluralModelLabel = 'İletişim Mesajları';
    protected static string | \UnitEnum | null $navigationGroup = 'İletişim';
    protected static ?int $navigationSort = 1;

    // ── RBAC ─────────────────────────────────────────────────────────────────
    // Contact messages: support reads/marks-read; only super_admin can delete.
    // No edit/create pages are registered on this resource.
    protected static array $viewRoles   = ['support'];
    protected static array $createRoles = [];
    protected static array $editRoles   = [];
    protected static array $deleteRoles = [];

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Ad Soyad')->searchable()->sortable(),
                TextColumn::make('email')->label('E-posta')->searchable(),
                TextColumn::make('phone')->label('Telefon')->default('—'),
                TextColumn::make('subject')->label('Konu')->default('—')->limit(40),
                TextColumn::make('message')->label('Mesaj')->limit(60)
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_read')->label('Okundu')->boolean(),
                TextColumn::make('created_at')->label('Tarih')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('is_read')->label('Durum')
                    ->options(['0' => 'Okunmamış', '1' => 'Okunmuş']),
            ])
            ->actions([
                Action::make('mark_read')
                    ->label('Okundu İşaretle')
                    ->icon('heroicon-o-check')
                    ->visible(fn ($record) => ! $record->is_read)
                    ->action(fn ($record) => $record->markAsRead()),
                ViewAction::make(),
            ])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'view'  => Pages\ViewContactMessage::route('/{record}'),
        ];
    }
}
