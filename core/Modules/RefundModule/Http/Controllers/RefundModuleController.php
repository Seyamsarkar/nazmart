<?php

namespace Modules\RefundModule\Http\Controllers;

use App\Helpers\FlashMsg;
use App\Models\ProductOrder;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\RefundModule\Entities\RefundProduct;

class RefundModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $refund_list = RefundProduct::paginate(10);
        return view('tenant.frontend.user.dashboard.refund.refund-list', compact('refund_list'));
    }

    public function request_refund(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|numeric',
            'refund_products' => 'required|array'
        ]);

        $user = \Auth::guard('web')->user();
        ProductOrder::findOrFail($validated['order_id']);

        $data = [];
        foreach ($validated['refund_products'] ?? [] as $key => $product)
        {
            $refund = RefundProduct::where([
                'user_id' => $user->id,
                'order_id' => $validated['order_id'],
                'product_id' => $product,
            ])->first();

            if (empty($refund))
            {
                RefundProduct::create([
                    'user_id' => $user->id,
                    'order_id' => $validated['order_id'],
                    'product_id' => $product,
                    'status' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        return back()->with(FlashMsg::explain('success', 'Your refund request is sent successfully'));
    }
}
