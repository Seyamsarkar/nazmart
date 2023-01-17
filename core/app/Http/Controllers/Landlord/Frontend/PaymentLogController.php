<?php

namespace App\Http\Controllers\Landlord\Frontend;

use App\Actions\Payment\PaymentGateways;
use App\Events\TenantRegisterEvent;
use App\Helpers\FlashMsg;
use App\Helpers\Payment\DatabaseUpdateAndMailSend\LandlordPricePlanAndTenantCreate;
use App\Helpers\Payment\PaymentGatewayCredential;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Mail\PlaceOrder;
use App\Mail\TenantCredentialMail;
use App\Models\FormBuilder;
use App\Models\PaymentGateway;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use App\Models\Tenant;
use App\Models\User;
use App\Models\ZeroPricePlanHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Entities\WalletSettings;
use Modules\Wallet\Entities\WalletTenantList;
use Modules\Wallet\Http\Services\WalletService;
use Xgenious\Paymentgateway\Facades\XgPaymentGateway;


class PaymentLogController extends Controller
{
    private const CANCEL_ROUTE = 'landlord.frontend.order.payment.cancel';
    private const SUCCESS_ROUTE = 'landlord.frontend.order.payment.success';

    private float $total;
    private object $payment_details;
    protected function cancel_page()
    {
        return redirect()->route('landlord.frontend.order.payment.cancel.static');
    }

