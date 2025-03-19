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
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\SalesResource\Pages;

class SalesResource extends Resource
{
    protected static ?string $model = Sales::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Hidden::make('user_id')
                    ->default(fn() => Filament::auth()->user()?->id)
                    ->dehydrated(),
            ]),

            TextInput::make('invoice_number')
                ->default('ABC' . random_int(100000, 999999))
                ->disabled()
                ->dehydrated(),
            Section::make()->schema([
                TextInput::make('barcode')
                    ->label('Scan Barcode')
                    ->reactive()
                    ->afterStateUpdated(fn(Set $set, Get $get, $state) => self::handleBarcode($set, $get, $state))->columnSpanFull()->live(onBlur: true),

                Repeater::make('sale_items')
                    ->relationship('saleItems')
                    ->schema([
                        Select::make('products_id')
                            ->label('Product')
                            ->options(fn() => Products::pluck('name', 'id'))
                            ->searchable()
                            ->reactive()
                            ->required(),

                        Select::make('stock_id')
                            ->label('Stock')
                            ->options(
                                fn(Get $get) =>
                                Stocks::where('products_id', $get('products_id'))
                                    ->pluck('price', 'id')
                            )
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(
                                fn(Set $set, Get $get, $state) =>
                                $set('price_at_sale', Stocks::find($state)?->price ?? 0)
                            ),

                        TextInput::make('quantity')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->reactive(),

                        Placeholder::make('total_price')
                            ->label('Total Price')
                            ->content(
                                fn(Get $get) => ($get('stock_id') ?
                                    (Stocks::find($get('stock_id'))->price ?? 0)
                                    * ($get('quantity') ?? 1)
                                    : 0)
                            )->columnSpanFull(),
                        Hidden::make('price_at_sale')
                            ->dehydrated()
                            ->afterStateHydrated(
                                fn(Set $set, Get $get, $state) =>
                                $set('price_at_sale', $state ?: (Stocks::find($get('stock_id'))?->price ?? 0))
                            ),
                    ])
                    ->columns()->columnSpanFull()
                    ->reactive(),

                Hidden::make('price_at_sale')
                    ->dehydrated(),
            ]),




            Placeholder::make('total_amount')
                ->label('Total Amount')
                ->disabled()
                ->dehydrated()
                ->reactive()
                ->live()
                ->content(fn(Set $set, Get $get) => self::calculateTotalAmount($set, $get)),

            Hidden::make('total_amount')->dehydrated(),

            Radio::make('discount_type')
                ->label('Discount Type')
                ->options(['percentage' => 'Percentage (%)', 'fixed' => 'Fixed Value'])
                ->default('percentage')
                ->inline()
                ->reactive(),

            TextInput::make('discount_value')
                ->label('Discount Value')
                ->numeric()
                ->default(0)
                ->reactive(),

            Placeholder::make('display_final_amount')
                ->label('Final Amount')
                ->content(fn(Get $get, Set $set) => self::calculateFinalAmount($set, $get))
                ->reactive(),

            Hidden::make('final_amount')->dehydrated(),

            Select::make('payment_method')
                ->label('Payment Method')
                ->options(['upi' => 'UPI', 'cash' => 'Cash'])
                ->default('cash'),



        ]);
    }


    private static function getProductOptions()
    {
        return Products::pluck('name', 'id');
    }

    private static function getStockOptions(Get $get)
    {
        return Stocks::where('products_id', $get('products_id'))->pluck('price', 'id');
    }

    private static function updatePriceAtSale(Set $set, Get $get, $state)
    {
        $set('price_at_sale', Stocks::find($state)?->price ?? 0);
    }

    private static function calculateTotalPrice(Get $get)
    {
        return ($get('stock_id') ? (Stocks::find($get('stock_id'))->price ?? 0) * ($get('quantity') ?? 1) : 0);
    }

    private static function calculateTotalAmount(Set $set, Get $get)
    {
        return $set('total_amount', collect($get('sale_items') ?? [])->sum(fn($item) => (Stocks::find($item['stock_id'])->price ?? 0) * ($item['quantity'] ?? 1)));
    }

    private static function calculateFinalAmount(Set $set, Get $get)
    {
        $total = collect($get('sale_items') ?? [])->sum(
            fn($item) =>
            (float) (Stocks::find($item['stock_id'])->price ?? 0) * (int) ($item['quantity'] ?? 1)
        );

        $discount = $get('discount_type') === 'percentage'
            ? ($total * ((float) ($get('discount_value') ?? 0) / 100))
            : (float) ($get('discount_value') ?? 0);

        return $set('final_amount', $total - $discount);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user_id')->sortable(),
                Tables\Columns\TextColumn::make('final_amount'),
                Tables\Columns\TextColumn::make('payment_method'),
                Tables\Columns\TextColumn::make('created_at')->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_invoice')
                    ->label(__("View Invoice"))
                    ->url(function ($record) {
                        return self::getUrl('invoice', ['record' => $record]);
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSales::route('/create'),
            'edit' => Pages\EditSales::route('/{record}/edit'),
            'invoice' => Pages\Invoice::route('/{record}/invoice'),
        ];
    }
}
