<?php


namespace Plugins\PageBuilder;

use App\Models\PageBuilder;
use Plugins\PageBuilder\Addons\Landlord\Blog\BlogSliderOne;
use Plugins\PageBuilder\Addons\Landlord\Blog\BlogStyleOne;
use Plugins\PageBuilder\Addons\Landlord\Common\Brand;
use Plugins\PageBuilder\Addons\Landlord\Common\ContactArea;
use Plugins\PageBuilder\Addons\Landlord\Common\ContactCards;
use Plugins\PageBuilder\Addons\Landlord\Common\FaqOne;
use Plugins\PageBuilder\Addons\Landlord\Common\Feedback;
use Plugins\PageBuilder\Addons\Landlord\Common\HowItWorks;
use Plugins\PageBuilder\Addons\Landlord\Common\Newsletter;
use Plugins\PageBuilder\Addons\Landlord\Common\NumberCounter;
use Plugins\PageBuilder\Addons\Landlord\Common\PricePlan;
use Plugins\PageBuilder\Addons\Landlord\Common\TemplateDesign;
use Plugins\PageBuilder\Addons\Landlord\Common\Themes;
use Plugins\PageBuilder\Addons\Landlord\Common\WhyChooseUs;
use Plugins\PageBuilder\Addons\Landlord\Header\AboutHeaderStyleOne;
use Plugins\PageBuilder\Addons\Landlord\Header\FeaturesStyleOne;
use Plugins\PageBuilder\Addons\Landlord\Header\HeaderStyleOne;
use Plugins\PageBuilder\Addons\Tenants\ThemeOne\Contact\ContactAreaOne;
use Plugins\PageBuilder\Addons\Tenants\ThemeOne\Contact\GoogleMap;
use Plugins\PageBuilder\Addons\Tenants\Service\ServiceOne;
use Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\CollectionArea;
use Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\DealArea;
use Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\Services;
use Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\Testimonial;
use Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\TestimonialTwo;
use Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\Team;
use Plugins\PageBuilder\Addons\Tenants\ThemeOne\Header\HeaderOne;
use Plugins\PageBuilder\Addons\Tenants\ThemeOne\Product\FeaturedProductSlider;
use Plugins\PageBuilder\Addons\Tenants\ThemeOne\Product\FlashStore;
use Plugins\PageBuilder\Addons\Tenants\ThemeOne\Product\ProductTypeList;
use Plugins\PageBuilder\Addons\Tenants\ThemeOne\About\AboutStory;
use Plugins\PageBuilder\Addons\Tenants\ThemeOne\About\AboutCounter;
use Plugins\PageBuilder\Addons\Tenants\ThemeTwo\Blog\BlogOne;
use Plugins\PageBuilder\Addons\Tenants\ThemeTwo\Common\CategoriesSlider;
use Plugins\PageBuilder\Addons\Tenants\ThemeTwo\Common\CollectionCard;
use Plugins\PageBuilder\Addons\Tenants\ThemeTwo\Product\TrendingProducts;


class PageBuilderSetup
{

    private static function registerd_widgets(): array
    {
        if (!is_null(tenant()))
        {
            $theme = tenant()->theme_slug;

            //Tenant Register
            if ($theme == 'theme-1')
            {
                // Theme One
                $addons = [
                    HeaderOne::class,
                    Addons\Tenants\ThemeOne\Blog\BlogOne::class,
                    Addons\Tenants\ThemeOne\Common\Brand::class,
                    DealArea::class,
                    ContactAreaOne::class,
                    GoogleMap::class,
                    ServiceOne::class,
                    CollectionArea::class,
                    FeaturedProductSlider::class,
                    ProductTypeList::class,
                    FlashStore::class,
                    Services::class,
                    Testimonial::class,
                    TestimonialTwo::class,
                    AboutStory::class,
                    AboutCounter::class,
                    Team::class,
                ];
            } elseif ($theme == 'theme-2') {
                // Theme Two
                $addons = [
                    \Plugins\PageBuilder\Addons\Tenants\ThemeTwo\Header\HeaderOne::class,
                    CollectionCard::class,
                    TrendingProducts::class,
                    CategoriesSlider::class,
                    \Plugins\PageBuilder\Addons\Tenants\ThemeTwo\Common\Brand::class,
                    \Plugins\PageBuilder\Addons\Tenants\ThemeTwo\Common\Testimonial::class,
                    \Plugins\PageBuilder\Addons\Tenants\ThemeTwo\Common\Services::class,
                    \Plugins\PageBuilder\Addons\Tenants\ThemeTwo\Common\CollectionArea::class,
                    \Plugins\PageBuilder\Addons\Tenants\ThemeTwo\Product\ProductTypeList::class,
                    \Plugins\PageBuilder\Addons\Tenants\ThemeTwo\Contact\ContactAreaOne::class,
                    \Plugins\PageBuilder\Addons\Tenants\ThemeTwo\Contact\GoogleMap::class,
                    BlogOne::class,
                    \Plugins\PageBuilder\Addons\Tenants\ThemeTwo\About\AboutCounter::class,
                    \Plugins\PageBuilder\Addons\Tenants\ThemeTwo\About\AboutStory::class,
                ];
            } elseif ($theme == 'theme-3') {

            }
        } else {
            //Admin Register
            $addons = [
                HeaderStyleOne::class,
                FeaturesStyleOne::class,
                Themes::class,
                HowItWorks::class,
                WhyChooseUs::class,
                TemplateDesign::class,
                PricePlan::class,
                Feedback::class,
                FaqOne::class,
                ContactArea::class,
                BlogSliderOne::class,
                NumberCounter::class,
                Newsletter::class,
                AboutHeaderStyleOne::class,
                ContactCards::class,
                BlogStyleOne::class,
            ];
        }


        //check module wise widget by set condition
        return $addons;
    }

