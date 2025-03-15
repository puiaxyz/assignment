<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Sales;
use App\Models\Stocks;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Products;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\SalesResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SalesResource\RelationManagers;

class SalesResource extends Resource
{
    protected static ?string $model = Sales::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Hidden::make('user_id')
                ->default(fn () => Filament::auth()->user()?->id)
                ->dehydrated(),

            // Invoice Number - Auto Generated
            TextInput::make('invoice_number')
                ->default('ABC' . random_int(100000, 999999))
                ->disabled()
                ->dehydrated(),

            // Repeater for Sales Items
            Repeater::make('sale_items')
                ->relationship('saleItems')
                ->schema([
                    Select::make('products_id')
                        ->label('Product')
                        ->options(fn () => Products::pluck('name', 'id'))
                        ->searchable()
                        ->reactive(),

                    Select::make('stock_id')
                        ->label('Stock')
                        ->options(fn (Get $get) =>
                            Stocks::where('products_id', $get('products_id'))
                                ->pluck('price', 'id')
                        )
                        ->required()
                        ->reactive(),

                    TextInput::make('quantity')
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->reactive(),

                    // Total price per item (price * quantity)
                    Placeholder::make('total_price')
                        ->label('Total Price')
                        ->content(fn (Get $get) =>
                            ($get('stock_id') ?
                                (\App\Models\Stocks::find($get('stock_id'))->price ?? 0)
                                * ($get('quantity') ?? 1)
                                : 0)
                        ),
                ])
                ->columns(4)
                ->reactive(),

            // Total Amount (Sum of repeater items)
            TextInput::make('total_amount')
                ->label('Total Amount')
                ->default(0)
                ->disabled()
                ->dehydrated()
                ->reactive()
                ->afterStateUpdated(fn (Set $set, Get $get) =>
                    $set('total_amount', collect($get('sale_items') ?? [])->sum(fn ($item) =>
                        (\App\Models\Stocks::find($item['stock_id'])->price ?? 0) * ($item['quantity'] ?? 1)
                    ))
                ),

            // Discount Type Selection
            Radio::make('discount_type')
                ->label('Discount Type')
                ->options([
                    'percentage' => 'Percentage (%)',
                    'fixed' => 'Fixed Value',
                ])
                ->default('percentage')
                ->inline()
                ->reactive(),

            // Discount Value
            TextInput::make('discount_value')
                ->label('Discount Value')
                ->numeric()
                ->default(0)
                ->reactive(),

            // Final Amount (Total Amount - Discount)
            TextInput::make('final_amount')
                ->label('Final Amount')
                ->disabled()
                ->dehydrated()
                ->reactive()
                ->afterStateUpdated(fn (Set $set, Get $get) => {
                    $total = collect($get('sale_items') ?? [])->sum(fn ($item) =>
                        (\App\Models\Stocks::find($item['stock_id'])->price ?? 0) * ($item['quantity'] ?? 1)
                    );
                    $discount = $get('discount_value') ?? 0;
                    $final = ($get('discount_type') === 'percentage') ?
                        $total - ($total * $discount / 100) :
                        $total - $discount;
                    $set('final_amount', $final);
                }),

            // Payment Method Selection
            Select::make('payment_method')
                ->label('Payment Method')
                ->options([
                    'upi' => 'UPI',
                    'cash' => 'Cash',
                ])
                ->default('cash'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('final_amount'),
                Tables\Columns\TextColumn::make('payment_method'),
                Tables\Columns\TextColumn::make('created_at')->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSales::route('/create'),
            'edit' => Pages\EditSales::route('/{record}/edit'),
        ];
    }
}
