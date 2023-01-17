<?php

namespace Database\Seeders\Tenant;

use App\Helpers\SanitizeInput;
use App\Mail\TenantCredentialMail;
use App\Models\Admin;
use App\Models\Language;
use App\Models\Menu;
use App\Models\Page;
use App\Models\PageBuilder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PageSeed extends Seeder
{
    public function run()
    {
        $page_data = new Page();
        $page_data->slug = Str::slug('home');
        $page_data->title = __('Home');
        $page_data->page_content = __('Home');
        $page_data->visibility = 0;
        $page_data->status = 1;
        $page_data->navbar_variant = '01';
        $page_data->footer_variant = '01';
        $page_data->page_builder = 1;
        $page_data->breadcrumb = 0;

        $Metas = [
            'title' => SanitizeInput::esc_html(''),
            'description' => SanitizeInput::esc_html('Demo meta desc'),
            'image' => null,
            //twitter
            'tw_image' => null,
            'tw_title' => SanitizeInput::esc_html('tw title'),
            'tw_description' => SanitizeInput::esc_html('tw desc'),
            //facebook
            'fb_image' => null,
            'fb_title' =>  SanitizeInput::esc_html('fb title'),
            'fb_description' =>  SanitizeInput::esc_html('fb desc'),
        ];

        $page_data->save();
        $page_data->metainfo()->create($Metas);

        // Uploading page layout
        if (session()->get('theme'))
        {
            $file_name = session()->get('theme').'/home-layout.json';
        } else {
            $file_name = 'theme-1/home-layout.json';
        }
        $this->upload_layout($file_name, $page_data->id);


        $page_data_2 = new Page();
        $page_data_2->slug = Str::slug('about');
        $page_data_2->title = __('About Us');
        $page_data_2->page_content = __('About us content');
        $page_data_2->visibility = 0;
        $page_data_2->status = 1;
        $page_data_2->navbar_variant = '01';
        $page_data_2->footer_variant = '01';
        $page_data_2->page_builder = 1;
        $page_data_2->breadcrumb = 1;

        $Metas_2 = [
            'title' => SanitizeInput::esc_html('Demo Meta Title'),
            'description' => SanitizeInput::esc_html('Demo meta desc'),
            'image' => null,
            //twitter
            'tw_image' => null,
            'tw_title' => SanitizeInput::esc_html('tw title'),
            'tw_description' => SanitizeInput::esc_html('tw desc'),
            //facebook
            'fb_image' => null,
            'fb_title' =>  SanitizeInput::esc_html('fb title'),
            'fb_description' =>  SanitizeInput::esc_html('fb desc'),
        ];

        $page_data_2->save();
        $page_data_2->metainfo()->create($Metas_2);

        // Uploading page layout
        $file_name = 'theme-1/about-layout.json';
        $this->upload_layout($file_name, $page_data_2->id);

        $page_data_5 = new Page();
        $page_data_5->slug = Str::slug('contact');
        $page_data_5->title = SanitizeInput::esc_html('Contact');
        $page_data_5->page_content = __('contact content');
        $page_data_5->visibility = 0;
        $page_data_5->status = 1;
        $page_data_5->navbar_variant = '01';
        $page_data_5->footer_variant = '01';
        $page_data_5->page_builder = 1;
        $page_data_5->breadcrumb = 1;

        $Metas_5 = [
            'title' => SanitizeInput::esc_html('Demo Meta Title'),
            'description' => SanitizeInput::esc_html('Demo meta desc'),
            'image' => null,
            //twitter
            'tw_image' => null,
            'tw_title' => SanitizeInput::esc_html('tw title'),
            'tw_description' => SanitizeInput::esc_html('tw desc'),
            //facebook
            'fb_image' => null,
            'fb_title' =>  SanitizeInput::esc_html('fb title'),
            'fb_description' =>  SanitizeInput::esc_html('fb desc'),
        ];

        $page_data_5->save();
        $page_data_5->metainfo()->create($Metas_5);

        // Uploading page layout
        $file_name = 'theme-1/contact-layout.json';
        $this->upload_layout($file_name, $page_data_5->id);


        $page_data_6 = new Page();
        $page_data_6->slug = Str::slug('shop');
        $page_data_6->title = SanitizeInput::esc_html('Shop');
        $page_data_6->page_content = __('contact content');
        $page_data_6->visibility = 0;
        $page_data_6->status = 1;
        $page_data_6->navbar_variant = '01';
        $page_data_6->footer_variant = '01';
        $page_data_6->page_builder = 0;
        $page_data_6->breadcrumb = 1;

        $Metas_6 = [
            'title' => SanitizeInput::esc_html('Demo Meta Title'),
            'description' => SanitizeInput::esc_html('Demo meta desc'),
            'image' => null,
            //twitter
            'tw_image' => null,
            'tw_title' => SanitizeInput::esc_html('tw title'),
            'tw_description' => SanitizeInput::esc_html('tw desc'),
            //facebook
            'fb_image' => null,
            'fb_title' =>  SanitizeInput::esc_html('fb title'),
            'fb_description' =>  SanitizeInput::esc_html('fb desc'),
        ];

        $page_data_6->save();
        $page_data_6->metainfo()->create($Metas_6);

        $page_data_7 = new Page();
        $page_data_7->slug = Str::slug('blog');
        $page_data_7->title = SanitizeInput::esc_html('Blog');
        $page_data_7->page_content = __('Blog content');
        $page_data_7->visibility = 0;
        $page_data_7->status = 1;
        $page_data_7->navbar_variant = '01';
        $page_data_7->footer_variant = '01';
        $page_data_7->page_builder = 0;
        $page_data_7->breadcrumb = 1;

        $Metas_7 = [
            'title' => SanitizeInput::esc_html('Demo Meta Title'),
            'description' => SanitizeInput::esc_html('Demo meta desc'),
            'image' => null,
            //twitter
            'tw_image' => null,
            'tw_title' => SanitizeInput::esc_html('tw title'),
            'tw_description' => SanitizeInput::esc_html('tw desc'),
            //facebook
            'fb_image' => null,
            'fb_title' =>  SanitizeInput::esc_html('fb title'),
            'fb_description' =>  SanitizeInput::esc_html('fb desc'),
        ];

        $page_data_7->save();
        $page_data_7->metainfo()->create($Metas_7);
    }

    private function upload_layout($file, $page_id)
    {
        DB::beginTransaction();
        try {
            $file_contents = json_decode(file_get_contents('assets/tenant/seeder_files/page-layouts/'.$file));

            $contentArr = [];
            if (current($file_contents)->addon_page_type == 'dynamic_page')
            {
                foreach ($file_contents as $key => $content)
                {
                    unset($content->id);
                    $content->addon_page_id = (int)trim($page_id);
                    $content->created_at = now();
                    $content->updated_at = now();

                    foreach ($content as $key2 => $con)
                    {
                        $contentArr[$key][$key2] = $con;
                    }
                }

                Page::findOrFail($page_id)->update(['page_builder' => 1]);

                PageBuilder::where('addon_page_id', $page_id)->delete();
                PageBuilder::insert($contentArr);
            } else {
                Page::findOrFail($page_id)->update([
                    'page_builder' => 0,
                    'page_content' => current($file_contents)->text
                ]);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }
    }
}
