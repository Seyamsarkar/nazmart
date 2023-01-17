<?php

use App\Helpers\SanitizeInput;
use App\Models\PricePlan;
use App\Models\StaticOption;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Artesaos\SEOTools\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\OpenGraph;
use Artesaos\SEOTools\SEOTools;
use Illuminate\Support\Str;
use Modules\ShippingModule\Entities\CountryShipping;
use Modules\ShippingModule\Entities\StateShipping;
use Modules\TaxModule\Entities\CountryTax;
use Modules\TaxModule\Entities\StateTax;

/* all helper function will be here */

/**
 * @param $option_name
 * @param $default
 * @return mixed|null
 */
function get_static_option($option_name, $default = null)
{
    $value = Cache::remember($option_name, 60 * 60, function () use ($option_name) {
        try {
            return StaticOption::where('option_name', $option_name)->first();
        } catch (\Exception $e) {
            return null;
        }
    });

    return $value->option_value ?? $default;
}

function get_static_option_central($option_name, $default = null)
{
    $value = Cache::remember($option_name, 30, function () use ($option_name) {
        try {
            return \App\Models\StaticOptionCentral::where('option_name', $option_name)->first();
        } catch (\Exception $e) {
            return null;
        }
    });

    return $value->option_value ?? $default;
}


function get_user_lang()
{
    return $lang = \App\Facades\GlobalLanguage::user_lang_slug();
}

//render_image_markup_by_attachment_id
function render_image_markup_by_attachment_data($data)
{
    //todo: render image based on image data not from database

}

function get_attachment_image_by_id($id, $size = null, $default = false): array
{
    $image_details = Cache::remember('media_image_' . $id, 60 * 60 * 24, function () use ($id) {
        return \App\Models\MediaUploader::find($id);
    });
    $return_val = [];
    $image_url = '';

    if (!empty($id) && !empty($image_details)) {

        $tenant_subdomain = '';
        if (tenant()) {
            $tenant_user = tenant() ?? null;
            $tenant_subdomain = !is_null($tenant_user) ? $tenant_user->id . '/' : '';
        }

        $path_prefix = is_null(tenant()) ? 'assets/landlord' : 'assets/tenant';
        $path = global_asset($path_prefix . '/uploads/media-uploader/' . $tenant_subdomain);
        $base_path = global_assets_path($path_prefix . '/uploads/media-uploader/' . $tenant_subdomain);
        $image_url = $path . $image_details->path;
        switch ($size) {
            case "large":
                if ($base_path . 'large/large-' . $image_details->path && !is_dir($base_path . 'large/large-' . $image_details->path)) {
                    $image_url = $path . '/large/large-' . $image_details->path;
                }
                break;
            case "grid":
                if ($base_path . 'grid/grid-' . $image_details->path && !is_dir($base_path . 'grid/grid-' . $image_details->path)) {
                    $image_url = $path . '/grid/grid-' . $image_details->path;
                }
                break;
            case "thumb":
                if ($base_path . 'thumb/thumb-' . $image_details->path && !is_dir($base_path . 'thumb/thumb-' . $image_details->path)) {
                    $image_url = $path . '/thumb/thumb-' . $image_details->path;
                }
                break;
            default:
                if (is_numeric($id) && file_exists($base_path . $image_details->path) && !is_dir($base_path . $image_details->path)) {
                    $image_url = $path . '/' . $image_details->path;
                }
                break;
        }
    }

    if (!empty($image_details)) {
        $return_val['image_id'] = $image_details->id;
        $return_val['path'] = $image_details->path;
        $return_val['img_url'] = $image_url;
        $return_val['img_alt'] = $image_details->alt;
    } elseif (empty($image_details) && $default) {
        $return_val['img_url'] = global_asset('assets/common/img/no-image.jpeg');
        $return_val['img_alt'] = '';
    }

    return $return_val;
}

function get_attachment_image_by_path($id, $path, $alt = null, $size = null, $default = false): array
{
    $image_details = Cache::remember('media_image_' . $id, 300, function () use ($id, $path) {
        return $path;
    });
    $return_val = [];
    $image_url = '';

    if (!empty($image_details)) {

        $tenant_subdomain = '';
        if (tenant()) {
            $tenant_user = tenant() ?? null;
            $tenant_subdomain = !is_null($tenant_user) ? $tenant_user->id . '/' : '';
        }

        $path_prefix = is_null(tenant()) ? 'assets/landlord' : 'assets/tenant';
        $path = global_asset($path_prefix . '/uploads/media-uploader/' . $tenant_subdomain);
        $base_path = global_assets_path($path_prefix . '/uploads/media-uploader/' . $tenant_subdomain);
        $image_url = $path . $image_details;
        switch ($size) {
            case "large":
                if ($base_path . 'large/large-' . $image_details && !is_dir($base_path . 'large/large-' . $image_details)) {
                    $image_url = $path . '/large/large-' . $image_details;
                }
                break;
            case "grid":
                if ($base_path . 'grid/grid-' . $image_details && !is_dir($base_path . 'grid/grid-' . $image_details)) {
                    $image_url = $path . '/grid/grid-' . $image_details;
                }
                break;
            case "thumb":
                if ($base_path . 'thumb/thumb-' . $image_details && !is_dir($base_path . 'thumb/thumb-' . $image_details)) {
                    $image_url = $path . '/thumb/thumb-' . $image_details;
                }
                break;
            default:
                if (is_numeric($id) && file_exists($base_path . $image_details) && !is_dir($base_path . $image_details)) {
                    $image_url = $path . '/' . $image_details;
                }
                break;
        }
    }

    if (!empty($image_details)) {
        $return_val['image_id'] = $id;
        $return_val['path'] = $image_details;
        $return_val['img_url'] = $image_url;
        $return_val['img_alt'] = $alt;
    } elseif (empty($image_details) && $default) {
        $return_val['img_url'] = global_asset('no-image.jpeg');
        $return_val['img_alt'] = '';
    }

    return $return_val;
}

function product_prices($product_object, $class = '')
{
    $markup = '';
    $sale_price = $product_object->sale_price;
    if ($product_object->price != null) {
        $regular_price = $product_object->price;

        $markup = '<span class="flash-prices ' . $class . '">' . amount_with_currency_symbol($sale_price) . '</span>';
        $markup .= '<span class="flash-old-prices">' . amount_with_currency_symbol($regular_price) . '</span>';

        return $markup;
    }

    return '<span class="flash-prices ' . $class . '">' . amount_with_currency_symbol($sale_price) . '</span>';
}

