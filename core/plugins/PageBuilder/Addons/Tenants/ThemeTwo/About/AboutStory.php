<?php

namespace Plugins\PageBuilder\Addons\Tenants\ThemeTwo\About;

use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\IconPicker;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class AboutStory extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/common/how-it-work-01.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

            $output .= Text::get([
                'name' => 'primary_title',
                'label' => __('Primary Title'),
                'value' => $widget_saved_values['primary_title'] ?? null,
            ]);

            $output .= Textarea::get([
                'name' => 'primary_description',
                'label' => __('Primary Description'),
                'value' => $widget_saved_values['primary_description'] ?? null,
            ]);

            $output .= Image::get([
                'name' => 'primary_image',
                'label' => __('Primary Image'),
                'value' => $widget_saved_values['primary_image'] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'secondary_title_one',
                'label' => __('Secondary Title One'),
                'value' => $widget_saved_values['secondary_title_one'] ?? null,
                'info' => 'If you do not want to use it, leave it empty'
            ]);

            $output .= Textarea::get([
                'name' => 'secondary_description_one',
                'label' => __('Secondary Description One'),
                'value' => $widget_saved_values['secondary_description_one'] ?? null,
                'info' => 'If you do not want to use it, leave it empty'
            ]);

        $output .= Text::get([
            'name' => 'secondary_title_two',
            'label' => __('Secondary Title Two'),
            'value' => $widget_saved_values['secondary_title_two'] ?? null,
            'info' => 'If you do not want to use it, leave it empty'
        ]);

        $output .= Textarea::get([
            'name' => 'secondary_description_two',
            'label' => __('Secondary Description Two'),
            'value' => $widget_saved_values['secondary_description_two'] ?? null,
            'info' => 'If you do not want to use it, leave it empty'
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
        $primary_title = SanitizeInput::esc_html($this->setting_item('primary_title')) ?? '';
        $secondary_title_one = SanitizeInput::esc_html($this->setting_item('secondary_title_one')) ?? '';
        $secondary_title_two = SanitizeInput::esc_html($this->setting_item('secondary_title_two')) ?? '';

        $primary_description = SanitizeInput::esc_html($this->setting_item('primary_description')) ?? '';
        $secondary_description_one = SanitizeInput::esc_html($this->setting_item('secondary_description_one')) ?? '';
        $secondary_description_two = SanitizeInput::esc_html($this->setting_item('secondary_description_two')) ?? '';

        $primary_image = $this->setting_item('primary_image') ?? '';

        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));


        $data = [
            'primary_title'=> $primary_title,
            'secondary_title_one'=> $secondary_title_one,
            'secondary_title_two'=> $secondary_title_two,
            'primary_description'=> $primary_description,
            'secondary_description_one'=> $secondary_description_one,
            'secondary_description_two'=> $secondary_description_two,
            'primary_image'=> $primary_image,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
        ];

        return self::renderView('tenant.theme_one.about.about_story',$data);
    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Theme 1: About Story(01)');
    }
}
