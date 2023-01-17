<?php

namespace Plugins\PageBuilder\Addons\Tenants\ThemeTwo\Header;

use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class HeaderOne extends PageBuilderBase
{

    public function preview_image()
    {
       return 'Tenant/Home/home-01-header-01.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'id' => 'header_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'label' => 'Title',
                    'name' => 'title',
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'label' => 'Subtitle',
                    'name' => 'subtitle',
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'label' => 'Shop Button Text',
                    'name' => 'shop_button_text',
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'label' => 'Shop Button URL',
                    'name' => 'shop_button_url',
                ],
                [
                    'type' => RepeaterField::COLOR_PICKER,
                    'label' => 'Button Color',
                    'name' => 'button_color',
                    'info' => '<div class="text-primary mt-1">'.__('This field is not necessary if you want to keep theme default colors').'</div>'
                ],
                [
                    'type' => RepeaterField::IMAGE,
                    'label' => 'Figure Image',
                    'name' => 'figure_image',
                    'dimensions' => __('775x557 | 770x550 px')
                ]
            ]
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
        $repeater_data = $this->setting_item('header_repeater');
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $data = [
                 'repeater_data' => $repeater_data,
                 'padding_top' => $padding_top,
                 'padding_bottom'=> $padding_bottom
            ];

        return self::renderView('tenant.theme_two.header.header-one',$data);

    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Theme 2: Header(01)');
    }



}
