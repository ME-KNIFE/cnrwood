<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductCategoryResource\Pages;
use App\Filament\Concerns\AuthorizesByRole;
use App\Models\ProductCategory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductCategoryResource extends Resource
{
    use AuthorizesByRole;

    protected static ?string $model = ProductCategory::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Kategoriler';
    protected static ?string $modelLabel = 'Kategori';
    protected static ?string $pluralModelLabel = 'Kategoriler';
    protected static string | \UnitEnum | null $navigationGroup = 'Ürün Yönetimi';
    protected static ?int $navigationSort = 2;

    // ── RBAC ─────────────────────────────────────────────────────────────────
    protected static array $viewRoles   = ['product_manager', 'sales_manager'];
    protected static array $createRoles = ['product_manager'];
    protected static array $editRoles   = ['product_manager'];
    protected static array $deleteRoles = [];

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Kategori Bilgileri')
                ->schema([
                    Select::make('parent_id')
                        ->label('Üst Kategori')
                        ->options(fn () => ProductCategory::orderBy('sort_order')
                            ->get()
                            ->mapWithKeys(fn ($c) => [$c->id => is_array($c->name) ? ($c->name['tr'] ?? '-') : ($c->name ?? '-')])
                            ->toArray()
                        )
                        ->placeholder('Kök Kategori (Üst Yok)')
                        ->searchable()
                        ->nullable(),
                    TextInput::make('name.tr')->label('İsim (TR)')->required(),
                    TextInput::make('name.en')->label('Name (EN)'),
                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(table: 'product_categories', column: 'slug', ignoreRecord: true),
                    TextInput::make('sort_order')->label('Sıralama')->numeric()->default(0),
                    Toggle::make('is_active')->label('Aktif')->default(true),
                ])->columns(2),
            Section::make('Açıklama')
                ->schema([
                    Textarea::make('description.tr')->label('Açıklama (TR)')->rows(3),
                    Textarea::make('description.en')->label('Description (EN)')->rows(3),
                ])->columns(2)->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('İsim')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['tr'] ?? '-') : ($state ?? '-'))
                    ->searchable(query: fn (Builder $query, string $search): Builder =>
                        $query->where('name', 'like', "%{$search}%")
                    )
                    ->sortable(),
                TextColumn::make('parent.name')
                    ->label('Üst Kategori')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['tr'] ?? '—') : ($state ?? '—'))
                    ->default('—'),
                TextColumn::make('sort_order')->label('Sıra')->sortable(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                TextColumn::make('products_count')->label('Ürün')->counts('products')->sortable(),
                TextColumn::make('created_at')
                    ->label('Oluşturuldu')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('is_active')->label('Durum')->options(['1' => 'Aktif', '0' => 'Pasif']),
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
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

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProductCategories::route('/'),
            'create' => Pages\CreateProductCategory::route('/create'),
            'edit'   => Pages\EditProductCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
