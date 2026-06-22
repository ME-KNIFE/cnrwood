<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BlogPostResource\Pages;
use App\Filament\Concerns\AuthorizesByRole;
use App\Models\BlogPost;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class BlogPostResource extends Resource
{
    use AuthorizesByRole;

    protected static ?string $model = BlogPost::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationLabel = 'Blog Yazıları';
    protected static ?string $modelLabel = 'Blog Yazısı';
    protected static ?string $pluralModelLabel = 'Blog Yazıları';
    protected static string | \UnitEnum | null $navigationGroup = 'İçerik';
    protected static ?int $navigationSort = 1;

    // ── RBAC ─────────────────────────────────────────────────────────────────
    protected static array $viewRoles   = ['product_manager', 'sales_manager', 'support'];
    protected static array $createRoles = ['product_manager'];
    protected static array $editRoles   = ['product_manager'];
    protected static array $deleteRoles = ['product_manager'];

    // ── Form ─────────────────────────────────────────────────────────────────
    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Yayın Ayarları')
                ->schema([
                    Select::make('status')
                        ->label('Durum')
                        ->options(['draft' => 'Taslak', 'published' => 'Yayında'])
                        ->default('draft')
                        ->required(),

                    DateTimePicker::make('published_at')
                        ->label('Yayın Tarihi')
                        ->seconds(false)
                        ->nullable(),

                    TextInput::make('featured_image_url')
                        ->label('Öne Çıkan Görsel URL')
                        ->url()
                        ->maxLength(500)
                        ->nullable()
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Tabs::make('İçerik')
                ->tabs([
                    Tab::make('Türkçe')
                        ->schema([
                            TextInput::make('title.tr')
                                ->label('Başlık (TR)')
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
                                ->unique(BlogPost::class, 'slug', ignoreRecord: true)
                                ->helperText('Otomatik oluşturulur, değiştirilebilir.'),

                            Textarea::make('excerpt.tr')
                                ->label('Özet (TR)')
                                ->rows(3)
                                ->maxLength(500)
                                ->nullable(),

                            Textarea::make('body.tr')
                                ->label('İçerik (TR)')
                                ->rows(12)
                                ->required(),

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
                                ->label('Başlık (EN)')
                                ->maxLength(255)
                                ->nullable(),

                            Textarea::make('excerpt.en')
                                ->label('Özet (EN)')
                                ->rows(3)
                                ->maxLength(500)
                                ->nullable(),

                            Textarea::make('body.en')
                                ->label('İçerik (EN)')
                                ->rows(12)
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
        ]);
    }

    // ── Table ─────────────────────────────────────────────────────────────────
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Başlık')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['tr'] ?? '-') : ($state ?? '-'))
                    ->searchable(query: fn (Builder $q, string $s) => $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(title, '$.tr')) LIKE ?", ["%{$s}%"]))
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

                TextColumn::make('published_at')
                    ->label('Yayın Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('author.name')
                    ->label('Yazar')
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('Oluşturuldu')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index'  => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit'   => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
