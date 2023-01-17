<?php

namespace Plugins\PageBuilder\Addons\Tenants\ThemeOne\Product;

use App\Helpers\SanitizeInput;
use Modules\Attributes\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductCategory;
use Plugins\PageBuilder\Fields\NiceSelect;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;

class ProductTypeList extends PageBuilderBase
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

        $output .= Text::get([
            'name' => 'title',
            'label' => __('Section Title'),
            'value' => $widget_saved_values['title'] ?? null,
        ]);

        $categories = Category::where(['status_id' => 1])->get()->mapWithKeys(function ($item){
            return [$item->id => $item->name];
        })->toArray();

        $output .= NiceSelect::get([
            'multiple' => true,
            'name' => 'categories',
            'label' => __('Select Categories'),
            'options' => $categories,
            'value' => $widget_saved_values['categories'] ?? null,
            'info' => __('you can select your desired product categories or leave it empty')
        ]);

        $output .= Number::get([
            'name' => 'item_show',
            'label' => __('Product Show'),
            'value' => $widget_saved_values['item_show'] ?? null,
            'info' => 'How many products will be shown under the selected category'
        ]);

        $output .= Text::get([
            'name' => 'view_all_url',
            'label' => __('View All URL'),
            'value' => $widget_saved_values['view_all_url'] ?? '#',
            'info' => 'Copy and page any page link here'
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
        $categories_id = $this->setting_item('categories');
        $title = SanitizeInput::esc_html($this->setting_item('title') ?? '');
        $item_show = SanitizeInput::esc_html($this->setting_item('item_show') ?? '');
        $view_all_url = SanitizeInput::esc_html($this->setting_item('view_all_url') ?? '');
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $categories = Category::where('status_id',1);
        $products = Product::with('badge')->where('status_id', 1);

        if (!empty($categories_id))
        {
            $categories = $categories->whereIn('id', $categories_id)->select('id', 'name', 'slug')->get();
            $products_id = ProductCategory::whereIn('category_id', $categories_id)->pluck('product_id')->toArray();
            $products->whereIn('id', $products_id);
        }

        if(!empty($item_show)){
            $products = $products->orderBy('id','desc')->select('id', 'name', 'slug', 'price', 'sale_price', 'badge_id', 'image_id')->take($item_show)->get();
        }else{
            $products = $products->orderBy('id', 'desc')->select('id', 'name', 'slug', 'price', 'sale_price', 'badge_id', 'image_id')->take(6)->get();
        }


        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'title' => $title,
            'view_all_url' => $view_all_url,
            'categories'=> $categories,
            'products'=> $products,
            'product_limit' => $item_show ?? 6
        ];

        return self::renderView('tenant.theme_one.product.product_type_list',$data);
    }

    public function addon_title()
    {
        return __('Product Type List: 01');
    }
}
