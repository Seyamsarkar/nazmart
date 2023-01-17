<?php

namespace Database\Seeders\Tenant\Addons;

use App\Helpers\ImageDataSeedingHelper;
use App\Models\PageBuilder;
use Illuminate\Database\Seeder;

class HomePageAddon extends Seeder
{
    public function run()
    {
        //Header Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'HeaderOne',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Header\HeaderOne',
            'addon_page_id' => 1,
            'addon_order' => 1,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->header_content()),
        ]);

        //Key Feature Addon
         PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'KeyFeatureStyleOne',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\KeyFeatureStyleOne',
            'addon_page_id' => 1,
            'addon_order' => 2,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->key_feature_content()),
        ]);

        //Price Plan Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'PricePlanOne',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\PricePlanOne',
            'addon_page_id' => 1,
            'addon_order' => 3,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->price_plan_content()),
        ]);

        //Apps of the month  Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'AppsOfTheMonthOne',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\AppsOfTheMonthOne',
            'addon_page_id' => 1,
            'addon_order' => 4,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->app_of_the_month_content()),
        ]);

        //Support One Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'SupportAreaOne',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\SupportAreaOne',
            'addon_page_id' => 1,
            'addon_order' => 5,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->support_one_content()),
        ]);

        //Support Two Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'SupportAreaOne',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\SupportAreaOne',
            'addon_page_id' => 1,
            'addon_order' => 6,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->support_two_content()),
        ]);

        //Testimonial  Addon
         PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'TestimonialOne',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\TestimonialOne',
            'addon_page_id' => 1,
            'addon_order' => 7,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->testimonial_content()),
        ]);

        //Blog  Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'BlogSliderOne',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Blog\BlogOne',
            'addon_page_id' => 1,
            'addon_order' => 8,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->blog_content()),
        ]);

    }


    public function header_content()
    {
        $inner_bg_image_id = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/header-slider/utility/bg.png');
        $right_bg_image_id = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/header-slider/utility/01.png');

        $app_store_image_id = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/download/01.png');
        $play_store_image_id = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/download/02.png');

        $widget_content = [

            "id"=>"1",
            "addon_name"=>"HeaderOne",
            "addon_namespace"=>"UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xIZWFkZXJcSGVhZGVyT25l",
            "addon_type"=>"update",
            "addon_location"=>"dynamic_page",
            "addon_order"=>"1",
            "addon_page_id"=>"1",
            "addon_page_type"=>"dynamic_page",
            "title_en_US"=>"Smart Apps for Next-Gen Utility Operations",
            "subtitle_en_US"=>"Capture and retrieve your lists across devices to help you stay organized at work, home,and on the go.",
            "title_ar"=>"تطبيقات ذكية لعمليات المرافق من الجيل التالي",
            "subtitle_ar"=>"التقط واسترد قوائمك عبر الأجهزة لمساعدتك على البقاء منظمًا في العمل والمنزل وأثناء التنق.",
            "apple_store_image"=> $app_store_image_id,
            "apple_store_url"=>"#",
            "play_store_image"=> $play_store_image_id,
            "play_store_url"=>"#",
            "inner_bg_one"=> $inner_bg_image_id,
            "right_bg_image"=> $right_bg_image_id,
            "padding_top"=>"0",
            "padding_bottom"=>"0",
        ];

        return $widget_content;

    }

    private function key_feature_content()
    {

        $widget_content =

            array (
                'id' => '2',
                'addon_name' => 'KeyFeatureStyleOne',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cS2V5RmVhdHVyZVN0eWxlT25l',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_page_id' => '1',
                'addon_order' => '2',
                'addon_page_type' => 'dynamic_page',
                'key_feature_one' =>
                    array (
                        'repeater_title_en_US' =>
                            array (
                                0 => 'Eyes on the Ground',
                                1 => 'Eyes on the Ground',
                                2 => 'Eyes on the Ground',
                                3 => 'Eyes on the Ground',
                                4 => 'Eyes on the Ground',
                                5 => 'Eyes on the Ground',
                            ),
                        'repeater_title_url_en_US' =>
                            array (
                                0 => '#',
                                1 => '#',
                                2 => '#',
                                3 => '#',
                                4 => '#',
                                5 => '#',
                            ),
                        'repeater_description_en_US' =>
                            array (
                                0 => 'Dozens of leading utility providers like National Grid are gaining enhanced situational awareness',
                                1 => 'Dozens of leading utility providers like National Grid are gaining enhanced situational awareness',
                                2 => 'Dozens of leading utility providers like National Grid are gaining enhanced situational awareness',
                                3 => 'Dozens of leading utility providers like National Grid are gaining enhanced situational awareness',
                                4 => 'Dozens of leading utility providers like National Grid are gaining enhanced situational awareness',
                                5 => 'Dozens of leading utility providers like National Grid are gaining enhanced situational awareness',
                            ),
                        'repeater_icon_en_US' =>
                            array (
                                0 => 'las la-eye',
                                1 => 'las la-clock',
                                2 => 'las la-exchange-alt',
                                3 => 'las la-list-alt',
                                4 => 'las la-desktop',
                                5 => 'las la-microscope',
                            ),
                        'repeater_title_ar' =>
                            array (
                                0 => 'عيون على الأرض',
                                1 => 'عيون على الأرض',
                                2 => 'عيون على الأرض',
                                3 => 'عيون على الأرض',
                                4 => 'عيون على الأرض',
                                5 => 'عيون على الأرض',
                            ),
                        'repeater_title_url_ar' =>
                            array (
                                0 => '#',
                                1 => '#',
                                2 => '#',
                                3 => '#',
                                4 => '#',
                                5 => '#',
                            ),
                        'repeater_description_ar' =>
                            array (
                                0 => 'التقط واسترد قوائمك عبر الأجهزة لمساعدتك على البقاء منظمًا في العمل والمنزل وأثناء التنقل.',
                                1 => 'التقط واسترد قوائمك عبر الأجهزة لمساعدتك على البقاء منظمًا في العمل والمنزل وأثناء التنقل.',
                                2 => 'التقط واسترد قوائمك عبر الأجهزة لمساعدتك على البقاء منظمًا في العمل والمنزل وأثناء التنقل.',
                                3 => 'التقط واسترد قوائمك عبر الأجهزة لمساعدتك على البقاء منظمًا في العمل والمنزل وأثناء التنقل.',
                                4 => 'التقط واسترد قوائمك عبر الأجهزة لمساعدتك على البقاء منظمًا في العمل والمنزل وأثناء التنقل.',
                                5 => 'التقط واسترد قوائمك عبر الأجهزة لمساعدتك على البقاء منظمًا في العمل والمنزل وأثناء التنقل.',
                            ),
                        'repeater_icon_ar' =>
                            array (
                                0 => 'las la-eye',
                                1 => 'las la-clock',
                                2 => 'las la-exchange-alt',
                                3 => 'las la-list-alt',
                                4 => 'las la-desktop',
                                5 => 'las la-microscope',
                            ),
                    ),
                'padding_top' => '100',
                'padding_bottom' => '100',
            );

        return $widget_content;

    }

    private function price_plan_content()
    {
        $bg_image_id1 = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/utlities/bg.png');

        $widget_content = array (
                'id' => '3',
                'addon_name' => 'PricePlanOne',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cUHJpY2VQbGFuT25l',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '3',
                'addon_page_id' => '1',
                'addon_page_type' => 'dynamic_page',
                'title_en_US' => 'Affordable Pricing plans',
                'button_text_en_US' => 'Try It Now',
                'title_ar' => 'خطط تسعير في المتناول',
                'button_text_ar' => 'جربه الآن',
                'bg_image' => $bg_image_id1,
                'order_by' => 'id',
                'order' => 'asc',
                'padding_top' => '100',
                'padding_bottom' => '100',
            );


        return $widget_content;
    }

    private function app_of_the_month_content()
    {
        $left_image_id = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/header-slider/utility/02.png');

        $widget_content = array (
                'id' => '4',
                'addon_name' => 'AppsOfTheMonthOne',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cQXBwc09mVGhlTW9udGhPbmU=',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '4',
                'addon_page_id' => '1',
                'addon_page_type' => 'dynamic_page',
                'title_en_US' => 'App of the Month Crew Manager',
                'subtitle_en_US' => 'Each time a digital asset is purchased or sold, Sequoir donates a percentage of the fees back into the development of the asset through its charitable foundation.',
                'title_ar' => 'تطبيق مدير طاقم الشهر',
                'subtitle_ar' => 'في كل مرة يتم فيها شراء أو بيع أصل رقمي ، تتبرع شركة بنسبة مئوية من الرسوم لإعادة تطوير الأصل من خلال مؤسستها الخيرية.',
                'left_image' => $left_image_id,
                'app_of_the_month_repeater' =>
                    array (
                        'repeater_title_en_US' =>
                            array (
                                0 => 'See Real Time',
                                1 => 'All device supported',
                                2 => 'Report analytics',
                            ),
                        'repeater_title_url_en_US' =>
                            array (
                                0 => '#',
                                1 => '#',
                                2 => '#',
                            ),
                        'repeater_subtitle_en_US' =>
                            array (
                                0 => 'Tracker app is a tracking and location forecasting solution forecasts arrival and tracks and manages outside contractor',
                                1 => 'Tracker app is a tracking and location forecasting solution forecasts arrival and tracks and manages outside contractor',
                                2 => 'Tracker app is a tracking and location forecasting solution forecasts arrival and tracks and manages outside contractor',
                            ),
                        'repeater_icon_en_US' =>
                            array (
                                0 => 'las la-clock',
                                1 => 'las la-desktop',
                                2 => 'las la-list',
                            ),
                        'repeater_title_ar' =>
                            array (
                                0 => 'رؤية الوقت الحقيقي',
                                1 => 'رؤية الوقت الحقيقي',
                                2 => 'رؤية الوقت الحقيقي',
                            ),
                        'repeater_title_url_ar' =>
                            array (
                                0 => '#',
                                1 => '#',
                                2 => '#',
                            ),
                        'repeater_subtitle_ar' =>
                            array (
                                0 => 'تطبيق هو حل للتتبع والتنبؤ بالموقع يتنبأ بالوصول ويتتبع ويدير المقاول الخارجي',
                                1 => 'تطبيق هو حل للتتبع والتنبؤ بالموقع يتنبأ بالوصول ويتتبع ويدير المقاول الخارجي',
                                2 => 'تطبيق هو حل للتتبع والتنبؤ بالموقع يتنبأ بالوصول ويتتبع ويدير المقاول الخارجي',
                            ),
                        'repeater_icon_ar' =>
                            array (
                                0 => 'las la-clock',
                                1 => 'las la-clock',
                                2 => 'las la-clock',
                            ),
                    ),
                'padding_top' => '150',
                'padding_bottom' => '100',
            );

            return $widget_content;
    }

    private function testimonial_content()
    {

       $bg_image_id = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/header-slider/utility/bg-02.png');

        $widget_content = array (
                'id' => '7',
                'addon_name' => 'TestimonialOne',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cVGVzdGltb25pYWxPbmU=',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '7',
                'addon_page_id' => '1',
                'addon_page_type' => 'dynamic_page',
                'title_en_US' => 'Inspired Testimonial',
                'title_ar' => 'شهادة من وحي',
                'icon' => 'las la-quote-right',
                'bg_image' => $bg_image_id,
                'item' => '3',
                'order_by' => 'id',
                'order' => 'asc',
                'padding_top' => '100',
                'padding_bottom' => '100',
            );

            return $widget_content;
    }

    private function blog_content()
    {
        $widget_content = array (
                'id' => '8',
                'addon_name' => 'BlogSliderOne',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xCbG9nXEJsb2dPbmU=',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '8',
                'addon_page_id' => '1',
                'addon_page_type' => 'dynamic_page',
                'title_en_US' => 'Health Tips & Blog',
                'title_ar' => 'مدونة ونصائح صحية',
                'categories' => '1',
                'order_by' => 'id',
                'order' => 'asc',
                'items' => '3',
                'padding_top' => '110',
                'padding_bottom' => '50',
            );

         return $widget_content;
    }

    private function support_one_content(){

        $image_id = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/tracks/01.png');

        $widget_content = array (
                'id' => '80',
                'addon_name' => 'SupportAreaOne',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cU3VwcG9ydEFyZWFPbmU=',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '5',
                'addon_page_id' => '1',
                'addon_page_type' => 'dynamic_page',
                'title_en_US' => 'The most powerful audio streamer for',
                'subtitle_en_US' => 'Inspiration comes in many ways and you like to save everything from. sed do eiusmo',
                'button_text_en_US' => 'Learn More',
                'button_url_en_US' => '#',
                'title_ar' => 'أقوى جهاز بث صوتي لـ',
                'subtitle_ar' => 'في كل مرة يتم فيها شراء أو بيع أصل رقمي ، تتبرع شركة بنسبة مئوية من الرسوم مرة أخرى في التطوير',
                'button_text_ar' => 'يتعلم أكثر',
                'button_url_ar' => '#',
                'image' => $image_id,
                'support_area_repeater' =>
                    array (
                        'repeater_title_en_US' =>
                            array (
                                0 => '50+ Supports various file formats',
                                1 => '50+ Supports various file formats',
                                2 => '50+ Supports various file formats',
                            ),
                        'repeater_icon_en_US' =>
                            array (
                                0 => 'las la-check',
                                1 => 'las la-check',
                                2 => 'las la-check',
                            ),
                        'repeater_title_ar' =>
                            array (
                                0 => 'يأتي الإلهام بعدة طرق وتريد التوفير',
                                1 => 'يأتي الإلهام بعدة طرق وتريد التوفير',
                                2 => 'يأتي الإلهام بعدة طرق وتريد التوفير',
                            ),
                        'repeater_icon_ar' =>
                            array (
                                0 => 'las la-check',
                                1 => 'las la-check',
                                2 => 'las la-check',
                            ),
                    ),
                'section_alignment' => 'left',
                'heading_style' => 'title',
                'button_color' => 'style',
                'padding_top' => '110',
                'padding_bottom' => '110',
            );
            return $widget_content;
    }

    private function support_two_content(){

       $image_id = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/tracks/02.png');

        $widget_content = array (
                'id' => '81',
                'addon_name' => 'SupportAreaOne',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cU3VwcG9ydEFyZWFPbmU=',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '6',
                'addon_page_id' => '1',
                'addon_page_type' => 'dynamic_page',
                'title_en_US' => 'Online, In Store, and On the',
                'subtitle_en_US' => 'Each time a digital asset is purchased or sold, Sequoir donates a percentage of the fees back into the development',
                'button_text_en_US' => 'Learn More',
                'button_url_en_US' => '#',
                'title_ar' => 'أقوى جهاز بث صوتي لـ',
                'subtitle_ar' => 'عبر الإنترنت وفي المتجر وأثناء التنقل',
                'button_text_ar' => 'يتعلم أكثر',
                'button_url_ar' => '#',
               'image' => $image_id,
                'support_area_repeater' =>
                    array (
                        'repeater_title_en_US' =>
                            array (
                                0 => '80+ Supports various file formats',
                                1 => '90+ Supports various file formats',
                                2 => '100+ Supports various file formats',
                            ),
                        'repeater_icon_en_US' =>
                            array (
                                0 => 'las la-check',
                                1 => 'las la-check',
                                2 => 'las la-check',
                            ),
                        'repeater_title_ar' =>
                            array (
                                0 => 'يأتي الإلهام بعدة طرق وتريد التوفير',
                                1 => 'يأتي الإلهام بعدة طرق وتريد التوفير',
                                2 => 'يأتي الإلهام بعدة طرق وتريد التوفير',
                            ),
                        'repeater_icon_ar' =>
                            array (
                                0 => 'las la-check',
                                1 => 'las la-check',
                                2 => 'las la-check',
                            ),
                    ),
                'section_alignment' => 'right',
                'heading_style' => 'title',
                'button_color' => 'style',
                'padding_top' => '50',
                'padding_bottom' => '80',
            );

        return $widget_content;
    }
}
