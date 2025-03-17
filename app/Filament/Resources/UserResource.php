<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')->columnSpanFull()->required()->maxLength(255),
                Forms\Components\TextInput::make('email')->columnSpanFull()->required()->maxLength(255)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('description')->columnSpanFull()->maxLength(255),
                Forms\Components\TextInput::make('phone_no')->columnSpanFull()->tel(),  //TODO: put phone no in users table
                Forms\Components\Checkbox::make('is_admin')->columnSpanFull(),
                Forms\Components\TextInput::make('password')->password()->minLength(6)->confirmed()->maxLength(255)->dehydrated(fn($state) => filled($state))->nullable(),
                Forms\Components\TextInput::make('password_confirmation')->label("Confirm password")->same('password')->password()->dehydrated(false),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->label('ID'),
                Tables\Columns\TextColumn::make('name')->sortable()->label('Name'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('is_admin')
                    ->label('Admin')
                    ->formatStateUsing(fn($state) => $state ? 'Yes' : 'No')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')->label('Quantity'),
                Tables\Columns\TextColumn::make('phone_no')->label('Phone Number'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
