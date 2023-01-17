<?php

namespace Plugins\PageBuilder\Addons\Tenants\ThemeOne\Blog;

use App\Helpers\SanitizeInput;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;
use Plugins\PageBuilder\Fields\NiceSelect;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;

class BlogOne extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/blog/blog-01.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        $categories = BlogCategory::where(['status' => 1])->get()->mapWithKeys(function ($item){
            return [$item->id => $item->title];
        })->toArray();

        $output .= NiceSelect::get([
            'multiple' => true,
            'name' => 'categories',
            'label' => __('Select Category'),
            'options' => $categories,
            'value' => $widget_saved_values['categories'] ?? null,
            'info' => __('you can select your desired blog categories or leave it empty')
        ]);

        $output .= Select::get([
            'name' => 'order_by',
            'label' => __('Order By'),
            'options' => [
                'id' => __('ID'),
                'created_at' => __('Date'),
            ],
            'value' => $widget_saved_values['order_by'] ?? null,
            'info' => __('set order by')
        ]);
        $output .= Select::get([
            'name' => 'order',
            'label' => __('Order'),
            'options' => [
                'asc' => __('Accessing'),
                'desc' => __('Decreasing'),
            ],
            'value' => $widget_saved_values['order'] ?? null,
            'info' => __('set order')
        ]);
        $output .= Number::get([
            'name' => 'items',
            'label' => __('Items'),
            'value' => $widget_saved_values['items'] ?? null,
            'info' => __('enter how many item you want to show in frontend'),
        ]);

        // add padding option
        $output .= $this->padding_fields($widget_saved_values);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $category = $this->setting_item('categories');
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by')) ?? 'id';
        $order = SanitizeInput::esc_html($this->setting_item('order')) ?? 'asc';
        $items = SanitizeInput::esc_html($this->setting_item('items'));
        $title = SanitizeInput::esc_html($this->setting_item('title'));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $blogs = Blog::where('status', 1);

        if(!empty($category)) {
            $blogs->whereIn('category_id',$category);
        }

        if (!empty($items)) {
            $blogs =  $blogs->orderBy($order_by,$order)->take($items)->get();
        } else {
            $blogs =  $blogs->orderBy($order_by,$order)->take(4)->get();
        }

        $data = [
            'title'=> $title,
            'blogs'=> $blogs,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
        ];

        return self::renderView('tenant.theme_one.blog.blog-one',$data);
    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Theme 1: Blog(01)');
    }
}
