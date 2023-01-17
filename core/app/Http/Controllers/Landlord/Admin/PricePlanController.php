<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Helpers\FlashMsg;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use App\Http\Controllers\Controller;
use App\Models\PlanFeature;
use App\Models\PricePlan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PricePlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:price-plan-list|price-plan-edit|price-plan-delete',['only' => ['all_price_plan']]);
        $this->middleware('permission:price-plan-create',['only' => ['create_price_plan','store_new_price_plan']]);
        $this->middleware('permission:price-plan-edit',['only' => ['edit_price_plan','update']]);
        $this->middleware('permission:price-plan-delete',['only' => ['delete']]);
    }

    public function create_price_plan(){
        return view('landlord.admin.price-plan.create');
    }

    public function all_price_plan(){
        $all_plans = PricePlan::orderBy('id','desc')->get();
        return view('landlord.admin.price-plan.index',compact('all_plans'));
    }

    public function delete($id){

        if(!tenant()){
           $plan = PricePlan::findOrFail($id);
           $plan->plan_features()->delete();
           $plan->delete();
        }else{
            PricePlan::findOrFail($id)->delete();
        }

        return response()->danger(ResponseMessage::delete());
    }

    public function edit_price_plan($id){
        $plan = PricePlan::find($id);
        return view('landlord.admin.price-plan.edit',compact('plan'));
    }
    public function store_new_price_plan(Request $request){
        $this->validate($request,[
            'title' => 'required|string',
            'package_badge' => 'required|string',
            'features' => 'required',
            'type' => 'required|integer',
            'price' => 'required|numeric',
            'status' => 'required|integer',
            'page_permission_feature'=> 'nullable|integer|min:-1',
            'blog_permission_feature'=> 'nullable|integer|min:-1',
            'product_permission_feature'=> 'nullable|integer|min:-1',
            'storage_permission_feature'=> 'required|integer|min:-1',
        ]);

        //create data for price plan
        $price_plan = new PricePlan();
        $price_plan->title = SanitizeInput::esc_html($request->title);
        $price_plan->package_badge = SanitizeInput::esc_html($request->package_badge);

        if(!tenant()){
            $faq_item = $request->faq ?? ['title' => ['']];

            if ($request->has_trial != null)
            {
                $price_plan->has_trial = true;
                $price_plan->trial_days = $request->trial_days;
            }

            $price_plan->page_permission_feature = $request->page_permission_feature;
            $price_plan->blog_permission_feature = $request->blog_permission_feature;
            $price_plan->product_permission_feature = $request->product_permission_feature;
            $price_plan->storage_permission_feature = $request->storage_permission_feature;
            $price_plan->faq = serialize($faq_item);

        }

        $price_plan->type = $request->type;
        $price_plan->price = $request->price;
        $price_plan->status = $request->status;
        $price_plan->save();

        if(!tenant()) {
            $features = $request->features;
            foreach ($features as $feat) {
                PlanFeature::create([
                    'plan_id' => $price_plan->id,
                    'feature_name' => $feat,
                ]);
            }

        }

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function update(Request $request){
        $type_validation  = tenant() ? 'nullable' : 'required';
        $this->validate($request,[
            'id' => 'required|integer',
            'title' => 'required|string',
            'package_badge' => 'required|string',
            'features' => 'required',
            'type' => ''.$type_validation.'|integer',
            'price' => 'required|numeric',
            'status' => 'required|integer',
            'page_permission_feature'=> 'nullable|integer|min:-1',
            'blog_permission_feature'=> 'nullable|integer|min:-1',
            'product_permission_feature'=> 'nullable|integer|min:-1',
            'storage_permission_feature'=> 'required|integer|min:-1',
        ]);
        //create data for price plan
        $price_plan =  PricePlan::find($request->id);
        $price_plan->title = SanitizeInput::esc_html($request->title);
        $price_plan->package_badge = SanitizeInput::esc_html($request->package_badge);


        if(!tenant()){
            $faq_item = $request->faq ?? ['title' => ['']];

            if (!empty($faq_item))
            {
                $faq_set = [];
                foreach ($request->faq as $key => $faq)
                {
                    $faqs = [];
                    foreach ($faq as $f)
                    {
                        $faqs[] = SanitizeInput::esc_html($f);
                    }
                    $faq_set[$key] = $faqs;
                }
            }

            if ($request->has_trial != null)
            {
                $price_plan->has_trial = true;
                $price_plan->trial_days = $request->trial_days;
            } else {
                $price_plan->has_trial = false;
                $price_plan->trial_days = null;
            }

            $price_plan->page_permission_feature = $request->page_permission_feature;
            $price_plan->blog_permission_feature = $request->blog_permission_feature;
            $price_plan->product_permission_feature = $request->product_permission_feature;
            $price_plan->storage_permission_feature = $request->storage_permission_feature;
            $price_plan->faq = serialize($faq_set);
        }

        $price_plan->type = $request->type;
        $price_plan->price = $request->price;
        $price_plan->status = $request->status;
        $price_plan->save();

        if(!tenant()) {
            $price_plan->plan_features()->delete();
            $features = $request->features;
            foreach ($features as $feat) {
                PlanFeature::where('plan_id',$price_plan->id)->create([
                    'plan_id' => $price_plan->id,
                    'feature_name' => $feat,
                ]);
            }
        }

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function price_plan_settings()
    {
        return view('landlord.admin.price-plan.settings');
    }

    public function update_price_plan_settings(Request $request)
    {
        $request->validate([
            'package_expire_notify_mail_days'=> 'required|array',
            'package_expire_notify_mail_days.*'=> 'required|max:7',
            'default_theme'=> 'required',
            'zero_plan_limit' => 'required|integer|min:1'
        ]);

        update_static_option('package_expire_notify_mail_days',json_encode($request->package_expire_notify_mail_days));
        update_static_option('default_theme', $request->default_theme);
        update_static_option('zero_plan_limit', $request->zero_plan_limit);

        return response()->success(ResponseMessage::SettingsSaved());
    }
}