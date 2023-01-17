<?php

namespace App\Http\Controllers\Tenant\Admin;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    public function index()
    {
        $all_menu = Menu::all();
        return view('tenant.admin.menu.menu-index')->with([
            'all_menu' => $all_menu
        ]);
    }

    public function store_new_menu(Request $request)
    {
        $this->validate($request, [
            'content' => 'nullable',
            'title' => 'required',
        ]);

        Menu::create([
            'content' => $request->page_content,
            'title' => SanitizeInput::esc_html($request->title),
        ]);

        return response()->success(__('Menu Created Successfully..'));

    }
    public function edit_menu($id)
    {
        $page_post = Menu::find($id);

        return view('tenant.admin.menu.menu-edit')->with([
            'page_post' => $page_post
        ]);

    }
    public function update_menu(Request $request, $id)
    {

        $this->validate($request, [
            'content' => 'nullable',
            'title' => 'required',
        ]);
        Menu::where('id', $id)->update([
            'content' => $request->menu_content,
            'title' => SanitizeInput::esc_html($request->title),
        ]);

        return redirect()->back()->with([
            'msg' => __('Menu updated...'),
            'type' => 'success'
        ]);
    }

    public function delete_menu(Request $request, $id)
    {
        Menu::find($id)->delete();
        return redirect()->back()->with([
            'msg' => __('Menu Delete Success...'),
            'type' => 'danger'
        ]);
    }

    public function set_default_menu(Request $request, $id)
    {
        $lang = Menu::find($id);
        Menu::where(['status' => 'default'])->update(['status' => '']);

        Menu::find($id)->update(['status' => 'default']);
        $lang->status = 'default';
        $lang->save();
        return redirect()->back()->with([
            'msg' => __('Default Menu Set To') .' '. SanitizeInput::esc_html($lang->title),
            'type' => 'success'
        ]);
    }
}