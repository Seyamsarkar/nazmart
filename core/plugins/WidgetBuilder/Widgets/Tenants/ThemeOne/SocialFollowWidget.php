<?php

namespace Plugins\WidgetBuilder\Widgets\Tenants\ThemeOne;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\WidgetBuilder\Traits\LanguageFallbackForWidgetBuilder;
use Plugins\WidgetBuilder\WidgetBase;

class SocialFollowWidget extends WidgetBase
{
    use LanguageFallbackForWidgetBuilder;

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'title',
            'label' => __('Widget Title'),
            'value' => $widget_saved_values['title'] ?? null
        ]);

        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'id' => 'footer_social_follow',
            'fields' => [
                [
                    'type' => RepeaterField::ICON_PICKER,
                    'name' => 'repeater_icon',
                    'label' => __('Icon')
                ],

                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_icon_url',
                    'label' => __('Icon URL')
                ],
            ]
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
        $widget_title = SanitizeInput::esc_html($widget_saved_values['title'] ?? '');
        $repeater_data = $widget_saved_values['footer_social_follow'] ?? '';

        $markup = $this->widget_column_start();

        $li_markup = '';
        foreach(current($repeater_data) as $key => $data)
        {
            $li_markup .= '<li class="lists">
                               <a class="facebook" href="'.SanitizeInput::esc_url($repeater_data['repeater_icon_url_'][$key]).'">
                                    <i class="'.$repeater_data['repeater_icon_'][$key].'"></i>
                               </a>
                           </li>';
        }

        $markup .= '<div class="footer-widget widget center-text">
                            <h4 class="widget-titile fw-500"> '.$widget_title.' </h4>
                            <div class="footer-inner mt-4">
                                <div class="footer-socials">
                                    <ul class="footer-social-list">
                                        '.$li_markup.'
                                    </ul>
                                </div>
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
        return __('Tenant Social Follow: TH-01');
    }

}