function campaign_product_prices($product_object, $campaign_price, $class = '')
{
    $markup = '';
    $sale_price = $product_object->sale_price;

    $markup = '<span class="flash-prices ' . $class . '">' . amount_with_currency_symbol($campaign_price) . '</span>';
    $markup .= '<span class="flash-old-prices">' . amount_with_currency_symbol($sale_price) . '</span>';

    return $markup;
}

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

    return round(1024 ** ($base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}


/**
 * @param $key
 * @param $value
 * @return bool
 */
function update_static_option($key, $value): bool
{
    $static_option = null;
    if ($static_option === null) {
        try {
            $static_option = StaticOption::query();
        } catch (\Exception $e) {
        }
    }
    try {
        $static_option->updateOrCreate(['option_name' => $key], [
            'option_name' => $key,
            'option_value' => $value
        ]);
    } catch (\Exception $e) {
        return false;
    }

    \Illuminate\Support\Facades\Cache::forget($key);
    return true;
}

function update_static_option_central($key, $value): bool
{
    $static_option = null;
    if ($static_option === null) {
        try {
            $static_option = \App\Models\StaticOptionCentral::query();
        } catch (\Exception $e) {
        }
    }
    try {
        $static_option->updateOrCreate(['option_name' => $key], [
            'option_name' => $key,
            'option_value' => $value
        ]);
    } catch (\Exception $e) {
        return false;
    }

    \Illuminate\Support\Facades\Cache::forget($key);
    return true;
}

function delete_static_option($key)
{
    try {
        return StaticOption::where('option_name', $key)->delete();
    } catch (\Exception $e) {
        //handle error
    }
}

function filter_static_option_value(string $index, array $array = [])
{
    return $array[$index] ?? '';
}

function render_favicon_by_id($id): string
{

    $site_favicon = get_attachment_image_by_id($id, "full", false);
    $output = '';
    if (!empty($site_favicon)) {
        $output .= '<link rel="icon" href="' . $site_favicon['img_url'] . '" type="image/png">';
    }
    return $output;
}

function render_image_markup_by_attachment_path($id, $alt, $path, $class = null, $size = 'full', $default = false): string
{
    $image_details = get_attachment_image_by_path($id, $path, $alt, $size, $default);
    if (!empty($image_details)) {
        $class_list = !empty($class) ? 'class="' . $class . '"' : '';
        $output = '<img src="' . $image_details['img_url'] . '" ' . $class_list . ' alt="' . $image_details['img_alt'] . '"/>';
    }
    return $output;
}

function render_image_markup_by_attachment_id($id, $class = null, $size = 'full', $default = false): string
{
    if (empty($id) && !$default) return '';
    $output = '';

    $image_details = get_attachment_image_by_id($id, $size, $default);
    if (!empty($image_details)) {
        $class_list = !empty($class) ? 'class="' . $class . '"' : '';
        $output = '<img src="' . $image_details['img_url'] . '" ' . $class_list . ' alt="' . $image_details['img_alt'] . '"/>';
    }

    return $output;
}

function get_theme_image($slug)
{
    //Info - Theme image path - assets/img/theme
    $themes = [];

    foreach (range(1, 12) as $item)
    {
        $themes['theme-'.$item] = global_asset('assets/img/theme/th-'.$item.'.jpg');
    }

    if (array_key_exists($slug, $themes))
    {
        return $themes[$slug];
    }

    return false;
}

function render_background_image_markup_by_attachment_id($id, $size = 'full'): string
{
    if (empty($id)) return '';
    $output = '';

    $image_details = get_attachment_image_by_id($id, $size);
    if (!empty($image_details)) {
        $output = 'style="background-image: url(' . $image_details['img_url'] . ');"';
    }
    return $output;
}

function render_og_meta_image_by_attachment_id($id, $size = 'full'): string
{
    if (empty($id)) return '';
    $output = '';

    $image_details = get_attachment_image_by_id($id, $size);
    if (!empty($image_details)) {
        $output = ' <meta property="og:image" content="' . $image_details['img_url'] . '" />';
    }
    return $output;
}

function render_star_rating_markup($rating): string
{
    $star = (int)(2 * $rating) . '0';

    return '<div class="rating-wrap mt-2">
                 <div class="ratings">
                      <span class="hide-rating"></span>
                      <span class="show-rating" style="width: ' . $star . '%' . '"></span>
                 </div>
            </div>';
}

function render_product_star_rating_markup_with_count($product_object): string
{
    $sum = 0;
    $product_review = $product_object->reviews;
    $product_count = count($product_review) < 1 ? 1 : count($product_review);

    if ($product_count >= 1) {
        foreach ($product_review as $review) {
            $sum += $review?->rating;
        }
    } else {
        $sum = current($product_review)?->rating ?? 0;
    }

    $rating = $sum / $product_count;
    $star = (int)(2 * $rating) . '0';

    $rating_markup = '';
    if ($sum > 0) {
        $rating_markup = '<div class="ratings">
                            <span class="hide-rating"></span>
                            <span class="show-rating" style="width: ' . $star . '%' . '"></span>
                        </div>
                        <p>
                            <span class="total-ratings">(' . $product_count . ')</span>
                        </p>';
    }

    return '<div class="rating-wrap mt-2">
                 ' . $rating_markup . '
            </div>';
}

function get_footer_copyright_text()
{
    $footer_copyright_text = get_static_option('site_footer_copyright_text');
    return str_replace(array('{copy}', '{year}'), array('&copy;', date('Y')), $footer_copyright_text);
}

function get_modified_title($title)
{
    if (str_contains($title, '{h}') && str_contains($title, '{/h}')) {
        $text = explode('{h}', $title);

        $highlighted_word = explode('{/h}', $text[1])[0];

        $highlighted_text = '<span class="section-shape">' . $highlighted_word . '</span>';
        return $final_title = '<h2 class="title">' . str_replace('{h}' . $highlighted_word . '{/h}', $highlighted_text, $title) . '</h2>';

    } else {
        return $final_title = '<h2 class="title">' . $title . '</h2>';
    }
}

function get_tenant_highlighted_text($title)
{
    if (str_contains($title, '{h}') && str_contains($title, '{/h}')) {
        $text = explode('{h}', $title);

        $highlighted_word = explode('{/h}', $text[1])[0];

        $highlighted_text = '<span class="color-two">' . $highlighted_word . '</span>';
        return str_replace('{h}' . $highlighted_word . '{/h}', $highlighted_text, $title);
    }

    return $title;
}

function get_price_plan_expire_status($date_expire): string
{
    $expire_date = \Carbon\Carbon::parse($date_expire);

    if ($expire_date != null) {
        $now_date = \Carbon\Carbon::now();
        return $now_date > $expire_date ? 'expired' : 'active';
    }

    return 'active';
}

function get_trial_status($payment_log_create_date, $trial_days): string
{
    $create_date = $payment_log_create_date;
    $trial_expire_date = \Carbon\Carbon::parse($create_date)->addDays($trial_days);
    $now_date = \Carbon\Carbon::parse(now());

    return $now_date->greaterThan($trial_expire_date) ? __('expired') : __('active');
}

function get_trial_days_left($tenant)
{
    $create = $tenant->created_at;
    $trial_days = optional(optional($tenant->payment_log)->package)->trial_days;

    $will_expire = \Illuminate\Support\Carbon::parse($tenant->created_at)->addDays($trial_days);

    return $days_left = \Carbon\Carbon::now()->diffInDays($will_expire, false);
}

function get_plan_left_days($package_id, $tenant_expire_date)
{
    $order_details = PricePlan::find($package_id) ?? '';

    $package_start_date = '';
    $package_expire_date = '';

    if (!empty($order_details)) {
        if ($order_details->type == 0) { //monthly
            $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
            $package_expire_date = Carbon::now()->addMonth(1)->format('d-m-Y h:i:s');

        } elseif ($order_details->type == 1) { //yearly
            $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
            $package_expire_date = Carbon::now()->addYear(1)->format('d-m-Y h:i:s');
        } else { //lifetime
            $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
            $package_expire_date = null;
        }
    }

    $left_days = 0;
    if ($package_expire_date != null) {
        $old_days_left = Carbon::now()->diff($tenant_expire_date);

        if ($old_days_left->invert == 0) {
            $left_days = $old_days_left->days;
        }

        $renew_left_days = 0;
        $renew_left_days = Carbon::parse($package_expire_date)->diffInDays();

        $sum_days = $left_days + $renew_left_days;
        $new_package_expire_date = Carbon::today()->addDays($sum_days)->format("d-m-Y h:i:s");
    } else {
        $new_package_expire_date = null;
    }

    return $left_days == 0 ? $package_expire_date : $new_package_expire_date;
}

function get_tenant_storage_info($format = 'kb')
{
    $file_size = 0;
    $tenant = tenant()->id;
    $scan_path = Storage::disk("root_url")->allFiles('assets/tenant/uploads/media-uploader/' . $tenant);

    foreach ($scan_path as $file) {
        clearstatcache();
        $exploded = explode('/', $file);
        if ($exploded[count($exploded) - 1] === '.DS_Store' || $file === 'NAN') {
            continue;
        }

        $file_size += filesize($file);
    }

    if (strtolower($format) == 'kb') {
        $file_size /= 1024;
    } elseif (strtolower($format) == 'mb') {
        $file_size = (($file_size / 1024)) / 1024;
    }

    return $file_size;
}

function get_product_dynamic_price($product_object)
{
    $is_expired = 0;
    $campaign_name = null;
    (double)$regular_price = $product_object->price;
    (double)$sale_price = $product_object->sale_price;
    $discount = null;

    if (!is_null($product_object?->campaign_product)) {
        if ($product_object?->campaign_product?->campaign?->status == 'publish') {
            $start_date = \Carbon\Carbon::parse($product_object?->campaign_product?->start_date);
            $end_date = \Carbon\Carbon::parse($product_object?->campaign_product?->end_date);

            if ($start_date->lessThanOrEqualTo(now()) && $end_date->greaterThanOrEqualTo(now())) {
                (string)$campaign_name = $product_object?->campaign_product?->campaign?->title;
                (double)$sale_price = $product_object?->campaign_product?->campaign_price;
                (double)$regular_price = $product_object->sale_price;

                $discount = 100 - round(($sale_price / $regular_price) * 100);
                $is_expired = 1;
            }
        }
    }

    $data['campaign_name'] = $campaign_name;
    $data['sale_price'] = $sale_price;
    $data['regular_price'] = $regular_price;
    $data['discount'] = $discount;
    $data['is_expired'] = $is_expired;

    return $data;
}

function get_all_static_option($option_name)
{
    $all_static_options = all_static_options();
    $array = Cache::remember('all_static_options', 600, function () use ($all_static_options) {
        $array = StaticOption::select('option_name', 'option_value')->whereIn('option_name', $all_static_options)->get()->toArray();
        return $array;
    });

    $new = [];
    foreach ($array as $arr) {
        $new[$arr['option_name']] = $arr['option_value'];
    }

    return $new[$option_name] ?? '';
}

function all_static_options()
{
    $option_names = Cache::remember('static_option_names', 600, function () {
        $new_arr = [];
        $array = StaticOption::select('option_name')->get()->toArray();
        foreach ($array as $key => $arr) {
            $new_arr[$key] = $arr['option_name'];
        }

        return $new_arr;
    });

    return $option_names;
}

function setEnvValue(array $values)
{

    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);

    if (count($values) > 0) {
        foreach ($values as $envKey => $envValue) {

            $str .= "\n"; // In case the searched variable is in the last line without \n
            $keyPosition = strpos($str, "{$envKey}=");
            $endOfLinePosition = strpos($str, "\n", $keyPosition);
            $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

            // If key does not exist, add it
            if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                $str .= "{$envKey}={$envValue}\n";
            } else {
                $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
            }
        }
    }

    $str = substr($str, 0, -1);
    if (!file_put_contents($envFile, $str)) return false;
    return true;
}

