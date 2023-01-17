<?php

namespace App\Console\Commands;

use App\Mail\BasicMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AccountRemoval extends Command
{
    protected $signature = 'account:remove';
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

                $day_list = json_decode(get_static_option('tenant_account_delete_notify_mail_days')) ?? [];
                $remove_day = get_static_option('account_remove_day_within_expiration');

                rsort($day_list);

                foreach ($day_list as $day){

                    if (\Carbon\Carbon::parse($payment_log->expire_date)->addDay($remove_day)->subDay($day)->lessThan(\Carbon\Carbon::today())){
                        $message['subject'] = 'Account will be deleted -' . get_static_option('site_' . get_default_language() . '_title');
                        $message['body'] = 'Your Account will be removed within ' . ($day) .  ' days.  Please subscribe to a plan before we remove your account ';
                        $message['body'].= '<br><br><a href="'.route('landlord.frontend.plan.order',optional($payment_log->package)->id).'">'.__('Go to plan page').'</a>';
                        Mail::to($payment_log->email)->send(new BasicMail( $message['body'],$message['subject']));
                        break;
                    }

                }
            }
        }

        return 0;
    }
}
