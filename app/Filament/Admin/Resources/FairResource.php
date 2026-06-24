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
use Illuminate\Support\Str;

class FairResource extends Resource
{
    use AuthorizesByRole;

    protected static ?string $model = Fair::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Fuarlar';
    protected static ?string $modelLabel = 'Fuar';
    protected static ?string $pluralModelLabel = 'Fuarlar';
    protected static string|\UnitEnum|null $navigationGroup = 'Icerik';
    protected static ?int $navigationSort = 3;

    protected static array $viewRoles   = ['product_manager', 'sales_manager', 'support'];
    protected static array $createRoles = ['product_manager', 'sales_manager'];
    protected static array $editRoles   = ['product_manager', 'sales_manager'];
    protected static array $deleteRoles = ['product_manager'];

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Yayin Ayarlari')
                ->schema([
                    Toggle::make('is_published')
                        ->label('Yayinda')
                        ->default(false)
                        ->helperText('Aktif edilmeden site ziyaretcileri goremiyor.'),

                    Toggle::make('is_featured')
                        ->label('One Cikan')
                        ->default(false)
                        ->helperText('One cikan fuarlar listede ilk sirada gozukur.'),

                    TextInput::make('sort_order')
                        ->label('Siralama')
                        ->numeric()
                        ->default(0),
                ])
                ->columns(3),

            Section::make('Fuar Bilgileri')
                ->schema([
                    DatePicker::make('start_date')
                        ->label('Baslangic Tarihi')
                        ->required(),

                    DatePicker::make('end_date')
                        ->label('Bitis Tarihi')
                        ->nullable()
                        ->afterOrEqual('start_date'),

                    TextInput::make('city')
                        ->label('Sehir')
                        ->maxLength(100)
                        ->nullable(),

                    TextInput::make('venue')
                        ->label('Mekan / Fuar Merkezi')
                        ->maxLength(255)
                        ->nullable(),
                ])
                ->columns(2),

            Tabs::make('Icerik')
                ->tabs([
                    Tab::make('Turkce')
                        ->schema([
                            TextInput::make('name.tr')
                                ->label('Fuar Adi (TR)')
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
                                ->helperText('Otomatik olusturulur.'),

                            Textarea::make('description.tr')
                                ->label('Aciklama (TR)')
                                ->rows(4)
                                ->nullable()
                                ->columnSpanFull(),
                        ])
                        ->columns(2),

                    Tab::make('Ingilizce')
                        ->schema([
                            TextInput::make('name.en')
                                ->label('Fuar Adi (EN)')
                                ->maxLength(255)
                                ->nullable(),

                            Textarea::make('description.en')
                                ->label('Aciklama (EN)')
                                ->rows(4)
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
                        ->directory('fair-images')
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
                    ->defaultImageUrl(null)
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Fuar Adi')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['tr'] ?? '--') : ($state ?? '--'))
                    ->searchable(query: fn (Builder $q, string $s) =>
                        $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.tr')) LIKE ?", ["%{$s}%"])
                          ->orWhere('city', 'like', "%{$s}%")
                          ->orWhere('venue', 'like', "%{$s}%"))
                    ->limit(60),

                TextColumn::make('city')
                    ->label('Sehir')
                    ->placeholder('--'),

                TextColumn::make('venue')
                    ->label('Mekan')
                    ->placeholder('--')
                    ->limit(40)
                    ->toggleable(),

                IconColumn::make('is_published')
                    ->label('Yayinda')
                    ->boolean()
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash'),

                IconColumn::make('is_featured')
                    ->label('One Cikan')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star'),

                TextColumn::make('start_date')
                    ->label('Baslangic')
                    ->date('d.m.Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Bitis')
                    ->date('d.m.Y')
                    ->placeholder('--')
                    ->sortable(),
            ])
            ->defaultSort('start_date', 'desc')
            ->filters([
                Filter::make('is_published')
                    ->label('Yayinda')
                    ->query(fn (Builder $q) => $q->where('is_published', true)),

                Filter::make('is_featured')
                    ->label('One Cikan')
                    ->query(fn (Builder $q) => $q->where('is_featured', true)),

                Filter::make('upcoming')
                    ->label('Yaklasan')
                    ->query(fn (Builder $q) => $q->where('start_date', '>=', now()->toDateString())),

                Filter::make('past')
                    ->label('Gecmis')
                    ->query(fn (Builder $q) => $q->where('start_date', '<', now()->toDateString())),
            ])
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
