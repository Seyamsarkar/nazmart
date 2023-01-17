<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Actions\Payment\PaymentGateways;
use App\Events\TenantRegisterEvent;
use App\Http\Controllers\Controller;
use App\Listeners\TenantDomainCreate;
use App\Mail\TenantCredentialMail;
use App\Models\FormBuilder;
use App\Models\Language;
use App\Mail\BasicMail;
use App\Mail\OrderReply;
use App\Mail\PlaceOrder;
use App\Models\Order;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use App\Models\Tenant;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:package-order-all-order|package-order-pending-order|package-order-progress-order|
        package-order-edit|package-order-delete|package-order-complete-order|package-order-success-order-page|package-order-order-page-manage
        package-order-order-report');
    }

    private const ROOT_PATH = 'landlord.admin.package-order-manage.';

    public function all_orders(Request $request)
    {
        if ($request->filter != null && $request->filter != 'all')
        {
            $all_orders = PaymentLogs::where('status', $request->filter)->get();
        } else {
            $all_orders = PaymentLogs::all();
        }

        return view(self::ROOT_PATH.'order-manage-all')->with(['all_orders' => $all_orders]);
    }

    public function view_order($id)
    {
        if(!empty($id)){
            $order = PaymentLogs::find($id);
        }

        return view(self::ROOT_PATH.'order-view',compact('order'));
    }

    public function pending_orders(){
        $all_orders = PaymentLogs::where('status','pending')->get();
        return view(self::ROOT_PATH.'order-manage-pending')->with(['all_orders' => $all_orders]);
    }

    public function completed_orders(){
        $all_orders = PaymentLogs::where('status','complete')->get();
        return view(self::ROOT_PATH.'order-manage-completed')->with(['all_orders' => $all_orders]);
    }
    public function in_progress_orders(){
        $all_orders = PaymentLogs::where('status','in_progress')->get();
        return view(self::ROOT_PATH.'order-manage-in-progress')->with(['all_orders' => $all_orders]);
    }

    public function change_status(Request $request){
        $this->validate($request,[
            'order_status' => 'required|string|max:191',
            'order_id' => 'required|string|max:191'
        ]);

        $order_details = PaymentLogs::find($request->order_id);
        $order_details->status = $request->order_status;
        $order_details->save();
        $data['subject'] = __('your order status has been changed');
        $data['message'] = __('hello').' '.$order_details->name .'<br>';
        $data['message'] .= __('your order').' #'.$order_details->id.' ';
        $data['message'] .= __('status has been changed to').' '.str_replace('_',' ',$request->order_status).'.';

        //send mail while order status change
        try {
            Mail::to($order_details->email)->send(new BasicMail($data['message'], $data['subject']));
        }catch(\Exception $e){

        return redirect()->back()->with(['type'=> 'danger', 'msg' => $e->getMessage()]);
        }


        return redirect()->back()->with(['msg' => __('PaymentLogs Status Update Success...'),'type' => 'success']);
    }

    public function order_reminder(Request $request){
        $order_details = PaymentLogs::find($request->id);
        $route = route('landlord.frontend.plan.order',optional($order_details->package)->id);
        //send order reminder mail
        $data['subject'] = __('your order is still in pending at').' '. get_static_option('site_'.get_default_language().'_title');
        $data['message'] = __('hello').' '.$order_details->name .'<br>';
        $data['message'] .= __('your order').' #'.$order_details->id.' ';
        $data['message'] .= __('is still in pending, to complete your order go to');
        $data['message'] .= ' <br> <br><a href="'.$route.'">'.__('go to payment ').'</a>';
        //send mail while order status change

        try {
            Mail::to($order_details->email)->send(new BasicMail($data['message'], $data['subject'] ));
        }catch(\Exception $e){
            return redirect()->back()->with(['type'=> 'danger', 'msg' => $e->getMessage()]);
        }

        return redirect()->back()->with(['msg' => __('PaymentLogs Reminder Mail Send Success...'),'type' => 'success']);
    }

    public function order_delete(Request $request,$id)
    {
        $log = PaymentLogs::findOrFail($id);
        $user = \App\Models\User::findOrFail($log->user_id);

        if(!empty($user)){
            return redirect()->back()->with(['msg' => __('You cannot delete this item, This data is associated with a user, please delete the user then it will be deleted automatically..!'),'type' => 'danger']);
        }
        $log->delete();

        return redirect()->back()->with(['msg' => __('PaymentLogs Deleted Success...'),'type' => 'danger']);
    }


    public function send_mail(Request $request){
        $this->validate($request,[
            'email' => 'required|string|max:191',
            'name' => 'required|string|max:191',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);
        $subject = str_replace('{site}',get_static_option('site_'.get_default_language().'_title'),$request->subject);
        $data = [
            'name' => $request->name,
            'message' => $request->message,
        ];
        try{
          Mail::to($request->email)->send(new OrderReply($data,$subject));
        }catch(\Exception $e){
            return redirect()->back()->with(['type'=> 'danger', 'msg' => $e->getMessage()]);
        }
        return redirect()->back()->with(['msg' => __('Order Reply Mail Send Success...'),'type' => 'success']);
    }


    public function all_payment_logs(){
        $paymeng_logs = PaymentLogs::all();
        return view('landlord.admin.payment-logs.payment-logs-all')->with(['payment_logs' => $paymeng_logs]);
    }

    public function payment_logs_delete(Request $request,$id){
        $log = PaymentLogs::findOrFail($id);
        $user = \App\Models\User::findOrFail($log->user_id);
        if(!empty($user)){
            return redirect()->back()->with(['msg' => __('You cannot delete this item, This data is associated with a user, please delete the user then it will be deleted automatically..!'),'type' => 'danger']);
        }

        $log->delete();
        return redirect()->back()->with(['msg' => __('Payment Log Delete Success...'),'type' => 'danger']);
    }

    public function payment_logs_approve(Request $request, $id){
        $payment_logs = PaymentLogs::find($id);
        $payment_logs->payment_status = 'complete';
        $payment_logs->save();

        $msg = __('Payment status changed successfully..!');
        if ($payment_logs->payment_status == 'complete')
        {
            (new PaymentGateways())->tenant_create_event_with_credential_mail($payment_logs->id);
            $payment_logs->order_id = $payment_logs->id;
            (new PaymentGateways())->update_tenant($payment_logs);
            $msg .= ' '.__('And a new tenant has been created for the payment log');
        }

        $subject = __('Your order payment has been approved');
        $message = __('Your order has been approved.').' #'.$payment_logs->id;
        $message .= ' '.__('Package:').' '.$payment_logs->package_name;

        Mail::to($payment_logs->email)->send(new BasicMail($message, $subject));

        return redirect()->back()->with(['msg' => __('Manual Payment Accept Success'),'type' => 'success']);
    }

    public function order_success_payment(){
        $all_languages = Language::all();
        return view(self::ROOT_PATH.'order-success-page')->with(['all_languages' => $all_languages]);
    }

    public function update_order_success_payment(Request $request){

        $all_language = Language::all();
        foreach ($all_language as $lang) {
            $this->validate($request, [
                'site_order_success_page_' . $lang->slug . '_title' => 'nullable',
                'site_order_success_page_' . $lang->slug . '_description' => 'nullable',
            ]);
            $title = 'site_order_success_page_' . $lang->slug . '_title';
            $description = 'site_order_success_page_' . $lang->slug . '_description';

            update_static_option($title, $request->$title);
            update_static_option($description, $request->$description);
        }
        return redirect()->back()->with(['msg' => __('PaymentLogs Success Page Update Success...'),'type' => 'success']);
    }

    public function order_cancel_payment(){
        $all_languages = Language::all();
        return view(self::ROOT_PATH.'order-cancel-page')->with(['all_languages' => $all_languages]);
    }

    public function update_order_cancel_payment(Request $request)
    {
        $all_language = Language::all();
        foreach ($all_language as $lang) {
            $this->validate($request, [
                'site_order_cancel_page_' . $lang->slug . '_title' => 'nullable',
                'site_order_cancel_page_' . $lang->slug . '_subtitle' => 'nullable',
                'site_order_cancel_page_' . $lang->slug . '_description' => 'nullable',
            ]);
            $title = 'site_order_cancel_page_' . $lang->slug . '_title';
            $subtitle = 'site_order_cancel_page_' . $lang->slug . '_subtitle';
            $description = 'site_order_cancel_page_' . $lang->slug . '_description';

            update_static_option($title, $request->$title);
            update_static_option($subtitle, $request->$subtitle);
            update_static_option($description, $request->$description);
        }
        return redirect()->back()->with(['msg' => __('PaymentLogs Cancel Page Update Success...'),'type' => 'success']);
    }

    public function bulk_action(Request $request){
        $all = PaymentLogs::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

    public function payment_log_bulk_action(Request $request){
        $all = PaymentLogs::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

    public function order_report(Request  $request){

        $order_data = '';
        $query = PaymentLogs::query();
        if (!empty($request->start_date)){
            $query->whereDate('created_at','>=',$request->start_date);
        }
        if (!empty($request->end_date)){
            $query->whereDate('created_at','<=',$request->end_date);
        }
        if (!empty($request->order_status)){
            $query->where(['status' => $request->order_status ]);
        }
        if (!empty($request->payment_status)){
            $query->where(['payment_status' => $request->payment_status ]);
        }
        $error_msg = __('select start & end date to generate order report');
        if (!empty($request->start_date) && !empty($request->end_date)){
            $query->orderBy('id','DESC');
            $order_data =  $query->paginate($request->items);
            $error_msg = '';
        }

        return view(self::ROOT_PATH.'order-report')->with([
            'order_data' => $order_data,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'items' => $request->items,
            'order_status' => $request->order_status,
            'payment_status' => $request->payment_status,
            'error_msg' => $error_msg
        ]);
    }

    public function payment_report(Request  $request){

        $order_data = '';
        $query = PaymentLogs::query();
        if (!empty($request->start_date)){
            $query->whereDate('created_at','>=',$request->start_date);
        }
        if (!empty($request->end_date)){
            $query->whereDate('created_at','<=',$request->end_date);
        }
        if (!empty($request->payment_status)){
            $query->where(['status' => $request->payment_status ]);
        }
        $error_msg = __('select start & end date to generate payment report');
        if (!empty($request->start_date) && !empty($request->end_date)){
            $query->orderBy('id','DESC');
            $order_data =  $query->paginate($request->items);
            $error_msg = '';
        }

        return view('landlord.admin.payment-logs.payment-report')->with([
            'order_data' => $order_data,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'items' => $request->items,
            'payment_status' => $request->payment_status,
            'error_msg' => $error_msg
        ]);
    }

    public function index(){
        $all_custom_form = FormBuilder::all();
        return view(self::ROOT_PATH.'form-section')->with(['all_custom_form' => $all_custom_form]);
    }
    public function udpate(Request $request){
        $this->validate($request,[
            'order_form' => 'nullable|string',
        ]);

        $all_language = Language::all();

        foreach ($all_language as $lang){

            $this->validate($request,[
                'order_page_'.$lang->slug.'_form_title' => 'nullable|string',
            ]);
            $field = 'order_page_'.$lang->slug.'_form_title';
            update_static_option('order_page_'.$lang->slug.'_form_title',$request->$field);
        }

        update_static_option('order_form',$request->order_form);

        return redirect()->back()->with(['msg' => __('Settings Updated....'),'type' => 'success']);
    }

    public function generate_package_invoice(Request $request)
    {
        $payment_details = PaymentLogs::find($request->id);
        if (empty($payment_details)) {
            return abort(404);
        }

        $pdf = PDF::loadview('landlord.frontend.invoice.package-order', ['payment_details' => $payment_details])->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('package-invoice.pdf');
    }


    public function payment_log_payment_status_change($id)
    {
        try {
            $payment_log = PaymentLogs::findOrFail($id);
            $payment_log->payment_status = 'complete';

            $payment_log->save();

            $msg = __('Payment status changed successfully..!');
            if ($payment_log->payment_status == 'complete')
            {
                (new PaymentGateways())->tenant_create_event_with_credential_mail($payment_log->id);
                $payment_log->order_id = $payment_log->id;
                (new PaymentGateways())->update_tenant($payment_log);

                $msg .= ' '.__('And a new tenant has been created for the payment log');
            }
        } catch (\Exception $exception) {

        }

        return redirect()->back()->with(['msg' => $msg,'type' => 'success']);
    }
}
