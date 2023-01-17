<?php

namespace Modules\Blog\Http\Controllers\Tenant\Frontend;

use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\Mail\BasicMail;
use App\Traits\SeoDataConfig;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;
use Modules\Blog\Entities\BlogComment;
use Modules\Blog\Entities\BlogTag;

class BlogController extends Controller
{
    use SEOToolsTrait, SeoDataConfig;

    private const BASE_PATH = 'blog::tenant.frontend.blog.';

    public function index()
    {
        $blogs = Blog::where('status', 1)->orderBy('id', 'desc')->paginate(get_static_option('blog_page_item_show') ?? 9);
        return view(self::BASE_PATH . 'blog-all', compact('blogs'));
    }

    public function blog_single($slug)
    {
        $blog_post = Blog::with(['user', 'category', 'comments'])->where(['slug' => $slug, 'status' => 1])->firstOrFail();
        $blog_comments = BlogComment::where(['blog_id' => $blog_post->id, 'parent_id' => null])->orderByDesc('created_at')->take(3)->get();
        $blog_comments_count = BlogComment::where(['blog_id' => $blog_post->id, 'parent_id' => null])->count();

        $all_category = BlogCategory::withCount('blogs')->has('blogs')->get();
        $all_tags = BlogTag::orderByDesc('created_at')->select('id','title','slug')->take(15)->get();

        $this->setMetaDataInfo($blog_post);

        return view(self::BASE_PATH . 'blog-single', compact('blog_post', 'blog_comments', 'blog_comments_count', 'all_category', 'all_tags'));
    }

    public function category_wise_blog_page($slug)
    {
        abort_if(empty($slug), 404);

        $sorting = blog_sorting(request());
        $order_by = $sorting['order_by'];
        $order = $sorting['order'];
        $order_type = $sorting['order_type'];

        $category = BlogCategory::where('slug', $slug)->firstOrFail();
        $blogs = Blog::where(['category_id' => $category->id, 'status' => 1])->orderBy($order_by, $order)->paginate(get_static_option('category_page_item_show') ?? 9);
        $category_name = $category->title;

        return view(self::BASE_PATH . 'blog-category')->with([
            'blogs' => $blogs,
            'category_name' => $category_name,
            'order_type' => $order_type,
        ]);
    }


    public function blog_search_page(Request $request)
    {

        $request->validate([
            'search' => 'required'
        ],
            ['search.required' => 'Enter anything to search']);

        $all_blogs = Blog::Where('title', 'LIKE', '%' . $request->search . '%')
            ->orderBy('id', 'desc')->paginate(get_static_option('blog_search_item_show') ?? 9);

        return view(self::BASE_PATH . 'blog-search')->with([
            'all_blogs' => $all_blogs,
            'search_term' => $request->search,
        ]);
    }

    public function tags_wise_blog_page($tag)
    {
        $sorting = blog_sorting(request());
        $order_by = $sorting['order_by'];
        $order = $sorting['order'];
        $order_type = $sorting['order_type'];

        $all_blogs = Blog::Where('tags', 'LIKE', '%' . $tag . '%')
            ->orderBy($order_by, $order)->paginate(get_static_option('blog_tag_item_show') ?? 9);

        return view(self::BASE_PATH . 'blog-tags')->with([
            'blogs' => $all_blogs,
            'tag_name' => $tag,
            'order_type' => $order_type
        ]);
    }

