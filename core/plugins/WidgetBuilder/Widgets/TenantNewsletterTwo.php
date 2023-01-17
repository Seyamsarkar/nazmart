<?php

namespace Plugins\WidgetBuilder\Widgets;


use App\Helpers\LanguageHelper;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\WidgetBuilder\Traits\LanguageFallbackForWidgetBuilder;
use Plugins\WidgetBuilder\WidgetBase;
use function __;
use function get_user_lang;
use function render_image_markup_by_attachment_id;
use function url;
use App\Helpers\SanitizeInput;

class TenantNewsletterTwo extends WidgetBase
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
                'label' => __('Title'),
                'value' => $widget_saved_values['title'] ?? null,
            ]);


            $output .= Text::get([
                'name' => 'subtitle',
                'label' => __('Subtitle'),
                'value' => $widget_saved_values['subtitle'] ?? null,
            ]);


        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $settings = $this->get_settings();
        $widget_title = SanitizeInput::esc_html($settings['title'] ?? '');
        $widget_subtitle =SanitizeInput::esc_html( $settings['description'] ?? '');
        $form_action = route('tenant.frontend.subscribe.newsletter');

        return '<div class="col-lg-3 col-md-6 col-sm-6 mt-4">
                        <div class="footer-widget widget">
                            <h4 class="widget-title fw-400">'.$widget_title.'</h4>
                            <div class="footer-inner mt-4">
                                <p class="subscribe-para"> '.$widget_subtitle.' </p>
                                <form action="'.$form_action.'" class="subscribe-form" method="POST">
                                    <div class="widget-form-single">
                                        <input class="form--control email" type="text" name="email" placeholder="'.__('Enter Your Email').'">
                                    </div>

                                    <div class="form-message-show mt-2"></div>

                                    <button type="submit" class="submit-btn w-100 mt-3 newsletter-submit-btn"> '.__('Subscribe').' </button>
                                </form>
                            </div>
                        </div>
                    </div>';
    }

    public function enable(): bool
    {
        return !is_null(tenant()) ? true : false;
    }

    public function widget_title(){
        return __('Theme 2: Tenant Newsletter(01)');
    }
}
