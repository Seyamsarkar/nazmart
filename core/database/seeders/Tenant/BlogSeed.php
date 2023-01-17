<?php

namespace Database\Seeders\Tenant;

use App\Helpers\ImageDataSeedingHelper;
use App\Helpers\SanitizeInput;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;

class BlogSeed extends Seeder
{
    public function run()
    {
        //category store
        $this->blog_category_seed();
        $this->blog_tag_seed();

        $description = 'Was drawing natural fat respect husband. An as noisy an offer drawn blush place. These tried for way joy wrote witty.
         In mr began music weeks after at begin. Education no dejection so direction pretended household do to. Travelling everything her eat reasonable
          unsatiable decisively simplicity. Morning request be lasting it fortune demands highest of.
        Whether article spirits new her covered hastily sitting her. Money witty books nor son add. Chicken age had evening believe but proceed pretend mrs.
         At missed advice my it no sister. Miss told ham dull knew see she spot near can. Spirit her entire her called.
        Acceptance middletons me if discretion boisterous travelling an. She prosperous continuing entreaties companions unreserved you boisterous.
         Middleton sportsmen sir now cordially ask additions for. You ten occasional saw everything but conviction. Daughter returned quitting few are day
         advanced branched. Do enjoyment defective objection or we if favourite. At wonder afford so danger cannot former seeing. Power visit charm money add
         heard new other put. Attended no indulged marriage is to judgment offering landlords.';

        $title_one = 'Money witty books nor son add. Chicken age had evening believe';
        $title_two = 'Was drawing natural fat respect husband';
        $title_three = 'Ten Highways is my organization are added and seal there single';
        $title_four = 'Attended no indulged marriage is to judgment offering landlords';
        $title_five = 'Miss told ham dull knew see she spot near can';
        $title_six = 'Do enjoyment defective objection or we if favourite';

        $this->blog_store($title_one, $description, 4, 319);
        $this->blog_store($title_two, $description, 5, 318);
        $this->blog_store($title_three, $description, 6, 320);
        $this->blog_store($title_four, $description, 7, 318);
        $this->blog_store($title_five, $description, 8, 319);
        $this->blog_store($title_six, $description, 9, 320);
    }


    private function blog_category_seed()
    {
        DB::statement("INSERT INTO `blog_categories` (`id`, `title`, `status`, `created_at`, `updated_at`, `slug`) VALUES
        (4, 'Travel', '1', '2022-08-17 05:46:51', '2022-08-17 05:46:51', 'travel'),
        (5, 'Online Course', '1', '2022-08-17 05:47:03', '2022-08-17 05:47:03', 'online-course'),
        (6, 'Hosting', '1', '2022-08-17 05:47:10', '2022-08-17 05:47:10', 'hosting'),
        (7, 'Game', '1', '2022-08-17 05:47:16', '2022-08-17 05:47:36', 'game'),
        (8, 'Restaurant', '1', '2022-08-17 05:47:23', '2022-08-17 05:47:23', 'restaurant'),
        (9, 'Ticket', '1', '2022-08-17 05:47:29', '2022-08-17 05:47:29', 'ticket')");
    }

    private function blog_tag_seed()
    {
        DB::statement("INSERT INTO `blog_tags` (`id`, `title`, `slug`, `created_at`, `updated_at`) VALUES
        (11, 'Gadget', 'gadget', '2022-08-17 05:45:56', '2022-08-17 05:45:56'),
        (12, 'Games', 'games', '2022-08-17 05:46:01', '2022-08-17 05:46:01'),
        (13, 'Fashion', 'fashion', '2022-08-17 05:46:18', '2022-08-17 05:46:18'),
        (14, 'Watch', 'watch', '2022-08-17 05:46:27', '2022-08-17 05:46:27'),
        (15, 'Camera', 'camera', '2022-08-17 05:46:34', '2022-08-17 05:46:34'),
        (16, 'Travel', 'travel', '2022-08-17 05:46:39', '2022-08-17 05:46:39'),
        (17, 'Tech', 'tech', '2022-08-17 05:46:43', '2022-08-17 05:46:43'),
        (18, 'Book', 'book', '2022-08-17 05:45:26', '2022-08-17 05:45:35')");
    }

    private function blog_store($title, $description, $category_id, $image_id)
    {
        $admin = Admin::first();

        $blog = new Blog();
        $blog->title =  SanitizeInput::esc_html($title);
        $blog->blog_content = $description;
        $blog->excerpt = SanitizeInput::esc_html('blog excerpt');

        $slug = Str::slug($title);

        $blog->slug = SanitizeInput::esc_html($slug);
        $blog->category_id = $category_id;
        $blog->featured = null;
        $blog->visibility = 'public';
        $blog->status = 1;
        $blog->admin_id = $admin->id;
        $blog->user_id = null;
        $blog->author = $admin->name;
        $blog->image = $image_id;
        $blog->image_gallery = null;
        $blog->views = 0;
        $blog->tags = null;
        $blog->created_by = 'admin';

        $Metas = [
            'title' => SanitizeInput::esc_html('blog'),
            'description' => SanitizeInput::esc_html('blog'),
            'image' => null,
            //twitter
            'tw_image' => null,
            'tw_title' => SanitizeInput::esc_html('blog'),
            'tw_description' => SanitizeInput::esc_html('blog'),
            //facebook
            'fb_image' => null,
            'fb_title' =>SanitizeInput::esc_html('blog'),
            'fb_description' => SanitizeInput::esc_html('blog'),
        ];

        $blog->save();
        $blog->metainfo()->create($Metas);
    }
}
