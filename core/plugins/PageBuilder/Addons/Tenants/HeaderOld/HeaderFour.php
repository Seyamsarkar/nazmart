<?php

namespace Plugins\PageBuilder\Addons\Tenants\Header;

use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;

class HeaderFour extends PageBuilderBase
{

    public function preview_image()
    {
       return 'Tenant/Home/header-04.png';
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
                'name' => 'button_text_'.$lang->slug,
                'label' => __('Button Text'),
                'value' => $widget_saved_values['button_text_'.$lang->slug] ?? null,
            ]);
            $output .= Text::get([
                'name' => 'button_url_'.$lang->slug,
                'label' => __('Button URL'),
                'value' => $widget_saved_values['button_url_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'right_title_'.$lang->slug,
                'label' => __('Button Right Title'),
                'value' => $widget_saved_values['right_title_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'inner_title_'.$lang->slug,
                'label' => __('Inner Title'),
                'value' => $widget_saved_values['inner_title_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'inner_description_'.$lang->slug,
                'label' => __('Inner Description'),
                'value' => $widget_saved_values['inner_description_'.$lang->slug] ?? null,
            ]);


            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end();


        $output .= Image::get([
            'name' => 'bg_image',
            'label' => __('Background Image'),
            'value' => $widget_saved_values['bg_image'] ?? null,
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
        $button_right_title = $this->setting_item('right_title_'.$current_lang) ?? '';
        $inner_title = $this->setting_item('inner_title_'.$current_lang) ?? '';
        $inner_description = $this->setting_item('inner_description_'.$current_lang) ?? '';
        $button_text = $this->setting_item('button_text_'.$current_lang) ?? '';
        $button_url = $this->setting_item('button_url_'.$current_lang) ?? '';
        $bg_image = $this->setting_item('bg_image') ?? '';

        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $data = [
                 'button_right_title'=>$button_right_title,
                 'inner_title'=>$inner_title,
                 'inner_description'=>$inner_description,
                 'button_text'=>$button_text,
                 'button_url'=>$button_url,
                 'bg_image'=>$bg_image,
                 'padding_top'=>$padding_top,
                 'padding_bottom'=>$padding_bottom
            ];

        return self::renderView('tenant.header.home-three-header-four',$data);

    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Header (04)');
    }



}
