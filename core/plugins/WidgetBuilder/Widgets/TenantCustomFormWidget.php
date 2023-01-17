<?php

namespace Plugins\WidgetBuilder\Widgets;
use App\Facades\GlobalLanguage;
use App\Helpers\FormBuilderCustom;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\Models\FormBuilder;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\WidgetBuilder\Traits\LanguageFallbackForWidgetBuilder;
use Plugins\WidgetBuilder\WidgetBase;
use function __;
use function get_user_lang;
use function render_image_markup_by_attachment_id;
use function url;

class TenantCustomFormWidget extends WidgetBase
{
    use LanguageFallbackForWidgetBuilder;
    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

            $output .= Text::get([
                'name' => 'widget_title',
                'label' => __('Widget Title'),
                'value' => $widget_saved_values['widget_title'] ?? null,
            ]);

        $output .= Select::get([
            'name' => 'custom_form_id',
            'label' => __('Select Custom Form'),
            'value' => $widget_saved_values['custom_form_id'] ?? null,
            'options' => FormBuilder::all()->pluck('title','id')->toArray(),
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $widget_saved_values = $this->get_settings();
        $widget_title = SanitizeInput::esc_html($widget_saved_values['widget_title']) ?? '';
        $custom_form_id = SanitizeInput::esc_html($widget_saved_values['custom_form_id'] ?? '');
        $custom_form_markup = FormBuilderCustom::render_form($custom_form_id,null,null,'submit-btn custom_submit_form_button',null);

        $output = $this->widget_before('no-padding-border'); //render widget before content

        $widget_title_markup = '';
        if (!empty($widget_title)){
            $widget_title_markup = ' <h3 class="title">'.$widget_title.'</h3>';
        }

        $output .= <<<HTML
<div class="attorney-contact-form-wrap">
   {$widget_title_markup}
    <div class="attorney-contact-form">
         {$custom_form_markup}
    </div>
</div>
HTML;

        $output .= $this->widget_after(); // render widget after content

        return $output;
    }

    public function enable(): bool
    {
        return !is_null(tenant()) ? true : false;
    }

    public function widget_title(){
        return __('Custom Form');
    }

}
