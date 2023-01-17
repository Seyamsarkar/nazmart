<?php

namespace Plugins\PageBuilder\Addons\Tenants\Common;

use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\Models\Testimonial;
use Plugins\PageBuilder\Fields\IconPicker;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\NiceSelect;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class TestimonialTwo extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/common/testimonial-02.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();
        $output .= $this->admin_language_tab(); //have to start language tab from here on
        $output .= $this->admin_language_tab_start();
        $all_languages = LanguageHelper::all_languages();

        foreach ($all_languages as $key => $lang) {
            $output .= $this->admin_language_tab_content_start([
                'class' => $key == 0 ? 'tab-pane fade show active' : 'tab-pane fade',
                'id' => "nav-home-" . $lang->slug
            ]);
            $output .= Text::get([
                'name' => 'title_'.$lang->slug,
                'label' => __('Title'),
                'value' => $widget_saved_values['title_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= IconPicker::get([
            'name' => 'icon',
            'label' => __('Icon'),
            'value' => $widget_saved_values['icon'] ?? null,
        ]);


        $output .= Image::get([
            'name' => 'bg_image',
            'label' => __('Background Image'),
            'value' => $widget_saved_values['bg_image'] ?? null,
        ]);

        $output .= Number::get([
            'name' => 'item',
            'label' => __('Item'),
            'value' => $widget_saved_values['item'] ?? null,
        ]);

        $output .= Select::get([
            'name' => 'order_by',
            'label' => __('Order By'),
            'options' => [
                'id' => __('ID'),
                'created_at' => __('Date'),
            ],
            'value' => $widget_saved_values['order_by'] ?? null,
            'info' => __('set order by')
        ]);
        $output .= Select::get([
            'name' => 'order',
            'label' => __('Order'),
            'options' => [
                'asc' => __('Accessing'),
                'desc' => __('Decreasing'),
            ],
            'value' => $widget_saved_values['order'] ?? null,
            'info' => __('set order')
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
        $current_lang = LanguageHelper::user_lang_slug();
        $title = $this->setting_item('title_'.$current_lang) ?? '';
        $icon = $this->setting_item('icon') ?? '';
        $item = $this->setting_item('item') ?? '';
        $bg_image = $this->setting_item('bg_image') ?? '';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));

        $testimonial = Testimonial::query();
        $testimonial = $testimonial->where('status',1)->orderBy($order_by ?? 'id',$order ?? 'asc');
        if(!empty($item)) {
            if($item < 2){
                $testimonial = $testimonial->take(2)->get();
            }else{
                $testimonial = $testimonial->take($item)->get();
            }

        }else{
             $testimonial = $testimonial->take(3)->get();
        }

        $data = [
            'title'=> $title,
            'icon'=> $icon,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'testimonial'=> $testimonial,
            'bg_image'=> $bg_image,
        ];

        return self::renderView('tenant.common.testimonial-two',$data);

    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Testimonial (02)');
    }
}
