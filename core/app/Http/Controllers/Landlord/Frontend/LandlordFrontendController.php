<?php

namespace App\Http\Controllers\Landlord\Frontend;

use App\Actions\Tenant\TenantCreateEventWithMail;
use App\Actions\Tenant\TenantRegisterSeeding;
use App\Actions\Tenant\TenantTrialPaymentLog;
use App\Events\TenantRegisterEvent;
use App\Facades\GlobalLanguage;
use App\Helpers\FlashMsg;
use App\Helpers\ImageDataSeedingHelper;
use App\Helpers\EmailHelpers\VerifyUserMailSend;
use App\Helpers\LanguageHelper;
use App\Helpers\Payment\DatabaseUpdateAndMailSend\LandlordPricePlanAndTenantCreate;
use App\Http\Controllers\Controller;
use App\Mail\AdminResetEmail;
use App\Mail\BasicMail;
use App\Mail\TenantCredentialMail;
use App\Models\Admin;
use App\Models\Newsletter;
use App\Models\Page;
use App\Models\PaymentGateway;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use App\Models\Tenant;
use App\Models\Themes;
use App\Models\User;
use App\Traits\SeoDataConfig;
use Artesaos\SEOTools\SEOMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use function view;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;

class LandlordFrontendController extends Controller
{
    use SEOToolsTrait, SeoDataConfig;

    private const BASE_VIEW_PATH = 'landlord.frontend.';

    public function homepage()
    {
        $id = get_static_option('home_page');
        $page_post = Page::where('id', $id)->first();
        $this->setMetaDataInfo($page_post);
        return view(self::BASE_VIEW_PATH . 'frontend-home', compact('page_post'));
    }

    /* -------------------------
        SUBDOMAIN AVIALBILITY
    -------------------------- */
    public function subdomain_check(Request $request)
    {
        $this->validate($request, [
            'subdomain' => 'required|unique:tenants,id'
        ]);
        return response()->json('ok');
    }

    /* -------------------------
        TENENT EMAIL VERIFY
    -------------------------- */
    public function verify_user_email()
    {
        if (empty(get_static_option('user_email_verify_status'))) {
            return redirect()->route('landlord.user.home');
        }

        if (Auth::guard('web')->user()->email_verified == 1) {
            return redirect()->route('landlord.user.home');
        }

        return view('landlord.frontend.auth.email-verify');
    }

    public function check_verify_user_email(Request $request)
    {
        $this->validate($request, [
            'verify_code' => 'required|string'
        ]);
        $user_info = User::where(['id' => Auth::guard('web')->id(), 'email_verify_token' => $request->verify_code])->first();
        if (is_null($user_info)) {
            return back()->with(['msg' => __('enter a valid verify code'), 'type' => 'danger']);
        }

        $user_info->email_verified = 1;
        $user_info->save();

        return redirect()->route('landlord.user.home');
    }

    public function resend_verify_user_email(Request $request)
    {

        VerifyUserMailSend::sendMail(Auth::guard('web')->user());
        return redirect()->route('landlord.user.email.verify')->with(['msg' => __('Verify mail send'), 'type' => 'success']);
    }

    public function dynamic_single_page($slug)
    {
        $page_post = Page::where('slug', $slug)->firstOrFail();

        abort_if(empty($page_post), 404);

        $this->setMetaDataInfo($page_post);

        $price_page_slug = get_page_slug(get_static_option('pricing-plan'), 'price-plan');
        if ($slug === $price_page_slug) {
            $all_blogs = PricePlan::where(['status' => 'publish'])->paginate(10);
            return view(self::BASE_VIEW_PATH . 'pages.dynamic-single')->with([
                'all_blogs' => $all_blogs,
                'page_post' => $page_post
            ]);
        }

        return view(self::BASE_VIEW_PATH . 'pages.dynamic-single')->with([
            'page_post' => $page_post
        ]);
    }

