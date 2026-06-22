<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FairResource\Pages;
use App\Filament\Concerns\AuthorizesByRole;
use App\Models\Fair;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class FairResource extends Resource
{
    use AuthorizesByRole;

    protected static ?string $model = Fair::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Fuarlar';
    protected static ?string $modelLabel = 'Fuar';
    protected static ?string $pluralModelLabel = 'Fuarlar';
    protected static string|\UnitEnum|null $navigationGroup = 'İçerik';
    protected static ?int $navigationSort = 3;

    protected static array $viewRoles   = ['product_manager', 'sales_manager', 'support'];
    protected static array $createRoles = ['product_manager'];
    protected static array $editRoles   = ['product_manager'];
    protected static array $deleteRoles = ['product_manager'];

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Fuar Bilgileri')
                ->schema([
                    DatePicker::make('start_date')
                        ->label('Başlangıç Tarihi')
                        ->required(),

                    DatePicker::make('end_date')
                        ->label('Bitiş Tarihi')
                        ->nullable()
                        ->afterOrEqual('start_date'),

                    TextInput::make('city')
                        ->label('Şehir')
                        ->maxLength(100)
                        ->nullable(),

                    TextInput::make('venue')
                        ->label('Mekan / Fuar Merkezi')
                        ->maxLength(255)
                        ->nullable(),

                    TextInput::make('sort_order')
                        ->label('Sıralama')
                        ->numeric()
                        ->default(0),
                ])
                ->columns(2),

            Tabs::make('İçerik')
                ->tabs([
                    Tab::make('Türkçe')
                        ->schema([
                            TextInput::make('name.tr')
                                ->label('Fuar Adı (TR)')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, $set, $get) {
                                    if (! $get('slug')) {
                                        $set('slug', Str::slug($state));
                                    }
                                }),

                            TextInput::make('slug')
                                ->label('Slug (URL)')
                                ->required()
                                ->maxLength(255)
                                ->unique(Fair::class, 'slug', ignoreRecord: true)
                                ->helperText('Otomatik oluşturulur.'),

                            Textarea::make('description.tr')
                                ->label('Açıklama (TR)')
                                ->rows(4)
                                ->nullable(),
                        ]),

                    Tab::make('İngilizce')
                        ->schema([
                            TextInput::make('name.en')
                                ->label('Fuar Adı (EN)')
                                ->maxLength(255)
                                ->nullable(),

                            Textarea::make('description.en')
                                ->label('Açıklama (EN)')
                                ->rows(4)
                                ->nullable(),
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Fuar Adı')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['tr'] ?? '—') : ($state ?? '—'))
                    ->searchable(query: fn (Builder $q, string $s) =>
                        $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.tr')) LIKE ?", ["%{$s}%"]))
                    ->limit(60),

                TextColumn::make('city')
                    ->label('Şehir')
                    ->placeholder('—'),

                TextColumn::make('venue')
                    ->label('Mekan')
                    ->placeholder('—')
                    ->limit(40)
                    ->toggleable(),

                TextColumn::make('start_date')
                    ->label('Başlangıç')
                    ->date('d.m.Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Bitiş')
                    ->date('d.m.Y')
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->defaultSort('start_date', 'desc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFairs::route('/'),
            'create' => Pages\CreateFair::route('/create'),
            'edit'   => Pages\EditFair::route('/{record}/edit'),
        ];
    }
}
