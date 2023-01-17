<?php

namespace Modules\RefundModule\Http\Controllers;

use App\Events\SupportMessage;
use App\Helpers\FlashMsg;
use App\Helpers\SanitizeInput;
use App\Mail\BasicMail;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\RefundModule\Entities\RefundChat;
use Modules\RefundModule\Entities\RefundChatMessage;
use function GuzzleHttp\Promise\all;

class RefundChatController extends Controller
{
    public function chat_list(){
        $all_chats = RefundChat::where('user_id', \Auth::guard('web')->user()->id)->paginate(10);
        return view('tenant.frontend.user.dashboard.refund.chat.refund-chat-list', compact('all_chats'));
    }

    public function chat_store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'subject' => 'required|string|max:191',
            'description' => 'required|string',
        ],[
            'title.required' => __('title required'),
            'subject.required' =>  __('subject required'),
            'description.required' => __('description required'),
        ]);

        RefundChat::create([
            'title' => SanitizeInput::esc_html($request->title),
            'via' => $request->via,
            'operating_system' => null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'description' => str_replace('script', '', $request->description),
            'subject' => SanitizeInput::esc_html($request->subject),
            'status' => 'open',
            'user_id' => Auth::guard('web')->user()->id,
            'admin_id' => null,
        ]);

        //send mail to user
        try {
            $message_body = __('You have a new refund request message');
            Mail::to(get_static_option('tenant_site_global_email'))->send(new BasicMail($message_body, __('Refund Request Message')));

        } catch (\Exception $e) {
            return redirect()->back()->with(FlashMsg::explain('danger' ,$e->getMessage()));
        }

        $msg = get_static_option('support_ticket_success_message') ?? __('thanks for contact us, we will reply soon');
        return redirect()->back()->with(['msg' => $msg, 'type' => 'success']);
    }

    public function message_show($id)
    {
        $chat = RefundChat::findOrFail($id);
        $all_messages = RefundChatMessage::where(['refund_chat_id' => $id])->get();
        $q = $request->q ?? '';

        return view('tenant.frontend.user.dashboard.refund.chat.view-chat', compact('chat', 'all_messages', 'q'));
    }

    public function refund_request_message(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required',
            'user_type' => 'required|string|max:191',
            'message' => 'required',
            'send_notify_mail' => 'nullable|string',
            'file' => 'nullable|mimes:zip',
        ]);

        $ticket_info = RefundChatMessage::create([
            'refund_chat_id' => $request->ticket_id,
            'user_id' => Auth::guard('web')->id(),
            'type' => $request->user_type,
            'message' => str_replace('script', '', $request->message),
            'notify' => $request->send_notify_mail ? 'on' : 'off',
        ]);

        if ($request->hasFile('file')){
            $uploaded_file = $request->file;
            $file_extension = $uploaded_file->getClientOriginalExtension();
            $file_name =  pathinfo($uploaded_file->getClientOriginalName(),PATHINFO_FILENAME).time().'.'.$file_extension;
            $uploaded_file->move('assets/uploads/refund_chat',$file_name);
            $ticket_info->attachment = $file_name;
            $ticket_info->save();
        }

        //send mail to user
        try {
            $message_body = 'Username: '.Auth::guard('web')->user()->name;
            $message_body .= $ticket_info->message;
            Mail::to(get_static_option('tenant_site_global_email'))->send(new BasicMail($message_body, __('Refund Request Message')));

        } catch (\Exception $e) {
            return redirect()->back()->with(FlashMsg::explain('danger' ,$e->getMessage()));
        }

        return redirect()->back()->with(['msg' => __('Mail Send Success'), 'type' => 'success']);
    }
}
