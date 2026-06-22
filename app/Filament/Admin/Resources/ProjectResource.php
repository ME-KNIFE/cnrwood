<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProjectResource\Pages;
use App\Filament\Concerns\AuthorizesByRole;
use App\Models\Project;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    use AuthorizesByRole;

    protected static ?string $model = Project::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Projeler';
    protected static ?string $modelLabel = 'Proje';
    protected static ?string $pluralModelLabel = 'Projeler';
    protected static string|\UnitEnum|null $navigationGroup = 'İçerik';
    protected static ?int $navigationSort = 2;

    protected static array $viewRoles   = ['product_manager', 'sales_manager', 'support'];
    protected static array $createRoles = ['product_manager'];
    protected static array $editRoles   = ['product_manager'];
    protected static array $deleteRoles = ['product_manager'];

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Proje Ayarları')
                ->schema([
                    Select::make('status')
                        ->label('Durum')
                        ->options(['draft' => 'Taslak', 'published' => 'Yayında'])
                        ->default('published')
                        ->required(),

                    DatePicker::make('completed_at')
                        ->label('Tamamlanma Tarihi')
                        ->nullable(),

                    TextInput::make('sort_order')
                        ->label('Sıralama')
                        ->numeric()
                        ->default(0),
                ])
                ->columns(3),

            Tabs::make('İçerik')
                ->tabs([
                    Tab::make('Türkçe')
                        ->schema([
                            TextInput::make('title.tr')
                                ->label('Proje Adı (TR)')
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
                                ->unique(Project::class, 'slug', ignoreRecord: true)
                                ->helperText('Otomatik oluşturulur.'),

                            Textarea::make('description.tr')
                                ->label('Açıklama (TR)')
                                ->rows(5)
                                ->nullable(),

                            TextInput::make('meta_title.tr')
                                ->label('SEO Başlık (TR)')
                                ->maxLength(70)
                                ->nullable(),

                            Textarea::make('meta_description.tr')
                                ->label('SEO Açıklama (TR)')
                                ->rows(2)
                                ->maxLength(160)
                                ->nullable(),
                        ]),

                    Tab::make('İngilizce')
                        ->schema([
                            TextInput::make('title.en')
                                ->label('Proje Adı (EN)')
                                ->maxLength(255)
                                ->nullable(),

                            Textarea::make('description.en')
                                ->label('Açıklama (EN)')
                                ->rows(5)
                                ->nullable(),

                            TextInput::make('meta_title.en')
                                ->label('SEO Başlık (EN)')
                                ->maxLength(70)
                                ->nullable(),

                            Textarea::make('meta_description.en')
                                ->label('SEO Açıklama (EN)')
                                ->rows(2)
                                ->maxLength(160)
                                ->nullable(),
                        ]),
                ]),

            Section::make('Proje Görselleri')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('project_gallery')
                        ->label('Galeri (birden fazla görsel ekleyebilirsiniz)')
                        ->collection('project_gallery')
                        ->multiple()
                        ->reorderable()
                        ->image()
                        ->imageEditor()
                        ->maxFiles(20)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Proje Adı')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['tr'] ?? '—') : ($state ?? '—'))
                    ->searchable(query: fn (Builder $q, string $s) =>
                        $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(title, '$.tr')) LIKE ?", ["%{$s}%"]))
                    ->sortable()
                    ->limit(60),

                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'published' => 'success',
                        'draft'     => 'gray',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'published' => 'Yayında',
                        'draft'     => 'Taslak',
                        default     => $state,
                    }),

                TextColumn::make('completed_at')
                    ->label('Tamamlandı')
                    ->date('d.m.Y')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('sort_order')
                    ->label('Sıra')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Oluşturuldu')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options(['draft' => 'Taslak', 'published' => 'Yayında']),
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit'   => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
