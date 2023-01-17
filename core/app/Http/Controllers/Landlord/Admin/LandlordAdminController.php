<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Blog\Entities\Blog;
use Modules\Service\Entities\Service;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use function view;

class LandlordAdminController extends Controller
{
    private const BASE_VIEW_PATH = 'landlord.admin.';

    public function dashboard(){
        $total_admin= Admin::count();
        $total_user= User::count();
        $all_blogs = Blog::count();
        $total_price_plan = PricePlan::count();
        $total_brand = Brand::all()->count();
        $total_testimonial = Testimonial::all()->count();
        $recent_order_logs = PaymentLogs::orderBy('id','desc')->take(5)->get();

        return view(self::BASE_VIEW_PATH.'admin-home',compact('total_admin','total_user','all_blogs','total_brand','total_price_plan','total_testimonial','recent_order_logs'));
    }

    public function change_password(){
        return view(self::BASE_VIEW_PATH.'auth.change-password');
    }
    public function edit_profile(){
        return view(self::BASE_VIEW_PATH.'auth.edit-profile');
    }
    public function update_change_password(Request $request){
        $this->validate($request,[
            'password' => 'required|confirmed|min:8'
        ]);

        Admin::find(auth('admin')->id())->update(['password'=> Hash::make($request->password)]);
        //store this data in landlord database
        Auth::guard('admin')->logout();
        return response()->success(__('Password Change Success'));
    }
    public function update_edit_profile(Request $request){
        $this->validate($request,[
            'name' => 'required|string',
            'email' => 'required|email|unique:admins,email,'.auth('admin')->id(),
            'mobile' => 'nullable|numeric',
            'image' => 'nullable|integer',
        ]);

        Admin::find(auth('admin')->id())->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile ,
            'image' => $request->image ,
        ]);

        //store this data in landlord database
        return response()->success(__('Settings Saved'));
    }

    public function topbar_settings()
    {
        return view('landlord.admin.topbar-settings');
    }

    public function update_topbar_settings(Request $request)
    {
        $request->validate([
            'topbar_twitter_url'=>'nullable',
            'topbar_linkedin_url'=>'nullable',
            'topbar_facebook_url'=>'nullable',
            'topbar_youtube_url'=>'nullable',
            'landlord_frontend_language_show_hide'=>'nullable',
        ]);

        $data = [
            'topbar_twitter_url',
            'topbar_linkedin_url',
            'topbar_facebook_url',
            'topbar_youtube_url',
            'landlord_frontend_language_show_hide',
        ];

        foreach ($data as $item)
        {
            update_static_option($item, $request->$item);
        }

        return response()->success(__('Settings Saved'));
    }

    public function get_chart_data_month(Request $request){
        /* -------------------------------------
            TOTAL ORDER BY MONTH CHART DATA
        ------------------------------------- */
        $all_donation_by_month = PaymentLogs::select('package_price','created_at')->where(['payment_status' => 'complete'])
            ->whereYear('created_at',date('Y'))
            ->get()
            ->groupBy(function ($query){
                return Carbon::parse($query->created_at)->format('F');
            })->toArray();
        $chart_labels = [];
        $chart_data= [];
        foreach ($all_donation_by_month as $month => $amount){
            $chart_labels[] = $month;
            $chart_data[] =  array_sum(array_column($amount,'package_price'));
        }
        return response()->json( [
            'labels' => $chart_labels,
            'data' => $chart_data
        ]);
    }

    public function get_chart_by_date_data(Request $request){
        /* -----------------------------------------------------
           TOTAL ORDER BY Per Day In Current month CHART DATA
       -------------------------------------------------------- */
        $all_donation_by_month = PaymentLogs::select('package_price','created_at')->where(['payment_status' => 'complete'])
            // ->whereMonth('created_at',date('m'))
            ->whereDate('created_at', '>', Carbon::now()->subDays(30))
            ->get()
            ->groupBy(function ($query){
                return Carbon::parse($query->created_at)->format('D, d F Y');
            })->toArray();
        $chart_labels = [];
        $chart_data= [];
        foreach ($all_donation_by_month as $month => $amount){
            $chart_labels[] = $month;
            $chart_data[] =  array_sum(array_column($amount,'package_price'));
        }

        return response()->json( [
            'labels' => $chart_labels,
            'data' => $chart_data
        ]);
    }
}
