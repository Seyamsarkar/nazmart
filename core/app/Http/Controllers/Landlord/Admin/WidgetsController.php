<?php

namespace App\Http\Controllers\Landlord\Admin;
use App\Http\Controllers\Controller;
use App\Models\Widgets;
use Plugins\WidgetBuilder\WidgetBuilderSetup;
use Illuminate\Http\Request;

class WidgetsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:form-builder');
    }

    public function index(){
        return view('landlord.admin.widgets.widget-index');
    }

    public function widget_markup(Request $request){
        $output = WidgetBuilderSetup::render_widgets_by_name_for_admin([
            'name' => $request->widget_name,
            'namespace' => $request->widget_namespace,
            'type' => 'new',
            'after' => false,
            'before' => false,
        ]);

        return $output;
    }

    public function new_widget(Request $request){
        $this->validate($request,[
           'widget_name' => 'required',
           'widget_order' => 'required',
           'widget_location' => 'required',
           'namespace' => 'required'
        ]);

        unset($request['_token']);
        $widget_content = (array) $request->all();

       $widget_id = Widgets::create([
            'widget_name' => $request->widget_name,
            'widget_order' => $request->widget_order,
            'widget_location' => $request->widget_location,
            'widget_content' => serialize($widget_content),
            'widget_namespace' => $request->namespace,
        ])->id;

        $data['id'] = $widget_id;
        $data['status'] = 'ok';
        return response()->json($data);
    }
    public function update_widget(Request $request){
        $this->validate($request,[
            'widget_name' => 'required',
            'widget_order' => 'required',
            'widget_location' => 'required',
            'namespace' => 'required'
        ]);

        unset($request['_token']);
        $widget_content = (array) $request->all();

        Widgets::findOrFail($request->id)->update([
            'widget_name' => $request->widget_name,
            'widget_order' => $request->widget_order,
            'widget_location' => $request->widget_location,
            'widget_content' => serialize($widget_content),
            'widget_namespace' => $request->namespace,
        ]);

        return response()->json('ok');
    }

    public function delete_widget(Request $request){
        Widgets::findOrFail($request->id)->delete();
        return response()->json('ok');
    }

    public function update_order_widget(Request $request){
        Widgets::findOrFail($request->id)->update(['widget_order' => $request->widget_order]);
        return response()->json('ok');
    }
}
