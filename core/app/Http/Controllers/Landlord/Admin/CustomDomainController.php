<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Helpers\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Models\CustomDomain;
use App\Models\FormBuilder;
use App\Models\Language;
use App\Mail\BasicMail;
use App\Mail\OrderReply;
use App\Mail\PlaceOrder;
use App\Models\Order;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomDomainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    private const ROOT_PATH = 'landlord.admin.custom-domain.';

    public function all_pending_custom_domain_requests(){
        $domain_infos = CustomDomain::where('custom_domain_status','pending')->get();
        return view(self::ROOT_PATH.'all-pending-requests')->with(['domain_infos' => $domain_infos]);
    }

    public function all_domain_requests()
    {
        $domain_infos = CustomDomain::all();
        return view(self::ROOT_PATH.'all-requests')->with(['domain_infos' => $domain_infos]);
    }

    public function status_change(Request $request)
    {
        $request->validate([
            'custom_domain_id' => 'required',
            'custom_domain_status' => 'required'
        ]);

        $custom_domain = CustomDomain::findOrFail($request->custom_domain_id);
        $custom_domain->custom_domain_status = $request->custom_domain_status;
        $custom_domain->save();

        $full_custom_domain = $custom_domain->custom_domain;

        if($request->custom_domain_status == 'connected'){
            $custom_domain->tenant->domains()->update(['domain' => $full_custom_domain]);
        }

        $domain_status_color = ['pending' => '#ffc107', 'in_progress' => 'blue', 'connected' => 'green', 'removed' => 'red', 'rejected' => 'red'];
        $domain_status = '<b style="color:'.$domain_status_color[$request->custom_domain_status].';text-transform: uppercase">'.str_replace('_', ' ', $request->custom_domain_status).'</b>';
        $email = optional(optional(optional($custom_domain)->tenant)->user)->email;
        $message = __('We have reviewed your request and took an action on your custom domain request. Your custom domain new status is '.$domain_status);
        $subject =  __('Custom domain request message');

        try {
            Mail::to($email)->send(new BasicMail($message,$subject));
        }catch (\Exception $ex){
            return redirect()->back()->with(ResponseMessage::delete($ex->getMessage()));
        }

        return response()->success(ResponseMessage::SettingsSaved('Custom domain status change settings saved'));
    }


    public function delete_request($id)
    {
        $custom_domain = CustomDomain::findOrFail($id);
        $custom_domain->custom_domain_status = 'removed';
        $custom_domain->save();

        return response()->success(ResponseMessage::SettingsSaved('Custom domain deleted successfully'));
    }


    public function bulk_action(Request $request){
        $all = CustomDomain::find($request->ids);
        foreach($all as $item){
            $item->custom_domain_status = 'removed';
            $item->save();
        }
        return response()->json(['status' => 'ok']);
    }

    public function settings()
    {
        return view(self::ROOT_PATH.'settings');
    }

    public function update_settings(Request $request)
    {
       $data =  $request->validate([
            'custom_domain_settings_title' =>  'nullable',
            'custom_domain_settings_description' =>  'nullable',
            'custom_domain_table_title' =>  'nullable',
            'custom_domain_settings_screem_show_image' =>  'nullable',

        ]);

       foreach ($data as $key => $item){
           update_static_option_central($key,$request->$key);
       }

        return response()->success(ResponseMessage::SettingsSaved());
    }
}