<?php

namespace Plugins\WidgetBuilder\Widgets;
use App\Helpers\SanitizeInput;
use App\Models\Language;
use Plugins\WidgetBuilder\Traits\LanguageFallbackForWidgetBuilder;
use Plugins\WidgetBuilder\WidgetBase;
use Mews\Purifier\Facades\Purifier;

class TenantContactInfoWidget extends WidgetBase
{
    use LanguageFallbackForWidgetBuilder;

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

            $widget_title = $widget_saved_values['widget_title'] ?? '';
            $location =  $widget_saved_values['location'] ?? '';
            $phone =  $widget_saved_values['phone'] ?? '';
            $email =  $widget_saved_values['email'] ?? '';

            $output .= '<div class="form-group"><input type="text" name="widget_title"  class="form-control" placeholder="' . __('Widget Title') . '" value="'. SanitizeInput::esc_html($widget_title) .'"></div>';
            $output .= '<div class="form-group"><input type="text" name="location" class="form-control" placeholder="' . __('Location') . '" value="'. SanitizeInput::esc_html($location) .'"></div>';
            $output .= '<div class="form-group"><input type="text" name="phone"  class="form-control" placeholder="' . __('Phone') . '" value="'. SanitizeInput::esc_html($phone) .'"></div>';
            $output .= '<div class="form-group"><input type="email" name="email" class="form-control" placeholder="' . __('Email') . '" value="'. SanitizeInput::esc_html($email) .'"></div>';

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        // TODO: Implement frontend_render() method.
        $widget_saved_values = $this->get_settings();
        $widget_title =  SanitizeInput::esc_html($this->setting_item('widget_title') ?? '');

        $location =  SanitizeInput::esc_html($this->setting_item('location') ?? '');
        $phone =  SanitizeInput::esc_html($this->setting_item('phone') ?? '');
        $email = SanitizeInput::esc_html($this->setting_item('email') ?? '');


  return <<<HTML
 <div class="col-lg-3 col-md-6 col-sm-6">
     <div class="footer-widget">

    <h4 class="widget-title">{$widget_title}</h4>
        <ul class="contact_info_list">

             <li class="single-info-item">
                    <div class="icon">
                       <i class="las la-home"></i>
                    </div>
                    <div class="details">
                      {$location}
                    </div>
                </li>


               <li class="single-info-item">
                    <div class="icon">
                       <i class="las la-phone-volume"></i>
                    </div>
                    <div class="details">
                      {$phone}
                    </div>
                </li>

                <li class="single-info-item">
                    <div class="icon">
                       <i class="las la-envelope-open"></i>
                    </div>
                    <div class="details">
                      {$email}
                    </div>
                </li>

               </ul>
       </div>
       </div>


HTML;

    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function widget_title()
    {
        return __('Tenant Contact Info');
    }

}
