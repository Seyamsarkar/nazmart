<?php

namespace App\Http\Middleware\Landlord;

use App\Models\ContactMessage;
use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Modules\Blog\Entities\BlogComment;

class AdminGlobalVariable
{

    public function handle(Request $request, Closure $next)
    {

        $all_messages = ContactMessage::orderBy('id','desc')->take(5)->get();
        $new_message =  ContactMessage::where('status',1)->count();
        $new_comments = BlogComment::orderBy('id','DESC')->take(5)->get();

        $share_data = [
            'all_messages' => $all_messages,
            'new_message' => $new_message,
            'new_comments' => $new_comments,
        ];
        view()->composer('*', function ($view) use ($share_data) {

            $view->with($share_data);

        });
        return $next($request);
    }
}