    public function order_payment_form(Request $request)
    {
        $manual_transection_condition = $request->selected_payment_gateway == 'manual_payment' ? 'required' : 'nullable';
        $request_pack_id = $request->package_id;
        $plan = PricePlan::findOrFail($request_pack_id);
        if($plan->price == 0)
        {
            $request->selected_payment_gateway = 'manual_payment';

            $purchased_packaged = ZeroPricePlanHistory::where('user_id', Auth::guard('web')->user()->id)->count();
            $zero_plan_limit = get_static_option('zero_plan_limit');

            if ($purchased_packaged >= $zero_plan_limit)
            {
                return back()->with(FlashMsg::explain('danger', 'Sorry! You can not purchase more free plan.'));
            }
        }

        $data = $request->validate([
            'name' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
            'package_id' => 'required|string',
            'payment_gateway' => 'nullable|string',
            'trasaction_id' => '' . $manual_transection_condition . '',
            'trasaction_attachment' => '' . $manual_transection_condition . '|mimes:jpeg,png,jpg,gif|max:2048',
            'subdomain' => "required_if:custom_subdomain,!=,null",
            'custom_subdomain' => "required_if:subdomain,==,custom_domain__dd",
        ],
            [
                "custom_subdomain.required_if" => "Custom Sub Domain Required",
                "trasaction_id" => "Transaction ID Required",
                "trasaction_attachment" => "Transaction Attachment Required",
            ]);

        if ($request->custom_subdomain == null) {
            $request->validate([
                'subdomain' => 'required'
            ]);

            $existing_lifetime_plan = PaymentLogs::where(['tenant_id' => $request->subdomain, 'payment_status' => 'complete', 'expire_date' => null])->first();
            if ($existing_lifetime_plan != null) {
                return back()->with(['type' => 'danger', 'msg' => 'You are already using a lifetime plan']);
            }
        }

        if ($request->custom_subdomain != null) {
            $has_subdomain = Tenant::find(trim($request->custom_subdomain));
            if (!empty($has_subdomain)) {
                return back()->with(['type' => 'danger', 'msg' => 'This subdomain is already in use, Try something different']);
            }

            $site_domain = url('/');
            $site_domain = str_replace(['http://', 'https://'], '', $site_domain);
            $site_domain = substr($site_domain, 0, strpos($site_domain, '.'));
            $restricted_words = ['https', 'http', 'http://', 'https://','www', 'subdomain', 'domain', 'primary-domain', 'central-domain',
                'landlord', 'landlords', 'tenant', 'tenants', 'multi-store', 'multistore', 'admin',
                'user', 'user', $site_domain];

            if (in_array(trim($request->custom_subdomain), $restricted_words))
            {
                return back()->with(FlashMsg::explain('danger', 'Sorry, You can not use this subdomain'));
            }

            $sub = $request->custom_subdomain;
            $check_type = false;
            for ($i=0; $i<strlen($sub); $i++)
            {
                if(ctype_alnum($sub[$i])) {
                    $check_type = true;
                }
            }

            if ($check_type == false)
            {
                return back()->with(FlashMsg::explain('danger', 'Sorry, You can not use this subdomain'));
            }
        }


        $order_details = PricePlan::find($request->package_id) ?? '';

        $package_start_date = '';
        $package_expire_date = '';

        if (!empty($order_details)) {
            if ($order_details->type == 0) {
                //monthly
                $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
                $package_expire_date = Carbon::now()->addMonth(1)->format('d-m-Y h:i:s');

            } elseif ($order_details->type == 1) {
                //yearly
                $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
                $package_expire_date = Carbon::now()->addYear(1)->format('d-m-Y h:i:s');
            } else {
                //lifetime
                $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
                $package_expire_date = null;
            }
        }

        if ($request->subdomain != 'custom_domain__dd') {
            $subdomain = Str::slug($request->subdomain);
        } else {
            $subdomain = Str::slug($request->custom_subdomain);
        }

        $amount_to_charge = $order_details->price;
        $this->total = $amount_to_charge;
        $request_date_remove = $request;

        $selected_payment_gateway = $request_date_remove['selected_payment_gateway'] ?? $request_date_remove['payment_gateway'];
        if ($selected_payment_gateway == null) {
            $selected_payment_gateway = 'manual_payment';
        }

        $package_id = $request_date_remove['package_id'];
        $name = $request_date_remove['name'];
        $email = $request_date_remove['email'];
        $trasaction_id = $request_date_remove['trasaction_id'];

        if ($request->trasaction_attachment != null) {
            $image = $request->file('trasaction_attachment');
            $image_extenstion = $image->extension();
            $image_name_with_ext = $image->getClientOriginalName();

            $image_name = pathinfo($image_name_with_ext, PATHINFO_FILENAME);
            $image_name = strtolower(Str::slug($image_name));
            $image_db = $image_name . time() . '.' . $image_extenstion;

            $path = global_assets_path('assets/landlord/uploads/payment_attachments/');
            $image->move($path, $image_db);
        }
        $trasaction_attachment = $image_db ?? null;

        unset($request_date_remove['custom_form_id']);
        unset($request_date_remove['payment_gateway']);
        unset($request_date_remove['package_id']);
        unset($request_date_remove['package']);
        unset($request_date_remove['pkg_user_name']);
        unset($request_date_remove['pkg_user_email']);
        unset($request_date_remove['name']);
        unset($request_date_remove['email']);
        unset($request_date_remove['trasaction_id']);
        unset($request_date_remove['trasaction_attachment']);

        $auth = auth()->guard('web')->user();
        $auth_id = $auth->id;

        $is_tenant = Tenant::find($subdomain);

        DB::beginTransaction(); // Starting all the actions as safe translations
        try {
        // Exising Tenant + Plan
        if (!is_null($is_tenant)) {
            $old_tenant_log = PaymentLogs::where(['user_id' => $auth_id, 'tenant_id' => $is_tenant->id])->latest()->first() ?? '';

            // If Payment Renewing
            if (!empty($old_tenant_log->package_id) == $request_pack_id && !empty($old_tenant_log->user_id) && $old_tenant_log->user_id == $auth_id && ($old_tenant_log->payment_status == 'complete' || $old_tenant_log->status == 'trial')) {
                if ($package_expire_date != null) {
                    $old_days_left = Carbon::now()->diff($old_tenant_log->expire_date);
                    $left_days = 0;

                    if ($old_days_left->invert == 0) {
                        $left_days = $old_days_left->days;
                    }

                    $renew_left_days = 0;
                    $renew_left_days = Carbon::parse($package_expire_date)->diffInDays();

                    $sum_days = $left_days + $renew_left_days;
                    $new_package_expire_date = Carbon::today()->addDays($sum_days)->format("d-m-Y h:i:s");
                } else {
                    $new_package_expire_date = null;
                }

                PaymentLogs::findOrFail($old_tenant_log->id)->update([
                    'email' => $email,
                    'name' => $name,
                    'package_name' => $order_details->title,
                    'package_price' => $amount_to_charge,
                    'package_gateway' => $selected_payment_gateway,
                    'package_id' => $package_id,
                    'user_id' => auth()->guard('web')->user()->id ?? null,
                    'tenant_id' => $subdomain ?? null,
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'renew_status' => is_null($old_tenant_log->renew_status) ? 1 : $old_tenant_log->renew_status + 1,
                    'is_renew' => 1,
                    'track' => Str::random(10),
                    'updated_at' => Carbon::now(),
                    'start_date' => $package_start_date,
                    'expire_date' => $new_package_expire_date
                ]);

                $payment_details = PaymentLogs::findOrFail($old_tenant_log->id);
                $this->payment_details = $payment_details;
            } // If Payment Pending
            elseif (!empty($old_tenant_log) && $old_tenant_log->payment_status == 'pending') {
                PaymentLogs::findOrFail($old_tenant_log->id)->update([
                    'email' => $email,
                    'name' => $name,
                    'package_name' => $order_details->title,
                    'package_price' => $amount_to_charge,
                    'package_gateway' => $selected_payment_gateway,
                    'package_id' => $package_id,
                    'user_id' => auth()->guard('web')->user()->id ?? null,
                    'tenant_id' => $subdomain ?? null,
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'is_renew' => $old_tenant_log->renew_status != null ? 1 : 0,
                    'track' => Str::random(10),
                    'updated_at' => Carbon::now(),
                    'start_date' => $package_start_date,
                    'expire_date' => $package_expire_date
                ]);

                $payment_details = PaymentLogs::findOrFail($old_tenant_log->id);
                $this->payment_details = $payment_details;
            }
        } // New Tenant + Plan (New Payment)
        else {
            $old_tenant_log = PaymentLogs::where(['user_id' => $auth_id, 'tenant_id' => trim($request->custom_subdomain)])->latest()->first();
            if (empty($old_tenant_log)) {
                $payment_log_id = PaymentLogs::create([
                    'email' => $email,
                    'name' => $name,
                    'package_name' => $order_details->title,
                    'package_price' => $amount_to_charge,
                    'package_gateway' => $selected_payment_gateway,
                    'package_id' => $package_id,
                    'user_id' => auth()->guard('web')->user()->id ?? null,
                    'tenant_id' => $subdomain ?? null,
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'is_renew' => 0,
                    'track' => Str::random(10),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'start_date' => $package_start_date,
                    'expire_date' => $package_expire_date,
                ])->id;

                $payment_details = PaymentLogs::findOrFail($payment_log_id);
                $this->payment_details = $payment_details;
            } else {
                $old_tenant_log->update([
                    'email' => $email,
                    'name' => $name,
                    'package_name' => $order_details->title,
                    'package_price' => $amount_to_charge,
                    'package_gateway' => $selected_payment_gateway,
                    'package_id' => $package_id,
                    'user_id' => auth()->guard('web')->user()->id ?? null,
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'is_renew' => 0,
                    'track' => Str::random(10),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'start_date' => $package_start_date,
                    'expire_date' => $package_expire_date,
                ]);

                $payment_details = PaymentLogs::findOrFail($old_tenant_log->id);
                $this->payment_details = $payment_details;
            }
        }

            DB::commit(); // Committing all the actions
        } catch (\Exception $exception) {
            DB::rollBack(); // Rollback all the actions
            return back()->with('msg', 'Something went wrong');
        }

        if ($request->selected_payment_gateway === 'manual_payment')
        {
            PaymentLogs::find($this->payment_details['id'])->update([
                'transaction_id' => $trasaction_id ?? '',
                'attachments' => $trasaction_attachment ?? '',
            ]);

            if ($this->payment_details['price'] == 0)
            {
                ZeroPricePlanHistory::create([
                    'user_id' => $this->payment_details['user_id'],
                    'plan_id' => $this->payment_details['package_id'],
                ]);
            }

            try {
                (new PaymentGateways())->send_order_mail($this->payment_details['id']);
            } catch (\Exception $e) {}

            return redirect()->route(self::SUCCESS_ROUTE, wrap_random_number($this->payment_details['id']));
        } else {
            return $this->payment_with_gateway($request->selected_payment_gateway);
        }
    }

