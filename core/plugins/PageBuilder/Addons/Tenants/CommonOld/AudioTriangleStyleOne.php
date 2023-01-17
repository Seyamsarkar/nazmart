<?php

namespace Plugins\PageBuilder\Addons\Tenants\Common;

use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class AudioTriangleStyleOne extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/common/audio-triangle-01.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        //repeater
        $output .= Repeater::get([
            'multi_lang' => true,
            'settings' => $widget_saved_values,
            'id' => 'audio_triangle_type_one',
            'info' => 'Left Section',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title',
                    'label' => __('Left Title')
                ],

                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title_url',
                    'label' => __('Left Title URL')
                ],

                [
                    'type' => RepeaterField::TEXTAREA,
                    'name' => 'repeater_description',
                    'label' => __('Left Description')
                ],
            ]
        ]);

        $output .= Image::get([
            'name' => 'image',
            'label' => __('Middle Image'),
            'value' => $widget_saved_values['image'] ?? null,
        ]);

        $output .= Image::get([
            'name' => 'bg_image',
            'label' => __('Middle Background Image'),
            'value' => $widget_saved_values['bg_image'] ?? null,
        ]);


        $output .= Repeater::get([
            'multi_lang' => true,
            'settings' => $widget_saved_values,
            'id' => 'audio_triangle_type_two',
            'info'=> 'Right Section',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title',
                    'label' => __('Right Title')
                ],

                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title_url',
                    'label' => __('Right  Title URL')
                ],

                [
                    'type' => RepeaterField::TEXTAREA,
                    'name' => 'repeater_description',
                    'label' => __('Right Description')
                ],
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
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $image = $this->setting_item('image');
        $bg_image = $this->setting_item('bg_image');
        $repeater_data_one = $this->setting_item('audio_triangle_type_one');
        $repeater_data_two = $this->setting_item('audio_triangle_type_two');

        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'repeater_data_one'=> $repeater_data_one,
            'repeater_data_two'=> $repeater_data_two,
            'image'=> $image,
            'bg_image'=> $bg_image,
        ];

        return self::renderView('tenant.common.audio-triangle-one',$data);

    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('HM2 : Audio Triangle (01)');
    }
}
