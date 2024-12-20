<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Informasi Produk')->schema([
                        TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Set $set) => $operation
                            === 'create' ? $set('slug', Str::slug($state)) : null),
                
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated()
                            ->unique(Product::class, 'slug', ignoreRecord: true),
                
                        MarkdownEditor::make('description')
                            ->label('Deskripsi Produk')
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('products'),
                    ])->columns(2),
                
                    Section::make('Gambar Produk')->schema([
                        FileUpload::make('images')
                            ->label('Upload 1 atau lebih')
                            ->multiple()
                            ->directory('products')
                            ->maxFiles(5)
                            ->reorderable(),
                    ])
                ])->columnSpan(2),

                Group::make()->schema([
                    Section::make('Detail Produk')->schema([
                        TextInput::make('price')
                            ->label('Harrga Produk')
                            ->numeric()
                            ->required()
                            ->prefix('IDR')
                            ->afterStateUpdated(function ($state, $set) {
                                $cleanedValue = preg_replace('/[^0-9]/', '', $state); 
                                $set('price', $cleanedValue); 
                            })
                            ->formatStateUsing(fn ($state) => $state)
                            ->minValue(0)
                            ->maxValue(1000000000),
                        ]),

                        TextInput::make('weight')
                            ->label('Berat (gr)')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(1000000) // Atur batas maksimum sesuai kebutuhan
                            ->helperText('Masukan berat dalam gram.'),

                        Textinput::make('in_stock')
                            ->label('Stok')
                            ->required(),
                
                    Section::make('Lainnya')->schema([
                        Select::make('category_id')
                            ->label('Kategori Produk')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('category', 'name'),
                
                        Select::make('brand_id')
                            ->label('Merek Produk')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('brand', 'name'),
                    ]),
                ])->columnSpan(1)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),

                TextColumn::make('brand.name')
                    ->label('Merek')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => 'IDR ' . number_format((int) $state, 0, '', ''))
                    ->sortable(),

                TextColumn::make('in_stock')
                    ->label('Stok')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()  
                    ->sortable()   
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()  
                    ->sortable()   
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name'),

                SelectFilter::make('brand')
                    ->relationship('brand', 'name'),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