    public function payment_with_gateway($payment_gateway_name)
    {
        try {
            $gateway_function = 'get_' . $payment_gateway_name . '_credential';
            $gateway = PaymentGatewayCredential::$gateway_function();

            $redirect_url = $gateway->charge_customer(
                $this->common_charge_customer_data($payment_gateway_name)
            );

            return $redirect_url;
        } catch (\Exception $e) {
            return back()->with(['msg' => $e->getMessage(), 'type' => 'danger']);
        }
    }

    public function common_charge_customer_data($payment_gateway_name)
    {
        $user = Auth::guard('web')->user();
        $email = $user->email;
        $name = $user->name;

        return [
            'amount' => $this->total,
            'title' => $this->payment_details['package_name'],
            'description' => 'Payment For Package Order Id: #' . $this->payment_details['id'] . ' Package Name: ' . $this->payment_details['package_name']  . ' Payer Name: ' . $this->payment_details['name']  . ' Payer Email:' . $this->payment_details['email'] ,
            'ipn_url' => route('landlord.frontend.' . strtolower($payment_gateway_name) . '.ipn', $this->payment_details['id']),
            'order_id' => $this->payment_details['id'],
            'track' => \Str::random(36),
            'cancel_url' => route(self::CANCEL_ROUTE, $this->payment_details['id']),
            'success_url' => route(self::SUCCESS_ROUTE, $this->payment_details['id']),
            'email' => $email,
            'name' => $name,
            'payment_type' => 'order',
        ];
    }


