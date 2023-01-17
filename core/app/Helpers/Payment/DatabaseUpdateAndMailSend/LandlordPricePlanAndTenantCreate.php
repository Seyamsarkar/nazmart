<?php

namespace App\Helpers\Payment\DatabaseUpdateAndMailSend;

use App\Events\TenantRegisterEvent;
use App\Mail\PlaceOrder;
use App\Mail\TenantCredentialMail;
use App\Models\PaymentLogs;
use App\Models\Tenant;
use App\Models\TenantException;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use DB;
use App\Mail\BasicMail;


class LandlordPricePlanAndTenantCreate
{
    public static function update_database($order_id, $transaction_id)
    {
        $previous_log = PaymentLogs::where('id', $order_id)->first();
        PaymentLogs::where('id', $order_id)->update([
            'transaction_id' => $transaction_id,
            'status' => $previous_log->status == 'trial' ? 'trial' : 'complete',
            'payment_status' => $previous_log->status == 'trial' ? 'pending' : 'complete',
            'updated_at' => Carbon::now()
        ]);
    }

    public static function update_tenant($payment_data, $theme = 'theme-1')
    {
        $payment_log = PaymentLogs::where('id', $payment_data['order_id'])->first();
        $tenant = Tenant::find($payment_log->tenant_id);

        try {
            \DB::table('tenants')->where('id', $tenant->id)->update([
                'start_date' => $payment_log->start_date,
                'expire_date' => $payment_log->status == 'trial' ? $payment_log->expire_date : get_plan_left_days($payment_log->package_id, $tenant->expire_date),
                'user_id' => $payment_log->user_id,
                'theme_slug' => $theme
            ]);
        }catch (\Exception $e){
            self::store_exception($payment_log->tenant_id,'database update',$e->getMessage(),1);
        }


    }

    public static function send_order_mail($order_id)
    {
        $package_details = PaymentLogs::findOrFail($order_id);
        $all_fields = [];
        unset($all_fields['package']);
        $all_attachment = [];
        $order_mail = get_static_option('order_page_form_mail') ? get_static_option('order_page_form_mail') : get_static_option('site_global_email');

        try {
            Mail::to($order_mail)->send(new PlaceOrder($all_fields, $all_attachment, $package_details, "admin", 'regular'));
            Mail::to($package_details->email)->send(new PlaceOrder($all_fields, $all_attachment, $package_details, 'user', 'regular'));

        } catch (\Exception $e) {
            self::store_exception($package_details->tenant_id,'order mail',$e->getMessage(),1);
            return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
        }
    }


    public static function tenant_create_event_with_credential_mail($order_id,$event=true)
    {
        $log = PaymentLogs::findOrFail($order_id);
        $user = User::where('id', $log->user_id)->first();
        $tenant = Tenant::find($log->tenant_id);

        if (!empty($log) && $log->payment_status == 'complete' && is_null($tenant)) {
            try{
                self::createDatabaseUsingEventListener($log,$user,$event);
            }catch (\Exception $e) {

                self::store_exception($log->tenant_id,'domain create failed',$e->getMessage(),0);
            }

        } else if (!empty($log) && $log->payment_status == 'complete' && !is_null($tenant) && $log->is_renew == 0) {
            try{
                self::createDatabaseUsingEventListener($log,$user,$event);
            }catch (\Exception $e) {

                self::store_exception($log->tenant_id,'domain create failed',$e->getMessage(),0);
            }
        }


        if(empty($tenant->domain)){
            self::store_exception($log->tenant_id,'domain create failed',__('Not root username password entry'),0);
        }

        return true;
    }

    public static function createDatabaseUsingEventListener($log,$user,$event=true){

        if($event){
            event(new TenantRegisterEvent($user, $log->tenant_id));
        }

        $raw_pass = get_static_option('tenant_default_pass') ?? '12345678';
        $credential_password = $raw_pass;
        $credential_email = $user->email;
        $credential_username = get_static_option('tenant_default_username') ?? 'super_admin';

        try{
            Mail::to($credential_email)->send(new TenantCredentialMail($credential_username, $credential_password));
        }catch(\Exception $e){}
    }

    public static function store_exception($tenant_id,$issue_type,$description,$domain_create_status)
    {
        TenantException::create([
            'tenant_id' => $tenant_id,
            'issue_type' => $issue_type,
            'description' => $description,
            'domain_create_status' => $domain_create_status,
        ]);


        $admin_email = get_static_option('site_global_email');

        $data['subject'] = __('User Domain or database create failed');
        $data['message'] = __('hello') . '<br>';
        $data['message'] .= __('This users domain create failed please take action of ') . ':' . $tenant_id . ' ';
        try {
            //send mail while order status change
            Mail::to($admin_email)->send(new BasicMail($data['message'], $data['subject']));
        } catch (\Exception $e) {
            //handle error
        }
    }

}
