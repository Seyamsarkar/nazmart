<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Facades\GlobalLanguage;
use App\Helpers\ImageDataSeedingHelper;
use App\Helpers\LanguageHelper;
use App\Helpers\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\Page;
use App\Models\PaymentGateway;
use App\Models\StaticOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Helpers\SanitizeInput;

class GeneralSettingsController extends Controller
{
    const BASE_PATH = 'landlord.admin.general-settings.';

    public function __construct()
    {

        $this->middleware('permission:general-settings-site-identity',['only'=>['site_identity','update_site_identity']]);
        $this->middleware('permission:general-settings-page-settings',['only'=>['page_settings','update_page_settings']]);
        $this->middleware('permission:general-settings-global-navbar-settings',['only'=>['global_variant_navbar','update_global_variant_navbar']]);
        $this->middleware('permission:general-settings-global-footer-settings',['only'=>['global_variant_footer','update_global_variant_footer']]);
        $this->middleware('permission:general-settings-basic-settings',['only'=>['basic_settings','update_basic_settings']]);
        $this->middleware('permission:general-settings-color-settings',['only'=>['color_settings','update_color_settings']]);
        $this->middleware('permission:general-settings-typography-settings',['only'=>['typography_settings','get_single_font_variant','update_typography_settings']]);
        $this->middleware('permission:general-settings-seo-settings',['only'=>['seo_settings','update_seo_settings']]);
        $this->middleware('permission:general-settings-third-party-scripts',['only'=>['update_scripts_settings','scripts_settings']]);
        $this->middleware('permission:general-settings-smtp-settings',['only'=>['email_settings','update_email_settings']]);
        $this->middleware('permission:general-settings-payment-settings',['only'=>['payment_settings','update_payment_settings']]);
        $this->middleware('permission:general-settings-custom-css',['only'=>['custom_css_settings','update_custom_css_settings']]);
        $this->middleware('permission:general-settings-custom-js',['only'=>['custom_js_settings','update_custom_js_settings']]);
        $this->middleware('permission:general-settings-licence-settings',['only'=>['license_settings','update_license_settings']]);
        $this->middleware('permission:general-settings-cache-settings',['only'=>['cache_settings','update_cache_settings']]);
    }



    public function page_settings()
    {
        $all_home_pages = Page::where(['status'=> 1])->get();
        return view(self::BASE_PATH.'page-settings',compact('all_home_pages'));
    }
    public function update_page_settings(Request $request)
    {
        $this->validate($request, [
            'home_page' => 'nullable|string',
            'pricing_plan' => 'nullable|string',
            'blog_page' => 'nullable|string',
            'shop_page' => 'nullable|string',
            'track_order' => 'nullable|string',
            'terms_condition' => 'nullable|string',
            'privacy_policy' => 'nullable|string',
        ]);
        $fields = [
            'home_page','pricing_plan', 'blog_page', 'shop_page', 'track_order', 'terms_condition', 'privacy_policy'
        ];

        foreach ($fields as $field) {
            update_static_option($field, $request->$field);
        }
        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function global_variant_footer()
    {
        return view(self::BASE_PATH.'footer-global-variant');
    }
    public function update_global_variant_footer(Request $request)
    {
        $this->validate($request, [
            'global_footer_variant' => 'nullable|string',
        ]);
        $fields = [
            'global_footer_variant',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                update_static_option($field, $request->$field);
            }
        }
        return response()->success(ResponseMessage::SettingsSaved());
    }


    public function basic_settings(){
        return view(self::BASE_PATH.'basic-settings');
    }
    