    public function ajax_login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|min:6'
        ], [
            'username.required' => __('Username required'),
            'password.required' => __('Password required'),
            'password.min' => __('Password length must be 6 characters')
        ]);
        if (Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password], $request->get('remember'))) {
            return response()->json([
                'msg' => __('Login Success Redirecting'),
                'type' => 'success',
                'status' => 'valid'
            ]);
        }
        return response()->json([
            'msg' => __('User name and password do not match'),
            'type' => 'danger',
            'status' => 'invalid'
        ]);
    }


    public function lang_change(Request $request)
    {
        session()->put('lang', $request->lang);
        return redirect()->route('landlord.homepage');
    }


    public function order_payment_cancel($id)
    {
        $order_details = PaymentLogs::find($id);
        return view('landlord.frontend.payment.payment-cancel')->with(['order_details' => $order_details]);
    }

    public function order_payment_cancel_static()
    {
        return view('landlord.frontend.payment.payment-cancel-static');
    }

    public function view_plan($id, $trial = null)
    {
        $order_details = PricePlan::findOrFail($id);
        $themes = Themes::where('status', 1)->get();

        return view('landlord.frontend.pages.package.view-plan')->with([
            'themes' => $themes,
            'order_details' => $order_details,
            'trial' => $trial != null ? true : false,
        ]);

    }

    public function plan_order($id)
    {
        abort_if(empty($id), 404);

        $order_details = PricePlan::findOrFail($id);
        $payment_gateways = PaymentGateway::where('name', 'manual_payment')->first();

        $user = Auth::guard('web')->user();
        if ($user)
        {
            $payment_old_data = PaymentLogs::where(['user_id' => $user->id, 'payment_status' => 'complete'])->get()->toArray();
        }

        return view('landlord.frontend.pages.package.order-page')->with([
            'order_details' => $order_details,
            'payment_old_data' => $payment_old_data ?? [],
            'payment_gateways' => $payment_gateways ?? []
        ]);
    }

    public function order_confirm($id)
    {
        $order_details = PricePlan::where('id', $id)->first();

        $user = Auth::guard('web')->user();
        $payment_old_data = PaymentLogs::where(['user_id' => $user->id, 'payment_status' => 'complete'])->get()->toArray();

        return view('landlord.frontend.pages.package.order-page')->with([
            'order_details' => $order_details,
            'payment_old_data' => $payment_old_data ?? []
        ]);
    }


    public function order_payment_success($id)
    {
        $extract_id = substr($id, 6);
        $extract_id = substr($extract_id, 0, -6);

        $payment_details = PaymentLogs::findOrFail($extract_id);

        $domain = \DB::table('domains')->where('tenant_id',$payment_details->tenant_id)->first();

        if (empty($extract_id)) {
            abort(404);
        }

        return view('landlord.frontend.payment.payment-success', compact('payment_details', 'domain'));
    }

    public function logout_tenant_from_landlord()
    {
        Auth::guard('web')->logout();
        return redirect()->back();
    }


