<?php

namespace App\Filament\Sales\Resources;

use App\Filament\Sales\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Projeler';
    protected static ?string $modelLabel = 'Proje';
    protected static ?string $pluralModelLabel = 'Projeler';
    protected static string|\UnitEnum|null $navigationGroup = 'Icerik';
    protected static ?int $navigationSort = 5;

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Yayin Ayarlari')
                ->schema([
                    Toggle::make('is_published')
                        ->label('Yayinda')
                        ->default(false),

                    Toggle::make('is_featured')
                        ->label('One Cikan')
                        ->default(false),

                    TextInput::make('sort_order')
                        ->label('Siralama')
                        ->numeric()
                        ->default(0),

                    DatePicker::make('completed_at')
                        ->label('Tamamlanma Tarihi')
                        ->nullable(),
                ])
                ->columns(4),

            Section::make('Proje Bilgileri')
                ->schema([
                    TextInput::make('category')
                        ->label('Kategori')
                        ->maxLength(100)
                        ->nullable(),

                    TextInput::make('client_name')
                        ->label('Musteri / Firma')
                        ->maxLength(255)
                        ->nullable(),

                    TextInput::make('location')
                        ->label('Konum')
                        ->maxLength(255)
                        ->nullable(),
                ])
                ->columns(3),

            Tabs::make('Icerik')
                ->tabs([
                    Tab::make('Turkce')
                        ->schema([
                            TextInput::make('title.tr')
                                ->label('Proje Adi (TR)')
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
                                ->helperText('Otomatik olusturulur.'),

                            Textarea::make('excerpt_tr')
                                ->label('Kisa Aciklama (TR)')
                                ->rows(3)
                                ->maxLength(300)
                                ->nullable()
                                ->columnSpanFull(),

                            Textarea::make('content_tr')
                                ->label('Detayli Icerik (TR)')
                                ->rows(8)
                                ->nullable()
                                ->columnSpanFull(),
                        ])
                        ->columns(2),

                    Tab::make('Ingilizce')
                        ->schema([
                            TextInput::make('title.en')
                                ->label('Proje Adi (EN)')
                                ->maxLength(255)
                                ->nullable(),

                            Textarea::make('excerpt_en')
                                ->label('Kisa Aciklama (EN)')
                                ->rows(3)
                                ->maxLength(300)
                                ->nullable()
                                ->columnSpanFull(),

                            Textarea::make('content_en')
                                ->label('Detayli Icerik (EN)')
                                ->rows(8)
                                ->nullable()
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ]),

            Section::make('Kapak Gorseli')
                ->schema([
                    FileUpload::make('cover_image_path')
                        ->label('Kapak Gorseli')
                        ->disk('public')
                        ->directory('project-images')
                        ->image()
                        ->imageEditor()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(5120)
                        ->helperText('Maks. 5 MB. JPEG, PNG veya WebP.')
                        ->nullable()
                        ->columnSpanFull(),

                    TextInput::make('image_alt_tr')
                        ->label('Gorsel Alt Metni (TR)')
                        ->maxLength(255)
                        ->nullable(),

                    TextInput::make('image_alt_en')
                        ->label('Gorsel Alt Metni (EN)')
                        ->maxLength(255)
                        ->nullable(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image_path')
                    ->label('Gorsel')
                    ->disk('public')
                    ->height(40)
                    ->width(60)
                    ->toggleable(),

                TextColumn::make('title')
                    ->label('Proje Adi')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['tr'] ?? '--') : ($state ?? '--'))
                    ->searchable(query: fn (Builder $q, string $s) =>
                        $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(title, '$.tr')) LIKE ?", ["%{$s}%"])
                          ->orWhere('client_name', 'like', "%{$s}%"))
                    ->sortable()
                    ->limit(50),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->placeholder('--')
                    ->toggleable(),

                IconColumn::make('is_published')
                    ->label('Yayinda')
                    ->boolean(),

                IconColumn::make('is_featured')
                    ->label('One Cikan')
                    ->boolean(),

                TextColumn::make('completed_at')
                    ->label('Tamamlandi')
                    ->date('d.m.Y')
                    ->sortable()
                    ->placeholder('--'),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Filter::make('is_published')
                    ->label('Yayinda')
                    ->query(fn (Builder $q) => $q->where('is_published', true)),
            ])
            ->actions([
                EditAction::make(),
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
