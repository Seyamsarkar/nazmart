<?php

namespace Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common;

use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use Modules\Campaign\Entities\Campaign;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class DealArea extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/common/hardwork-area.png';
    }

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
        $output .= Textarea::get([
            'name' => 'short_description',
            'label' => __('Short Description'),
            'value' => $widget_saved_values['short_description'] ?? null,
        ]);

        $campaigns = Campaign::where('status', 'publish')->pluck('title', 'id')->toArray();
        $output .= Select::get([
            'name' => 'campaign',
            'label' => __('Select Campaign'),
            'options' => $campaigns,
            'value' => $widget_saved_values['campaign'] ?? '',
        ]);

        $output .= Text::get([
            'name' => 'button_text',
            'label' => __('Button Text'),
            'value' => $widget_saved_values['button_text'] ?? '',
        ]);

        $output .= Text::get([
            'name' => 'button_url',
            'label' => __('Button URL'),
            'value' => $widget_saved_values['button_url'] ?? '',
            'info' => 'Leave it empty if you do not use external link for the button'
        ]);

        $output .= Image::get([
            'name' => 'bg_image',
            'label' => __('Background Image'),
            'value' => $widget_saved_values['bg_image'] ?? null,
        ]);


        // add padding option
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $title = SanitizeInput::esc_html($this->setting_item('title')) ?? '';
        $short_description = SanitizeInput::esc_html($this->setting_item('short_description')) ?? '';
        $button_text = SanitizeInput::esc_html($this->setting_item('button_text')) ?? '';
        $button_url = SanitizeInput::esc_url($this->setting_item('button_url')) ?? '';
        $campaign = $this->setting_item('campaign') ?? '';
        $bg_image = $this->setting_item('bg_image') ?? '';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));


        $data = [
            'title'=> $title,
            'short_description'=> $short_description,
            'button_text'=> $button_text,
            'button_url'=> $button_url,
            'campaign'=> $campaign,
            'bg_image'=> $bg_image,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
        ];

        return self::renderView('tenant.theme_one.common.deal-area',$data);

    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Theme 1: Deal Area');
    }
}