    public function search_wise_blog_page(Request $request)
    {
        $all_blogs = Blog::Where('title', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('slug', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('tags', 'LIKE', '%' . $request->search . '%')
                    ->orderBy('id', 'desc')->paginate(get_static_option('search_page_item_show') ?? 9);

        return view(self::BASE_PATH . 'blog-search')->with([
            'blogs' => $all_blogs,
            'tag_name' => $request->search,
        ]);
    }

    public function blog_comment_store(Request $request)
    {
        $request->validate([
            'comment_content' => 'required'
        ],
            [
                'comment_content.required' => 'Comment field is required'
            ]);

        $content = BlogComment::create([
            'blog_id' => $request->blog_id,
            'user_id' => $request->user_id,
            'parent_id' => $request->comment_id ?? null,
            'commented_by' => $request->commented_by,
            'comment_content' => SanitizeInput::esc_html($request->comment_content),
        ]);

        $sub = __('You have a comment from') . ' ' . get_static_option('site_title');
        $message = __('you have a new comment submitted by') . ' ' . Auth::user()->name . ' ' . __('Email') . ' ' . Auth::user()->email . ' .' . __('check admin panel for more info');

        try {
            Mail::to(get_static_option('site_global_email'))->send(new BasicMail($sub, $message));

        } catch (\Exception $e) {

        }


        return response()->json([
            'msg' => __('Your comment sent succefully'),
            'type' => 'success',
            'status' => 'ok',
            'content' => $content,
        ]);
    }

    public function load_more_comments(Request $request)
    {
        $all_comment = BlogComment::with(['blog', 'user', 'reply'])
            ->where('parent_id', null)
            ->orderBy('id', 'desc')
            ->skip($request->items)
            ->take(5)
            ->get();

        $markup = '<ul class="comment-list wow fadeInLeft" data-wow-delay=".1s">';
        foreach ($all_comment as $item) {
            $commented_user_image = render_image_markup_by_attachment_id(get_static_option('blog_avatar_image'), '', '');

            $var_data_parent_name = optional($item->user)->name;
            $title = optional($item->user)->name ?? '';
            $created_at = date('d F Y', strtotime($item->created_at ?? ''));
            $comment_content = $item->comment_content;
            $data_id = $item->id;
            $replay_mark = '';
            $replay_text = __('Reply');


            $replay_mark .= <<<REPLA
         <a href="javascript:void(0)" data-comment_id="{$data_id}" class="reply-btn btn-replay"><i class="las la-reply icon"></i><span class="text">{$replay_text}</span></a>
REPLA;

            $repl = auth('web')->check() && auth('web')->user()->id != $item->user_id ? $replay_mark : '';

            $li_data = '<ul class="comment-list wow fadeInLeft" data-wow-delay=".2s">';
            foreach ($item->reply as $repData) {
                $child_image = render_image_markup_by_attachment_id(optional($repData->user)->image ?? get_static_option('blog_avatar_image'), '', '', false);
                $child_user_name = optional($repData->user)->name ?? '';
                $child_commented_date = date('d F Y', strtotime($repData->created_at ?? ''));
                $child_comment = $repData->comment_content ?? '';


                $li_data .= <<<LIDATA
<li>
    <div class="blog-details-flex-content">
        <div class="blog-details-thumb radius-10">
          {$child_image}
        </div>
        <div class="blog-details-content">
            <div class="blog-details-content-flex">
                <div class="blog-details-content-item">
                     <h5 class="blog-details-content-title">
                        <a href="javascript:void(0)"> {$child_user_name} </a>
                     </h5>
                     <span class="blog-details-content-date"> {$child_commented_date} </span>
                </div>
            </div>
            <p class="blog-details-content-para"> {$child_comment} </p>
        </div>
    </div>
</li>
LIDATA;
            }
            $li_data .= '</ul>';

            $markup .= <<<HTML
                        <li>
                            <div class="blog-details-flex-content">
                                <div class="blog-details-thumb radius-10">
                                    {$commented_user_image}
                                </div>
                                <div class="blog-details-content">
                                    <div class="blog-details-content-flex">
                                        <div class="blog-details-content-item">
                                            <h5 class="blog-details-content-title"><a
                                                    href="javascript:void(0)" class="title" data-parent_name="{$var_data_parent_name}"> {$title} </a>
                                            </h5>
                                            <span
                                                class="blog-details-content-date"> {$created_at} </span>
                                        </div>

                                        {$repl}
                                    </div>
                                    <p class="blog-details-content-para"> {$comment_content} </p>
                                </div>
                            </div>


                            {$li_data}
                        </li>
HTML;
        }
        $markup .= '</ul>';

        return response()->json(['blogComments' => $all_comment, 'markup' => $markup]);
    }

}
