<?php

namespace App\Actions\Tenant;

use App\Models\PaymentLogs;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TenantTrialPaymentLog
{
//    public static function trial_payment_log($user, $plan)
//    {
//        $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
//        $package_expire_date = Carbon::now()->addDays($plan->trial_days)->format('d-m-Y h:i:s');;
//
//        $tenant = \DB::table('tenants')->where('user_id', $user->id)->latest()->select('id')->first();
//        PaymentLogs::create([
//            'email' => $user->email,
//            'name' => $user->name,
//            'package_name' => $plan->title,
//            'package_price' => $plan->price,
//            'package_id' => $plan->id,
//            'user_id' => $user->id ?? null,
//            'tenant_id' => $tenant->id ?? null,
//            'status' => 'trial',
//            'payment_status' => 'pending',
//            'is_renew' => 0,
//            'track' => Str::random(10) . Str::random(10),
//            'created_at' => \Illuminate\Support\Carbon::now(),
//            'updated_at' => Carbon::now(),
//            'start_date' => $package_start_date,
//            'expire_date' => $package_expire_date,
//        ]);
//
//        \DB::table('tenants')->where('id', $tenant->id)->update([
//            'start_date' => $package_start_date,
//            'expire_date' => $package_expire_date,
//        ]);
//
//        return true;
//    }

    public static function trial_payment_log($user, $plan,$subdomain = null)
    {
        $trial_start_date = '';
        $trial_expire_date =  '';

        $plan_trial_days = $plan->trial_days;

        if(!empty($plan)){
            if($plan->type == 0){
                $trial_start_date = \Illuminate\Support\Carbon::now()->format('d-m-Y h:i:s');
                $trial_expire_date = Carbon::now()->addDays($plan_trial_days)->format('d-m-Y h:i:s');

            }elseif ($plan->type == 1){
                $trial_start_date = Carbon::now()->format('d-m-Y h:i:s');
                $trial_expire_date = Carbon::now()->addDays($plan_trial_days)->format('d-m-Y h:i:s');
            }else{
                $trial_start_date = Carbon::now()->format('d-m-Y h:i:s');
                $trial_expire_date =  Carbon::now()->addDays($plan_trial_days)->format('d-m-Y h:i:s');
            }
        }

        PaymentLogs::create([
            'email' => $user->email,
            'name' => $user->name,
            'package_name' => $plan->title,
            'package_price' => $plan->price,
            'package_id' => $plan->id,
            'user_id' => $user->id ?? null,
            'tenant_id' => $subdomain ?? null,
            'status' => 'trial',
            'payment_status' => 'pending',
            'is_renew' => 0,
            'track' => Str::random(10),
            'created_at' => \Illuminate\Support\Carbon::now(),
            'updated_at' => Carbon::now(),
            'start_date' => $trial_start_date,
            'expire_date' => $trial_expire_date,
            'theme' => session()->get('theme'),
        ]);

        \DB::table('tenants')->where('id', $subdomain)->update([
            'start_date' => $trial_start_date,
            'expire_date' => $trial_expire_date,
            'theme_slug' => session()->get('theme'),
        ]);

        return true;
    }
}
