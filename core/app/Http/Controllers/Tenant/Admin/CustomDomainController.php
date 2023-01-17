<?php

namespace App\Http\Controllers\Tenant\Admin;

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
use App\Models\Tenant;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stancl\Tenancy\Database\Models\Domain;

class CustomDomainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    private const ROOT_PATH = 'tenant.admin.custom-domain.';

    public function custom_domain_request(){
        $user_domain_infos = tenant()->user()->first();
        $custom_domain_info = CustomDomain::where('user_id',$user_domain_infos->id)->first();

        return view(self::ROOT_PATH.'custom-domain')->with(['user_domain_infos' => $user_domain_infos, 'custom_domain_info'=>$custom_domain_info]);
    }

    public function custom_domain_request_change(Request $request)
    {
        $request->validate([
            'custom_domain' => 'required|regex:/^[a-za-z.-]+$/',
        ]);

        $all_tenant = Domain::where('domain',$request->custom_domain)->first();

        if(!is_null($all_tenant)){
            return response()->danger(ResponseMessage::delete('You can not add this as your domain, this is reserved to landlord hosting domain'));
        }

        $current_subdomain = \tenant()->id;
        CustomDomain::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'old_domain' => $current_subdomain
            ],
            [
                'old_domain' => $current_subdomain,
                'custom_domain' => $request->custom_domain,
                'custom_domain_status' => 'pending',
            ]);

        return response()->success(ResponseMessage::SettingsSaved('Custom domain change request sent successfully..!'));

    }


}
