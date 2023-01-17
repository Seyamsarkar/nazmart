<?php

namespace Plugins\PageBuilder\Addons\Tenants\Common;

use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class SupportAreaOne extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/common/support-area-01.png';
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
            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Image::get([
            'name' => 'image',
            'label' => __('Image'),
            'value' => $widget_saved_values['image'] ?? null,
        ]);


        //repeater
        $output .= Repeater::get([
            'multi_lang' => true,
            'settings' => $widget_saved_values,
            'id' => 'support_area_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title',
                    'label' => __('Inner Title')
                ],
                [
                    'type' => RepeaterField::ICON_PICKER,
                    'name' => 'repeater_icon',
                    'label' => __('Inner Icon')
                ],
            ]
        ]);

        $output .= Select::get([
            'name' => 'section_alignment',
            'label' => __('Section Alignment'),
            'options' => [
                'left' => __('Left'),
                'right' => __('Right'),
            ],
            'value' => $widget_saved_values['section_alignment'] ?? null,
            'info' => __('set where you want to show this')
        ]);

        $output .= Select::get([
            'name' => 'heading_style',
            'label' => __('Heading Style'),
            'options' => [
                'title' => __('Style One'),
                'heading-title' => __('Style Two'),
            ],
            'value' => $widget_saved_values['heading_style'] ?? null,
            'info' => __('you can set heading style from here')
        ]);

        $output .= Select::get([
            'name' => 'button_color',
            'label' => __('Button Color'),
            'options' => [
                'style' => __('Style One'),
                'style-01' => __('Style Two'),
                'style-02' => __('Style Three'),
                'style-03' => __('Style Four'),
            ],
            'value' => $widget_saved_values['button_color'] ?? null,
            'info' => __('you can set button color from here')
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
        $button_text = $this->setting_item('button_text_'.$current_lang) ?? '';
        $button_url = $this->setting_item('button_url_'.$current_lang) ?? '';
        $heading_style = $this->setting_item('heading_style') ?? '';
        $section_alignment = $this->setting_item('section_alignment') ?? '';
        $button_color = $this->setting_item('button_color') ?? '';
        $image = $this->setting_item('image') ?? '';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $repeater_data = $this->setting_item('support_area_repeater');

        $data = [
            'title'=> $title,
            'subtitle'=> $subtitle,
            'button_text'=> $button_text,
            'button_url'=> $button_url,
            'section_alignment'=> $section_alignment,
            'heading_style'=> $heading_style,
            'button_color'=> $button_color,
            'image'=> $image,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'repeater_data'=> $repeater_data,
        ];

        return self::renderView('tenant.common.support-area-one',$data);

    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Support Area : 01');
    }
}
