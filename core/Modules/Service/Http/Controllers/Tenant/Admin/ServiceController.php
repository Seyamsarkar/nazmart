<?php

namespace Modules\Service\Http\Controllers\Tenant\Admin;

use App\Helpers\LanguageHelper;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Blog\Entities\Blog;
use Modules\Service\Entities\Service;
use Modules\Service\Entities\ServiceCategory;
use function view;

class ServiceController extends Controller
{

    public function index(Request $request){

        $all_services = Service::all();
        return view('service::tenant.admin.services.service-index')->with([
            'all_services' => $all_services,
            'default_lang'=> $request->lang ?? LanguageHelper::default_slug()
        ]);
    }

    public function add(Request $request){

        $categories = ServiceCategory::all();
        return view('service::tenant.admin.services.service-add')->with([
            'categories' => $categories,
            'default_lang'=> $request->lang ?? LanguageHelper::default_slug()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'slug' => 'nullable|string|max:191',
            'description' => 'required',
            'category_id' => 'required',
            'image' => 'nullable|string|max:191',
            'meta_tag' => 'nullable|string|max:191',
            'meta_description' => 'nullable|string|max:191',
        ]);

        if(tenant()) {
            $current_package = tenant()->user()->first()->payment_log()->firstOrFail()->package ?? [];
            $pages_count = Service::count();
            $permission_page = $current_package->service_permission_feature;

            if(!empty($permission_page) && $pages_count >= $permission_page){
                return response()->danger(ResponseMessage::delete(sprintf('You can not create service avobe %d in this package',$permission_page)));
            }
        }

        $service = new Service();
        $service->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title))
                ->setTranslation('description',$request->lang, SanitizeInput::esc_html($request->description));
        $service->slug = empty($request->slug) ? Str::slug($request->title) : Str::slug($request->slug);
        $service->category_id = $request->category_id;
        $service->image = $request->image;
        $service->meta_tag = $request->meta_tag;
        $service->meta_description = $request->meta_description;
        $service->status = $request->status;
        $service->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function edit_service(Request $request,$id){

        if(!empty($id)){
            $service = Service::find($id);
        }
        $categories = ServiceCategory::all();
        return view('service::tenant.admin.services.service-edit')->with([
            'service' => $service,
            'categories' => $categories,
            'default_lang'=> $request->lang ?? LanguageHelper::default_slug()
        ]);
    }


    public function update_service(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'slug' => 'nullable|string|max:191',
            'description' => 'required',
            'category_id' => 'required',
            'image' => 'nullable|string|max:191',
            'meta_tag' => 'nullable|string|max:191',
            'meta_description' => 'nullable|string|max:191',
        ]);

        $service = Service::find($request->id);
        $service->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title))
                ->setTranslation('description',$request->lang, $request->description);
        $service->slug = empty($request->slug) ? Str::slug($request->title) : Str::slug($request->slug);
        $service->category_id = $request->category_id;
        $service->image = $request->image;
        $service->meta_tag = $request->meta_tag;
        $service->meta_description = $request->meta_description;
        $service->status = $request->status;
        $service->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }


    public function delete(Request $request,$id){
        Service::find($id)->delete();
        return response()->danger(ResponseMessage::delete('Service Deleted'));
    }

    public function bulk_action_service(Request $request){
        $all = Service::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }
}
