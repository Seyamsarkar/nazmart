<?php


namespace Plugins\WidgetBuilder\Widgets;

use App\Helpers\SanitizeInput;
use App\Models\Language;
use App\Models\MediaUploader;
use Modules\Service\Entities\ServiceCategory;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Number;
use Plugins\WidgetBuilder\WidgetBase;
use function __;
use function get_user_lang;

class TenantSidebarBannerWidget extends WidgetBase
{

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= Image::get([
            'name' => 'banner_image',
            'label' => __('Banner Image'),
            'value' => $widget_saved_values['banner_image'] ?? null,
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $settings = $this->get_settings();
        $banner_image = $settings['banner_image'] ?? '';

        $image = get_attachment_image_by_id($banner_image);

 return <<<HTML
    <div class="widget sidebar-widget">
        <img src="{$image['img_url']}" alt="">
    </div>
HTML;
    }



    public function widget_title()
    {
        return __('Blog Banner : 01');
    }
}
