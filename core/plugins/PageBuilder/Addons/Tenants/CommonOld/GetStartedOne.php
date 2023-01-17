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

class GetStartedOne extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/common/get-started-01.png';
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
                'name' => 'rating_title_'.$lang->slug,
                'label' => __('Rating Title'),
                'value' => $widget_saved_values['rating_title_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'rating_subtitle_'.$lang->slug,
                'label' => __('Rating Subtitle'),
                'value' => $widget_saved_values['rating_subtitle_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'content_title_'.$lang->slug,
                'label' => __('Content Title'),
                'value' => $widget_saved_values['content_title_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Image::get([
            'name' => 'bg_image',
            'label' => __('Background Image'),
            'value' => $widget_saved_values['bg_image'] ?? null,
        ]);

        //repeater
        $output .= Repeater::get([
            'multi_lang' => true,
            'settings' => $widget_saved_values,
            'id' => 'get_started_one_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title',
                    'label' => __('Title')
                ],

                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title_url',
                    'label' => __('Title URL')
                ],

                [
                    'type' => RepeaterField::TEXTAREA,
                    'name' => 'repeater_subtitle',
                    'label' => __('Subtitle')
                ],

                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'repeater_image',
                    'label' => __('Image')
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
        $rating_title = $this->setting_item('rating_title_'.$current_lang) ?? '';
        $rating_subtitle = $this->setting_item('rating_subtitle_'.$current_lang) ?? '';
        $content_title = $this->setting_item('content_title_'.$current_lang) ?? '';
        $bg_image = $this->setting_item('bg_image') ?? '';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $repeater_data = $this->setting_item('get_started_one_repeater');

        $data = [
            'rating_title'=> $rating_title,
            'rating_subtitle'=> $rating_subtitle,
            'content_title'=> $content_title,
            'bg_image'=> $bg_image,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'repeater_data'=> $repeater_data,
        ];

        return self::renderView('tenant.common.get-started-one',$data);

    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Get Started: 01');
    }
}
