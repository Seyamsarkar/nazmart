<?php

namespace Database\Seeders\Tenant\Addons;

use App\Models\FormBuilder;
use App\Models\PageBuilder;
use Illuminate\Database\Seeder;

class ContactPageAddon extends Seeder
{
    public function run()
    {
        //Contact Area Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'ContactAreaOne',
            'addon_namespace' =>  'Plugins\PageBuilder\Addons\Tenants\Contact\ContactAreaOne',
            'addon_order' => 1,
            'addon_page_id' => 5,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->contact_area_content()),
        ]);

        //Google Map Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'GoogleMap',
            'addon_namespace' =>  'Plugins\PageBuilder\Addons\Tenants\Contact\GoogleMap',
            'addon_order' => 2,
            'addon_page_id' => 5,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->google_map_content()),
        ]);
    }

    private function contact_area_content(){

        $widget_content =

            array (
                'id' => '60',
                'addon_name' => 'ContactAreaOne',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb250YWN0XENvbnRhY3RBcmVhT25l',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '1',
                'addon_page_id' => '5',
                'addon_page_type' => 'dynamic_page',
                'title_en_US' => 'Let’s scale your brand, together',
                'title_ar' => 'دعونا نقيس علامتك التجارية معًا',
                'contact_tenant_repeater' =>
                    array (
                        'repeater_info_' =>
                            array (
                                0 => '+458 123 657',
                                1 => 'info@example.com',
                                2 => '66 broklyn street, new york',
                                3 => 'Sunday- Thursday',
                                4 => 'On you time',
                            ),
                        'repeater_icon_' =>
                            array (
                                0 => 'flaticon-call',
                                1 => 'flaticon-email',
                                2 => 'flaticon-pin',
                                3 => 'flaticon-clock',
                                4 => 'flaticon-switch',
                            ),
                    ),
                'custom_form_id' => 1,
                'padding_top' => '100',
                'padding_bottom' => '0',
            );

        return $widget_content;
    }

    private function google_map_content(){

        $widget_content =

            array (
                'id' => '61',
                'addon_name' => 'GoogleMap',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb250YWN0XEdvb2dsZU1hcA==',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '2',
                'addon_page_id' => '5',
                'addon_page_type' => 'dynamic_page',
                'location' => 'Avenue Afton, MN 55001',
                'map_height' => '500',
                'padding_top' => '110',
                'padding_bottom' => '110',
            );

        return $widget_content;
    }


}