    public function update_basic_settings(Request $request)
    {
        $nonlang_fields = [
            'dark_mode_for_admin_panel' => 'nullable|string',
            'maintenance_mode' => 'nullable|string',
            'backend_preloader' => 'nullable|string',
            'user_email_verify_status' => 'nullable|string',
            'language_selector_status' => 'nullable|string',
            'guest_order_system_status' => 'nullable|string',
            'timezone' => 'nullable',
            'placeholder_image' => 'nullable|integer'
        ];

        $this->validate($request,$nonlang_fields);
        $fields = [
            'site_title'  => 'nullable|string',
            'site_tag_line' => 'nullable|string',
            'site_footer_copyright_text' => 'nullable|string',
            ];

        $this->validate($request,$fields);
        foreach ($fields as $field_name => $rules){
            update_static_option($field_name, SanitizeInput::esc_html($request->$field_name));
        }

        foreach ($nonlang_fields as $field_name => $rules){
            update_static_option($field_name,$request->$field_name);
        }

        $timezone = get_static_option('timezone');
        if (!empty($timezone)) {
            setEnvValue(['APP_TIMEZONE' => $timezone]);
        }


        return response()->success(ResponseMessage::SettingsSaved());
    }
    
    public function site_identity(){
        return view(self::BASE_PATH.'site-identity');
    }
    public function update_site_identity(Request $request){
        $fields = [
            'site_logo' => 'required|integer',
            'site_white_logo' => 'required|integer',
            'site_favicon' => 'required|integer',
        ];
        $this->validate($request,$fields);
        foreach ($fields as $field_name => $rules){
            update_static_option($field_name,$request->$field_name);
        }
        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function email_settings(){
        return view(self::BASE_PATH.'tenant-email-settings');
    }

    public function update_email_settings(Request $request){
        $fields = [
            'tenant_site_global_email' => 'required|email',
        ];
        $this->validate($request,$fields);
        foreach ($fields as $field_name => $rules){
            update_static_option($field_name,$request->$field_name);
        }
        return response()->success(ResponseMessage::SettingsSaved());
    }


    public function color_settings(){
        return view(self::BASE_PATH.'color-settings');
    }
    public function update_color_settings(Request $request){
        $theme_suffix = ['theme_one', 'theme_two', 'theme_three'];

        if (tenant())
        {
            foreach ($theme_suffix as $key => $suffix)
            {
                $fields[$key] = [
                    'main_color_one_'.$suffix => 'nullable|string|max:191',
                    'main_color_two_'.$suffix => 'nullable|string|max:191',
                    'main_color_three_'.$suffix => 'nullable|string|max:191',
                    'main_color_four_'.$suffix => 'nullable|string|max:191',
                    'secondary_color_'.$suffix => 'nullable|string|max:191',
                    'secondary_color_two_'.$suffix => 'nullable|string|max:191',
                    'section_bg_1_'.$suffix => 'nullable|string|max:191',
                    'section_bg_2_'.$suffix => 'nullable|string|max:191',
                    'section_bg_3_'.$suffix => 'nullable|string|max:191',
                    'section_bg_4_'.$suffix => 'nullable|string|max:191',
                    'section_bg_5_'.$suffix => 'nullable|string|max:191',
                    'section_bg_6_'.$suffix => 'nullable|string|max:191',
                    'breadcrumb_bg_'.$suffix => 'nullable|string|max:191',
                    'heading_color_'.$suffix => 'nullable|string|max:191',
                    'body_color_'.$suffix => 'nullable|string|max:191',
                    'light_color_'.$suffix => 'nullable|string|max:191',
                    'extra_light_color_'.$suffix => 'nullable|string|max:191',
                    'review_color_'.$suffix => 'nullable|string|max:191',
                    'feedback_bg_item_'.$suffix => 'nullable|string|max:191',
                    'new_color_'.$suffix => 'nullable|string|max:191',
                ];
            }

            $fields = array_merge($fields[0], $fields[1], $fields[2]);
        } else {
            $fields = [
                'main_color_one' => 'nullable|string|max:191',
                'main_color_two' => 'nullable|string|max:191',
                'main_color_three' => 'nullable|string|max:191',
                'main_color_four' => 'nullable|string|max:191',
                'secondary_color' => 'nullable|string|max:191',
                'secondary_color_two' => 'nullable|string|max:191',
                'section_bg_1' => 'nullable|string|max:191',
                'section_bg_2' => 'nullable|string|max:191',
                'section_bg_3' => 'nullable|string|max:191',
                'section_bg_4' => 'nullable|string|max:191',
                'section_bg_5' => 'nullable|string|max:191',
                'section_bg_6' => 'nullable|string|max:191',
                'heading_color' => 'nullable|string|max:191',
                'body_color' => 'nullable|string|max:191',
                'light_color' => 'nullable|string|max:191',
                'extra_light_color' => 'nullable|string|max:191',
                'review_color' => 'nullable|string|max:191',
                'feedback_bg_item' => 'nullable|string|max:191',
                'new_color' => 'nullable|string|max:191',
            ];
        }

        $this->validate($request,$fields);

        foreach ($fields as $field_name => $rules){
            update_static_option($field_name,$request->$field_name);
        }

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function typography_settings()
    {
        $prefix =  is_null(tenant()) ? 'landlord' : 'tenant';
        $all_google_fonts = file_get_contents('assets/'.$prefix.'/frontend/webfonts/google-fonts.json');

        return view(self::BASE_PATH.'typography-settings')->with(['google_fonts' => json_decode($all_google_fonts)]);
    }

    public function get_single_font_variant(Request $request)
    {
        $prefix =  is_null(tenant()) ? 'landlord' : 'tenant';
        $all_google_fonts = file_get_contents('assets/'.$prefix.'/frontend/webfonts/google-fonts.json');
        $decoded_fonts = json_decode($all_google_fonts, true);

        $data = [
            'decoded_fonts' => $decoded_fonts[$request->font_family],
            'theme' => $request->theme
        ];

        return response()->json($data);
    }

    public function update_typography_settings(Request $request)
    {
        $theme_suffix = ['theme_one', 'theme_two', 'theme_three'];

        if (tenant())
        {
            foreach ($theme_suffix as $key => $suffix)
            {
                $fields[$key] = [
                    'body_font_family_'.$suffix => 'required|string|max:191',
                    'body_font_variant_'.$suffix => 'required',
                    'heading_font_'.$suffix => 'nullable|string',
                    'heading_font_family_'.$suffix => 'nullable|string|max:191',
                    'heading_font_variant_'.$suffix => 'nullable',
                ];

                $save_data[$key] = [
                    'body_font_family_'.$suffix,
                    'heading_font_family_'.$suffix,
                    'heading_font_'.$suffix
                ];

                $font_variant[$key] = [
                    'body_font_variant_'.$suffix,
                    'heading_font_variant_'.$suffix,
                ];
            }

            $fields = array_merge($fields[0], $fields[1], $fields[2]);

            $this->validate($request,$fields);

            $save_data = array_merge($save_data[0], $save_data[1], $save_data[2]);
            foreach ($save_data as $item) {
                update_static_option($item, $request->$item);
            }

            // Issue to fix
            $font_variant = array_merge($font_variant[0], $font_variant[1], $font_variant[2]);
            foreach ($font_variant as $variant)
            {
                update_static_option($variant, serialize(!empty($request->$variant) ?  $request->$variant : ['regular']));
            }
        } else {
            $fields = [
                'body_font_family' => 'required|string|max:191',
                'body_font_variant' => 'required',
                'heading_font' => 'nullable|string',
                'heading_font_family' => 'nullable|string|max:191',
                'heading_font_variant' => 'nullable',
            ];

            $this->validate($request,$fields);
            foreach ($fields as $item) {
                update_static_option($item, $request->$item);
            }
        }

        return redirect()->back()->with(['msg' => __('Typography Settings Updated..'), 'type' => 'success']);
    }


    public function seo_settings(){
        return view(self::BASE_PATH.'seo-settings');
    }

    public function update_seo_settings(Request $request){

            $fields = [
                'site_meta_title'  => 'nullable|string',
                'site_meta_tags' => 'nullable|string',
                'site_meta_keywords' => 'nullable|string',
                'site_meta_description' => 'nullable|string',
                'site_og_meta_title' => 'nullable|string',
                'site_og_meta_description' => 'nullable|string',
                'site_og_meta_image' => 'nullable|string',
            ];
            
            $this->validate($request,$fields);
            foreach ($fields as $field_name => $rules){
                update_static_option($field_name, SanitizeInput::esc_html($request->$field_name));
            }

        return response()->success(ResponseMessage::SettingsSaved());
    }


    public function smtp_settings(){
        return view(self::BASE_PATH.'smtp-settings');
    }
    public function update_smtp_settings(Request $request){
        $fields = [
            'site_global_email' => 'required|email',
            'site_smtp_host' => 'required|string|regex:/^\S*$/u',
            'site_smtp_username' => 'required|string',
            'site_smtp_password' => 'required|string',
            'site_smtp_port' => 'required|numeric',
            'site_smtp_encryption' => 'required|string',
            'site_smtp_driver' => 'required|string',
        ];
        $this->validate($request,$fields);
        foreach ($fields as $field_name => $rules){
            update_static_option($field_name,$request->$field_name);
        }
        setEnvValue([
            'MAIL_MAILER'=> $request->site_smtp_driver,
            'MAIL_HOST'=> $request->site_smtp_host,
            'MAIL_PORT'=> $request->site_smtp_port,
            'MAIL_USERNAME'=>$request->site_smtp_username,
            'MAIL_PASSWORD'=> addQuotes($request->site_smtp_password),
            'MAIL_ENCRYPTION'=> $request->site_smtp_encryption,
            'MAIL_FROM_ADDRESS'=> $request->site_global_email
        ]);
        return response()->success(ResponseMessage::SettingsSaved());
    }
    public function send_test_mail(Request $request){
        $this->validate($request,[
            'subject' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);
        try {
            Mail::to($request->email)->send(new BasicMail($request->message,$request->subject));
        }catch (\Exception $e){
            return  response()->warning($e->getMessage());
        }
        return response()->success(ResponseMessage::mailSendSuccess());
    }


    public function cache_settings(){
        return view(self::BASE_PATH.'cache-settings');
    }
    public function update_cache_settings(Request $request){
        $this->validate($request,[
            'type' => 'required|string'
        ]);
        switch ($request->type){
            case "route":
            case "view":
            case "config":
            case "event":
            case "queue":
                Artisan::call($request->type.':clear');
                break;
             default:
                Artisan::call('cache:clear');
                break;
        }
        return response()->success(ResponseMessage::success(sprintf(__('%s Cache Cleared'),ucfirst($request->type))));
    }

    public function third_party_script_settings()
    {
        return view(self::BASE_PATH.'third-party');
    }


    public function update_third_party_script_settings(Request $request)
    {
        $this->validate($request, [
            'tawk_api_key' => 'nullable|string',
            'google_adsense_id' => 'nullable|string',
            'site_third_party_tracking_code' => 'nullable|string',
            'site_google_analytics' => 'nullable|string',
            'site_google_captcha_v3_secret_key' => 'nullable|string',
            'site_google_captcha_v3_site_key' => 'nullable|string',
        ]);

        update_static_option('site_disqus_key', $request->site_disqus_key);
        update_static_option('site_google_analytics', $request->site_google_analytics);
        update_static_option('tawk_api_key', $request->tawk_api_key);
        update_static_option('site_third_party_tracking_code', $request->site_third_party_tracking_code);
        update_static_option('site_google_captcha_v3_site_key', $request->site_google_captcha_v3_site_key);
        update_static_option('site_google_captcha_v3_secret_key', $request->site_google_captcha_v3_secret_key);

        $fields = [
            'site_google_captcha_v3_secret_key',
            'site_google_captcha_v3_site_key',
            'site_third_party_tracking_code',
            'site_google_analytics',
            'tawk_api_key'
        ];
        foreach ($fields as $field){
            update_static_option($field,$request->$field);
        }

        return redirect()->back()->with(['msg' => __('Third Party Scripts Settings Updated..'), 'type' => 'success']);
    }

    public function custom_css_settings()
    {
        $prefix =  is_null(tenant()) ? 'landlord' : 'tenant';
        $id = is_null(tenant()) ? '' : tenant()->id.'/';

        $custom_css = '/* Write Custom Css Here */';
        if (file_exists('assets/'.$prefix.'/frontend/css/'.$id.'dynamic-style.css')) {
            $custom_css = file_get_contents('assets/'.$prefix.'/frontend/css/'.$id.'dynamic-style.css');
        } else {
            $directory_name = 'assets/'.$prefix.'/frontend/css/'.$id;

            if (!is_null(tenant()))
            {
                mkdir($directory_name, 0777, true);
            }
            fopen($directory_name.'dynamic-style.css', 'w+');
        }

        return view(self::BASE_PATH.'custom-css')->with(['custom_css' => $custom_css]);
    }

    public function update_custom_css_settings(Request $request)
    {
        $prefix =  is_null(tenant()) ? 'landlord' : 'tenant';
        $id = is_null(tenant()) ? '' : tenant()->id.'/';
        file_put_contents('assets/'.$prefix.'/frontend/css/'.$id.'dynamic-style.css', $request->custom_css_area);

        return redirect()->back()->with(['msg' => __('Custom Style Successfully Added...'), 'type' => 'success']);
    }

    public function custom_js_settings()
    {
        $custom_js = '/* Write Custom js Here */';
        $prefix =  is_null(tenant()) ? 'landlord' : 'tenant';
        $id = is_null(tenant()) ? '' : tenant()->id.'/';

        if (file_exists('assets/'.$prefix.'/frontend/js/'.$id.'dynamic-script.js')) {
            $custom_js = file_get_contents('assets/'.$prefix.'/frontend/js/'.$id.'dynamic-script.js');
        } else {
            $directory_name = 'assets/'.$prefix.'/frontend/js/'.$id;

            if (!is_null(tenant())) {
                mkdir($directory_name, 0777, true);
            }
            fopen($directory_name.'dynamic-script.js', 'w+');
        }

        return view(self::BASE_PATH.'custom-js')->with(['custom_js' => $custom_js]);
    }

    public function update_custom_js_settings(Request $request)
    {
        $prefix =  is_null(tenant()) ? 'landlord' : 'tenant';
        $id = is_null(tenant()) ? '' : tenant()->id.'/';

        file_put_contents('assets/'.$prefix.'/frontend/js/'.$id.'dynamic-script.js', $request->custom_js_area);
        return redirect()->back()->with(['msg' => __('Custom Script Successfully Added...'), 'type' => 'success']);
    }

    public function payment_settings()
    {
        $all_gateway = PaymentGateway::all();
        return view(self::BASE_PATH.'payment-gateway',compact('all_gateway'));
    }


    public function update_payment_settings(Request $request)
    {
        $this->validate($request, [
            'site_global_currency'=> 'nullable|string|max:191',
            'site_currency_symbol_position'=> 'nullable|string|max:191',
            'site_default_payment_gateway'=> 'nullable|string|max:191',
        ]);

        $save_data = [
            'site_global_currency',
            'site_global_payment_gateway',
            'site_currency_symbol_position',
            'site_default_payment_gateway',
        ];

        foreach ($save_data as $item) {
            update_static_option($item, $request->$item);
        }

        $global_currency = get_static_option('site_global_currency');


        $this->validate($request, [
            'site_usd_to_ngn_exchange_rate' => 'nullable|numeric',
            'site_euro_to_ngn_exchange_rate' => 'nullable|numeric',
            'site_' . strtolower($global_currency) . '_to_idr_exchange_rate' => 'nullable|numeric',
            'site_' . strtolower($global_currency) . '_to_inr_exchange_rate' => 'nullable|numeric',
            'site_' . strtolower($global_currency) . '_to_ngn_exchange_rate' => 'nullable|numeric',
            'site_' . strtolower($global_currency) . '_to_zar_exchange_rate' => 'nullable|numeric',
            'site_' . strtolower($global_currency) . '_to_brl_exchange_rate' => 'nullable|numeric',
        ]);

        $save_data_exchange_rates = [
            'site_usd_to_ngn_exchange_rate',
            'site_euro_to_ngn_exchange_rate',
            'site_' . strtolower($global_currency) . '_to_idr_exchange_rate',
            'site_' . strtolower($global_currency) . '_to_inr_exchange_rate',
            'site_' . strtolower($global_currency) . '_to_ngn_exchange_rate',
            'site_' . strtolower($global_currency) . '_to_zar_exchange_rate',
            'site_' . strtolower($global_currency) . '_to_brl_exchange_rate',
        ];

        foreach ($save_data_exchange_rates as $item) {
            update_static_option($item, SanitizeInput::esc_html($request->$item));
        }

        $all_gateway = PaymentGateway::all();
        foreach ($all_gateway as $gateway){
            // todo: if manual payament gatewya then save description into database
            $image_name = $gateway->name.'_logo';
            $status_name = $gateway->name.'_gateway';
            $test_mode_name = $gateway->name.'_test_mode';

            $credentials = !empty($gateway->credentials) ? json_decode($gateway->credentials) : [];
            $update_credentials = [];
            foreach($credentials as $cred_name => $cred_val){
                $crd_req_name = $gateway->name.'_'.$cred_name;
                $update_credentials[$cred_name] = $request->$crd_req_name;
            }

            PaymentGateway::where(['name' => $gateway->name])->update([
                'image' => $request->$image_name,
                'status' => isset($request->$status_name ) ? 1 : 0,
                'test_mode' => isset($request->$test_mode_name ) ? 1 : 0,
                'credentials' => json_encode($update_credentials)
            ]);
        }

        Artisan::call('cache:clear');
        return redirect()->back()->with([
            'msg' => __('Payment Settings Updated..'),
            'type' => 'success'
        ]);
    }

    public function database_upgrade(){
        return view(self::BASE_PATH.'database-upgrade');
    }

    public function update_database_upgrade(Request $request){
        setEnvValue(['APP_ENV' => 'local']);
        Artisan::call('migrate', ['--force' => true ]);
        Artisan::call('db:seed', ['--force' => true ]);
        Artisan::call('cache:clear');
        setEnvValue(['APP_ENV' => 'production']);
        return redirect()->back()->with(['msg' => __('Database Upgraded successfully.'), 'type' => 'success']);
    }

    public function license_settings()
    {
        if (!is_null(tenant()))
        {
            return redirect()->route('tenant.admin.dashboard');
        }
        return view(self::BASE_PATH.'license-settings');
    }


    public function update_license_settings(Request $request)
    {
        if (!is_null(tenant()))
        {
            return redirect()->route('tenant.admin.dashboard');
        }

        $this->validate($request, [
            'item_purchase_key' => 'required|string'
        ]);
        $response = Http::get('https://api.xgenious.com/license/new', [
            'purchase_code' => $request->item_purchase_key,
            'site_url' => url('/'),
            'item_unique_key' => '5Wkm8ykkG5iZG19rJ3GhxA2ldyqVHWKX',
        ]);
        $result = $response->json();
        if ($response->ok()){
            update_static_option('item_purchase_key', $request->item_purchase_key);
            update_static_option('item_license_status', $result['license_status']);
            update_static_option('item_license_msg', $result['msg']);

            $type = 'verified' == $result['license_status'] ? 'success' : 'danger';
            setcookie("site_license_check", "", time() - 3600, '/');
            $license_info = [
                "item_license_status" => $result['license_status'],
                "last_check" => time(),
                "purchase_code" => get_static_option('item_purchase_key'),
                "xgenious_app_key" => env('XGENIOUS_API_KEY'),
                "author" => env('XGENIOUS_API_AUTHOR'),
                "message" => $result['msg']
            ];
            @file_put_contents('core/license.json', json_encode($license_info));
            return redirect()->back()->with(['msg' => $result['msg'], 'type' => $type]);
        }

        return redirect()->back()->with(['msg' => 'there is a problem to connect xgenious server please contact support', 'type' => 'danger']);
    }


}
