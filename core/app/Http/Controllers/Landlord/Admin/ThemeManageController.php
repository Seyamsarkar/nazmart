<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use App\Http\Controllers\Controller;
use App\Models\Themes;
use Illuminate\Http\Request;

class ThemeManageController extends Controller
{
    public function __construct()
    {

    }

    public function all_theme()
    {
        $all_themes = Themes::orderBy('id', 'asc')->get();
        return view('landlord.admin.themes.index', compact('all_themes'));
    }

    public function update_status(Request $request)
    {
        $theme_status = Themes::findOrFail($request->id);
        $theme_status->status = $theme_status->status ? 0 : 1;
        $theme_status->save();

        $status = $theme_status->status == 1 ? 'inactive' : 'active';
        return response()->json([
            'status' => $theme_status->status,
            'msg' => 'The theme is '.$status.' successfully'
        ]);
    }

    public function update_theme(Request $request)
    {
        $request->validate([
           'theme_id' => 'required',
           'theme_name' => 'required',
           'theme_description' => 'nullable',
           'theme_url' => 'nullable'
        ]);

        $updated = Themes::findOrFail($request->theme_id)->update([
            'title' => SanitizeInput::esc_html($request->theme_name),
            'description' => SanitizeInput::esc_html($request->theme_description),
            'theme_url' => SanitizeInput::esc_url($request->theme_url),
        ]);

        return $updated ? response()->success(ResponseMessage::SettingsSaved()) : response()->success(ResponseMessage::delete());
    }

    public function theme_settings()
    {
        return view('landlord.admin.themes.settings');
    }

    public function theme_settings_update(Request $request)
    {
            $this->validate($request, [
                'up_coming_themes_backend' => 'nullable|string',
                'up_coming_themes_frontend' => 'nullable|string',
            ]);

            update_static_option('up_coming_themes_backend', $request->up_coming_themes_backend);
            update_static_option('up_coming_themes_frontend', $request->up_coming_themes_frontend);

        return redirect()->back()->with([
            'msg' => __('Theme Settings Updated ...'),
            'type' => 'success'
        ]);
    }
}