function addQuotes($str)
{
    return '"' . $str . '"';
}

function site_title()
{
    return get_static_option('site_' . \App\Facades\GlobalLanguage::default_slug() . '_title');
}

function site_global_email()
{
    $admin_mail_check = is_null(tenant()) ? get_static_option('site_global_email') : get_static_option('tenant_site_global_email');
    return $admin_mail_check;
}

function get_tenant_website_url($user_details)
{
    return '//' . $user_details->subdomain . '.' . current(config('tenancy.central_domains'));
}

function route_prefix($end = null)
{
    $prefix = is_null(tenant()) ? 'landlord' : 'tenant';
    return $prefix . '.' . $end;
}

function render_attachment_preview_for_admin($id)
{
    $markup = '';
    $header_bg_img = get_attachment_image_by_id($id, null, true);
    $img_url = $header_bg_img['img_url'] ?? '';
    $img_alt = $header_bg_img['img_alt'] ?? '';

    if (!empty($img_url)) {
        $markup = sprintf('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="%1$s" alt="%2$s"></div></div></div>', $img_url, $img_alt);
    }

    return $markup;
}


function render_drag_drop_form_builder_markup($content = '')
{
    $output = '';

    $form_fields = json_decode($content);
    $output .= '<ul id="sortable" class="available-form-field main-fields">';
    if (!empty($form_fields)) {
        $select_index = 0;
        foreach ($form_fields->field_type as $key => $ftype) {
            $args = [];
            $required_field = '';
            if (property_exists($form_fields, 'field_required')) {
                $filed_requirement = (array)$form_fields->field_required;
                $required_field = !empty($filed_requirement[$key]) ? 'on' : '';
            }
            if ($ftype == 'select') {
                $args['select_option'] = isset($form_fields->select_options[$select_index]) ? $form_fields->select_options[$select_index] : '';
                $select_index++;
            }
            if ($ftype == 'file') {
                $args['mimes_type'] = isset($form_fields->mimes_type->$key) ? $form_fields->mimes_type->$key : '';
            }
            $output .= render_drag_drop_form_builder_field_markup($key, $ftype, $form_fields->field_name[$key], $form_fields->field_placeholder[$key], $required_field, $args);
        }
    } else {
        $output .= render_drag_drop_form_builder_field_markup('1', 'text', 'your-name', 'Your Name', '');
    }

    $output .= '</ul>';
    return $output;
}

function render_drag_drop_form_builder_field_markup($key, $type, $name, $placeholder, $required, $args = [])
{
    $required_check = !empty($required) ? 'checked' : '';
    $placeholder = htmlspecialchars(strip_tags($placeholder));
    $name = htmlspecialchars(strip_tags($name));
    $type = htmlspecialchars(strip_tags($type));
    $output = '<li class="ui-state-default">
                     <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                    <span class="remove-fields">x</span>
                    <a data-toggle="collapse" href="#fileds_collapse_' . $key . '" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        ' . ucfirst($type) . ': <span
                                class="placeholder-name">' . $placeholder . '</span>
                    </a>';
    $output .= '<div class="collapse" id="fileds_collapse_' . $key . '">
            <div class="card card-body margin-top-30">
                <input type="hidden" class="form-control" name="field_type[]"
                       value="' . $type . '">
                <div class="form-group">
                    <label>' . __('Name') . '</label>
                    <input type="text" class="form-control " name="field_name[]"
                           placeholder="' . __('enter field name') . '"
                           value="' . $name . '" >
                </div>
                <div class="form-group">
                    <label>' . __('Placeholder/Label') . '</label>
                    <input type="text" class="form-control field-placeholder"
                           name="field_placeholder[]" placeholder="' . __('enter field placeholder/label') . '"
                           value="' . $placeholder . '" >
                </div>
                <div class="form-group">
                    <label ><strong>' . __('Required') . '</strong></label>
                    <label class="switch">
                        <input type="checkbox" class="field-required" ' . $required_check . ' name="field_required[' . $key . ']">
                        <span class="slider-yes-no"></span>
                    </label>
                </div>';
    if ($type == 'select') {
        $output .= '<div class="form-group">
                        <label>' . __('Options') . '</label>
                            <textarea name="select_options[]" class="form-control max-height-120" cols="30" rows="10"
                                required>' . strip_tags($args['select_option']) . '</textarea>
                           <small>' . __('separate option by new line') . '</small>
                    </div>';
    }
    if ($type == 'file') {
        $output .= '<div class="form-group"><label>' . __('File Type') . '</label><select name="mimes_type[' . $key . ']" class="form-control mime-type">';
        $output .= '<option value="mimes:jpg,jpeg,png"';
        if (isset($args['mimes_type']) && $args['mimes_type'] == 'mimes:jpg,jpeg,png') {
            $output .= "selected";
        }
        $output .= '>' . __('mimes:jpg,jpeg,png') . '</option>';

        $output .= '<option value="mimes:txt,pdf"';
        if (isset($args['mimes_type']) && $args['mimes_type'] == 'mimes:txt,pdf') {
            $output .= "selected";
        }
        $output .= '>' . __('mimes:txt,pdf') . '</option>';

        $output .= '<option value="mimes:doc,docx"';
        if (isset($args['mimes_type']) && $args['mimes_type'] == 'mimes:mimes:doc,docx') {
            $output .= "selected";
        }
        $output .= '>' . __('mimes:mimes:doc,docx') . '</option>';

        $output .= '</select></div>';
    }
    $output .= '</div></div></li>';

    return $output;
}


