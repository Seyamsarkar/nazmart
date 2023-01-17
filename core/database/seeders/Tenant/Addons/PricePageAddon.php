<?php

namespace Database\Seeders\Tenant\Addons;

use App\Models\PageBuilder;
use Illuminate\Database\Seeder;

class PricePageAddon extends Seeder
{
    public function run()
    {
        //How It Works Addon
        PageBuilder::create([
            'addon_type' => 'update',
            'addon_location' => 'dynamic_page',
            'addon_name' => 'PricePlanOne',
            'addon_namespace' => 'Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common\PricePlanOne',
            'addon_order' => 1,
            'addon_page_id' => 4,
            'addon_page_type' => 'dynamic_page',
            'addon_settings' => json_encode($this->price_plan_content()),
        ]);

    }

    private function price_plan_content(){

        $widget_content =

            array (
                'id' => '82',
                'addon_name' => 'PricePlanOne',
                'addon_namespace' => 'UGx1Z2luc1xQYWdlQnVpbGRlclxBZGRvbnNcVGVuYW50c1xDb21tb25cUHJpY2VQbGFuT25l',
                'addon_type' => 'update',
                'addon_location' => 'dynamic_page',
                'addon_order' => '1',
                'addon_page_id' => '4',
                'addon_page_type' => 'dynamic_page',
                'title_en_US' => 'Affordable Pricing plans',
                'button_text_en_US' => 'Purchase',
                'title_ar' => 'خطط تسعير في المتناول',
                'button_text_ar' => 'شراء',
//                'bg_image' => '119',
                'order_by' => 'id',
                'order' => 'asc',
                'padding_top' => '99',
                'padding_bottom' => '100',
            );

        return $widget_content;
    }


}
