<?php

namespace App\Http\Services;

use App\Actions\Payment\Tenant\PaymentGatewayIpn;
use App\Enums\PaymentRouteEnum;
use App\Helpers\Payment\PaymentGatewayCredential;
use App\Mail\StockOutEmail;
use App\Models\OrderProducts;
use App\Models\ProductOrder;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Campaign\Entities\CampaignSoldProduct;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductInventoryDetail;

class CheckoutToPaymentService
{
    public static function checkoutToGateway($data) // getting all parameter in one array
    {
        $payment_details = ProductOrder::find($data['order_log_id']);
        $payment_gateway = $payment_details->payment_gateway;
        $checkout_type = $payment_details->checkout_type;
        $amount_to_charge = $payment_details->total_amount;

        $ordered_products = OrderProducts::where('order_id', $payment_details->id)->get();
        foreach ($ordered_products ?? [] as $product)
        {
            if($product->campaign_product !== null)
            {
                $sold_count = CampaignSoldProduct::where('product_id', $product->product_id)->first();
                if (empty($sold_count))
                {
                    CampaignSoldProduct::create([
                        'product_id' => $product->product_id,
                        'sold_count' => 1,
                        'total_amount' => $product->campaign_product->campaign_price,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } else {
                    if ($sold_count->sold_count < $product->campaign_product->units_for_sale)
                    {
                        if ($product->campaign_product->units_for_sale >= ($product->quantity + $sold_count->sold_count))
                        {
                            $sold_count->increment('sold_count', $product->quantity);
                            $sold_count->total_amount += $product->campaign_product->campaign_price*$product->quantity;
                            $sold_count->save();
                        } else {
                            return back()->withErrors('Campaign sell limitation is over, You can not purchase current amount');
                        }
                    } else {
                        return back()->withErrors('Campaign sell limitation is over, You can not purchase this product right now');
                    }
                }
            }

            if ($product->variant_id !== null)
            {
                $variants = ProductInventoryDetail::where(['product_id' => $product->product_id, 'id' => $product->variant_id])->get();
                if (!empty($variants))
                {
                    foreach ($variants ?? [] as $variant)
                    {
                        $variant->decrement('stock_count', $product->quantity);
                        $variant->increment('sold_count', $product->quantity);
                    }
                }
            }
            $product_inventory = ProductInventory::where('product_id', $product->product_id)->first();
            $product_inventory->decrement('stock_count', $product->quantity);
            $product_inventory->sold_count = $product_inventory->sold_count == null ? 1 : $product_inventory->sold_count + $product->quantity;
            $product_inventory->save();
        }

        self::checkStock(); // Checking Stock for warning and email notification

        if ($payment_gateway != 'manual_payment'  && $checkout_type === 'digital') {
            $credential_function = 'get_' . $payment_gateway . '_credential';
            $params = self::common_charge_customer_data($amount_to_charge, $payment_details, route('tenant.user.frontend.' . $payment_gateway . '.ipn'));
            return PaymentGatewayCredential::$credential_function()->charge_customer($params);

        } else {
            if($payment_gateway != null){
                $payment_details->update(['transaction_id' => $payment_details->transaction_id]);
            }
            $order_id = Str::random(6) . $payment_details->id . Str::random(6);

            (new PaymentGatewayIpn())->send_order_mail($payment_details['id']);
            Cart::destroy();

            return redirect()->route(PaymentRouteEnum::SUCCESS_ROUTE, $order_id);
        }

        return redirect()->route('homepage');
    }

    private static function common_charge_customer_data($amount_to_charge, $payment_details, $ipn_url): array
    {
        $data = [
            'amount' => $amount_to_charge,
            'title' => 'Order ID: '.$payment_details->id,
            'description' => 'Payment For Order ID: #' . $payment_details->id .
                ' Payer Name: ' . $payment_details->name .
                ' Payer Email: ' . $payment_details->email,
            'order_id' => $payment_details->id,
            'track' => $payment_details->payment_track,
            'cancel_url' => route(PaymentRouteEnum::CANCEL_ROUTE, $payment_details->id),
            'success_url' => route(PaymentRouteEnum::SUCCESS_ROUTE, $payment_details->id),
            'email' => $payment_details->email,
            'name' => $payment_details->name,
            'payment_type' => 'order',
            'ipn_url' => $ipn_url,
        ];

        return $data;
    }

    private static function checkStock()
    {
        // Inventory Warnings
        $threshold_amount = get_static_option('stock_threshold_amount');

        $inventory_product_items = \Modules\Product\Entities\ProductInventoryDetail::where('stock_count', '<=', $threshold_amount)
            ->whereHas('is_inventory_warn_able', function ($query) {
                $query->where('is_inventory_warn_able', 1);
            })
            ->select('id', 'product_id')
            ->get();

        $inventory_product_items_id = !empty($inventory_product_items) ? $inventory_product_items->pluck('product_id')->toArray() : [];

        $products = \Modules\Product\Entities\Product::with('inventory')
            ->where('is_inventory_warn_able', 1)
            ->whereHas('inventory', function ($query) use ($threshold_amount) {
                $query->where('stock_count', '<=', $threshold_amount);
            })
            ->select('id')
            ->get();

        $products_id = !empty($products) ? $products->pluck('id')->toArray() : [];

        $every_filtered_product_id = array_unique(array_merge($inventory_product_items_id, $products_id));
        $all_products = \Modules\Product\Entities\Product::whereIn('id', $every_filtered_product_id)->select('id', 'name', 'is_inventory_warn_able')->get();

        if (count($all_products) > 0)
        {
            foreach ($all_products as $item)
            {
                $inventory = $item?->inventory?->stock_count;
                $variant = $item->inventoryDetail->where('stock_count', '<=', $threshold_amount)->first();
                $variant = !empty($variant) ? $variant->stock_count : [];

                $stock = min($inventory, $variant);
                $item->stock = $stock;
            }

            $email = get_static_option('order_receiving_email') ?? get_static_option('tenant_site_global_email');
            try {
                Mail::to($email)->send(new StockOutEmail($all_products));
            }catch (\Exception $e){

            }
        }
    }
}
