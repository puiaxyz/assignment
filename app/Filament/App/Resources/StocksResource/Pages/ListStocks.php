<?php

namespace App\Filament\App\Resources\StocksResource\Pages;

use App\Filament\App\Resources\StocksResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStocks extends ListRecords
{
    protected static string $resource = StocksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
