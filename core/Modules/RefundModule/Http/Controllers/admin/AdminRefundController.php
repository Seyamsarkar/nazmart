<?php

namespace Modules\RefundModule\Http\Controllers\admin;

use App\Events\SupportMessage;
use App\Helpers\FlashMsg;
use App\Helpers\ResponseMessage;
use App\Mail\BasicMail;
use App\Models\Language;
use App\Models\OrderProducts;
use App\Models\ProductOrder;
use App\Models\SupportDepartment;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\Product\Entities\Product;
use Modules\RefundModule\Entities\RefundChat;
use Modules\RefundModule\Entities\RefundChatMessage;
use Modules\RefundModule\Entities\RefundProduct;
use Modules\Wallet\Entities\Wallet;

class AdminRefundController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:refund-list|refund-create|refund-edit|refund-delete',['only' => ['all_tickets','priority_change','status_change']]);
        $this->middleware('permission:refund-create',['only' => ['new_ticket','store_ticket']]);
        $this->middleware('permission:refund-edit',['only' => ['priority_change','status_change']]);
        $this->middleware('permission:refund-delete',['only' => ['delete','bulk_action']]);
    }

    private const BASE_PATH = 'refundmodule::tenant.admin.refund.';

    public function index()
    {
        $refunds = RefundProduct::all();
        return view(self::BASE_PATH.'index')->with(['refunds' => $refunds]);
    }
    public function status_change(Request $request){
        $request->validate([
            'status' => 'required|string|max:191'
        ]);

        RefundProduct::findOrFail($request->id)->update([
            'status' => $request->status,
        ]);

        return response()->json('ok');
    }

    public function product_view($id)
    {
        if (!empty($id)) {
            $order_product = OrderProducts::where('product_id', $id)->first();
            $order_details = ProductOrder::find($order_product->order_id);
            $product = Product::find($id);
        }

        return view(self::BASE_PATH. 'view', compact('order_product','order_details', 'product'));
    }
}
