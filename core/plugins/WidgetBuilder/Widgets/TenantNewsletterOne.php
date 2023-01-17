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

class TenantNewsletterOne extends WidgetBase
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
        $csrf = csrf_token();


return <<<HTML

    <div class="col-lg-8 offset-lg-1 col-md-8">
            <div class="footer-widget widget widget_subscribe wow animate__animated animate__fadeInUp animated">
                <div class="header-content">
                    <h4 class="title">{$widget_title}</h4>
                    <p>{$widget_subtitle}</p>
                </div>
                <form class="subscribe-form" action="{$form_action}">
                 <input type="hidden" name="_token" value="{$csrf}">
                    <div class="form-message-show"></div>
                    <div class="form-group">
                        <input type="email" class="form-control email" placeholder="Your email address">
                    </div>
                    <button type="submit" class="submit-btn style-01 newsletter-submit-btn"><i class="flaticon-send"></i></button>
                </form>
            </div>
        </div>

HTML;


    }

    public function enable(): bool
    {
        return !is_null(tenant()) ? true : false;
    }

    public function widget_title(){
        return __('Theme 1: Tenant Newsletter(01)');
    }
}
