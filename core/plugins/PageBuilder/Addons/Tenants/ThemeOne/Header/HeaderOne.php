<?php

namespace Plugins\PageBuilder\Addons\Tenants\ThemeOne\Header;

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

        $output .= Image::get([
            'name' => 'foreground_image',
            'label' => __('Foreground Image'),
            'value' => $widget_saved_values['foreground_image'] ?? null,
        ]);

        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'id' => 'header_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'label' => 'Title',
                    'name' => 'title',
                    'info' => __('To show the highlighted text, place your word between this code {h}YourText{/h]')
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
                    'type' => RepeaterField::IMAGE,
                    'label' => 'Background Image',
                    'name' => 'background_image',
                ],
                [
                    'type' => RepeaterField::IMAGE,
                    'label' => 'Figure Image',
                    'name' => 'figure_image',
                ]
            ]
        ]);

        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'id' => 'social_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'label' => 'Social Media Name',
                    'name' => 'social_media_name',
                    'info' => 'Use short form name'
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'label' => 'Social Media URL',
                    'name' => 'social_media_url',
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
        $foreground_image = $this->setting_item('foreground_image');
        $repeater_data = $this->setting_item('header_repeater');
        $social_repeater = $this->setting_item('social_repeater');
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $data = [
                 'foreground_image' => $foreground_image,
                 'repeater_data' => $repeater_data,
                 'social_repeater' => $social_repeater,
                 'padding_top' => $padding_top,
                 'padding_bottom'=> $padding_bottom
            ];

        return self::renderView('tenant.theme_one.header.header-one',$data);

    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Theme 1: Header(01)');
    }



}
