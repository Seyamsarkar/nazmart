<?php

namespace Plugins\PageBuilder\Addons\Tenants\ThemeOne\Product;

use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use Modules\Blog\Entities\BlogCategory;
use Modules\Product\Entities\Product;
use Plugins\PageBuilder\Fields\NiceSelect;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;

class FeaturedProductSlider extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/common/brand-01.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= Number::get([
            'name' => 'item_show',
            'label' => __('Item Show'),
            'value' => $widget_saved_values['item_show'] ?? null,
        ]);

        $products = Product::where(['status_id' => 1])->get()->mapWithKeys(function ($item){
            return [$item->id => $item->name];
        })->toArray();

        $output .= NiceSelect::get([
            'multiple' => true,
            'name' => 'products',
            'label' => __('Select Products'),
            'options' => $products,
            'value' => $widget_saved_values['products'] ?? null,
            'info' => __('you can select your desired products or leave it empty')
        ]);

        // add padding option
        $output .= $this->padding_fields($widget_saved_values);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $products_id = $this->setting_item('products');
        $item_show = SanitizeInput::esc_html($this->setting_item('item_show') ?? '');
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $products = Product::where('status_id',1);

        if (!empty($products_id))
        {
            $products->whereIn('id', $products_id);
        }

        if(!empty($item_show)){
            $products = $products->take($item_show)->get();
        }else{
            $products = $products->take(4)->get();
        }

        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'products'=> $products
        ];

        return self::renderView('tenant.theme_one.product.featured_product_slider',$data);
    }

    public function addon_title()
    {
        return __('Featured Product Slider: 01');
    }
}
