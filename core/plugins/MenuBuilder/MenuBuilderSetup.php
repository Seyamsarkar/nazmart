<?php


namespace Plugins\MenuBuilder;


class MenuBuilderSetup extends MenuBuilderBase
{
     public static function Instance(){
        return new self();
    }

    public static function multilang(){
        return true;
    }


    public function  static_pages_list()
    {
        // TODO: Implement static_pages_list() method.
        return [];
    }

    function register_dynamic_menus()
    {
        // TODO: Implement register_dynamic_menus() method.
        return [

            'pages' => [
                'model' => 'App\Models\Page',
                'name' => 'pages_page_[lang]_name',
                'route' => 'tenant.dynamic.page',
                'route_params' => ['slug'],
                'title_param' => 'title',
                'query' => 'no_lang' //old_lang|new_lang
            ],
            'blogs' => [
                'model' => 'Modules\Blog\Entities\Blog',
                'name' => 'blog_page_[lang]_name',
                'route' => 'tenant.frontend.blog.single',
                'route_params' => ['id','slug'],
                'title_param' => 'title',
                'query' => 'no_lang' //old_lang|new_lang
            ],
        ];
    }

}