// ========================================== LANDLORD HOME PAGE TENANT ROUTES ====================================


    public function showTenantLoginForm()
    {
        if (auth('web')->check()) {
            return redirect()->route('landlord.user.home');
        }
        return view('landlord.frontend.user.login');
    }


    public function showTenantRegistrationForm()
    {
        $plan_id= \request()->p ?? '';
        abort_if(!empty($plan_id) && empty(PricePlan::find($plan_id)), 404);

        if (auth('web')->check()) {
            return redirect()->route('tenant.user.home');
        }

        return view('landlord.frontend.auth.register', compact('plan_id'));
    }

    protected function tenant_user_create(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'username' => ['required', 'string', 'max:191', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms_condition' => ['required']
        ],
        [
            'terms_condition.required' => 'Please mark on our terms and condition to agree and proceed'
        ]);

        $user_id = DB::table('users')->insertGetId([
            'name' => $request['name'],
            'email' => $request['email'],
            'country' => $request['country'],
            'city' => $request['city'],
            'username' => $request['username'],
            'password' => Hash::make($request['password']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $user = User::findOrFail($user_id);

        Auth::guard('web')->login($user);

        $email = get_static_option('site_global_email');
        try {
            $subject = __('New user registration');
            $message_body = __('New user registered : ') . ' <span class="user-registration">' . $request['name'] . '</span>';
            Mail::to($email)->send(new BasicMail($subject, $message_body));

        } catch (\Exception $e) {
            //handle error
        }

        return response()->json([
            'msg' => __('Registration Success Redirecting'),
            'type' => 'success',
            'status' => 'valid'
        ]);
    }

    public function tenant_logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('landlord.user.login');
    }

    public function showUserForgetPasswordForm()
    {
        return view('landlord.frontend.user.forget-password');
    }

    public function sendUserForgetPasswordMail(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string:max:191'
        ]);

        $user_info = User::where('username', $request->username)->orWhere('email', $request->username)->first();

        if (!empty($user_info)) {
            $token_id = Str::random(30);
            $existing_token = DB::table('password_resets')->where('email', $user_info->email)->delete();
            if (empty($existing_token)) {
                DB::table('password_resets')->insert(['email' => $user_info->email, 'token' => $token_id]);
            }
            $message = __('Here is you password reset link, If you did not request to reset your password just ignore this mail.') . '<br> <a class="btn" href="' . route('landlord.user.reset.password', ['user' => $user_info->username, 'token' => $token_id]) . '" style="color:white;background:gray">' . __('Click Reset Password') . '</a>';
            $data = [
                'username' => $user_info->username,
                'message' => $message
            ];
            try {
                Mail::to($user_info->email)->send(new AdminResetEmail($data));
            } catch (\Exception $e) {
                return redirect()->back()->with([
                    'msg' => $e->getMessage(),
                    'type' => 'danger'
                ]);
            }

            return redirect()->back()->with([
                'msg' => __('Check Your Mail For Reset Password Link'),
                'type' => 'success'
            ]);
        }
        return redirect()->back()->with([
            'msg' => __('Your Username or Email Is Wrong!!!'),
            'type' => 'danger'
        ]);
    }

    public function showUserResetPasswordForm($username, $token)
    {
        return view('landlord.frontend.user.reset-password')->with([
            'username' => $username,
            'token' => $token
        ]);
    }

    public function UserResetPassword(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'username' => 'required',
            'password' => 'required|string|min:8|confirmed'
        ]);
        $user_info = User::where('username', $request->username)->first();
        $token_iinfo = DB::table('password_resets')->where(['email' => $user_info->email, 'token' => $request->token])->first();
        if (!empty($token_iinfo)) {
            $user_info->password = Hash::make($request->password);
            $user_info->save();
            return redirect()->route('landlord.user.login')->with(['msg' => __('Password Changed Successfully'), 'type' => 'success']);
        }
        return redirect()->back()->with(['msg' => __('Somethings Going Wrong! Please Try Again or Check Your Old Password'), 'type' => 'danger']);
    }


    public function newsletter_store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:191|unique:newsletters'
        ]);

        $verify_token = Str::random(32);
        Newsletter::create([
            'email' => $request->email,
            'verified' => 0,
            'token' => $verify_token
        ]);

        return response()->json([
            'msg' => __('Thanks for Subscribe Our Newsletter'),
            'type' => 'success'
        ]);
    }

    public function user_trial_account(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'subdomain' => 'required|unique:tenants,id',
            'theme' => 'required',
        ],[
            'theme.required' => 'No theme is selected.'
        ]);

        if ($request->subdomain != null) {
            $has_subdomain = Tenant::find(trim($request->subdomain));
            if (!empty($has_subdomain)) {
                return back()->with(['type' => 'danger', 'msg' => 'This subdomain is already in use, Try something different']);
            }

            $site_domain = url('/');
            $site_domain = str_replace(['http://', 'https://'], '', $site_domain);
            $site_domain = substr($site_domain, 0, strpos($site_domain, '.'));
            $restricted_words = ['https', 'http', 'http://', 'https://','www', 'subdomain', 'domain', 'primary-domain', 'central-domain',
                'landlord', 'landlords', 'tenant', 'tenants', 'multi-store', 'multistore', 'admin',
                'user', 'user', $site_domain];

            if (in_array(trim($request->subdomain), $restricted_words))
            {
                return response()->json([
                    'msg' => __('Sorry, You can not use this subdomain'),
                    'type' => 'danger'
                ]);
            }

            $sub = $request->subdomain;
            $check_type = false;
            for ($i=0; $i<strlen($sub); $i++)
            {
                if(ctype_alnum($sub[$i])) {
                    $check_type = true;
                }
            }

            if ($check_type == false)
            {
                return response()->json([
                    'msg' => __('Sorry, You can not use this subdomain'),
                    'type' => 'danger'
                ]);
            }
        }

        $user_id = Auth::guard('web')->user()->id;
        $user = User::find($user_id);
        if(is_null($user)){
            return response()->json([
                'msg' => __('user not found'),
                'type' => 'danger'
            ]);
        }
        if ($user->email_verified == 0)
        {
            return response()->json([
                'msg' => __('Please verify your account, Visit user dashboard for verification'),
                'type' => 'danger'
            ]);
        }

        $plan = PricePlan::find($request->order_id);
        if(is_null($plan)){
            return response()->json([
                'msg' => __('plan not found'),
                'type' => 'danger'
            ]);
        }

        $subdomain = $request->subdomain;
        $theme = $request->theme ?? 'theme-1';

        session()->put('theme',$theme);

        $tenant_data = $user->tenant_details ?? [];
        $has_trial = false;
        if(!is_null($tenant_data)){
            foreach ($tenant_data as $tenant){
                if(optional($tenant->payment_log)->status == 'trial'){
                    $has_trial = true;
                }
            }
            if($has_trial == true){
                return response()->json([
                    'msg' => __('Your trial limit is over! Please purchase a plan to continue').'<br>'.'<small>'.__('You can make trial once only..!').'</small>',
                    'type' => 'danger'
                ]);
            }
        }

        try{
            TenantCreateEventWithMail::tenant_create_event_with_credential_mail($user, $subdomain, $theme);
            TenantTrialPaymentLog::trial_payment_log($user,$plan,$subdomain);

        }catch(\Exception $ex){
            $message = $ex->getMessage();
            LandlordPricePlanAndTenantCreate::store_exception($subdomain,'domain failed on trial',$message,0);
            return response()->json(['msg' => __('something went wrong, we have notified to admin regarding this issue, please try after sometime'), 'type'=>'danger']);
        }

        $domain_details = DB::table('domains')->where('tenant_id',$subdomain)->first(); //domain; //issue in this line

        if(!is_null($domain_details)){
            $url = tenant_url_with_protocol($domain_details->domain);
            $user->update(['has_subdomain' => 1]);
            return response()->json([
                'url' => __($url),
                'type' => 'success'
            ]);
        }


        return response()->json([
            'url' => __('something went wrong, we have notified to admin regarding this issue, please try after sometime'),
            'type' => 'danger'
        ]);
    }
}
