<?php

namespace Plugins\WidgetBuilder\Widgets;


use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\WidgetBuilder\WidgetBase;
use function __;
use function get_user_lang;
use function render_image_markup_by_attachment_id;
use function url;

class TenantAboutUsWidgetTwo extends WidgetBase
{
    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $image_val = $widget_saved_values['site_logo'] ?? '';
        $image_preview = '';
        $image_field_label = __('Upload Image');
        if (!empty($widget_saved_values)) {
            $image_markup = render_image_markup_by_attachment_id($widget_saved_values['site_logo']);
            $image_preview = '<div class="attachment-preview"><div class="thumbnail"><div class="centered">' . $image_markup . '</div></div></div>';
            $image_field_label = __('Change Image');
        }

        $output .= '<div class="form-group"><label for="site_logo"><strong>' . __('Logo') . '</strong></label>';
        $output .= '<div class="media-upload-btn-wrapper"><div class="img-wrap">' . $image_preview . '</div><input type="hidden" name="site_logo" value="' . $image_val . '">';
        $output .= '<button type="button" class="btn btn-info btn-xs media_upload_form_btn" data-btntitle="Select Image" data-modaltitle="Upload Image" data-toggle="modal" data-target="#media_upload_modal">';
        $output .= $image_field_label . '</button></div>';
        $output .= '<small class="form-text text-muted">' . __('allowed image format: jpg,jpeg,png. Recommended image size 160x50') . '</small></div>';
        //start multi langual tab option

        //repeater
        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'id' => 'about_us_two_widget',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_text',
                    'label' => __('Text')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_icon_url',
                    'label' => __('Icon URL')
                ],
                [
                    'type' => RepeaterField::ICON_PICKER,
                    'name' => 'repeater_icon',
                    'label' => __('Icon')
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
        $widget_saved_values = $this->get_settings();
        $image_val = $widget_saved_values['site_logo'] ?? '';
        $foot_logo1 = render_image_markup_by_attachment_id($image_val, 'footer-logo') ;
        $root_url = url('/');
        $repeader_data = $widget_saved_values['about_us_two_widget'] ?? [];

        $social_markup = '';
        foreach ($repeader_data['repeater_icon_url_'] as $key => $url){
            $repeater_url = SanitizeInput::esc_url($url) ?? '';
            $repeater_text = SanitizeInput::esc_html($repeader_data['repeater_text_'][$key]) ?? '';
            $repeater_icon = $repeader_data['repeater_icon_'][$key] ?? '';

      $social_markup .= '<span class="contact-item">
                            <span>
                                <i class="'.$repeater_icon.'"></i> </span> <a href="#"> '.$repeater_text.' </a>
                            </span>';
        }

return '<div class="col-lg-3 col-md-6 col-sm-6 mt-4">
                        <div class="footer-widget widget">
                            <div class="about_us_widget">
                                <a href="'.$root_url.'" class="footer-logo">
                                    '.$foot_logo1.'
                                </a>
                            </div>
                            <div class="footer-inner mt-4">
                                <div class="footer-contact">
                                    '.$social_markup.'
                                </div>
                            </div>
                        </div>
                    </div>';
}

    public function enable(): bool
    {
        return !is_null(tenant()) ? true : false;
    }

    public function widget_title(){
        return __('Tenant About Us : 02');
    }

}
