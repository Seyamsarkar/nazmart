<?php


namespace Plugins\WidgetBuilder\Widgets;

use App\Helpers\SanitizeInput;
use App\Models\Language;
use Illuminate\Support\Str;
use Modules\Blog\Entities\BlogCategory;
use Plugins\PageBuilder\Fields\Number;
use Plugins\WidgetBuilder\WidgetBase;
use function __;
use function get_user_lang;

class BlogCategoryWidget extends WidgetBase
{

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $widget_title = $widget_saved_values['widget_title'] ?? '';
        $output .= '<div class="form-group"> <label>' .__('Widget Title').' </label><input type="text" name="widget_title" class="form-control" placeholder="' . __('Widget Title') . '" value="' . $widget_title . '"></div>';

        $output .= Number::get([
            'name' => 'category_items',
            'label' => __('Category Items'),
            'value' => $widget_saved_values['category_items'] ?? null,
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $settings = $this->get_settings();
        $widget_title = SanitizeInput::esc_html($settings['widget_title'] ?? '');
        $category_items = $settings['category_items'] ?? '';
        $blog_categories = BlogCategory::where('status',1)->orderBy('id', 'DESC')->take($category_items)->get();
        $category_markup = '';
        foreach ($blog_categories as $item){
            $title = $item->title;
            $slug = $item->slug;
            $url = route(route_prefix().'frontend.blog.category', ['id' => $item->id,'any' => $slug]);


 $category_markup.= <<<LIST
    <li class="single-item"><a href="{$url}" class="wrap">{$title}</a></li>
LIST;

}

 return <<<HTML
    <div class="widget sidebar-widget">
        <div class="category style-02 v-02">
            <h4 class="widget-title style-04">{$widget_title}</h4>
            <ul class="widget-category-list">
                {$category_markup}
            </ul>
        </div>
    </div>



HTML;
    }



    public function widget_title()
    {
        return __('Blog Category : 01');
    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }
}
