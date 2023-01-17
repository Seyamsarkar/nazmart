<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Actions\Tenant\TenantCreateEventWithMail;
use App\Events\TenantRegisterEvent;
use App\Helpers\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Mail\PlaceOrder;
use App\Models\CustomDomain;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Types\Self_;
use Spatie\Activitylog\Models\Activity;

class TenantManageController extends Controller
{
    const BASE_PATH = 'landlord.admin.tenant.';
    public function all_tenants(){
        $all_users = User::latest()->paginate(10);
        return view(self::BASE_PATH.'index',compact('all_users'));
    }

    public function new_tenant()
    {
        return view(self::BASE_PATH.'new');
    }

    public function new_tenant_store(Request $request)
    {
        $request->validate([
            'name'=> 'required|string|max:191',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'country'=> 'nullable',
            'city'=> 'nullable',
            'mobile'=> 'nullable',
            'state'=> 'nullable',
            'address'=> 'nullable',
            'image'=> 'nullable',
            'company'=> 'nullable',
        ]);

           User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'username' => Str::slug($request->username),
            'subdomain' => Str::slug($request->subdomain),
            'country' => $request->country,
            'city' => $request->city,
            'mobile' => $request->mobile,
            'state' => $request->state,
            'address' => $request->address,
            'image' => $request->image,
            'company' => $request->company,
        ]);

        return response()->success(ResponseMessage::success(__('Tenant has been created successfully..!')));

    }

    public function edit_profile($id)
    {

        $user = User::find($id);
        return view(self::BASE_PATH.'edit',compact('user'));
    }

    public function update_edit_profile(Request $request)
    {
        $request->validate([
            'name'=> 'required|string|max:191',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users','email')->ignore($request->id)],
            'username' => ['required', 'string','max:255', Rule::unique('users','username')->ignore($request->id)],
            'country'=> 'nullable',
            'city'=> 'nullable',
            'mobile'=> 'nullable',
            'state'=> 'nullable',
            'address'=> 'nullable',
            'image'=> 'nullable',
            'company'=> 'nullable',
        ]);

     User::where('id',$request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'username' => Str::slug($request->username),
            'country' => $request->country,
            'city' => $request->city,
            'mobile' => $request->mobile,
            'state' => $request->state,
            'address' => $request->address,
            'image' => $request->image,
            'company' => $request->company,
        ]);

        return response()->success(ResponseMessage::success(__('Tenant updated successfully..!')));

    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $tenants = Tenant::where('user_id',$user->id)->get();

        foreach ($tenants ?? [] as $tenant)
        {
            $path = 'assets/tenant/uploads/media-uploader/'.$tenant->id;

            if(\File::exists($path) && is_dir($path)){
                File::deleteDirectory($path);
            }
        }

        PaymentLogs::where('user_id',$user->id)->delete();
        CustomDomain::where('user_id',$user->id)->delete();

        if(!empty($tenants)){
            foreach ($tenants as $tenant)
            {
                $tenant->domains()->delete();
                $tenant->delete();
            }
        }

        $user->delete();

        return response()->danger(ResponseMessage::delete(__('Tenant deleted successfully..!')));
    }

    public function update_change_password(Request $request)
    {
        $this->validate(
            $request,[
                'password' => 'required|string|min:8|confirmed'
            ],
            [
                'password.required' => __('password is required'),
                'password.confirmed' => __('password does not matched'),
                'password.min' => __('password minimum length is 8'),
            ]
        );
        $user = User::findOrFail($request->ch_user_id);
        $user->password = Hash::make($request->password);
        $user->save();
         return response()->success(ResponseMessage::success(__('Password updated successfully..!')));
    }


    public function send_mail(Request $request){

        $this->validate($request,[
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

            $sub =  $request->subject;
            $msg = $request->message;

        try {
            Mail::to($request->email)->send(new BasicMail($msg,$sub));
        }catch (\Exception $ex){
            return response()->danger(ResponseMessage::delete($ex->getMessage()));
        }

        return response()->success(ResponseMessage::success(__('Mail Send Successfully..!')));
    }

    public function resend_verify_mail(Request $request){


        $subscriber_details = User::findOrFail($request->id);
        $token = $subscriber_details->email_verify_token ? $subscriber_details->email_verify_token  : Str::random(8);

        if (empty($subscriber_details->email_verify_token)){
            $subscriber_details->email_verify_token = $token;
            $subscriber_details->save();
        }
             $message = __('Verification Code: ').'<strong>'.$token.'</strong>'.'<br>'.__('Verify your email to get all news from '). get_static_option('site_'.get_default_language().'_title') . '<div class="btn-wrap"> <a class="anchor-btn" href="' . route('landlord.user.login') . '">' . __('Login') . '</a></div>';

            $msg = $message;
            $subject = __('verify your email');


        try {
            Mail::to($subscriber_details->email)->send(new BasicMail($msg,$subject));
        }catch (\Exception $ex){
            return response()->danger(ResponseMessage::delete($ex->getMessage()));
        }

        return response()->success(ResponseMessage::success(__('Email Verify Mail Send Successfully..!')));
    }

    public function tenant_activity_log()
    {
        $activities = Activity::with(['subject','causer'])->latest()->paginate(8);
        return view(self::BASE_PATH.'activity-log',compact('activities'));
    }

    public function tenant_details($id)
    {
        $user = User::with('tenant_details','tenant_details.payment_log')->findOrFail($id);

        return view(self::BASE_PATH.'details',compact('user'));
    }

    public function tenant_domain_delete($tenant_id)
    {
        //old domain = same = tenant id //

        $tenant = Tenant::findOrFail($tenant_id);
        $user_id = $tenant->user_id;

        $path = 'assets/tenant/uploads/media-uploader/'.$tenant->id;
        CustomDomain::where([['old_domain', $tenant->id], ['custom_domain_status', '!=','connected']])
                    ->orWhere([['custom_domain', $tenant->id], ['custom_domain_status', '==', 'connected']])->delete();
        PaymentLogs::where('tenant_id',$tenant->id)->delete();

        if(!empty($tenant)){
            $tenant->domains()->delete();
            $tenant->delete();
        }
        if(\File::exists($path) && is_dir($path)){
            File::deleteDirectory($path);
        }

        $check_tenant = Tenant::where('user_id', $user_id)->count();
        if ($check_tenant >! 0)
        {
            User::findOrFail($user_id)->update(['has_subdomain' => false]);
        }

        return response()->danger(ResponseMessage::delete(__('Tenant deleted successfully..!')));
    }

    public function tenant_account_status(Request $request)
    {
        $request->validate([
           'payment_log_id' => 'required',
           'account_status' => 'required',
           'payment_status' => 'required',
        ]);

        $payment_log = PaymentLogs::findOrFail($request->payment_log_id)->update([
            'status' => $request->account_status,
            'payment_status' => $request->payment_status
        ]);

        return back()->with(ResponseMessage::success(__('Tenant account status is updated..')));
    }

    public function assign_subscription(Request $request)
    {
       $request->validate([
           'package' => 'required',
           'payment_status' => 'required',
       ]);

       if ($request->custom_subdomain == null)
       {
           $request->validate([
               'subdomain' => 'required'
           ]);
       }

        $subdomain = $request->custom_subdomain != null ? $request->custom_subdomain : $request->subdomain;

        $user = User::findOrFail($request->subs_user_id);
        $package = PricePlan::findOrFail($request->subs_pack_id);

        $package_start_date = '';
        $package_expire_date =  '';
        if(!empty($package)){

            if($package->type == 0){ //monthly
                $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
                $package_expire_date = Carbon::now()->addMonth(1)->format('d-m-Y h:i:s');

            }elseif ($package->type == 1){ //yearly
                $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
                $package_expire_date = Carbon::now()->addYear(1)->format('d-m-Y h:i:s');
            }else{ //lifetime
                $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
                $package_expire_date = null;
            }
        }

        $tenant = Tenant::find($subdomain);
        if (!empty($tenant))
        {
            $old_tenant_log = PaymentLogs::where(['user_id'=>$user->id, 'tenant_id' => $tenant->id ])->latest()->first();

            if (!empty($old_tenant_log->package_id) && !empty($old_tenant_log->user_id) && $old_tenant_log->user_id == $user->id)
            {
                $old_days_left = Carbon::now()->diff($old_tenant_log->expire_date);
                $left_days = 0;
                if(!$old_days_left->invert){
                    $left_days = $old_days_left->days;
                }

                $new_days_left = Carbon::now()->diff($package_expire_date);
                $renew_left_days = 0;
                if(!$new_days_left->invert){
                    $renew_left_days = $new_days_left->days;
                }

                $sum_days = $left_days + $renew_left_days;
                $new_package_expire_date = Carbon::now()->addDay($sum_days)->format("d-m-Y h:i:s");

                PaymentLogs::findOrFail($old_tenant_log->id)->update([
                    'custom_fields' =>  [],
                    'attachments' =>  [],
                    'email' => $old_tenant_log->email,
                    'name' => $old_tenant_log->name,
                    'package_name' => $package->title,
                    'package_price' => $package->price,
                    'package_gateway' => null,
                    'package_id' => $package->id,
                    'user_id' => $old_tenant_log->user_id,
                    'tenant_id' => $tenant->id,
                    'status' => 'pending',
                    'payment_status' => $request->payment_status,
                    'renew_status' => is_null($old_tenant_log->renew_status) ? 1 : $old_tenant_log->renew_status + 1,
                    'is_renew' => 1,
                    'track' => Str::random(10) . Str::random(10),
                    'updated_at' => Carbon::now(),
                    'start_date' => $package_start_date,
                    'expire_date' => $new_package_expire_date
                ]);

                DB::table('tenants')->where('id', $tenant->id)->update([
                    'renew_status' => $renew_status = is_null($tenant->renew_status) ? 0 : $tenant->renew_status+1,
                    'is_renew' => $renew_status == 0 ? 0 : 1,
                    'start_date' => $package_start_date,
                    'expire_date' => $new_package_expire_date
                ]);

                $payment_details = PaymentLogs::findOrFail($old_tenant_log->id);
            }
        } else {
            event(new TenantRegisterEvent($user, $subdomain, get_static_option('default_theme')));
            $tenant = DB::table('tenants')->where('user_id', $user->id)->latest()->select('id')->first();

            $payment_log_id = PaymentLogs::create([
                'email' => $user->email,
                'name' => $user->name,
                'package_name' => $package->title,
                'package_price' => $package->price,
                'package_gateway' => null,
                'package_id' => $package->id,
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'status' => 'pending',
                'is_renew' => 0,
                'payment_status' => $request->payment_status,
                'track' => Str::random(10) . Str::random(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'start_date' => $package_start_date,
                'expire_date' => $package_expire_date,
            ]);

            DB::table('tenants')->where('id', $payment_log_id->tenant_id)->update([
                'start_date' => $package_start_date,
                'expire_date' => $package_expire_date,
            ]);
            $payment_details = PaymentLogs::findOrFail($payment_log_id->id);
        }

        $order_mail = get_static_option('order_page_form_mail') ? get_static_option('order_page_form_mail') : get_static_option('site_global_email');

        try {
            $all_fields = [];
            $all_attachment = [];
            Mail::to($order_mail)->send(new PlaceOrder($all_fields, $all_attachment, $payment_details,"admin",'custom_sub'));
            Mail::to($payment_details->email)->send(new PlaceOrder($all_fields, $all_attachment, $payment_details,'user','custom_sub'));

        }catch (\Exception $e){
            return redirect()->back()->with(['type'=> 'danger', 'msg' => $e->getMessage()]);
        }

        return response()->success(ResponseMessage::success(__('Subscription assigned for this user')));
    }


    public function account_settings()
    {
       return view(self::BASE_PATH.'settings');
    }

    public function account_settings_update(Request $request)
    {
         $request->validate([
            'tenant_account_delete_notify_mail_days' => 'required',
            'account_remove_day_within_expiration' => 'required|alpha_num|min:1',
        ]);

        $limit_days = 15;

        if($request->account_remove_day_within_expiration >= $limit_days){

            return redirect()->back()->with(['type'=> 'danger', 'msg' => sprintf('You can not set remove account day avobe %d',$limit_days)]);
        }

        update_static_option('tenant_account_delete_notify_mail_days',json_encode($request->tenant_account_delete_notify_mail_days));
        update_static_option('account_remove_day_within_expiration',$request->account_remove_day_within_expiration);

        return response()->success(ResponseMessage::success());

    }

}
