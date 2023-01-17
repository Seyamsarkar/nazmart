<?php

namespace Database\Seeders\Tenant\Addons;

use App\Helpers\ImageDataSeedingHelper;
use App\Models\PageBuilder;
use Illuminate\Database\Seeder;

class AboutPageAddon extends Seeder
{
    public function run()
    {

        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'SupportAreaOne',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\SupportAreaOne',
            'addon_order' => 1,
            'addon_page_id' => 2,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->support_one_content()),
        ]);

       //Key Feature Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'KeyFeatureStyleFour',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\KeyFeatureStyleFour',
            'addon_order' => 2,
            'addon_page_id' => 2,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->key_feature_content()),
        ]);

        //Team Member Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'TeamMemberOne',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\TeamMemberOne',
            'addon_order' => 3,
            'addon_page_id' => 2,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->team_member_content()),
        ]);

        //Portfolio Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'PortfolioAreaOne',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\PortfolioAreaOne',
            'addon_order' => 4,
            'addon_page_id' => 2,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->portfolio_content()),
        ]);

        //Organization Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'OrganizationAreaOne',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\OrganizationAreaOne',
            'addon_order' => 5,
            'addon_page_id' => 2,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->organization_content()),
        ]);

        //Testimonial Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'TestimonialSix',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\TestimonialSix',
            'addon_order' => 6,
            'addon_page_id' => 2,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->testimonial_content()),
        ]);

        //Brand Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'Brand',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\Brand',
            'addon_order' => 7,
            'addon_page_id' => 2,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->brand_content()),
        ]);

        //Get In Touch Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'GetInTouchOne',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\GetInTouchOne',
            'addon_order' => 8,
            'addon_page_id' => 2,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->get_in_touch_content()),
        ]);

    }

    private function support_one_content(){

        $img_id = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/unique-project/01.png');
        $widget_content = array (
            'id' => '80',
            'addon_name' => 'SupportAreaOne',
            'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cU3VwcG9ydEFyZWFPbmU=',
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_order' => '1',
            'addon_page_id' => '2',
            'addon_page_type' => 'dynamic_page',
            'title_en_US' => 'The most powerful audio streamer for',
            'subtitle_en_US' => 'Inspiration comes in many ways and you like to save everything from. sed do eiusmo',
            'button_text_en_US' => 'Learn More',
            'button_url_en_US' => '#',
            'title_ar' => 'أقوى جهاز بث صوتي لـ',
            'subtitle_ar' => 'في كل مرة يتم فيها شراء أو بيع أصل رقمي ، تتبرع شركة بنسبة مئوية من الرسوم مرة أخرى في التطوير',
            'button_text_ar' => 'يتعلم أكثر',
            'button_url_ar' => '#',
            'image' => $img_id,
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

    private function key_feature_content(){
        $widget_content =

            array (
                'id' => '53',
                'addon_name' => 'KeyFeatureStyleFour',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cS2V5RmVhdHVyZVN0eWxlRm91cg==',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '2',
                'addon_page_id' => '2',
                'addon_page_type' => 'dynamic_page',
                'key_feature_four' =>
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
                                2 => 'flaticon-notepad',
                            ),
                    ),
                'padding_top' => '0',
                'padding_bottom' => '110',
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
                'id' => '54',
                'addon_name' => 'TeamMemberOne',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cVGVhbU1lbWJlck9uZQ==',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '3',
                'addon_page_id' => '2',
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
                                0 => $team_img_id1,
                                1 => $team_img_id2,
                                2 => $team_img_id3,
                                3 => $team_img_id4,
                            ),
                    ),
                'padding_top' => '110',
                'padding_bottom' => '110',
            );

        return $widget_content;
    }

    private function portfolio_content(){

        $port_bg_img_id = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/map/map.png');
        $widget_content =

            array (
                'id' => '55',
                'addon_name' => 'PortfolioAreaOne',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cUG9ydGZvbGlvQXJlYU9uZQ==',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '4',
                'addon_page_id' => '2',
                'addon_page_type' => 'dynamic_page',
                'title_en_US' => 'We helped 400+ organizations',
                'subtitle_en_US' => 'Each time a digital asset is purchased or sold, Sequoir donates a percentage of the fees',
                'button_text_en_US' => 'Discover & Get Support From Our Team',
                'button_url_en_US' => '#',
                'title_ar' => 'لقد ساعدنا أكثر من 400 منظمة',
                'subtitle_ar' => 'في كل مرة يتم فيها شراء أو بيع أصل رقمي ، تتبرع شركة Sequoir بنسبة مئوية من الرسوم',
                'button_text_ar' => 'اكتشف واحصل على الدعم من فريقنا',
                'button_url_ar' => '#',
                'bg_image' => $port_bg_img_id,
                'padding_top' => '0',
                'padding_bottom' => '0',
            );

        return $widget_content;
    }

    private function organization_content(){

        $work_img_id1 = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/hard-work/01.jpg');
        $work_img_id2 = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/hard-work/02.jpg');
        $work_img_id3 = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/hard-work/03.jpg');

        $widget_content =
            array (
                'id' => '56',
                'addon_name' => 'OrganizationAreaOne',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cT3JnYW5pemF0aW9uQXJlYU9uZQ==',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '5',
                'addon_page_id' => '2',
                'addon_page_type' => 'dynamic_page',
                'organization_area_one_repeater' =>
                    array (
                        'repeater_title_en_US' =>
                            array (
                                0 => 'Zenefits.com',
                                1 => 'Ducbudy Redesign',
                                2 => 'New Way Redesign',
                            ),
                        'repeater_title_url_en_US' =>
                            array (
                                0 => '#',
                                1 => '#',
                                2 => '#',
                            ),
                        'repeater_subtitle_en_US' =>
                            array (
                                0 => 'Mobile App',
                                1 => 'Web App',
                                2 => 'IOS App',
                            ),
                        'repeater_image_en_US' =>
                            array (
                                0 => $work_img_id1,
                                1 => $work_img_id2,
                                2 => $work_img_id3,
                            ),
                        'repeater_title_ar' =>
                            array (
                                0 => 'Zenefits.com',
                                1 => 'إعادة تصميم Ducbudy',
                                2 => 'إعادة تصميم Ducbudy',
                            ),
                        'repeater_title_url_ar' =>
                            array (
                                0 => '#',
                                1 => '#',
                                2 => '#',
                            ),
                        'repeater_subtitle_ar' =>
                            array (
                                0 => 'تطبيق الهاتف المحمول',
                                1 => 'تطبيق الهاتف المحمول',
                                2 => 'تطبيق الهاتف المحمول',
                            ),
                        'repeater_image_ar' =>
                            array (
                                0 => $work_img_id1,
                                1 => $work_img_id2,
                                2 => $work_img_id3,
                            ),
                    ),
                'padding_top' => '110',
                'padding_bottom' => '110',
            );

        return $widget_content;
    }

    private function testimonial_content(){
        $widget_content =

            array (
                'id' => '57',
                'addon_name' => 'TestimonialSix',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cVGVzdGltb25pYWxTaXg=',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '6',
                'addon_page_id' => '2',
                'addon_page_type' => 'dynamic_page',
                'title_en_US' => 'What our customer’s say',
                'title_ar' => 'ماذا يقول عملاؤنا',
                'item' => '3',
                'order_by' => 'id',
                'order' => 'asc',
                'padding_top' => '110',
                'padding_bottom' => '110',
            );
        return $widget_content;
    }

    private function brand_content(){

        $widget_content = array (
                'id' => '58',
                'addon_name' => 'Brand',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cQnJhbmQ=',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '7',
                'addon_page_id' => '2',
                'addon_page_type' => 'dynamic_page',
                'title_en_US' => NULL,
                'title_ar' => NULL,
                'item_show' => '4',
                'padding_top' => '0',
                'padding_bottom' => '110',
            );
        return $widget_content;
    }

    private function get_in_touch_content(){

        $img_id1 = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/get-in-touch/01.png');
        $img_id2 = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/get-in-touch/02.png');
        $img_bg_id3 = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/get-in-touch/22.png');

        $widget_content =
            array (
                'id' => '59',
                'addon_name' => 'GetInTouchOne',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cR2V0SW5Ub3VjaE9uZQ==',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '8',
                'addon_page_id' => '2',
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
