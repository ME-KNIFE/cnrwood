<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Concerns\AuthorizesByRole;
use App\Filament\Admin\Resources\SitePageResource\Pages;
use App\Models\SitePage;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class SitePageResource extends Resource
{
    use AuthorizesByRole;

    protected static ?string $model = SitePage::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Sayfalar';
    protected static ?string $pluralModelLabel = 'Site Sayfaları';
    protected static ?string $modelLabel = 'Sayfa';
    protected static string|\UnitEnum|null $navigationGroup = 'Icerik';
    protected static ?int $navigationSort = 30;

    // super_admin is always allowed by AuthorizesByRole trait.
    // editor can view and edit; create/delete restricted to super_admin.
    protected static array $viewRoles   = ['editor'];
    protected static array $createRoles = [];
    protected static array $editRoles   = ['editor'];
    protected static array $deleteRoles = [];

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('tabs')->tabs([

                Tab::make('Turkce')
                    ->label('Turkce')
                    ->icon('heroicon-o-language')
                    ->schema([
                        Section::make('Sayfa Basligi & Icerik (TR)')
                            ->schema([
                                TextInput::make('title_tr')
                                    ->label('Baslik (TR)')
                                    ->maxLength(255)
                                    ->helperText('Bos birakilirsa mevcut varsayilan kullanilir.'),
                                Textarea::make('excerpt_tr')
                                    ->label('Giris Paragraf (TR)')
                                    ->rows(3)
                                    ->maxLength(1000)
                                    ->helperText('Hero bolumu alti veya ozet. Bos = hardcoded fallback.'),
                                Textarea::make('content_tr')
                                    ->label('Ana Icerik (TR)')
                                    ->rows(12)
                                    ->helperText('Bos birakilirsa sayfa hardcoded icerigini gostermeye devam eder.'),
                            ]),
                        Section::make('SEO (TR)')
                            ->collapsed()
                            ->schema([
                                TextInput::make('meta_title_tr')
                                    ->label('Meta Baslik (TR)')
                                    ->maxLength(255),
                                Textarea::make('meta_description_tr')
                                    ->label('Meta Aciklamasi (TR)')
                                    ->rows(2)
                                    ->maxLength(500),
                            ]),
                    ]),

                Tab::make('Ingilizce')
                    ->label('Ingilizce')
                    ->icon('heroicon-o-language')
                    ->schema([
                        Section::make('Sayfa Basligi & Icerik (EN)')
                            ->schema([
                                TextInput::make('title_en')
                                    ->label('Title (EN)')
                                    ->maxLength(255),
                                Textarea::make('excerpt_en')
                                    ->label('Intro Paragraph (EN)')
                                    ->rows(3)
                                    ->maxLength(1000),
                                Textarea::make('content_en')
                                    ->label('Main Content (EN)')
                                    ->rows(12),
                            ]),
                        Section::make('SEO (EN)')
                            ->collapsed()
                            ->schema([
                                TextInput::make('meta_title_en')
                                    ->label('Meta Title (EN)')
                                    ->maxLength(255),
                                Textarea::make('meta_description_en')
                                    ->label('Meta Description (EN)')
                                    ->rows(2)
                                    ->maxLength(500),
                            ]),
                    ]),

                Tab::make('Gorsel & Yayinlama')
                    ->label('Gorsel')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Section::make('Hero Gorseli')
                            ->schema([
                                FileUpload::make('hero_image_path')
                                    ->label('Hero Gorseli')
                                    ->disk('public')
                                    ->directory('page-images')
                                    ->image()
                                    ->imageEditor()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(2048)
                                    ->helperText('Isteğe bağlı. Görselsiz sayfa da calısır.'),
                                TextInput::make('image_alt_tr')
                                    ->label('Gorsel Alt Metni (TR)')
                                    ->maxLength(255),
                                TextInput::make('image_alt_en')
                                    ->label('Image Alt Text (EN)')
                                    ->maxLength(255),
                            ]),
                        Section::make('Yayinlama')
                            ->schema([
                                Toggle::make('is_published')
                                    ->label('Yayinda')
                                    ->helperText('Kapatilirsa sayfa fallback icerigini kullanmaya devam eder; rota kaybolmaz.'),
                                TextInput::make('sort_order')
                                    ->label('Siralama')
                                    ->numeric()
                                    ->default(0),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->disabled()
                                    ->helperText('Slug degistirilemez. URL yapisi buna gore sabitlenmistir.'),
                            ]),
                    ]),

            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                TextColumn::make('title_tr')
                    ->label('Baslik (TR)')
                    ->searchable()
                    ->placeholder('(varsayilan kullanilacak)')
                    ->limit(60),
                IconColumn::make('is_published')
                    ->label('Yayinda')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Son Guncelleme')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('is_published')
                    ->label('Sadece Yayindakiler')
                    ->query(fn ($query) => $query->where('is_published', true)),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->defaultSort('sort_order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSitePages::route('/'),
            'edit'  => Pages\EditSitePage::route('/{record}/edit'),
        ];
    }
}
