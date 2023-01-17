<?php

namespace Database\Seeders\Tenant\Addons;

use App\Helpers\ImageDataSeedingHelper;
use App\Models\PageBuilder;
use Illuminate\Database\Seeder;

class ServicePageAddon extends Seeder
{
    public function run()
    {
        //How It Works Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'HowItWorkOne',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\HowItWorkOne',
            'addon_order' => 1,
            'addon_page_id' => 3,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->how_it_work_content()),
        ]);

        //Service One Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'ServiceOne',
            'addon_namespace' =>  'Plugins\PageBuilder\Addons\Tenants\Service\ServiceOne',
            'addon_order' => 2,
            'addon_page_id' => 3,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->service_one_content()),
        ]);

        //Counterup One Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'CounterupOne',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\CounterupOne',
            'addon_order' => 3,
            'addon_page_id' => 3,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->counterup_content()),
        ]);

        //Key Feature Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'KeyFeatureStyleSix',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\KeyFeatureStyleSix',
            'addon_order' => 4,
            'addon_page_id' => 3,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->key_feature_content()),
        ]);

        //Team Member Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'TeamMemberOne',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\TeamMemberOne',
            'addon_order' => 5,
            'addon_page_id' => 3,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->team_member_content()),
        ]);

        //Brand Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'Brand',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\Brand',
            'addon_order' => 6,
            'addon_page_id' => 3,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->brand_content()),
        ]);

        //Get In Touch Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'GetInTouchOne',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\GetInTouchOne',
            'addon_order' => 7,
            'addon_page_id' => 3,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->get_in_touch_content()),
        ]);

    }

    private function how_it_work_content(){

        $widget_content = array (
                'id' => '65',
                'addon_name' => 'HowItWorkOne',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cSG93SXRXb3JrT25l',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '1',
                'addon_page_id' => '3',
                'addon_page_type' => 'dynamic_page',
                'left_title_en_US' => 'How It Works',
                'right_title_en_US' => 'The App Manage Everything You Need Whatever',
                'left_title_ar' => 'كيف تعمل',
                'right_title_ar' => 'يدير التطبيق كل ما تحتاجه مهما كان',
                'icon' => 'las la-lightbulb',
                'padding_top' => '200',
                'padding_bottom' => '110',
            );

        return $widget_content;
    }

    private function service_one_content(){

        $widget_content =
            array (
                'id' => '73',
                'addon_name' => 'ServiceOne',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xTZXJ2aWNlXFNlcnZpY2VPbmU=',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '2',
                'addon_page_id' => '3',
                'addon_page_type' => 'dynamic_page',
                'item' => '6',
                'order_by' => 'id',
                'order' => 'asc',
                'padding_top' => '110',
                'padding_bottom' => '110',
            );

        return $widget_content;
    }

    private function counterup_content(){

        $widget_content =

            array (
                'id' => '75',
                'addon_name' => 'CounterupOne',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cQ291bnRlcnVwT25l',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '3',
                'addon_page_id' => '3',
                'addon_page_type' => 'dynamic_page',
                'title_en_US' => 'Train you on best practices we’ve picked up from other enterprise customers.',
                'title_ar' => 'تدريبك على أفضل الممارسات التي اخترناها من عملاء مؤسسيين آخرين.',
                'counterup_one_repeater' =>
                    array (
                        'repeater_title_en_US' =>
                            array (
                                0 => 'User Reviews',
                                1 => 'Trusted & loved by users',
                                2 => 'Total Downloads',
                            ),
                        'repeater_number_en_US' =>
                            array (
                                0 => '4.82',
                                1 => '33,490',
                                2 => '107',
                            ),
                        'repeater_title_ar' =>
                            array (
                                0 => 'مراجعات المستخدم',
                                1 => 'مراجعات المستخدم',
                                2 => 'مراجعات المستخدم',
                            ),
                        'repeater_number_ar' =>
                            array (
                                0 => '4.95',
                                1 => '44,250',
                                2 => '107',
                            ),
                    ),
                'padding_top' => '110',
                'padding_bottom' => '110',
            );

        return $widget_content;
    }

    private function key_feature_content(){

        $bg_img = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/map/map.png');

        $widget_content =

            array (
                'id' => '76',
                'addon_name' => 'KeyFeatureStyleSix',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cS2V5RmVhdHVyZVN0eWxlU2l4',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '4',
                'addon_page_id' => '3',
                'addon_page_type' => 'dynamic_page',
                'title_en_US' => 'Why choose us',
                'subtitle_en_US' => 'We are awesome inventor',
                'title_ar' => 'لماذا أخترتنا',
                'subtitle_ar' => 'نحن مخترع رائع',
                'bg_image' => $bg_img,
                'key_feature_six' =>
                    array (
                        'repeater_title_en_US' =>
                            array (
                                0 => 'Strategy & Design',
                                1 => 'Development',
                                2 => 'Analysis & Reporting',
                            ),
                        'repeater_title_url_en_US' =>
                            array (
                                0 => '#',
                                1 => '#',
                                2 => '#',
                            ),
                        'repeater_description_en_US' =>
                            array (
                                0 => 'Each time a digital asset is purchased or sold, Sequoir donates a percentage of the fees back',
                                1 => 'Each time a digital asset is purchased or sold, Sequoir donates a percentage of the fees back',
                                2 => 'Each time a digital asset is purchased or sold, Sequoir donates a percentage of the fees back',
                            ),
                        'repeater_icon_en_US' =>
                            array (
                                0 => 'flaticon-monitor',
                                1 => 'flaticon-smartphone',
                                2 => 'flaticon-notepad',
                            ),
                        'repeater_title_ar' =>
                            array (
                                0 => 'الإستراتيجية والتصميم',
                                1 => 'الإستراتيجية والتصميم',
                                2 => 'الإستراتيجية والتصميم',
                            ),
                        'repeater_title_url_ar' =>
                            array (
                                0 => '#',
                                1 => '#',
                                2 => '#',
                            ),
                        'repeater_description_ar' =>
                            array (
                                0 => 'في كل مرة يتم فيها شراء أو بيع أصل رقمي ، تتبرع شركة Sequoir بنسبة مئوية من الرسوم المستردة',
                                1 => 'في كل مرة يتم فيها شراء أو بيع أصل رقمي ، تتبرع شركة Sequoir بنسبة مئوية من الرسوم المستردة',
                                2 => 'في كل مرة يتم فيها شراء أو بيع أصل رقمي ، تتبرع شركة Sequoir بنسبة مئوية من الرسوم المستردة',
                            ),
                        'repeater_icon_ar' =>
                            array (
                                0 => 'flaticon-monitor',
                                1 => 'flaticon-smartphone',
                                2 => 'flaticon-notebook',
                            ),
                    ),
                'padding_top' => '0',
                'padding_bottom' => '0',
            );

        return $widget_content;
    }

    private function team_member_content(){

        $team_img_id1 = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/team/01.png');
        $team_img_id2 = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/team/02.png');
        $team_img_id3 = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/team/03.png');
        $team_img_id4 = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/team/04.png');

        $widget_content =

           array (
                'id' => '77',
                'addon_name' => 'TeamMemberOne',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cVGVhbU1lbWJlck9uZQ==',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '5',
                'addon_page_id' => '3',
                'addon_page_type' => 'dynamic_page',
                'title_en_US' => 'Meet our team',
                'subtitle_en_US' => 'We glow all the way up',
                'title_ar' => 'التق بفريقنا',
                'subtitle_ar' => 'نحن نضيء طوال الطريق',
                'team_member_repeater' =>
                    array (
                        'repeater_name_en_US' =>
                            array (
                                0 => 'JOHNNY FUAD',
                                1 => 'DAVID MULLAR',
                                2 => 'SOPHIA JONES',
                                3 => 'JACKSON ROBIN',
                            ),
                        'repeater_designation_en_US' =>
                            array (
                                0 => 'Co-Founder',
                                1 => 'Co-Founder',
                                2 => 'Co-Founder',
                                3 => 'Co-Founder',
                            ),
                        'repeater_facebook_url_en_US' =>
                            array (
                                0 => '#',
                                1 => '#',
                                2 => '#',
                                3 => '#',
                            ),
                        'repeater_linkedin_url_en_US' =>
                            array (
                                0 => '#',
                                1 => '#',
                                2 => '#',
                                3 => '#',
                            ),
                        'repeater_twitter_url_en_US' =>
                            array (
                                0 => '#',
                                1 => '#',
                                2 => '#',
                                3 => '#',
                            ),
                        'repeater_image_en_US' =>
                            array (
                                0 => $team_img_id1,
                                1 => $team_img_id2,
                                2 => $team_img_id3,
                                3 => $team_img_id4,
                            ),
                        'repeater_name_ar' =>
                            array (
                                0 => 'جوني فؤاد',
                                1 => 'جوني فؤاد',
                                2 => 'جوني فؤاد',
                                3 => 'جوني فؤاد',
                            ),
                        'repeater_designation_ar' =>
                            array (
                                0 => 'شريك مؤسس',
                                1 => 'شريك مؤسس',
                                2 => 'شريك مؤسس',
                                3 => 'شريك مؤسس',
                            ),
                        'repeater_facebook_url_ar' =>
                            array (
                                0 => '#',
                                1 => '#',
                                2 => '#',
                                3 => '#',
                            ),
                        'repeater_linkedin_url_ar' =>
                            array (
                                0 => '#',
                                1 => '#',
                                2 => '#',
                                3 => '#',
                            ),
                        'repeater_twitter_url_ar' =>
                            array (
                                0 => '#',
                                1 => '#',
                                2 => '#',
                                3 => '#',
                            ),
                        'repeater_image_ar' =>
                            array (
                                0 => '',
                                1 => '',
                                2 => '',
                                3 => '',
                            ),
                    ),
                'padding_top' => '110',
                'padding_bottom' => '110',
            );

        return $widget_content;
    }

    private function brand_content(){

        $widget_content =

            array (
                'id' => '78',
                'addon_name' => 'Brand',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cQnJhbmQ=',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '6',
                'addon_page_id' => '3',
                'addon_page_type' => 'dynamic_page',
                'title_en_US' => NULL,
                'title_ar' => NULL,
                'item_show' => '4',
                'padding_top' => '50',
                'padding_bottom' => '100',
            );

        return $widget_content;
    }

    private function get_in_touch_content(){

        $img_id1 = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/get-in-touch/01.png');
        $img_id2 = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/get-in-touch/02.png');
        $img_bg_id3 = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/get-in-touch/22.png');

        $widget_content =

            array (
                'id' => '79',
                'addon_name' => 'GetInTouchOne',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cR2V0SW5Ub3VjaE9uZQ==',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '7',
                'addon_page_id' => '3',
                'addon_page_type' => 'dynamic_page',
                'title_en_US' => 'Get In Touch',
                'subtitle_en_US' => 'hello@ximas.com',
                'description_en_US' => '+ (383) 409 5120',
                'title_ar' => 'ابقى على تواصل',
                'subtitle_ar' => 'hello@ximas.com',
                'description_ar' => '+ (383) 409 5120',
                'left_image' => $img_id1,
                'right_image' => $img_id2,
                'bg_image' => $img_bg_id3,
                'padding_top' => '110',
                'padding_bottom' => '110',
            );

        return $widget_content;
    }

}