function get_default_language()
{
    $defaultLang = \App\Models\Language::where('default', 1)->first();
    return $defaultLang->slug;
}

function core_path($path)
{
    return str_replace('core/', '', public_path($path));
}

function global_assets_path($path)
{
    return str_replace(['core/public/', 'core\\public\\'], '', public_path($path));
}

function get_page_slug($id, $default = null)
{
    return \App\Models\Page::where('id', $id)->first()->slug ?? $default;
}

function get_page_info($id, $default = null)
{
    return \App\Models\Page::where('id', $id)->select('id','slug','title')->first() ?? $default;
}

function render_gallery_image_attachment_preview($gal_image)
{
    if (empty($gal_image)) {
        return;
    }
    $output = '';
    $gallery_images = explode('|', $gal_image);
    foreach ($gallery_images as $gl_img) {
        $work_section_img = get_attachment_image_by_id($gl_img, null, true);
        if (!empty($work_section_img)) {
            $output .= sprintf('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="%1$s" alt=""> </div></div></div>', $work_section_img['img_url']);
        }
    }
    return $output;
}

function render_frontend_sidebar($location, $args = [])
{
    $output = '';
    $all_widgets = \App\Models\Widgets::where(['widget_location' => $location])->orderBy('widget_order', 'ASC')->get();

    foreach ($all_widgets as $widget) {
        $output .= \Plugins\WidgetBuilder\WidgetBuilderSetup::render_widgets_by_name_for_frontend([
            'name' => $widget->widget_name,
            'location' => $location,
            'id' => $widget->id,
            'column' => $args['column'] ?? false,
            'namespace' => $widget->widget_namespace
        ]);
    }

    return $output;
}

function render_admin_panel_widgets_list()
{
    return \Plugins\WidgetBuilder\WidgetBuilderSetup::get_admin_panel_widgets();
}

function get_admin_sidebar_list()
{
    return \Plugins\WidgetBuilder\WidgetBuilderSetup::get_admin_widget_sidebar_list();
}

function render_admin_saved_widgets($location)
{
    $output = '';
    $all_widgets = \App\Models\Widgets::where(['widget_location' => $location])->orderBy('widget_order', 'asc')->get();

    foreach ($all_widgets as $widget) {
        $output .= \Plugins\WidgetBuilder\WidgetBuilderSetup::render_widgets_by_name_for_admin([
            'name' => $widget->widget_name,
            'namespace' => $widget->widget_namespace,
            'id' => $widget->id,
            'type' => 'update',
            'order' => $widget->widget_order,
            'location' => $widget->widget_location,
        ]);
    }

    return $output;
}

function single_post_share($url, $title, $img_url)
{
    $output = '';
    //get current page url
    $encoded_url = urlencode($url);
    //get current page title
    $post_title = str_replace(' ', '%20', $title);


    //all social share link generate
    $facebook_share_link = 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url;
    $twitter_share_link = 'https://twitter.com/intent/tweet?text=' . $post_title . '&amp;url=' . $encoded_url . '&amp;via=Crunchify';
    $linkedin_share_link = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $encoded_url . '&amp;title=' . $post_title;
    $pinterest_share_link = 'https://pinterest.com/pin/create/button/?url=' . $encoded_url . '&amp;media=' . $img_url . '&amp;description=' . $post_title;

    $output .= '<li><a class="facebook" href="' . $facebook_share_link . '"><i class="lab la-facebook-f"></i></a></li>';
    $output .= '<li><a class="twitter" href="' . $twitter_share_link . '"><i class="lab la-twitter"></i></a></li>';
    $output .= '<li><a class="linkedin" href="' . $linkedin_share_link . '"><i class="lab la-linkedin-in"></i></a></li>';
    $output .= '<li><a class="pinterest" href="' . $pinterest_share_link . '"><i class="lab la-pinterest-p"></i></a></li>';

    return $output;
}

//New Menu Functions
function render_pages_list($lang = null)
{
    $instance = new \Plugins\MenuBuilder\MenuBuilderHelpers();
    return $instance->get_static_pages_list($lang);
}

function render_dynamic_pages_list($lang = null)
{
    $instance = new \Plugins\MenuBuilder\MenuBuilderHelpers();
    return $instance->get_post_type_page_list($lang);
}

function render_mega_menu_list($lang = null)
{
    $instance = new \Plugins\MenuBuilder\MegaMenuBuilderSetup();
    return $instance->render_mega_menu_list($lang);
}

function render_draggable_menu($id)
{
    $instance = new \Plugins\MenuBuilder\MenuBuilderAdminRender();
    return $instance->render_admin_panel_menu($id);
}

function render_frontend_menu($id)
{
    $instance = new \Plugins\MenuBuilder\MenuBuilderFrontendRender();
    return $instance->render_frrontend_panel_menu($id);
}

function get_navbar_style()
{
    $fallback = get_static_option('global_navbar_variant');

    if (request()->routeIs(route_prefix() . 'dynamic.page')) {
        $page_info = \App\Models\Page::where(['slug' => request()->path()])->first();
        return !is_null($page_info) && !is_null($page_info->navbar_variant) ? $page_info->navbar_variant : $fallback;
    } elseif (request()->routeIs('homepage')) {
        $page_info = \App\Models\Page::find(get_static_option('home_page'));
        return !is_null($page_info) ? $page_info->navbar_variant : $fallback;

    } elseif (request()->is('/')) {
        $page_info = \App\Models\Page::find(get_static_option('home_page'));
    }

    return $fallback;
}


function get_footer_style()
{
    $fallback = get_static_option('global_footer_variant') ?? 01;
    if (request()->routeIs(route_prefix() . 'dynamic.page')) {

        $page_info = \App\Models\Page::where(['slug' => request()->path()])->first();
        return !is_null($page_info) && !is_null($page_info->footer_variant) ? $page_info->footer_variant : $fallback;
    } elseif (request()->routeIs('homepage')) {

        $page_info = \App\Models\Page::find(get_static_option('home_page'));
        return !is_null($page_info) ? $page_info->footer_variant : $fallback;

    } elseif (request()->is('/')) {

        $page_info = \App\Models\Page::find(get_static_option('home_page'));
        return !is_null($page_info) ? $page_info->footer_variant : $fallback;
    }

    return $fallback;
}

