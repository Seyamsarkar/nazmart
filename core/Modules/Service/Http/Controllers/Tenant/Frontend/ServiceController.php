<?php

namespace Modules\Service\Http\Controllers\Tenant\Frontend;

use App\Helpers\LanguageHelper;
use App\Traits\SeoDataConfig;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Modules\Service\Entities\Service;
use Modules\Service\Entities\ServiceCategory;


class ServiceController extends Controller
{
    use SEOToolsTrait,SeoDataConfig;
    private const BASE_PATH = 'service::tenant.frontend.service.';

    public function service_single($slug)
    {
        $service = Service::where(['slug'=> $slug, 'status' => 1])->first();
        $this->setMetaDataInfo($service);

        if(empty($service)){
            abort(404);
        }
        return view(self::BASE_PATH.'service-single',compact('service'));
    }

    public function category_wise_service_page($id)
    {
        if(empty($id)){
            abort(404);
        }
        $all_services = Service::usingLocale(LanguageHelper::default_slug())->where(['category_id' => $id,'status' => 1])->orderBy('id', 'desc')->paginate(4);
        $category = ServiceCategory::where(['id' => $id, 'status' => 1])->first();
        $category_name = $category->getTranslation('title',get_user_lang());

        return view(self::BASE_PATH.'service-category')->with([
            'all_services' => $all_services,
            'category_name' => $category_name,
        ]);
    }


}
