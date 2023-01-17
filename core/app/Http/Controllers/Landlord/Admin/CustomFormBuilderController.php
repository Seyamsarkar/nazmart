<?php

namespace App\Http\Controllers\Landlord\Admin;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use App\Http\Controllers\Controller;
use App\Models\FormBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomFormBuilderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:form-builder');
    }

    private const BASE_PATH = 'landlord.admin.form-builder.custom.';

    public function all(){
        $all_forms = FormBuilder::all();
        return view(self::BASE_PATH.'all',compact('all_forms'));
    }
    public function bulk_action(Request $request){
        FormBuilder::whereIn('id',$request->ids)->delete();
        return response()->json('ok');
    }
    public function edit($id){
       $form =  FormBuilder::findOrFail($id);
        return view(self::BASE_PATH.'edit',compact('form'));
    }
    public function update(Request $request){
        $this->validate($request,[
            'title' => 'required|string',
            'email' => 'required|string',
            'button_title' => 'required|string',
            'field_name' => 'required|max:191',
            'field_placeholder' => 'required|max:191',
            'success_message' => 'required',
        ]);
        $id = $request->id;
        $title = SanitizeInput::esc_html($request->title);
        $email = $request->email;
        $button_title = SanitizeInput::esc_html($request->button_title);
        unset($request['_token'],$request['email'],$request['button_title'],$request['title'],$request['id']);
        $all_fields_name = [];
        $all_request_except_token = $request->all();
        foreach ($request->field_name as $fname){
            $all_fields_name[] = strtolower(Str::slug($fname));
        }
        $all_request_except_token['field_name'] = $all_fields_name;
        $json_encoded_data = json_encode($all_request_except_token);

        FormBuilder::findOrfail($id)->update([
            'title' => $title,
            'email' => $email,
            'button_text' => $button_title,
            'success_message' => SanitizeInput::esc_html($request->success_message),
            'fields' => $json_encoded_data
        ]);

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function store(Request $request){
        $this->validate($request,[
           'title' => 'required|string',
           'email' => 'required|string',
           'button_title' => 'required|string',
           'success_message' => 'required|string',
        ]);
        FormBuilder::create([
            'title' => SanitizeInput::esc_html($request->title),
            'email' => $request->email,
            'button_text' => SanitizeInput::esc_html($request->button_title),
            'success_message' => SanitizeInput::esc_html($request->success_message),
        ]);
        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function delete($id){
        FormBuilder::findOrFail($id)->delete();
        return response()->danger(ResponseMessage::delete());
    }
}