function purify_html_raw($html)
{
    return \Mews\Purifier\Facades\Purifier::clean($html);
}

function get_user_lang_direction()
{
    $default = \App\Models\Language::where('default', 1)->first();
    $user_direction = \App\Models\Language::where('slug', session()->get('lang'))->first();
    return !empty(session()->get('lang')) ? $user_direction->direction : $default->direction;
}


function get_language_name_by_slug($slug)
{
    $data = \App\Models\Language::where('slug', $slug)->first();
    return $data->name;
}

function get_country_field($name, $id, $class)
{
    return '<select style="height:50px;" name="' . $name . '" id="' . $id . '" class="' . $class . '"><option value="">' . __('Select Country') . '</option><option value="Afghanistan" >Afghanistan</option><option value="Albania" >Albania</option><option value="Algeria" >Algeria</option><option value="American Samoa" >American Samoa</option><option value="Andorra" >Andorra</option><option value="Angola" >Angola</option><option value="Anguilla" >Anguilla</option><option value="Antarctica" >Antarctica</option><option value="Antigua and Barbuda" >Antigua and Barbuda</option><option value="Argentina" >Argentina</option><option value="Armenia" >Armenia</option><option value="Aruba" >Aruba</option><option value="Australia" >Australia</option><option value="Austria" >Austria</option><option value="Azerbaijan" >Azerbaijan</option><option value="Bahamas" >Bahamas</option><option value="Bahrain" >Bahrain</option><option value="Bangladesh" >Bangladesh</option><option value="Barbados" >Barbados</option><option value="Belarus" >Belarus</option><option value="Belgium" >Belgium</option><option value="Belize" >Belize</option><option value="Benin" >Benin</option><option value="Bermuda" >Bermuda</option><option value="Bhutan" >Bhutan</option><option value="Bolivia" >Bolivia</option><option value="Bosnia and Herzegovina" >Bosnia and Herzegovina</option><option value="Botswana" >Botswana</option><option value="Bouvet Island" >Bouvet Island</option><option value="Brazil" >Brazil</option><option value="British Indian Ocean Territory" >British Indian Ocean Territory</option><option value="Brunei Darussalam" >Brunei Darussalam</option><option value="Bulgaria" >Bulgaria</option><option value="Burkina Faso" >Burkina Faso</option><option value="Burundi" >Burundi</option><option value="Cambodia" >Cambodia</option><option value="Cameroon" >Cameroon</option><option value="Canada" >Canada</option><option value="Cape Verde" >Cape Verde</option><option value="Cayman Islands" >Cayman Islands</option><option value="Central African Republic" >Central African Republic</option><option value="Chad" >Chad</option><option value="Chile" >Chile</option><option value="China" >China</option><option value="Christmas Island" >Christmas Island</option><option value="Cocos (Keeling) Islands" >Cocos (Keeling) Islands</option><option value="Colombia" >Colombia</option><option value="Comoros" >Comoros</option><option value="Cook Islands" >Cook Islands</option><option value="Costa Rica" >Costa Rica</option><option value="Croatia (Hrvatska)" >Croatia (Hrvatska)</option><option value="Cuba" >Cuba</option><option value="Cyprus" >Cyprus</option><option value="Czech Republic" >Czech Republic</option><option value="Democratic Republic of the Congo" >Democratic Republic of the Congo</option><option value="Denmark" >Denmark</option><option value="Djibouti" >Djibouti</option><option value="Dominica" >Dominica</option><option value="Dominican Republic" >Dominican Republic</option><option value="East Timor" >East Timor</option><option value="Ecuador" >Ecuador</option><option value="Egypt" >Egypt</option><option value="El Salvador" >El Salvador</option><option value="Equatorial Guinea" >Equatorial Guinea</option><option value="Eritrea" >Eritrea</option><option value="Estonia" >Estonia</option><option value="Ethiopia" >Ethiopia</option><option value="Falkland Islands (Malvinas)" >Falkland Islands (Malvinas)</option><option value="Faroe Islands" >Faroe Islands</option><option value="Fiji" >Fiji</option><option value="Finland" >Finland</option><option value="France" >France</option><option value="France, Metropolitan" >France, Metropolitan</option><option value="French Guiana" >French Guiana</option><option value="French Polynesia" >French Polynesia</option><option value="French Southern Territories" >French Southern Territories</option><option value="Gabon" >Gabon</option><option value="Gambia" >Gambia</option><option value="Georgia" >Georgia</option><option value="Germany" >Germany</option><option value="Ghana" >Ghana</option><option value="Gibraltar" >Gibraltar</option><option value="Greece" >Greece</option><option value="Greenland" >Greenland</option><option value="Grenada" >Grenada</option><option value="Guadeloupe" >Guadeloupe</option><option value="Guam" >Guam</option><option value="Guatemala" >Guatemala</option><option value="Guernsey" >Guernsey</option><option value="Guinea" >Guinea</option><option value="Guinea-Bissau" >Guinea-Bissau</option><option value="Guyana" >Guyana</option><option value="Haiti" >Haiti</option><option value="Heard and Mc Donald Islands" >Heard and Mc Donald Islands</option><option value="Honduras" >Honduras</option><option value="Hong Kong" >Hong Kong</option><option value="Hungary" >Hungary</option><option value="Iceland" >Iceland</option><option value="India" >India</option><option value="Indonesia" >Indonesia</option><option value="Iran (Islamic Republic of)" >Iran (Islamic Republic of)</option><option value="Iraq" >Iraq</option><option value="Ireland" >Ireland</option><option value="Isle of Man" >Isle of Man</option><option value="Israel" >Israel</option><option value="Italy" >Italy</option><option value="Ivory Coast" >Ivory Coast</option><option value="Jamaica" >Jamaica</option><option value="Japan" >Japan</option><option value="Jersey" >Jersey</option><option value="Jordan" >Jordan</option><option value="Kazakhstan" >Kazakhstan</option><option value="Kenya" >Kenya</option><option value="Kiribati" >Kiribati</option><option value="Korea, Democratic People\'s Republic of" >Korea, Democratic People\'s Republic of</option><option value="Korea, Republic of" >Korea, Republic of</option><option value="Kosovo" >Kosovo</option><option value="Kuwait" >Kuwait</option><option value="Kyrgyzstan" >Kyrgyzstan</option><option value="Lao People\'s Democratic Republic" >Lao People\'s Democratic Republic</option><option value="Latvia" >Latvia</option><option value="Lebanon" >Lebanon</option><option value="Lesotho" >Lesotho</option><option value="Liberia" >Liberia</option><option value="Libyan Arab Jamahiriya" >Libyan Arab Jamahiriya</option><option value="Liechtenstein" >Liechtenstein</option><option value="Lithuania" >Lithuania</option><option value="Luxembourg" >Luxembourg</option><option value="Macau" >Macau</option><option value="Madagascar" >Madagascar</option><option value="Malawi" >Malawi</option><option value="Malaysia" >Malaysia</option><option value="Maldives" >Maldives</option><option value="Mali" >Mali</option><option value="Malta" >Malta</option><option value="Marshall Islands" >Marshall Islands</option><option value="Martinique" >Martinique</option><option value="Mauritania" >Mauritania</option><option value="Mauritius" >Mauritius</option><option value="Mayotte" >Mayotte</option><option value="Mexico" >Mexico</option><option value="Micronesia, Federated States of" >Micronesia, Federated States of</option><option value="Moldova, Republic of" >Moldova, Republic of</option><option value="Monaco" >Monaco</option><option value="Mongolia" >Mongolia</option><option value="Montenegro" >Montenegro</option><option value="Montserrat" >Montserrat</option><option value="Morocco" >Morocco</option><option value="Mozambique" >Mozambique</option><option value="Myanmar" >Myanmar</option><option value="Namibia" >Namibia</option><option value="Nauru" >Nauru</option><option value="Nepal" >Nepal</option><option value="Netherlands" >Netherlands</option><option value="Netherlands Antilles" >Netherlands Antilles</option><option value="New Caledonia" >New Caledonia</option><option value="New Zealand" >New Zealand</option><option value="Nicaragua" >Nicaragua</option><option value="Niger" >Niger</option><option value="Nigeria" >Nigeria</option><option value="Niue" >Niue</option><option value="Norfolk Island" >Norfolk Island</option><option value="North Macedonia" >North Macedonia</option><option value="Northern Mariana Islands" >Northern Mariana Islands</option><option value="Norway" >Norway</option><option value="Oman" >Oman</option><option value="Pakistan" >Pakistan</option><option value="Palau" >Palau</option><option value="Palestine" >Palestine</option><option value="Panama" >Panama</option><option value="Papua New Guinea" >Papua New Guinea</option><option value="Paraguay" >Paraguay</option><option value="Peru" >Peru</option><option value="Philippines" >Philippines</option><option value="Pitcairn" >Pitcairn</option><option value="Poland" >Poland</option><option value="Portugal" >Portugal</option><option value="Puerto Rico" >Puerto Rico</option><option value="Qatar" >Qatar</option><option value="Republic of Congo" >Republic of Congo</option><option value="Reunion" >Reunion</option><option value="Romania" >Romania</option><option value="Russian Federation" >Russian Federation</option><option value="Rwanda" >Rwanda</option><option value="Saint Kitts and Nevis" >Saint Kitts and Nevis</option><option value="Saint Lucia" >Saint Lucia</option><option value="Saint Vincent and the Grenadines" >Saint Vincent and the Grenadines</option><option value="Samoa" >Samoa</option><option value="San Marino" >San Marino</option><option value="Sao Tome and Principe" >Sao Tome and Principe</option><option value="Saudi Arabia" >Saudi Arabia</option><option value="Senegal" >Senegal</option><option value="Serbia" >Serbia</option><option value="Seychelles" >Seychelles</option><option value="Sierra Leone" >Sierra Leone</option><option value="Singapore" >Singapore</option><option value="Slovakia" >Slovakia</option><option value="Slovenia" >Slovenia</option><option value="Solomon Islands" >Solomon Islands</option><option value="Somalia" >Somalia</option><option value="South Africa" >South Africa</option><option value="South Georgia South Sandwich Islands" >South Georgia South Sandwich Islands</option><option value="South Sudan" >South Sudan</option><option value="Spain" >Spain</option><option value="Sri Lanka" >Sri Lanka</option><option value="St. Helena" >St. Helena</option><option value="St. Pierre and Miquelon" >St. Pierre and Miquelon</option><option value="Sudan" >Sudan</option><option value="Suriname" >Suriname</option><option value="Svalbard and Jan Mayen Islands" >Svalbard and Jan Mayen Islands</option><option value="Swaziland" >Swaziland</option><option value="Sweden" >Sweden</option><option value="Switzerland" >Switzerland</option><option value="Syrian Arab Republic" >Syrian Arab Republic</option><option value="Taiwan" >Taiwan</option><option value="Tajikistan" >Tajikistan</option><option value="Tanzania, United Republic of" >Tanzania, United Republic of</option><option value="Thailand" >Thailand</option><option value="Togo" >Togo</option><option value="Tokelau" >Tokelau</option><option value="Tonga" >Tonga</option><option value="Trinidad and Tobago" >Trinidad and Tobago</option><option value="Tunisia" >Tunisia</option><option value="Turkey" >Turkey</option><option value="Turkmenistan" >Turkmenistan</option><option value="Turks and Caicos Islands" >Turks and Caicos Islands</option><option value="Tuvalu" >Tuvalu</option><option value="Uganda" >Uganda</option><option value="Ukraine" >Ukraine</option><option value="United Arab Emirates" >United Arab Emirates</option><option value="United Kingdom" >United Kingdom</option><option value="United States" >United States</option><option value="United States minor outlying islands" >United States minor outlying islands</option><option value="Uruguay" >Uruguay</option><option value="Uzbekistan" >Uzbekistan</option><option value="Vanuatu" >Vanuatu</option><option value="Vatican City State" >Vatican City State</option><option value="Venezuela" >Venezuela</option><option value="Vietnam" >Vietnam</option><option value="Virgin Islands (British)" >Virgin Islands (British)</option><option value="Virgin Islands (U.S.)" >Virgin Islands (U.S.)</option><option value="Wallis and Futuna Islands" >Wallis and Futuna Islands</option><option value="Western Sahara" >Western Sahara</option><option value="Yemen" >Yemen</option><option value="Zambia" >Zambia</option><option value="Zimbabwe" >Zimbabwe</option></select>';
}

