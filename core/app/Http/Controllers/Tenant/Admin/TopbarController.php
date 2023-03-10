<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Helpers\SanitizeInput;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\TopbarInfo;
use Illuminate\Http\Request;

class TopbarController extends Controller
{
    public function index(){
        $all_social_icons = TopbarInfo::all();
        $all_language = Language::all();
        return view('tenant.admin.pages.topbar-settings')->with([
            'all_social_icons' => $all_social_icons,
            'all_languages' => $all_language,
        ]);
    }
    public function new_social_item(Request $request){
        $data = $this->validate($request,[
            'icon' => 'required|string',
            'url' => 'required|string',
        ]);

        $data['url'] = SanitizeInput::esc_html($data['url']);

        TopbarInfo::create($data);

        return redirect()->back()->with([
            'msg' => __('New Social Item Added...'),
            'type' => 'success'
        ]);
    }
    public function update_social_item(Request $request){
        $data = $this->validate($request,[
            'icon' => 'required|string',
            'url' => 'required|string',
        ]);

        $data['url'] = SanitizeInput::esc_html($data['url']);

        TopbarInfo::find($request->id)->update($data);
        return redirect()->back()->with([
            'msg' => __('Social Item Updated...'),
            'type' => 'success'
        ]);
    }
    public function delete_social_item(Request $request,$id){
        TopbarInfo::find($id)->delete();
        return redirect()->back()->with([
            'msg' => __('Social Item Deleted...'),
            'type' => 'danger'
        ]);
    }
}