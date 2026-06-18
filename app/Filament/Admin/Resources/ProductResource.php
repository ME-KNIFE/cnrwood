<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use App\Filament\Admin\Resources\ProductResource\RelationManagers\ProductImagesRelationManager;
use App\Filament\Admin\Resources\ProductResource\RelationManagers\ProductVariantsRelationManager;
use App\Filament\Concerns\AuthorizesByRole;
use App\Models\Product;
use App\Models\ProductCategory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    use AuthorizesByRole;

    protected static ?string $model = Product::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Ürünler';
    protected static ?string $modelLabel = 'Ürün';
    protected static ?string $pluralModelLabel = 'Ürünler';
    protected static string | \UnitEnum | null $navigationGroup = 'Ürün Yönetimi';
    protected static ?int $navigationSort = 1;

    // ── RBAC ─────────────────────────────────────────────────────────────────
    // super_admin is always allowed by the trait.
    protected static array $viewRoles   = ['product_manager', 'sales_manager'];
    protected static array $createRoles = ['product_manager'];
    protected static array $editRoles   = ['product_manager'];
    protected static array $deleteRoles = [];

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Temel Bilgiler')
                ->schema([
                    Select::make('product_type')
                        ->label('Ürün Tipi')
                        ->options(['buyable' => 'Satılık (Buyable)', 'quote_only' => 'Sadece Teklif (Quote Only)'])
                        ->required()
                        ->live()
                        ->helperText(fn ($state) => $state === 'quote_only'
                            ? '⚠️ Bu ürün sepete eklenemez — sadece teklif formu üzerinden talep edilebilir.'
                            : null
                        ),
                    Select::make('product_category_id')
                        ->label('Kategori')
                        ->options(fn () => ProductCategory::orderBy('sort_order')
                            ->get()
                            ->mapWithKeys(fn ($c) => [$c->id => is_array($c->name) ? ($c->name['tr'] ?? '-') : ($c->name ?? '-')])
                            ->toArray()
                        )
                        ->searchable()
                        ->required(),
                    TextInput::make('sku')->label('SKU')
                        ->unique(table: 'products', column: 'sku', ignoreRecord: true),
                    TextInput::make('slug')->label('Slug')->required()
                        ->unique(table: 'products', column: 'slug', ignoreRecord: true),
                    TextInput::make('name.tr')->label('İsim (TR)')->required(),
                    TextInput::make('name.en')->label('Name (EN)'),
                    Toggle::make('is_active')->label('Aktif')->default(true),
                    Toggle::make('is_featured')->label('Öne Çıkan')->default(false),
                ])->columns(2),

            Section::make('Fiyat & Stok')
                ->schema([
                    TextInput::make('price')
                        ->label('Fiyat (₺)')->numeric()->minValue(0)
                        ->required(fn ($get) => $get('product_type') === 'buyable')
                        ->hidden(fn ($get) => $get('product_type') === 'quote_only')
                        ->helperText('Buyable ürünler için zorunlu.'),
                    TextInput::make('compare_at_price')
                        ->label('Karşılaştırma Fiyatı (₺)')->numeric()
                        ->hidden(fn ($get) => $get('product_type') === 'quote_only'),
                    TextInput::make('stock_quantity')
                        ->label('Stok Adedi')->numeric()->minValue(0)
                        ->hidden(fn ($get) => $get('product_type') === 'quote_only'),
                    TextInput::make('low_stock_threshold')
                        ->label('Düşük Stok Eşiği')->numeric()->default(5)
                        ->hidden(fn ($get) => $get('product_type') === 'quote_only'),
                    Placeholder::make('quote_only_notice')
                        ->label('')
                        ->content('Bu ürün quote_only tipindedir. Fiyat ve stok alanları uygulanmaz.')
                        ->visible(fn ($get) => $get('product_type') === 'quote_only'),
                ])->columns(2),

            Section::make('Açıklamalar')
                ->schema([
                    Textarea::make('short_description.tr')->label('Kısa Açıklama (TR)')->rows(2),
                    Textarea::make('short_description.en')->label('Short Description (EN)')->rows(2),
                    Textarea::make('description.tr')->label('Uzun Açıklama (TR)')->rows(4),
                    Textarea::make('description.en')->label('Description (EN)')->rows(4),
                ])->columns(2)->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')->label('SKU')->searchable()->sortable(),
                TextColumn::make('name')
                    ->label('İsim')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['tr'] ?? '-') : ($state ?? '-'))
                    ->searchable(query: fn (Builder $query, string $search): Builder =>
                        $query->where('name', 'like', "%{$search}%")
                    )->sortable(),
                BadgeColumn::make('product_type')
                    ->label('Tip')
                    ->colors(['success' => 'buyable', 'warning' => 'quote_only'])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'buyable'    => 'Satılık',
                        'quote_only' => 'Sadece Teklif',
                        default      => $state,
                    }),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['tr'] ?? '-') : ($state ?? '-')),
                TextColumn::make('price')->label('Fiyat')->money('TRY')->default('—'),
                TextColumn::make('stock_quantity')->label('Stok')->default('—')->sortable(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                IconColumn::make('is_featured')->label('Öne Çıkan')->boolean(),
            ])
            ->filters([
                SelectFilter::make('product_type')->label('Ürün Tipi')
                    ->options(['buyable' => 'Satılık', 'quote_only' => 'Sadece Teklif']),
                SelectFilter::make('is_active')->label('Durum')
                    ->options(['1' => 'Aktif', '0' => 'Pasif']),
                SelectFilter::make('product_category_id')->label('Kategori')
                    ->relationship('category', 'slug'),
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(), RestoreAction::make(), ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(), RestoreBulkAction::make(), ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProductImagesRelationManager::class,
            ProductVariantsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
