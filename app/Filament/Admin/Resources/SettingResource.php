<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SettingResource\Pages;
use App\Filament\Concerns\AuthorizesByRole;
use App\Models\Setting;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    use AuthorizesByRole;

    protected static ?string $model = Setting::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Ayarları';
    protected static ?string $modelLabel = 'Ayar';
    protected static ?string $pluralModelLabel = 'Site Ayarları';
    protected static string | \UnitEnum | null $navigationGroup = 'Sistem';
    protected static ?int $navigationSort = 1;

    // ── RBAC ─────────────────────────────────────────────────────────────────
    // Super admin only — site settings affect the entire system.
    protected static array $viewRoles   = [];
    protected static array $createRoles = [];
    protected static array $editRoles   = [];
    protected static array $deleteRoles = [];

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Ayar Bilgileri')
                ->schema([
                    TextInput::make('group')->label('Grup')->default('general')->required(),
                    TextInput::make('key')->label('Anahtar')->required()
                        ->unique(table: 'settings', column: 'key', ignoreRecord: true),
                    Select::make('type')->label('Tip')
                        ->options(['string' => 'Metin', 'boolean' => 'Boolean', 'integer' => 'Tam Sayı', 'json' => 'JSON'])
                        ->default('string')->required(),
                    Textarea::make('value')->label('Değer')->rows(3)->columnSpanFull(),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')->label('Grup')->sortable()->searchable(),
                TextColumn::make('key')->label('Anahtar')->sortable()->searchable(),
                TextColumn::make('type')->label('Tip')->badge(),
                TextColumn::make('value')->label('Değer')->limit(50),
                TextColumn::make('updated_at')->label('Güncellendi')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->defaultSort('group')
            ->filters([
                SelectFilter::make('type')->label('Tip')
                    ->options(['string' => 'Metin', 'boolean' => 'Boolean', 'integer' => 'Tam Sayı', 'json' => 'JSON']),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit'   => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