function render_footer_copyright_text()
{
    $footer_copyright_text = get_static_option('site_' . get_user_lang() . '_footer_copyright');
    $footer_copyright_text = str_replace('{copy}', '&copy;', $footer_copyright_text);
    $footer_copyright_text = str_replace('{year}', date('Y'), $footer_copyright_text);

    return purify_html_raw($footer_copyright_text);
}


function create_slug($sluggable_text, $model_name, $is_module = false, $module_name = null, $column_name = 'slug')  // Idea, created, updated by Suzon extended by Md Zahid
{
    // Use CamelCase for Model and Module Name
    if ($is_module) {
        $model_path = 'Modules\\' . ucwords($module_name) . '\Entities\\' . ucwords($model_name);
    } else {
        $model_path = '\App\Models\\' . ucwords($model_name);
    }

    $slug = Str::slug($sluggable_text);
    $check = true;

    do {
        $old_category = (new $model_path)->where($column_name, $slug)->orderBy('id', 'DESC')->first();

        if ($old_category != null) {
            $old_category_name = $old_category->$column_name;
            $exploded = explode('-', $old_category_name);

            if (array_key_exists(1, $exploded)) {
                $number = end($exploded);

                if (is_numeric($number) == true) {
                    $number = (int)$number;
                    array_pop($exploded);

                    $final_array = array_merge($exploded, Arr::wrap(++$number));

                    $slug = implode('-', $final_array);
                } else {
                    $slug .= '-1';
                }
            } else {
                $slug .= '-1';
            }
        } else {
            $check = false;
        }
    } while ($check);

    return $slug;
}


function tenant_blog_single_route($slug)
{
    return route('tenant.frontend.blog.single', $slug);
}

function tenant_blog_category_route($slug)
{
    return route('tenant.frontend.blog.category', $slug);
}

function tenant_blog_tag_route($slug)
{
    return route('tenant.frontend.blog.tags.page', $slug);
}

function blog_sorting($request)
{
    $order_by = 'created_at';
    $order = 'desc';
    $order_type = 1;

    if ($request->has('sort')) {
        switch ($request->sort) {
            case 1;
                $order_by = 'title';
                $order = 'asc';
                $order_type = 1;
                break;

            case 2;
                $order = 'asc';
                $order_type = 2;
                break;

            case 3;
                $order = 'desc';
                $order_type = 3;
                break;
        }
    }

    return ['order_by' => $order_by, 'order' => $order, 'order_type' => $order_type];
}