    public static function get_tenant_admin_panel_widgets(): string
    {
        $widgets_markup = '';
        $widget_list = self::tenant_registerd_widgets();
        foreach ($widget_list as $widget) {
            try {
                $widget_instance = new  $widget();
            } catch (\Exception $e) {
                $msg = $e->getMessage();
                throw new \ErrorException($msg);
            }

            if ($widget_instance->enable()) {
                $widgets_markup .= self::render_admin_addon_item([
                    'addon_name' => $widget_instance->addon_name(),
                    'addon_namespace' => $widget_instance->addon_namespace(), // new added
                    'addon_title' => $widget_instance->addon_title(),
                    'preview_image' => $widget_instance->get_preview_image($widget_instance->preview_image())
                ]);
            }
        }
        return $widgets_markup;
    }

    public static function get_admin_panel_widgets(): string
    {
        $widgets_markup = '';
        $widget_list = self::registerd_widgets();
        foreach ($widget_list as $widget) {
            try {
                $widget_instance = new  $widget();
            } catch (\Exception $e) {
                $msg = $e->getMessage();
                throw new \ErrorException($msg);
            }

            if ($widget_instance->enable()) {
                $widgets_markup .= self::render_admin_addon_item([
                    'addon_name' => $widget_instance->addon_name(),
                    'addon_namespace' => $widget_instance->addon_namespace(), // new added
                    'addon_title' => $widget_instance->addon_title(),
                    'preview_image' => $widget_instance->get_preview_image($widget_instance->preview_image())
                ]);
            }
        }
        return $widgets_markup;
    }

    private static function render_admin_addon_item($args): string
    {
        return '<li class="ui-state-default widget-handler" data-name="' . $args['addon_name'] . '" data-namespace="' . base64_encode($args['addon_namespace']) . '" >
                    <h4 class="top-part"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>' . $args['addon_title'] . $args['preview_image'] . '</h4>
                </li>';
    }

    public static function render_widgets_by_name_for_admin($args)
    {
        $widget_class = $args['namespace'];
        $instance = new $widget_class($args);
        if ($instance->enable()) {
            //            return $instance->admin_render_raw();
            return $instance->admin_render();
        }
    }

    public static function render_widgets_by_name_for_frontend($args)
    {
        $widget_class = $args['namespace'];
        $instance = new $widget_class($args);
        if ($instance->enable()) {
            return $instance->frontend_render();
        }
    }

    public static function render_frontend_pagebuilder_content_by_location($location): string
    {
        $output = '';
        $all_widgets = PageBuilder::where(['addon_location' => $location])->orderBy('addon_order', 'ASC')->get();
        foreach ($all_widgets as $widget) {
            $output .= self::render_widgets_by_name_for_frontend([
                'name' => $widget->addon_name,
                'namespace' => $widget->addon_namespace,
                'location' => $location,
                'id' => $widget->id,
                'column' => $args['column'] ?? false
            ]);
        }
        return $output;
    }

    public static function get_saved_addons_by_location($location): string
    {
        $output = '';
        $all_widgets = PageBuilder::where(['addon_location' => $location])->orderBy('addon_order', 'asc')->get();
        foreach ($all_widgets as $widget) {
            $output .= self::render_widgets_by_name_for_admin([
                'name' => $widget->addon_name,
                'namespace' => $widget->addon_namespace,
                'id' => $widget->id,
                'type' => 'update',
                'order' => $widget->addon_order,
                'page_type' => $widget->addon_page_type,
                'page_id' => $widget->addon_page_id,
                'location' => $widget->addon_location
            ]);
        }

        return $output;
    }

    public static function get_saved_addons_for_dynamic_page($page_type, $page_id): string
    {
        $output = '';
        $all_widgets = \Cache::remember('page_id-'.$page_id, 60*60*24, function () use ($page_type, $page_id) {
            return PageBuilder::where(['addon_page_type' => $page_type, 'addon_page_id' => $page_id])->orderBy('addon_order', 'asc')->get();
        });

        foreach ($all_widgets as $widget) {
            $output .= self::render_widgets_by_name_for_admin([
                'name' => $widget->addon_name,
                'namespace' => $widget->addon_namespace,
                'id' => $widget->id,
                'type' => 'update',
                'order' => $widget->addon_order,
                'page_type' => $widget->addon_page_type,
                'page_id' => $widget->addon_page_id,
                'location' => $widget->addon_location
            ]);
        }

        return $output;
    }

    public static function render_frontend_pagebuilder_content_for_dynamic_page($page_type, $page_id): string
    {
        $output = '';
        $all_widgets = \Cache::remember('page_id-'.$page_id, 60*60, function () use ($page_type, $page_id) {
            return PageBuilder::where(['addon_page_type' => $page_type, 'addon_page_id' => $page_id])->orderBy('addon_order', 'asc')->get();
        });

        foreach ($all_widgets as $widget) {
            $output .= self::render_widgets_by_name_for_frontend([
                'name' => $widget->addon_name,
                'namespace' => $widget->addon_namespace,
                'id' => $widget->id,
                'column' => $args['column'] ?? false
            ]);
        }
        return $output;
    }
}
