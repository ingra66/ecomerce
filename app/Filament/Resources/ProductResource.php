<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Catálogo';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información Básica')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),

                        Forms\Components\Select::make('category_id')
                            ->label('Categoría')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable(),

                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->maxLength(1000),

                        Forms\Components\TextInput::make('price')
                            ->label('Precio (en centavos)')
                            ->required()
                            ->numeric()
                            ->minValue(0),

                        Forms\Components\TextInput::make('stock')
                            ->label('Stock')
                            ->required()
                            ->numeric()
                            ->minValue(0),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Destacado')
                            ->default(false),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0),
                    ])->columns(2),

                Forms\Components\Section::make('Imágenes')
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->label('Imágenes del Producto')
                            ->multiple()
                            ->image()
                            ->imageEditor()
                            ->directory('products')
                            ->maxFiles(5),
                    ]),

                Forms\Components\Section::make('Proveedores')
                    ->schema([
                        Forms\Components\Repeater::make('suppliers')
                            ->label('Proveedores')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('supplier_id')
                                    ->label('Proveedor')
                                    ->relationship('suppliers', 'nick')
                                    ->required(),
                                Forms\Components\TextInput::make('enlace')
                                    ->label('Enlace de pedido')
                                    ->url()
                                    ->nullable(),
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('Agregar proveedor')
                            ->columns(2),
                    ]),

                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Título')
                            ->maxLength(60),

                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Descripción')
                            ->rows(2)
                            ->maxLength(160),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('primary_image_url')
                    ->label('Imagen')
                    ->circular()
                    ->size(50),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoría')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Precio')
                    ->money('ARS')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable()
                    ->color(fn (string $state): string => match (true) {
                        $state < 10 => 'danger',
                        $state < 50 => 'warning',
                        default => 'success',
                    }),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Destacado')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('suppliers')
                    ->label('Proveedor')
                    ->formatStateUsing(function ($record) {
                        $proveedores = $record->suppliers;
                        if ($proveedores->isEmpty()) return '-';
                        return $proveedores->map(function($s) {
                            $color = $s->pivot->enlace ? 'style=\'color:#0ea5e9;font-weight:bold\'' : '';
                            $nick = e($s->nick);
                            return "<span $color>$nick</span>";
                        })->implode(', ');
                    })
                    ->html()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Activo'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Destacado'),

                Tables\Filters\Filter::make('low_stock')
                    ->label('Stock Bajo')
                    ->query(fn (Builder $query): Builder => $query->where('stock', '<', 10)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
} 