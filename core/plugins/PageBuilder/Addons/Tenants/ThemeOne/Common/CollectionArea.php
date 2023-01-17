<?php

namespace Plugins\PageBuilder\Addons\Tenants\ThemeOne\Common;

use App\Helpers\SanitizeInput;
use Modules\Campaign\Entities\Campaign;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class CollectionArea extends PageBuilderBase
{
    public function preview_image()
    {
        return 'Tenant/common/quality-work-01.png';
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
            'value' => $widget_saved_values['title'] ?? '',
        ]);

        $output .= Text::get([
            'name' => 'subtitle',
            'label' => __('Subtitle'),
            'value' => $widget_saved_values['subtitle'] ?? '',
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
            'name' => 'left_image',
            'label' => __('Left Image'),
            'value' => $widget_saved_values['left_image'] ?? null,
        ]);

        $output .= Image::get([
            'name' => 'right_image',
            'label' => __('Right Image'),
            'value' => $widget_saved_values['right_image'] ?? null,
        ]);

        // add padding option
        $output.= $this->padding_fields($widget_saved_values);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $title = SanitizeInput::esc_html($this->setting_item('title')) ?? '';
        $subtitle = SanitizeInput::esc_html($this->setting_item('subtitle')) ?? '';
        $button_text = SanitizeInput::esc_html($this->setting_item('button_text')) ?? '';
        $campaign = SanitizeInput::esc_html($this->setting_item('campaign')) ?? '';
        $button_url = SanitizeInput::esc_url($this->setting_item('button_url')) ?? '';
        $left_image = $this->setting_item('left_image') ?? '';
        $right_image = $this->setting_item('right_image') ?? '';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $data = [
            'title'=> $title,
            'subtitle'=> $subtitle,
            'button_text'=> $button_text,
            'campaign'=> $campaign,
            'button_url'=> $button_url,
            'left_image'=> $left_image,
            'right_image'=> $right_image,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
        ];

        return self::renderView('tenant.theme_one.common.collection-area',$data);
    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Theme 1: Collection(01)');
    }
}
