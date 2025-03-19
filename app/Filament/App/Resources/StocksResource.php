<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\StocksResource\Pages;
use App\Filament\App\Resources\StocksResource\RelationManagers;
use App\Models\Stocks;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StocksResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $navigationGroup = 'Product Management';
    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\Select::make('product_id')->label('Product')->options(\App\Models\Products::pluck('name', 'id'))->searchable()->required(),
                Forms\Components\Textarea::make('price')->columnSpanFull()->numeric(),
                Forms\Components\Textarea::make('quantity')->columnSpanFull()->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->label('ID'),
                Tables\Columns\TextColumn::make('products.name')->sortable()->label('Product Name'),
                Tables\Columns\TextColumn::make('price')->label('Price'),
                Tables\Columns\TextColumn::make('quantity')->label('Quantity'),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStocks::route('/'),
            'create' => Pages\CreateStocks::route('/create'),
            'edit' => Pages\EditStocks::route('/{record}/edit'),
        ];
    }
}
