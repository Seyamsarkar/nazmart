<?php

namespace Plugins\WidgetBuilder\Widgets\Tenants\ThemeOne;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\WidgetBuilder\Traits\LanguageFallbackForWidgetBuilder;
use Plugins\WidgetBuilder\WidgetBase;

class FooterAbout extends WidgetBase
{
    use LanguageFallbackForWidgetBuilder;

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= Image::get([
            'name' => 'image',
            'label' => __('Image / Logo'),
            'value' => $widget_saved_values['image'] ?? null
        ]);

        $output .= Textarea::get([
            'name' => 'description',
            'label' => __('Description'),
            'value' => $widget_saved_values['description'] ?? null
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        // TODO: Implement frontend_render() method.
        $widget_saved_values = $this->get_settings();
        $widget_title = SanitizeInput::esc_html($widget_saved_values['description'] ?? '');
        $widget_logo = $widget_saved_values['image'] ?? '';

        $image = render_image_markup_by_attachment_id($widget_logo, '', 'full');

        $markup = $this->widget_column_start();
        $markup .= '<div class="footer-widget widget center-text">
                            <div class="about_us_widget">
                                <a href="index.html" class="footer-logo">
                                    '.$image.'
                                </a>
                            </div>
                            <div class="footer-inner mt-4">
                                <div class="about-us-para"> '. $widget_title .' </div>
                            </div>
                        </div>';
        $markup .= $this->widget_column_end();

        return $markup;
    }

    public function enable(): bool
    {
        return (bool)!is_null(tenant());
    }

    public function widget_title()
    {
        return __('Footer About: TH-01');
    }

}
