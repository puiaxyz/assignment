<?php

namespace App\Filament\App\Resources\SalesResource\Pages;

use App\Models\Sales;
use Filament\Resources\Pages\Page;
use App\Filament\App\Resources\SalesResource;

class Invoice extends Page
{
    protected static string $resource = SalesResource::class;
    protected static string $view = 'filament.app.resources.sales-resource.pages.invoice';

    public $invoice;

    public function mount()
    {
        // Get the sale ID from the route
        $sale_id = request()->route('record');

        // Fetch the invoice data
        $this->invoice = Sales::with(['user', 'saleItems.product', 'saleItems'])
            ->findOrFail($sale_id);
    }
}
