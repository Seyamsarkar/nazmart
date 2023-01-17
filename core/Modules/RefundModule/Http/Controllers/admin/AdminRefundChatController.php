<?php

namespace Modules\RefundModule\Http\Controllers\admin;

use App\Events\SupportMessage;
use App\Helpers\FlashMsg;
use App\Helpers\ResponseMessage;
use App\Mail\BasicMail;
use App\Models\Language;
use App\Models\SupportDepartment;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\RefundModule\Entities\RefundChat;
use Modules\RefundModule\Entities\RefundChatMessage;

class AdminRefundChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:refund-chat-list|refund-chat-create|refund-chat-edit|refund-chat-delete',['only' => ['all_chats','status_change']]);
        $this->middleware('permission:refund-chat-create',['only' => ['new_chat','store_chat']]);
        $this->middleware('permission:refund-chat-edit',['only' => ['status_change']]);
        $this->middleware('permission:refund-chat-delete',['only' => ['delete','bulk_action']]);
    }

    private const BASE_PATH = 'refundmodule::tenant.admin.chat.';

    public function all_chats(){
        $all_tickets = RefundChat::orderBy('id','desc')->get();
        return view(self::BASE_PATH .'all-tickets')->with(['all_tickets' => $all_tickets ]);
    }

    public function new_chat(){
        $all_users = User::all();
        return view(self::BASE_PATH.'new-ticket')->with(['all_users' => $all_users]);
    }
    public function store_chat(Request $request){
        $request->validate([
            'title' => 'required|string|max:191',
            'subject' => 'required|string|max:191',
            'description' => 'required|string',
        ],[
            'title.required' => __('title required'),
            'subject.required' =>  __('subject required'),
            'priority.required' =>  __('priority required'),
            'description.required' => __('description required'),
            'departments.required' => __('departments required'),
        ]);

        RefundChat::create([
            'title' => $request->title,
            'via' => 'admin',
            'operating_system' => null,
            'user_agent' => null,
            'description' => $request->description,
            'subject' => $request->subject,
            'status' => 'open',
            'user_id' => $request->user_id,
            'admin_id' => Auth::guard('admin')->user()->id
        ]);

        $msg =  __('new ticket created successfully');
        return response()->success(ResponseMessage::SettingsSaved($msg));
    }
    public function status_change(Request $request){
        $request->validate([
            'status' => 'required|string|max:191'
        ]);
        RefundChat::findOrFail($request->id)->update([
            'status' => $request->status,
        ]);
        return response()->json('ok');
    }

    public function delete(Request $request,$id){
        $refund = RefundChat::findOrFail($id);
        $refund_messages = RefundChatMessage::where('refund_chat_id', $refund->id)->get();

        if (!empty($refund_messages))
        {
            foreach ($refund_messages as $message)
            {
                if ($message->attachment != null && file_exists('assets/uploads/refund_chat/'.$message->attachment))
                {
                    unlink('assets/uploads/refund_chat/'.$message->attachment);
                }
            }
        }

        RefundChatMessage::where('refund_chat_id', $refund->id)->delete();
        $refund->delete();

        return response()->danger(ResponseMessage::delete('Refund Request Message Deleted'));
    }

    public function views(Request $request,$id)
    {
        $ticket_details = RefundChat::findOrFail($id);
        $all_ticket_messages = RefundChatMessage::where(['refund_chat_id'=>$id])->get();
        $q = $request->q ?? '';
        return view(self::BASE_PATH.'view-ticket')->with(['ticket_details' => $ticket_details,'all_ticket_messages' => $all_ticket_messages,'q' => $q]);
    }

    public function send_message(Request $request){
        $request->validate([
            'ticket_id' => 'required',
            'user_type' => 'required|string|max:191',
            'message' => 'required',
            'send_notify_mail' => 'nullable|string',
            'file' => 'nullable|mimes:zip',
        ]);

        $ticket_info = RefundChatMessage::create([
            'refund_chat_id' => $request->ticket_id,
            'type' => $request->user_type,
            'user_id' => Auth::guard('admin')->user()->id,
            'message' => $request->message,
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
        //send mail to user
        try {
            $message_body = __('You have a message regarding product refund from').' '.env('APP_NAME');
            Mail::to(get_static_option('tenant_site_global_email'))->send(new BasicMail($message_body, __('Refund Request Message')));

        } catch (\Exception $e) {
            return redirect()->back()->with(FlashMsg::explain('danger' ,$e->getMessage()));
        }

        return response()->success(ResponseMessage::SettingsSaved('Message Sent'));
    }

    public function bulk_action(Request $request){
        RefundChat::whereIn('id',$request->ids)->delete();
        return response()->json('ok');
    }

    public function page_settings(){
        return view(self::BASE_PATH.'page-settings');
    }

    public function update_page_settings(Request $request){
        foreach (Language::all() as $lang){
            $request->validate([
                'support_ticket_'.$lang->slug.'_login_notice' => 'nullable|string',
                'support_ticket_'.$lang->slug.'_form_title' => 'nullable|string',
                'support_ticket_'.$lang->slug.'_button_text' => 'nullable|string',
                'support_ticket_'.$lang->slug.'_success_message' => 'nullable|string',
            ]);
            $field_list = [
                'support_ticket_'.$lang->slug.'_login_notice',
                'support_ticket_'.$lang->slug.'_form_title',
                'support_ticket_'.$lang->slug.'_button_text',
                'support_ticket_'.$lang->slug.'_success_message',
            ];
            foreach ($field_list as $field){
                update_static_option($field,$request->$field);
            }
        }
        return response()->success(ResponseMessage::SettingsSaved('Item Saved Succesfully..'));
    }
}
