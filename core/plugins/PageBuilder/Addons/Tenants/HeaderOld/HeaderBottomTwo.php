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

class HeaderBottomTwo extends PageBuilderBase
{

    public function preview_image()
    {
       return 'Tenant/Home/home-two-header-bottom-two.png';
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
                'name' => 'title_url_'.$lang->slug,
                'label' => __('Title Url'),
                'value' => $widget_saved_values['title_url_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'title_two_'.$lang->slug,
                'label' => __('Title Two'),
                'value' => $widget_saved_values['title_two_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'title_two_url_'.$lang->slug,
                'label' => __('Title Two Url'),
                'value' => $widget_saved_values['title_two_url_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= IconPicker::get([
            'name' => 'icon_one',
            'label' => __('Icon One'),
            'value' => $widget_saved_values['icon_one'] ?? null,
        ]);

        $output .= IconPicker::get([
            'name' => 'icon_two',
            'label' => __('Icon Two'),
            'value' => $widget_saved_values['icon_two'] ?? null,
        ]);


        $output .= Image::get([
            'name' => 'image',
            'label' => __('Image'),
            'value' => $widget_saved_values['image'] ?? null,
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
        $title_url = $this->setting_item('title_url_'.$current_lang) ?? '';
        $title_two = $this->setting_item('title_two_'.$current_lang) ?? '';
        $title_two_url = $this->setting_item('title_two_url_'.$current_lang) ?? '';
        $icon_one = $this->setting_item('icon_one') ?? '';
        $icon_two = $this->setting_item('icon_two') ?? '';
        $image = $this->setting_item('image') ?? '';

        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $data = [
                 'title'=>$title,
                 'title_url'=> $title_url,
                 'title_two'=> $title_two,
                 'title_two_url'=> $title_two_url,
                 'icon_one'=>$icon_one,
                 'icon_two'=>$icon_two,
                 'image'=>$image,
                 'padding_top'=>$padding_top,
                 'padding_bottom'=>$padding_bottom
            ];

        return self::renderView('tenant.header.home-two-header-bottom-two',$data);

    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('HM2: Header Bottom (02)');
    }



}
