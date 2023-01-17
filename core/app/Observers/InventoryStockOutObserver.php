<?php

namespace App\Observers;

use App\Mail\StockOutEmail;
use Illuminate\Support\Facades\Mail;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductInventoryDetail;

class InventoryStockOutObserver
{
    public function updated()
    {
        // Inventory Warnings
        $threshold_amount = get_static_option('stock_threshold_amount');

        $products = Product::with('inventory')
            ->where('is_inventory_warn_able', 1)
            ->whereHas('inventory', function ($query) use ($threshold_amount) {
                $query->where('stock_count', '<=', $threshold_amount);
            })
            ->select('id', 'name', 'slug', 'is_inventory_warn_able')
            ->get();

        foreach ($products as $item)
        {
            $inventory_stock = $item?->inventory?->stock_count;
            $item->stock = $inventory_stock;
        }

        $email = get_static_option('order_receiving_email') ?? get_static_option('tenant_site_global_email');
        Mail::to($email)->send(new StockOutEmail($products));
    }
}