    // IPNs
    public function paypal_ipn()
    {
        $paypal = PaymentGatewayCredential::get_paypal_credential();
        $payment_data = $paypal->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function paytm_ipn()
    {
        $paytm = PaymentGatewayCredential::get_paytm_credential();
        $payment_data = $paytm->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function flutterwave_ipn()
    {
        $flutterwave = PaymentGatewayCredential::get_flutterwave_credential();
        $payment_data = $flutterwave->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function stripe_ipn()
    {
        $stripe = PaymentGatewayCredential::get_stripe_credential();
        $payment_data = $stripe->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function razorpay_ipn()
    {
        $razorpay = PaymentGatewayCredential::get_razorpay_credential();
        $payment_data = $razorpay->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function paystack_ipn()
    {
        $paystack = PaymentGatewayCredential::get_paystack_credential();
        $payment_data = $paystack->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function payfast_ipn()
    {
        $payfast = PaymentGatewayCredential::get_payfast_credential();
        $payment_data = $payfast->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function mollie_ipn()
    {
        $mollie = PaymentGatewayCredential::get_mollie_credential();
        $payment_data = $mollie->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function midtrans_ipn()
    {
        $midtrans = PaymentGatewayCredential::get_midtrans_credential();
        $payment_data = $midtrans->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function cashfree_ipn()
    {
        $cashfree = PaymentGatewayCredential::get_cashfree_credential();
        $payment_data = $cashfree->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function instamojo_ipn()
    {
        $instamojo = PaymentGatewayCredential::get_instamojo_credential();
        $payment_data = $instamojo->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function marcadopago_ipn()
    {
        $marcadopago = PaymentGatewayCredential::get_marcadopago_credential();
        $payment_data = $marcadopago->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function squareup_ipn()
    {
        $squareup = PaymentGatewayCredential::get_squareup_credential();
        $payment_data = $squareup->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function cinetpay_ipn()
    {
        $cinetpay = PaymentGatewayCredential::get_cinetpay_credential();
        $payment_data = $cinetpay->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function paytabs_ipn()
    {
        $paytabs = PaymentGatewayCredential::get_paytabs_credential();
        $payment_data = $paytabs->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function billplz_ipn()
    {
        $billplz = PaymentGatewayCredential::get_billplz_credential();
        $payment_data = $billplz->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function zitopay_ipn()
    {
        $zitopay = PaymentGatewayCredential::get_zitopay_credential();
        $payment_data = $zitopay->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function toyyibpay_ipn()
    {
        $toyyibpay = PaymentGatewayCredential::get_toyyibpay_credential();
        $payment_data = $toyyibpay->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    private function common_ipn_data($payment_data)
    {
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            try {
                $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
                $this->send_order_mail($payment_data['order_id']);
                $this->tenant_create_event_with_credential_mail($payment_data['order_id']);
                $this->update_tenant($payment_data);

            } catch (\Exception $exception) {
                    $message = $exception->getMessage();
                    if(str_contains($message,'Access denied')){
                        if(request()->ajax()){
                            abort(462,__('Database created failed, Make sure your database user has permission to create database'));
                        }
                    }

                    $payment_details = PaymentLogs::where('id',$payment_data['order_id'])->first();
                    if(empty($payment_details))
                    {
                        abort(462,__('Does not exist, Tenant does not exists'));
                    }
                    LandlordPricePlanAndTenantCreate::store_exception($payment_details->tenant_id,'Domain create',$exception->getMessage(), 0);

                //todo: send an email to admin that this user databse could not able to create automatically

                try {
                    $message = sprintf(__('Database Creating failed for user id %1$s , please checkout admin panel and generate database for this user from admin panel manually'),
                    $payment_details->user_id);
                    $subject = sprintf(__('Database Crating failed for user id %1$s'),$payment_details->user_id);
                    Mail::to(get_static_option('site_global_email'))->send(new BasicMail($message,$subject));

                } catch (\Exception $e) {
                    LandlordPricePlanAndTenantCreate::store_exception($payment_details->tenant_id,'domain failed email',$e->getMessage(), 0);
                }
            }

            $order_id = wrap_random_number($payment_data['order_id']);
            return redirect()->route(self::SUCCESS_ROUTE, $order_id);
        }

        return $this->cancel_page();
    }

    private function update_database($order_id, $transaction_id)
    {
        PaymentLogs::where('id', $order_id)->update([
            'transaction_id' => $transaction_id,
            'status' => 'complete',
            'payment_status' => 'complete',
            'updated_at' => Carbon::now()
        ]);
    }

    public function update_tenant($payment_data)
    {
        try{
            $payment_log = PaymentLogs::where('id', $payment_data['order_id'])->first();
            $tenant = Tenant::find($payment_log->tenant_id);

            \DB::table('tenants')->where('id', $tenant->id)->update([
                'renew_status' => $renew_status = is_null($tenant->renew_status) ? 0 : $tenant->renew_status+1,
                'is_renew' => $renew_status == 0 ? 0 : 1,
                'start_date' => $payment_log->start_date,
                'expire_date' => get_plan_left_days($payment_log->package_id, $tenant->expire_date)
            ]);


        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            if(str_contains($message,'Access denied')){
                abort(462,__('Database created failed, Make sure your database user has permission to create database'));
            }
        }

    }

    public function send_order_mail($order_id)
    {
        $package_details = PaymentLogs::where('id', $order_id)->first();
        $all_fields = [];
        unset($all_fields['package']);
        $all_attachment = [];
        $order_mail = get_static_option('order_page_form_mail') ? get_static_option('order_page_form_mail') : get_static_option('site_global_email');

        try {
            Mail::to($order_mail)->send(new PlaceOrder($all_fields, $all_attachment, $package_details, "admin", 'regular'));
            Mail::to($package_details->email)->send(new PlaceOrder($all_fields, $all_attachment, $package_details, 'user', 'regular'));

        } catch (\Exception $e) {
            return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
        }
    }

    public function tenant_create_event_with_credential_mail($order_id)
    {
        $log = PaymentLogs::findOrFail($order_id);
        if (empty($log))
        {
            abort(462,__('Does not exist, Tenant does not exists'));
        }

        $user = User::where('id', $log->user_id)->first();
        $tenant = Tenant::find($log->tenant_id);

        if (!empty($log) && $log->payment_status == 'complete' && is_null($tenant)) {
            event(new TenantRegisterEvent($user, $log->tenant_id));
            try {
                $raw_pass = get_static_option('tenant_default_pass') ??'12345678';
                $credential_password = $raw_pass;
                $credential_email = $user->email;
                $credential_username = get_static_option('tenant_default_username') ?? 'super_admin';

                Mail::to($credential_email)->send(new TenantCredentialMail($credential_username, $credential_password));

            } catch (\Exception $e) {

            }

        } else if (!empty($log) && $log->payment_status == 'complete' && !is_null($tenant) && $log->is_renew == 0) {
            try {
                $raw_pass = get_static_option('tenant_default_pass') ??'12345678';
                $credential_password = $raw_pass;
                $credential_email = $user->email;
                $credential_username = get_static_option('tenant_default_username') ?? 'super_admin';

                Mail::to($credential_email)->send(new TenantCredentialMail($credential_username, $credential_password));

            } catch (\Exception $exception) {
                $message = $exception->getMessage();
                if(str_contains($message,'Access denied')){
                        abort(463,__('Database created failed, Make sure your database user has permission to create database'));
                }
            }
        }

        return true;
    }
}