function render_page_meta_data($blog_post)
{

    $site_url = url('/');
    $meta_title = optional($blog_post->metainfo)->title;
    $meta_description = optional($blog_post->metainfo)->description;
    $meta_image = get_attachment_image_by_id(optional($blog_post->metainfo)->image)['img_url'] ?? "";

    $facebook_meta_title = optional($blog_post->metainfo)->tw_title;
    $facebook_meta_description = optional($blog_post->metainfo)->fb_description;
    $facebook_meta_image = get_attachment_image_by_id(optional($blog_post->metainfo)->fb_image)['img_url'] ?? "";

    $twitter_meta_title = optional($blog_post->metainfo)->twitter_meta_tags;
    $twitter_meta_description = optional($blog_post->metainfo)->tw_description;
    $twitter_meta_image = get_attachment_image_by_id(optional($blog_post->metainfo)->tw_image)['img_url'] ?? "";


    return <<<HTML
       <meta property="meta_title" content="{$meta_title}">
       <meta property="og:image"content="{$meta_image}">
       <meta property="meta_description" content="{$meta_description}">
       <!--Facebook-->
       <meta property="og:url"content="{$site_url}" >
       <meta property="og:type"content="{$facebook_meta_title}" >
       <meta property="og:title"content="{$meta_title}" >
       <meta property="og:description"content="{$facebook_meta_description}" >
       <meta property="og:image"content="{$facebook_meta_image}">
       <!--Twitter-->
       <meta name="twitter:site" content="{$site_url}" >
       <meta name="twitter:title" content="{$twitter_meta_title}" >
       <meta name="twitter:description" content="$twitter_meta_description">
       <meta name="twitter:image" content="{$twitter_meta_image}">
HTML;

}

function script_currency_list()
{
    return \Xgenious\Paymentgateway\Base\GlobalCurrency::script_currency_list();
}

function site_currency_symbol($text = false)
{
    $all_currency = [
        'USD' => '$', 'EUR' => '€', 'INR' => '₹', 'IDR' => 'Rp', 'AUD' => 'A$', 'SGD' => 'S$', 'JPY' => '¥', 'GBP' => '£', 'MYR' => 'RM', 'PHP' => '₱', 'THB' => '฿', 'KRW' => '₩', 'NGN' => '₦', 'GHS' => 'GH₵', 'BRL' => 'R$',
        'BIF' => 'FBu', 'CAD' => 'C$', 'CDF' => 'FC', 'CVE' => 'Esc', 'GHP' => 'GH₵', 'GMD' => 'D', 'GNF' => 'FG', 'KES' => 'K', 'LRD' => 'L$', 'MWK' => 'MK', 'MZN' => 'MT', 'RWF' => 'R₣', 'SLL' => 'Le', 'STD' => 'Db', 'TZS' => 'TSh', 'UGX' => 'USh', 'XAF' => 'FCFA', 'XOF' => 'CFA', 'ZMK' => 'ZK', 'ZMW' => 'ZK', 'ZWD' => 'Z$',
        'AED' => 'د.إ', 'AFN' => '؋', 'ALL' => 'L', 'AMD' => '֏', 'ANG' => 'NAf', 'AOA' => 'Kz', 'ARS' => '$', 'AWG' => 'ƒ', 'AZN' => '₼', 'BAM' => 'KM', 'BBD' => 'Bds$', 'BDT' => '৳', 'BGN' => 'Лв', 'BMD' => '$', 'BND' => 'B$', 'BOB' => 'Bs', 'BSD' => 'B$', 'BWP' => 'P', 'BZD' => '$',
        'CHF' => 'CHf', 'CNY' => '¥', 'CLP' => '$', 'COP' => '$', 'CRC' => '₡', 'CZK' => 'Kč', 'DJF' => 'Fdj', 'DKK' => 'Kr', 'DOP' => 'RD$', 'DZD' => 'دج', 'EGP' => 'E£', 'ETB' => 'ብር', 'FJD' => 'FJ$', 'FKP' => '£', 'GEL' => 'ლ', 'GIP' => '£', 'GTQ' => 'Q',
        'GYD' => 'G$', 'HKD' => 'HK$', 'HNL' => 'L', 'HRK' => 'kn', 'HTG' => 'G', 'HUF' => 'Ft', 'ILS' => '₪', 'ISK' => 'kr', 'JMD' => '$', 'KGS' => 'Лв', 'KHR' => '៛', 'KMF' => 'CF', 'KYD' => '$', 'KZT' => '₸', 'LAK' => '₭', 'LBP' => 'ل.ل.', 'LKR' => 'ரூ', 'LSL' => 'L',
        'MAD' => 'MAD', 'MDL' => 'L', 'MGA' => 'Ar', 'MKD' => 'Ден', 'MMK' => 'K', 'MNT' => '₮', 'MOP' => 'MOP$', 'MRO' => 'MRU', 'MUR' => '₨', 'MVR' => 'Rf', 'MXN' => 'Mex$', 'NAD' => 'N$', 'NIO' => 'C$', 'NOK' => 'kr', 'NPR' => 'रू', 'NZD' => '$', 'PAB' => 'B/.', 'PEN' => 'S/', 'PGK' => 'K',
        'PKR' => '₨', 'PLN' => 'zł', 'PYG' => '₲', 'QAR' => 'QR', 'RON' => 'lei', 'RSD' => 'din', 'RUB' => '₽', 'SAR' => 'SR', 'SBD' => 'Si$', 'SCR' => 'SR', 'SEK' => 'kr', 'SHP' => '£', 'SOS' => 'Sh.so.', 'SRD' => '$', 'SZL' => 'E', 'TJS' => 'ЅM',
        'TRY' => '₺', 'TTD' => 'TT$', 'TWD' => 'NT$', 'UAH' => '₴', 'UYU' => '$U', 'UZS' => 'so\'m', 'VND' => '₫', 'VUV' => 'VT', 'WST' => 'WS$', 'XCD' => '$', 'XPF' => '₣', 'YER' => '﷼', 'ZAR' => 'R'
    ];

    $symbol = '$';
    $global_currency = get_static_option('site_global_currency');
    foreach ($all_currency as $currency => $sym) {
        if ($global_currency == $currency) {
            $symbol = $text ? $currency : $sym;
            break;
        }
    }
    return $symbol;
}

function decodeProductAttributes($endcoded_attributes): array
{
    $decoded_attributes = json_decode($endcoded_attributes, true);
    $result = [];
    if ($decoded_attributes) {
        foreach ($decoded_attributes as $key => $attributes) {
            $result[] = [
                'name' => count($attributes) ? $attributes[0]['type'] : '',
                'terms' => $attributes
            ];
        }
    }

    return $result;
}

function amount_with_currency_symbol($amount, $text = false)
{
    $amount = number_format((float)$amount, 0, '.', ',');
    $position = get_static_option('site_currency_symbol_position');
    $symbol = site_currency_symbol($text);
    $return_val = $symbol . $amount;
    if ($position == 'right') {
        $return_val = $amount . $symbol;
    }
    return $return_val;
}

function get_product_shipping_tax_data($billing_info)
{
    $data['shipping_cost'] = 0;
    $data['product_tax'] = 0;
    if ($billing_info) {
        if ($billing_info->state_id) {
            $tax = StateTax::where(['country_id' => $billing_info->country_id, 'state_id' => $billing_info->state_id])->select('id', 'tax_percentage')->first();
            if (empty($tax))
            {
                $tax = CountryTax::where('country_id', $billing_info->country_id)->select('id', 'tax_percentage')->first()->toArray();
            }
            $data['product_tax'] = !empty($tax) ? $tax['tax_percentage'] : 0;
        } else {
            $tax = CountryTax::where('country_id', $billing_info->country_id)->select('id', 'tax_percentage')->first()->toArray();
            $data['product_tax'] = !empty($tax) ? $tax['tax_percentage'] : 0;
        }
    }

    return $data;
}

