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

class HardworkArea extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/common/hardwork-area.png';
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
            'name' => 'image_one',
            'label' => __('Image One'),
            'value' => $widget_saved_values['image_one'] ?? null,
        ]);
        $output .= Text::get([
            'name' => 'image_one_url',
            'label' => __('Image One URL'),
            'value' => $widget_saved_values['image_one_url'] ?? null,
        ]);
        $output .= Image::get([
            'name' => 'image_two',
            'label' => __('Image Two'),
            'value' => $widget_saved_values['image_two'] ?? null,
        ]);
        $output .= Text::get([
            'name' => 'image_two_url',
            'label' => __('Image Two URL'),
            'value' => $widget_saved_values['image_two_url'] ?? null,
        ]);


        $output .= Image::get([
            'name' => 'bg_image',
            'label' => __('Background Image'),
            'value' => $widget_saved_values['bg_image'] ?? null,
        ]);



        // add padding option
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
        $image_one = $this->setting_item('image_one') ?? '';
        $image_one_url = $this->setting_item('image_one_url') ?? '';
        $image_two = $this->setting_item('image_two') ?? '';
        $image_two_url = $this->setting_item('image_two_url') ?? '';
        $bg_image = $this->setting_item('bg_image') ?? '';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));


        $data = [
            'title'=> $title,
            'subtitle'=> $subtitle,
            'image_one'=> $image_one,
            'image_one_url'=> $image_one_url,
            'image_two'=> $image_two,
            'image_two_url'=> $image_two_url,
            'bg_image'=> $bg_image,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
        ];

        return self::renderView('tenant.common.hard-work-area',$data);

    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Hard work Area');
    }
}
