<?php

namespace Plugins\PageBuilder\Addons\Landlord\Common;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\Models\FormBuilder;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class ContactArea extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Landlord/common/contact.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'title',
            'label' => __('Title'),
            'value' => $widget_saved_values['title'] ?? null,
        ]);

        $output .= Select::get([
            'name' => 'custom_form_id',
            'label' => __('Custom Form'),
            'placeholder' => __('Select form'),
            'options' => FormBuilder::all()->pluck('title', 'id')->toArray(),
            'value' => $widget_saved_values['custom_form_id'] ?? []
        ]);

        $output .= Text::get([
            'name' => 'location',
            'label' => __('Location'),
            'value' => $widget_saved_values['location'] ?? ''
        ]);

        $output .= Number::get([
            'name' => 'map_height',
            'label' => __('Map Height'),
            'value' => $widget_saved_values['map_height'] ?? '',
            'info' => __('Height is in px')
        ]);


        // add padding option
        $output .= $this->section_id_and_class_fields($widget_saved_values);
        $output .= $this->padding_fields($widget_saved_values);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $title = SanitizeInput::esc_html($this->setting_item('title')) ?? '';
        $custom_form_id = SanitizeInput::esc_html($this->setting_item('custom_form_id'));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $location = SanitizeInput::esc_html($this->setting_item('location'));
        $map_height = SanitizeInput::esc_html($this->setting_item('map_height'));

        $repeater_data = $this->setting_item('contact_repeater');
        $section_id = SanitizeInput::esc_html($this->setting_item('section_id'));

        $location = sprintf(
            '<iframe frameborder="0" scrolling="no" marginheight="0" height="' . $map_height . 'px" marginwidth="0" src="https://maps.google.com/maps?q=%s&amp;t=m&amp;z=%d&amp;output=embed&amp;iwloc=near" aria-label="%s"></iframe>',
            rawurlencode($location),
            10,
            $location
        );

        $data = [
            'title' => $title,
            'custom_form_id' => $custom_form_id,
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'repeater_data' => $repeater_data,
            'section_id' => $section_id,
            'location' => $location,
        ];

        return self::renderView('landlord.addons.common.contact', $data);

    }

    public function enable(): bool
    {
        return (bool)is_null(tenant());
    }

    public function addon_title()
    {
        return __('Contact Area');
    }
}
