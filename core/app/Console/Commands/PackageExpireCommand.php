<?php

namespace App\Console\Commands;

use App\Mail\BasicMail;
use App\Models\PaymentLogs;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PackageExpireCommand extends Command
{

    protected $signature = 'package:expire';
    protected $description = 'Command description';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $all_user = \App\Models\User::all();

        foreach ($all_user as $user){

            $table_user_id  = $user->tenant_details()->getChild()->id ?? '';

            if(!empty($table_user_id)){

                $payment_log = \App\Models\PaymentLogs::where(['user_id' => $table_user_id, 'payment_status' => 'complete'])->first();

                $day_list = json_decode(get_static_option('package_expire_notify_mail_days')) ?? [];
                rsort($day_list);

                foreach ($day_list as $day){

                    if (\Carbon\Carbon::parse($payment_log->expire_date)->subDay($day)->greaterThan(\Carbon\Carbon::today())){
                        $message['subject'] = 'Subscription Will Expire -' . get_static_option('site_' . get_default_language() . '_title');
                        $message['body'] = 'Your Subscription will expire very soon. Only ' . ($day) . ' Days Left. Please subscribe to a plan before expiration';
                        Mail::to($payment_log->email)->send(new BasicMail($message['subject'], $message['body']));
                        break;
                    }
                }
            }
        }


        return 0;
    }
}
