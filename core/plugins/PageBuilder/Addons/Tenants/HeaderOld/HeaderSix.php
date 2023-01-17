<?php

namespace Plugins\PageBuilder\Addons\Tenants\Header;

use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class HeaderSix extends PageBuilderBase
{

    public function preview_image()
    {
       return 'Tenant/Home/header-06.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $output .= $this->admin_language_tab();
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

            $output .= Text::get([
                'name' => 'subtitle_'.$lang->slug,
                'label' => __('Subtitle'),
                'value' => $widget_saved_values['subtitle_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'button_text_one_'.$lang->slug,
                'label' => __('Button Text One'),
                'value' => $widget_saved_values['button_text_one_'.$lang->slug] ?? null,
            ]);
            $output .= Text::get([
                'name' => 'button_url_one_'.$lang->slug,
                'label' => __('Button URL One'),
                'value' => $widget_saved_values['button_url_one_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'button_text_two_'.$lang->slug,
                'label' => __('Button Text Two'),
                'value' => $widget_saved_values['button_text_two_'.$lang->slug] ?? null,
            ]);
            $output .= Text::get([
                'name' => 'button_url_two_'.$lang->slug,
                'label' => __('Button URL Two'),
                'value' => $widget_saved_values['button_url_two_'.$lang->slug] ?? null,
            ]);


            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end();



        $output .= Image::get([
            'name' => 'image',
            'label' => __('Image'),
            'value' => $widget_saved_values['image'] ?? null,
        ]);

        $output .= Image::get([
            'name' => 'bg_image',
            'label' => __('Background Image'),
            'value' => $widget_saved_values['bg_image'] ?? null,
        ]);

        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'id' => 'header_six_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'repeater_image',
                    'label' => __('Image')
                ],

                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_url',
                    'label' => __('Image URL')
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
        $current_lang = LanguageHelper::user_lang_slug();
        $title = $this->setting_item('title_'.$current_lang) ?? '';
        $subtitle = $this->setting_item('subtitle_'.$current_lang) ?? '';
        $button_text_one = $this->setting_item('button_text_one_'.$current_lang) ?? '';
        $button_url_one = $this->setting_item('button_url_one_'.$current_lang) ?? '';
        $button_text_two = $this->setting_item('button_text_two_'.$current_lang) ?? '';
        $button_url_two = $this->setting_item('button_url_two_'.$current_lang) ?? '';
        $image = $this->setting_item('image') ?? '';
        $bg_image = $this->setting_item('bg_image') ?? '';
        $repeater_data = $this->setting_item('header_six_repeater');

        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $data = [
                 'title'=>$title,
                 'subtitle'=>$subtitle,
                 'button_text_one'=> $button_text_one,
                 'button_url_one'=>$button_url_one,
                 'button_text_two'=>$button_text_two,
                 'button_url_two'=>$button_url_two,
                 'image'=>$image,
                 'bg_image'=>$bg_image,
                 'repeater_data'=>$repeater_data,
                 'padding_top'=>$padding_top,
                 'padding_bottom'=>$padding_bottom
            ];

        return self::renderView('tenant.header.header-six',$data);

    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Header (06)');
    }



}
