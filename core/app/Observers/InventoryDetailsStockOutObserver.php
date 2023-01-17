<?php

namespace App\Observers;

use App\Mail\StockOutEmail;
use Illuminate\Support\Facades\Mail;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductInventoryDetail;

class InventoryDetailsStockOutObserver
{
    public function updated()
    {
        // Inventory Warnings
        $threshold_amount = get_static_option('stock_threshold_amount');

        $inventory_product_items = ProductInventoryDetail::where('stock_count', '<=', $threshold_amount)
            ->whereHas('is_inventory_warn_able', function ($query) {
                $query->where('is_inventory_warn_able', 1);
            })
            ->select('id', 'product_id')
            ->get();

        $inventory_product_items_id = !empty($inventory_product_items) ? $inventory_product_items->pluck('product_id')->toArray() : [];

        $all_products = Product::with('inventoryDetail')->whereIn('id', $inventory_product_items_id)->select('id', 'name', 'slug', 'is_inventory_warn_able')->get();

        foreach ($all_products as $item)
        {
            $variant = $item->inventoryDetail->where('stock_count', '<=', $threshold_amount)->first();
            $variant_stock = !empty($variant) ? $variant->stock_count : [];

            $item->stock = $variant_stock;
        }

        $email = get_static_option('order_receiving_email') ?? get_static_option('tenant_site_global_email');
        Mail::to($email)->send(new StockOutEmail($all_products));
    }
}
