<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Helpers\SanitizeInput;
use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class MaintainsPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:page-settings-maintain-page-manage');
    }

    public function maintains_page_settings()
    {
        return view('landlord.admin.maintain-page.maintain-page-index');
    }

    public function update_maintains_page_settings(Request $request)
    {
        $this->validate($request, [
            'maintenance_logo' => 'nullable|string|max:191',
            'maintenance_bg_image' => 'nullable|string|max:191',
        ]);

        $this->validate($request, [
            'maintains_page_title' => 'nullable|string',
            'maintains_page_description' => 'nullable|string'
        ]);
        $title = 'maintains_page_title';
        $description = 'maintains_page_description';
        $date = 'mentenance_back_date';

        update_static_option($title, SanitizeInput::esc_html($request->$title));
        update_static_option($description, SanitizeInput::esc_html($request->$description));
        update_static_option($date, $request->$date);


        if (!empty($request->maintenance_logo)) {
            update_static_option('maintenance_logo', $request->maintenance_logo);
        }

        if (!empty($request->maintenance_bg_image)) {
            update_static_option('maintenance_bg_image', $request->maintenance_bg_image);
        }

        return redirect()->back()->with(['msg' => __('Settings Updated....'), 'type' => 'success']);
    }
}