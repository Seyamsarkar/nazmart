<?php

namespace Plugins\PageBuilder\Addons\Tenants\ThemeTwo\Common;

use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use Modules\Campaign\Entities\Campaign;
use Plugins\PageBuilder\Fields\NiceSelect;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;

class Brand extends PageBuilderBase
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

        $brand = \Modules\Attributes\Entities\Brand::select('id','name')->get()->mapWithKeys(function ($item){
            return [$item->id => $item->name];
        })->toArray();

        $output .= NiceSelect::get([
            'multiple' => true,
            'name' => 'brand',
            'label' => __('Select Brand'),
            'options' => $brand,
            'value' => $widget_saved_values['brand'] ?? null,
            'info' => __('you can select your desired campaign or leave it empty')
        ]);

        $output .= Number::get([
            'name' => 'item_show',
            'label' => __('Item Show'),
            'value' => $widget_saved_values['item_show'] ?? null,
        ]);

        $output .= Number::get([
            'name' => 'item_pagination',
            'label' => __('Slider Pagination'),
            'value' => $widget_saved_values['item_pagination'] ?? null,
            'info' => __('Select pagination number or leave it empty')
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
        $brands_id = $this->setting_item('brand');
        $item_show = SanitizeInput::esc_html($this->setting_item('item_show') ?? '');
        $item_pagination = SanitizeInput::esc_html($this->setting_item('item_pagination') ?? '');
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $brands = [];
        if (!empty($brands_id)) {
            $brands = \Modules\Attributes\Entities\Brand::whereIn('id', $brands_id);

            if (!empty($item_show)) {
                $brands = $brands->take($item_show)->get();
            } else {
                $brands = $brands->take(3)->get();
            }
        }

        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'brands'=> $brands,
            'item_pagination' => $item_pagination ?? 6
        ];

        return self::renderView('tenant.theme_two.common.brand',$data);

    }

    public function addon_title()
    {
        return __('Them 2: Brand(01)');
    }
}
