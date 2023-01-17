<?php

namespace Plugins\PageBuilder\Addons\Landlord\Header;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;

use Plugins\PageBuilder\Fields\IconPicker;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\Traits\GlobalAdminFields;
use Plugins\PageBuilder\PageBuilderBase;

class HeaderStyleOne extends PageBuilderBase
{

    public function preview_image()
    {
       return 'Landlord/header/header-01.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= $this->admin_language_tab(); //have to start language tab from here on
        $output .= $this->admin_language_tab_start();

        $all_languages = GlobalLanguage::all_languages();

        foreach ($all_languages as $key => $lang) {
            $output .= $this->admin_language_tab_content_start([
                'class' => $key == 0 ? 'tab-pane fade show active' : 'tab-pane fade',
                'id' => "nav-home-" . $lang->slug
            ]);
            $output .= Text::get([
                'name' => 'title_'.$lang->slug,
                'label' => __('Title'),
                'value' => $widget_saved_values['title_'.$lang->slug] ?? null,
                'info' => __('To show the highlighted text, place your word between this code {h}YourText{/h]')
            ]);

            $output .= Text::get([
                'name' => 'subtitle_'.$lang->slug,
                'label' => __('Subtitle'),
                'value' => $widget_saved_values['subtitle_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'button_text_'.$lang->slug,
                'label' => __('Button Text'),
                'value' => $widget_saved_values['button_text_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'button_url_'.$lang->slug,
                'label' => __('Button Url'),
                'value' => $widget_saved_values['button_url_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab


        $output .= Image::get([
            'name' => 'background_shape',
            'label' => __('Background Shape Image'),
            'value' => $widget_saved_values['background_shape'] ?? null,
            'dimensions' => '1115x1036 | 1100x1000 px'
        ]);

        $output .= Image::get([
            'name' => 'left_particle_shape',
            'label' => __('Background Left Particle Shape Image'),
            'value' => $widget_saved_values['left_particle_shape'] ?? null,
            'dimensions' => '~160x115 px'
        ]);

        $output .= Image::get([
            'name' => 'right_particle_shape',
            'label' => __('Background Right Particle Shape Image'),
            'value' => $widget_saved_values['right_particle_shape'] ?? null,
            'dimensions' => '~125x115 px'
        ]);

        $output .= Image::get([
            'name' => 'right_background_shape',
            'label' => __('Right Background Shape Image'),
            'value' => $widget_saved_values['right_background_shape'] ?? null,
            'dimensions' => '585x585 px or 1:1 ratio'
        ]);

        $output .= Image::get([
            'name' => 'right_foreground_image',
            'label' => __('Right Foreground Image'),
            'value' => $widget_saved_values['right_foreground_image'] ?? null,
            'dimensions' => '~480x528 px'
        ]);

        $output .= Image::get([
            'name' => 'right_floating_image_1',
            'label' => __('Right Floating Image 1'),
            'value' => $widget_saved_values['right_floating_image_1'] ?? null,
            'dimensions' => '85x85 px or 1:1 ratio'
        ]);

        $output .= Image::get([
            'name' => 'right_floating_image_2',
            'label' => __('Right Floating Image 2'),
            'value' => $widget_saved_values['right_floating_image_2'] ?? null,
            'dimensions' => '85x85 px or 1:1 ratio'
        ]);

        $output .= Image::get([
            'name' => 'right_floating_image_3',
            'label' => __('Right Floating Image 3'),
            'value' => $widget_saved_values['right_floating_image_3'] ?? null,
            'dimensions' => '85x85 px or 1:1 ratio'
        ]);


        // add padding option

        $output.= $this->section_id_and_class_fields($widget_saved_values);
        $output .= $this->padding_fields($widget_saved_values);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {

        $current_lang = GlobalLanguage::user_lang_slug();
        $title = SanitizeInput::esc_html($this->setting_item('title_'.$current_lang)) ?? '';
        $subtitle = SanitizeInput::esc_html($this->setting_item('subtitle_'.$current_lang)) ?? '';
        $button_text = SanitizeInput::esc_html($this->setting_item('button_text_'.$current_lang)) ?? '';
        $button_url = SanitizeInput::esc_url($this->setting_item('button_url_'.$current_lang)) ?? '';
        $button_icon = $this->setting_item('button_icon') ?? '';

        $bg_shape_image = $this->setting_item('background_shape') ?? '';
        $left_shape_image = $this->setting_item('left_particle_shape') ?? '';
        $right_shape_image = $this->setting_item('right_particle_shape') ?? '';

        $right_background_shape = $this->setting_item('right_background_shape') ?? '';
        $right_foreground_image = $this->setting_item('right_foreground_image') ?? '';
        $right_floating_image_1 = $this->setting_item('right_floating_image_1') ?? '';
        $right_floating_image_2 = $this->setting_item('right_floating_image_2') ?? '';
        $right_floating_image_3 = $this->setting_item('right_floating_image_3') ?? '';

        $section_id = $this->setting_item('section_id') ?? '';


        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $data = [
            'title'=> $title,
            'subtitle'=> $subtitle,
            'button_text'=> $button_text,
            'button_url'=> $button_url,
            'button_icon'=> $button_icon,
            'bg_shape_image'=> $bg_shape_image,
            'left_shape_image'=> $left_shape_image,
            'right_shape_image'=> $right_shape_image,

            'right_background_shape' => $right_background_shape,
            'right_foreground_image' => $right_foreground_image,
            'right_floating_image_1' => $right_floating_image_1,
            'right_floating_image_2' => $right_floating_image_2,
            'right_floating_image_3' => $right_floating_image_3,

            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'section_id'=> $section_id,
        ];

        return self::renderView('landlord.addons.header.HeaderOne',$data);

    }

    public function enable(): bool
    {
        return (bool) is_null(tenant());
    }

    public function addon_title()
    {
        return __('Header :01');
    }
}
