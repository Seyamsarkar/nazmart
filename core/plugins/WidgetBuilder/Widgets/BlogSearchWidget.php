<?php


namespace Plugins\WidgetBuilder\Widgets;
use App\Helpers\SanitizeInput;
use App\Models\Language;
use Plugins\WidgetBuilder\Traits\LanguageFallbackForWidgetBuilder;
use Plugins\WidgetBuilder\WidgetBase;

class BlogSearchWidget extends WidgetBase
{
    use LanguageFallbackForWidgetBuilder;

    public function admin_render()
    {
        // TODO: Implement admin_render() method.
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $widget_title = $widget_saved_values['widget_title'] ?? '';
        $output .= '<div class="form-group"><input type="text" name="widget_title" class="form-control" placeholder="' . __('Widget Title') . '" value="' . $widget_title . '"></div>';

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        // TODO: Implement frontend_render() method.
        $widget_saved_values = $this->get_settings();

        $widget_title = SanitizeInput::esc_html($this->setting_item('widget_title') ?? '');

        $output = $this->widget_before(); //render widget before content

        if (!empty($widget_title)) {
            $output .= '<h4 class="widget-title">' . SanitizeInput::esc_html($widget_title) . '</h4>';
        }
        $output .='<div class=" blog-widget widget"><div class="widget_search">
                        <form action="'.route(route_prefix().'frontend.blog.search').'" method="get" class="search-form">
                            <div class="form-group">
                                <input type="text" class="form-control" name="search" placeholder="Search...">
                            </div>
                            <button class="submit-btn form-btn-1" type="submit"><i class="las la-search"></i></button>
                        </form>
                    </div>
                    </div>';

        $output .= $this->widget_after(); // render widget after content

        return $output;
    }


    public function widget_title()
    {
        // TODO: Implement widget_title() method.
        return __('Blog Search');
    }
}
