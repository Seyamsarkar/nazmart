<?php

namespace Plugins\WidgetBuilder\Widgets\Tenants\ThemeOne;

use App\Helpers\SanitizeInput;
use App\Models\Menu;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\ImageGallery;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\WidgetBuilder\Traits\LanguageFallbackForWidgetBuilder;
use Plugins\WidgetBuilder\WidgetBase;

class FooterPaymentGatewaysWidget extends WidgetBase
{
    use LanguageFallbackForWidgetBuilder;

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'id' => 'footer_payment_gateway',
            'fields' => [
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'repeater_image',
                    'label' => __('Image')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_image_url',
                    'label' => __('Image URL')
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
        $repeater_data = $widget_saved_values['footer_payment_gateway'] ?? '';

        $li_markup = '';
        foreach (current($repeater_data) ?? [] as $key => $item) {
            $image = render_image_markup_by_attachment_id($repeater_data['repeater_image_'][$key]);
            $url = SanitizeInput::esc_url($repeater_data['repeater_image_url_'][$key]) ?? '#';


            $li_markup .= '<li class="list">
                                <a href="'. $url .'">' .$image.'</a>
                           </li>';
        }

        $markup = $this->widget_column_start();
        $markup .= ' <div class="footer-widget widget">
                            <div class="footer-inner">
                                <div class="footer-menu">
                                    <ul class="payment-list">
                                        '. $li_markup .'
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
        return __('Footer Bottom Payment Gateway Images: TH-01');
    }

}
