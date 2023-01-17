<?php

namespace Plugins\PageBuilder\Addons\Tenants\Header;

use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\IconPicker;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\Traits\GlobalAdminFields;
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

            $output .= Text::get([
                'name' => 'subtitle_'.$lang->slug,
                'label' => __('Subtitle'),
                'value' => $widget_saved_values['subtitle_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab


        $output .= Image::get([
            'name' => 'apple_store_image',
            'label' => __('Apple Store Image'),
            'value' => $widget_saved_values['apple_store_image'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'apple_store_url',
            'label' => __('Apple Store URL'),
            'value' => $widget_saved_values['apple_store_url'] ?? null,
        ]);

        $output .= Image::get([
            'name' => 'play_store_image',
            'label' => __('Play Store Image'),
            'value' => $widget_saved_values['play_store_image'] ?? null,
        ]);
        $output .= Text::get([
            'name' => 'play_store_url',
            'label' => __('Play Store Url'),
            'value' => $widget_saved_values['play_store_url'] ?? null,
        ]);

        $output .= Image::get([
            'name' => 'inner_bg_one',
            'label' => __('Inner Background Image One'),
            'value' => $widget_saved_values['inner_bg_one'] ?? null,
        ]);


        $output .= Image::get([
            'name' => 'right_bg_image',
            'label' => __('Right Background Image'),
            'value' => $widget_saved_values['right_bg_image'] ?? null,
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
        $apple_store_image = $this->setting_item('apple_store_image') ?? '';
        $apple_store_url = $this->setting_item('apple_store_url') ?? '';

        $play_store_image = $this->setting_item('play_store_image') ?? '';
        $play_store_url = $this->setting_item('play_store_url') ?? '';

        $inner_bg_one = $this->setting_item('inner_bg_one') ?? '';
        $right_bg_image = $this->setting_item('right_bg_image') ?? '';

        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $data = [
                 'title'=>$title,
                 'subtitle'=>$subtitle,
                 'apple_store_image'=>$apple_store_image,
                 'apple_store_url'=>$apple_store_url,
                 'play_store_image'=>$play_store_image,
                 'play_store_url'=>$play_store_url,
                 'inner_bg_one'=>$inner_bg_one,
                 'right_bg_image'=>$right_bg_image,
                 'padding_top'=>$padding_top,
                 'padding_bottom'=>$padding_bottom
            ];

        return self::renderView('tenant.header.home-one-header-one',$data);

    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('HM1: Header(01)');
    }



}
