<?php

namespace Plugins\PageBuilder\Addons\Tenants\ThemeTwo\Common;

use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use Modules\Attributes\Entities\Category;
use Modules\Campaign\Entities\Campaign;
use Modules\Product\Entities\ProductCategory;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\NiceSelect;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Switcher;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class CategoriesSlider extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/common/hardwork-area.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $campaigns = Category::where('status_id', '1')->pluck('name', 'id')->toArray();
        $output .= NiceSelect::get([
            'multiple' => true,
            'name' => 'categories',
            'label' => __('Select Categories'),
            'options' => $campaigns,
            'value' => $widget_saved_values['categories'] ?? '',
        ]);

        $output .= Switcher::get([
            'name' => 'product_count',
            'label' => __('Show Product Count'),
            'value' => $widget_saved_values['product_count'] ?? true,
            'info' => 'Enable this if you want to show product count under the category'
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
        $product_count = $this->setting_item('product_count');
        $categories = $this->setting_item('categories') ?? '';

        $categories_info = Category::whereIn('id', !empty($categories) ? $categories : [])->select('id', 'name', 'slug', 'image_id')->get();

        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $data = [
            'categories_info'=> $categories_info,
            'product_count'=> $product_count,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
        ];

        return self::renderView('tenant.theme_two.common.categories-slider',$data);

    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Theme 2: Categories Slider');
    }
}