function render_form_field_for_frontend($form_content)
{
    if (empty($form_content)) {
        return;
    }
    $output = '';
    $form_fields = json_decode($form_content);
    $select_index = 0;
    $options = [];
    foreach ($form_fields->field_type as $key => $value) {
        if (!empty($value)) {
            if ($value == 'select') {
                $options = explode("\n", $form_fields->select_options[$select_index]);
            }
            $required = isset($form_fields->field_required->$key) ? $form_fields->field_required->$key : '';
            $mimes = isset($form_fields->mimes_type->$key) ? $form_fields->mimes_type->$key : '';
            $output .= get_field_by_type($value, $form_fields->field_name[$key], $form_fields->field_placeholder[$key], $options, $required, $mimes);
            if ($value == 'select') {
                $select_index++;
            };
        }
    }
    return $output;
}

function render_payment_gateway_for_form($cash_on_delivery = false)
{
    $output = '<div class="payment-gateway-wrapper">';
//    if (empty(get_static_option('site_payment_gateway'))) {
//        return;
//    }

    $output .= '<input type="hidden" name="selected_payment_gateway" value="' . get_static_option('site_default_payment_gateway') . '">';
//    $all_gateway = [
//        'paypal', 'manual_payment', 'mollie', 'paytm', 'stripe', 'razorpay', 'flutterwave', 'paystack','midtrans','payfast','cashfree','instamojo','marcadopago'
//    ];
    $all_gateway = \App\Models\PaymentGateway::all();
    $output .= '<ul>';
    if ($cash_on_delivery) {
        $output .= '<li data-gateway="cash_on_delivery" ><div class="img-select">';
        $output .= render_image_markup_by_attachment_id(get_static_option('cash_on_delivery_preview_logo'));
        $output .= '</div></li>';
    }
    foreach ($all_gateway as $gateway) {
        // Payfast & Flutterwave is removed due to support issue
//        if (!empty(get_static_option($gateway . '_gateway'))) :
        $class = (get_static_option('site_default_payment_gateway') == $gateway->name) ? 'class="selected"' : '';

        $output .= '<li data-gateway="' . $gateway->name . '" ' . $class . '><div class="img-select">';
        $output .= render_image_markup_by_attachment_id($gateway->image);
        $output .= '</div></li>';
//        endif;
    }
    $output .= '</ul>';

    $output .= '</div>';
    return $output;

}

function get_user_name_by_id($id)
{
    $user = \App\Models\User::find($id);
    return $user;
}


function set_seo_data($request)
{

    $request_data = [
        'meta_title' => SEOMeta::setTitle($request->meta_title),
        'meta_description' => SEOMeta::setDescription($request->meta_description),
        'meta_image' => SEOTools::jsonLd()->addImage($request->meta_image),

        'meta_fb_title' => OpenGraph::setTitle($request->meta_fb_title),
        'meta_fb_description' => OpenGraph::setDescription($request->meta_fb_description),
        'fb_image' => OpenGraph::addImages($request->fb_image),

        'meta_tw_title' => TwitterCard::setTitle($request->meta_tw_title),
        'meta_tw_description' => TwitterCard::setDescription($request->meta_tw_description),
        'tw_image' => TwitterCard::setImage($request->tw_image),
    ];

    return $request_data;
}

function canonical_url()
{
    if (\Illuminate\Support\Str::startsWith($current = url()->current(), 'https://www')) {
        return str_replace('https://www.', 'https://', $current);
    }

    return str_replace('https://', 'https://www.', $current);
}

function get_time_difference($time_type, $to)
{
    $from = \Illuminate\Support\Carbon::now();
    $type = 'diffIn' . ucfirst($time_type);

    $difference = $from->$type($to);

    return $difference;
}

function load_google_fonts($theme_number = '')
{
    //google fonts link;
    $fonts_url = 'https://fonts.googleapis.com/css2?family=';
    //body fonts
    $body_font_family = get_static_option(tenant() ? 'body_font_family_'.$theme_number.'' : 'body_font_family') ?? 'Open Sans';
    $heading_font_family = get_static_option(tenant() ? 'heading_font_family_'.$theme_number.'' : 'heading_font_family') ?? 'Montserrat';

    $load_body_font_family = str_replace(' ', '+', $body_font_family);
    $body_font_variant = get_static_option(tenant() ? 'body_font_variant_'.$theme_number.'' : 'body_font_variant');
    $body_font_variant_selected_arr = !empty($body_font_variant) ? unserialize($body_font_variant, ['class' => false]) : ['400'];
    $load_body_font_variant = is_array($body_font_variant_selected_arr) ? implode(';', $body_font_variant_selected_arr) : '400';

    $body_italic = '';
    preg_match('/1,/', $load_body_font_variant, $match);
    if (count($match) > 0) {
        $body_italic = 'ital,';
    } else {
        $load_body_font_variant = str_replace('0,', '', $load_body_font_variant);
    }

    $fonts_url .= $load_body_font_family . ':' . $body_italic . 'wght@' . $load_body_font_variant;
    $load_heading_font_family = str_replace(' ', '+', $heading_font_family);
    if (tenant()){
        $heading_font_variant = get_static_option('heading_font_variant_'.$theme_number.'');
    }else{
        $heading_font_variant = get_static_option('heading_font_variant');
    }

    $heading_font_variant_selected_arr = !empty($heading_font_variant) ? unserialize($heading_font_variant, ['class' => false]) : ['400'];
    $load_heading_font_variant = is_array($heading_font_variant_selected_arr) ? implode(';', $heading_font_variant_selected_arr) : '400';

    if (!empty(get_static_option('heading_font')) && $heading_font_family != $body_font_family) {

        $heading_italic = '';
        preg_match('/1,/', $load_heading_font_variant, $match);
        if (count($match) > 0) {
            $heading_italic = 'ital,';
        } else {
            $load_heading_font_variant = str_replace('0,', '', $load_heading_font_variant);
        }

        $fonts_url .= '&family=' . $load_heading_font_family . ':' . $heading_italic . 'wght@' . $load_heading_font_variant;
    }

    return sprintf('<link rel="preconnect" href="https://fonts.gstatic.com"> <link href="%1$s&display=swap" rel="stylesheet">', $fonts_url);
}

function wrap_random_number($number)
{
    return random_int(111111, 999999) . $number . random_int(111111, 999999);
}

function purify_html($html)
{
    return strip_tags(\Mews\Purifier\Facades\Purifier::clean($html));
}

function tenant_url_with_protocol($url)
{
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $protocol = "https://";
    } else {
        $protocol = "http://";
    }

    return $protocol . $url;
}

function float_amount_with_currency_symbol($amount, $text = false): string
{
    $symbol = site_currency_symbol($text);
    $position = get_static_option('site_currency_symbol_position');

    if (empty($amount)) {
        $return_val = $symbol . $amount;
        if ($position == 'right') {
            $return_val = $amount . $symbol;
        }
    }

    $amount = number_format((float)$amount, 2, '.', '');

    $return_val = $symbol . $amount;

    if ($position == 'right') {
        $return_val = $amount . $symbol;
    }

    return $return_val;
}


function moduleExists($name): bool
{
    $module_status = json_decode(file_get_contents(__DIR__.'/../../modules_statuses.json'));
    return property_exists($module_status,$name) ? $module_status->$name : false;
}

function tenant_module_migrations_file_path($moduleName){
    return str_replace('database','',database_path()).'Modules/'.$moduleName.'/Database/Migrations';
}

