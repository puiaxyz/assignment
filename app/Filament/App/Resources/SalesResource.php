<?php

namespace App\Filament\App\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Sales;
use App\Models\Products;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\App\Resources\SalesResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\SalesResource\RelationManagers;

class SalesResource extends Resource
{
    protected static ?string $model = Sales::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Repeater::make('saleItems')
            ->relationship()->schema([

                ])->columnSpanFull(),

            TextInput::make('total_amount')
            ->label('Total Amount')
            ->numeric()
            ->required()
            ->columnspanfull()
            ->live()
            ->afterStateUpdated(fn ($state, callable $set, $get) =>
            $set('final_amount', max(0,
                $get('discount_type') === 'percentage'
                ? $state * (1 - ($get('discount') / 100))
                : $state - $get('discount')
            ))
         ),

            Select::make('discount_type')
            ->label('Discount Type')
            ->options([
                'percentage' => 'Percentage (%)',
                'value' => 'Fixed Value (₹)',
                    ])
            ->default('percentage')
            ->reactive(),

            TextInput::make('discount')
            ->label('Discount')
            ->numeric()->rule('min:0')
            ->default(0)
            ->suffix(fn ($get) => $get('discount_type') === 'percentage' ? '%' : '₹')
            ->live()
            ->afterStateUpdated(fn ($state, callable $set, $get) =>
                $set('final_amount', max(0,
                $get('discount_type') === 'percentage'
                ? $get('total_amount') * (1 - ($state / 100))
                : $get('total_amount') - $state
            ))
        ),



        TextInput::make('final_amount')
        ->label('Final Amount')
        ->numeric()
        ->required()
        ->readonly(),
            Select::make('payment_method')
            ->label('Payment Method')
            ->options([
                'cash' => 'Cash',
                'upi' => 'UPI',
            ])
            ->required()

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
            ->filters([
                //
            ])
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
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSales::route('/create'),
            'edit' => Pages\EditSales::route('/{record}/edit'),
        ];
    }
}
