<?php

namespace App\Filament\App\Resources\StockResource\Pages;

use App\Filament\App\Resources\StockResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStock extends CreateRecord
{
    protected static string $resource = StockResource::class;
}
