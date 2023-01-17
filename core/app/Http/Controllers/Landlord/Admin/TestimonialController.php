<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:testimonial-list|testimonial-create|testimonial-edit|testimonial-delete',['only' => ['index']]);
        $this->middleware('permission:testimonial-create',['only' => ['store']]);
        $this->middleware('permission:testimonial-edit',['only' => ['update','clone']]);
        $this->middleware('permission:testimonial-delete',['only' => ['delete','bulk_action']]);
    }
    public function index(Request $request){

        $all_testimonials = Testimonial::all();
        return view('landlord.admin.testimonial.index')->with([
            'all_testimonials' => $all_testimonials,
            'default_lang'=> $request->lang ?? GlobalLanguage::default_slug()
        ]);
    }
    public function store(Request $request){

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'description' => 'required',
            'designation' => 'string|max:191',
            'company' => 'string|nullable|max:191',
            'image' => 'nullable|string|max:191',
        ]);

        $testimonial = new Testimonial();
        $testimonial->name =  SanitizeInput::esc_html($request->name);
        $testimonial->description = SanitizeInput::esc_html($request->description);
        $testimonial->designation = SanitizeInput::esc_html($request->designation);
        $testimonial->company = SanitizeInput::esc_html($request->company);
        $testimonial->image = $request->image;
        $testimonial->rating = 5;
        $testimonial->status = $request->status;
        $testimonial->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function update(Request $request){
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'description' => 'required',
            'designation' => 'string|max:191',
            'company' => 'string|max:191',
            'image' => 'nullable|string|max:191',
        ]);


        $testimonial = Testimonial::find($request->id);
        $testimonial->name = SanitizeInput::esc_html($request->name);
        $testimonial->description = SanitizeInput::esc_html($request->description);
        $testimonial->designation = SanitizeInput::esc_html($request->designation);
        $testimonial->company = SanitizeInput::esc_html($request->company);
        $testimonial->image = $request->image;
        $testimonial->rating = 5;
        $testimonial->status = $request->status;
        $testimonial->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function clone(Request $request){
        $testimonial = Testimonial::find($request->item_id);
        Testimonial::create([
            'name' => $testimonial->name,
            'description' => $testimonial->description,
            'status' => 0,
            'designation' => $testimonial->designation,
            'company' => $testimonial->company,
            'image' => $testimonial->image
        ]);
        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function delete(Request $request,$id){
        Testimonial::find($id)->delete();
        return response()->danger(ResponseMessage::delete('Testimonial Deleted'));
    }

    public function bulk_action(Request $request){
        $all = Testimonial::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }
}