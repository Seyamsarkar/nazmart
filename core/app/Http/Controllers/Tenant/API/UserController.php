<?php

namespace App\Http\Controllers\Tenant\API;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Product\Entities\ProductOrder;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'username' => 'required|max:191',
            'password' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'validation_errors' => $validate->messages()
            ])->setStatusCode(422);
        }

        $user = User::select('id', 'password', 'username')->where('username', trim($request->username))->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => __('Invalid Email or Password')
            ])->setStatusCode(422);
        } else {
            $token = $user->createToken(Str::slug(get_static_option('site_title', 'grenmart')) . 'api_keys')->plainTextToken;
            return response()->json([
                'users' => $user,
                'token' => $token,
            ]);
        }
        return [$user_login_type, $request->username,$user];
    }


    //social login
    public function socialLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'message' => __('invalid Email'),
            ])->setStatusCode(422);
        }

        $username = $request->isGoogle === 0 ?  'fb_'. Str::slug($request->displayName) : 'gl_'.Str::slug($request->displayName);
        $user = User::select('id', 'email', 'username')
            ->where('email', $request->email)
            ->first();

        if(User::where("username", $username)->count() > 0){
            $username = $username . uniqid();
        }

        if (is_null($user)) {
            $user = User::create([
                'name' => $request->displayName,
                'email' => $request->email,
                'username' => $username,
                'password' => Hash::make(\Str::random(8)),
                'terms_condition' => 1,
                'google_id' => $request->isGoogle == 1 ? $request->id : null,
                'facebook_id' => $request->isGoogle == 0 ? $request->id : null
            ]);
        }

        $token = $user->createToken(Str::slug(get_static_option('site_title', 'qixer')) . 'api_keys')->plainTextToken;

        return response()->json([
            'users' => $user,
            'token' => $token,
        ]);
    }

    //register api
    public function register(Request $request)
    {

        $validate = Validator::make($request->all(),[
            'full_name' => 'required|max:191',
            'email' => 'required|email|unique:users|max:191',
            'username' => 'required|unique:users|max:191',
            'phone' => 'required|unique:users|max:191',
            'password' => 'required|min:6|max:191',
            'country_id' => 'required',
            'country_code' => 'required',
            'state_id' => 'nullable',
            'terms_conditions' => 'required',
        ]);
        if ($validate->fails()){
            return response()->json([
                'validation_errors' => $validate->messages()
            ])->setStatusCode(422);
        }
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'message' => __('invalid Email'),
            ])->setStatusCode(422);
        }

        $user = User::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'country_code' => $request->country_code,
            'country' => $request->country_id,
            'state' => $request->state_id,
        ]);
        if (!is_null($user)) {
            $token = $user->createToken(Str::slug(get_static_option('site_title', 'grenmart')) . 'api_keys')->plainTextToken;
            return response()->json([
                'users' => $user,
                'token' => $token,
            ]);
        }
        return response()->json([
            'message' => __('Something Went Wrong'),
        ])->setStatusCode(422);
    }

    public function get_all_shipping_address(){
        $user_id = auth('sanctum')->user()->id;

        $all_shipping_address = ShippingAddress::where('user_id', $user_id)->get();
        return response()->json(["data" => $all_shipping_address ?? []]);
    }

    // send otp
    public function sendOTPSuccess(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'user_id' => 'required|integer',
            'email_verified' => 'required|integer',
        ]);
        if ($validate->fails()){
            return response()->json([
                'validation_errors' => $validate->messages()
            ])->setStatusCode(422);
        }

        if(!in_array($request->email_verified,[0,1])){
            return response()->json([
                'message' => __('email verify code must have to be 1 or 0'),
            ])->setStatusCode(422);
        }

        $user = User::where('id', $request->user_id)->update([
            'email_verified' =>  $request->email_verified
        ]);

        if(is_null($user)){
            return response()->json([
                'message' => __('Something went wrong, plese try after sometime,'),
            ])->setStatusCode(422);
        }

        return response()->json([
            'message' => __('Email Verify Success'),
        ]);
    }

    public function sendOTP(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'email' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'validation_errors' => $validate->messages()
            ])->setStatusCode(422);
        }
        $otp_code = sprintf("%d", random_int(1234, 9999));
        $user_email = User::where('email', $request->email)->first();

        if (!is_null($user_email)) {
            try {
                $message_body = __('Here is your otp code') . ' <span class="verify-code">' . $otp_code . '</span>';
                Mail::to($request->email)->send(new BasicMail([
                    'subject' => __('Your OTP Code'),
                    'message' => $message_body
                ]));
            } catch (\Exception $e) {
                return response()->json([
                    'message' => $e->getMessage(),
                ])->setStatusCode(422);
            }

            return response()->json([
                'email' => $request->email,
                'otp' => $otp_code,
            ]);

        }

        return response()->json([
            'message' => __('Email Does not Exists'),
        ])->setStatusCode(422);

    }

    //reset password
    public function resetPassword(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validate->fails()){
            return response()->josn([
                'validation_errors' => $validate->messages()
            ])->setStatusCode(422);
        }
        $email = $request->email;
        $user = User::select('email')->where('email', $email)->first();
        if (!is_null($user)) {
            User::where('email', $user->email)->update([
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                'message' => 'success',
            ]);
        } else {
            return response()->json([
                'message' => __('Email Not Found'),
            ])->setStatusCode(422);
        }
    }

    //logout
    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => __('Logout Success'),
        ]);
    }

    //User Profile
    public function profile(){

        $user_id = auth('sanctum')->id();

        $user = User::with('country','shipping','state')
            ->where('id',$user_id)->first();
        $image_url = null;
        if(!empty($user->image)){
            $img_details = get_attachment_image_by_id($user->image);
            $image_url = $img_details['img_url'] ?? null;
        }
        $user->profile_image_url = $image_url ?  : null;

        return response()->json([
            'user_details' => $user
        ]);
    }

//    change password after login
    public function changePassword(Request $request){
        $validate = Validator::make($request->all(),[
            'current_password' => 'required|min:6',
            'new_password' => 'required|min:6',
        ]);
        if ($validate->fails()){
            return response()->json([
                'validation_errors' => $validate->messages()
            ])->setStatusCode(422);
        }

        $user = User::select('id','password')->where('id', auth('sanctum')->user()->id)->first();
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => __('Current Password is Wrong'),
            ])->setStatusCode(422);
        }

        User::where('id',auth('sanctum')->user()->id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'current_password' => $request->current_password,
            'new_password' => $request->new_password,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth('sanctum')->user();
        $user_id = auth('sanctum')->user()->id;

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,id,' . $request->user_id,
            'phone' => 'nullable|string|max:191',
            'state' => 'nullable|string|max:191',
            'city' => 'nullable|string|max:191',
            'zipcode' => 'nullable|string|max:191',
            'country' => 'nullable|string|max:191',
            'country_code' => 'nullable|string|max:191',
            'address' => 'nullable|string',
        ], [
            'name.' => __('name is required'),
            'email.required' => __('email is required'),
            'email.email' => __('provide valid email'),
        ]);


        if($request->file('file')){
            MediaHelper::insert_media_image($request);
            $last_image_id = DB::getPdo()->lastInsertId();
        }

        User::find($user_id)->update(
            [
                'name' => $request->name,
                'email' => $request->email,
                'image' => $last_image_id ?? $user->image,
                'phone' => $request->phone,
                'state' => $request->state,
                'city' => $request->city,
                'zipcode' => $request->zipcode,
                'country' => $request->country,
                'country_code' => $request->country_code,
                'address' => $request->address,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function get_all_tickets(){
        $user_id = auth('sanctum')->user()->id;
        $all_tickets = SupportTicket::where('user_id', $user_id)->paginate(10)->withQueryString();

        return $all_tickets;
    }

    public function single_ticket($id){
        $user_id = auth('sanctum')->user()->id;

        $ticket_details = SupportTicket::where('user_id', $user_id)
            ->where("id",$id)
            ->first();
        $all_messages = SupportTicketMessage::where(['support_ticket_id' => $id])->get()->transform(function ($item){
            $item->attachment = !empty($item->attachment) ? asset('assets/uploads/ticket/'.$item->attachment) : null;

            return $item;
        });

        return response()->json(["ticket_details" => $ticket_details,"all_messages" => $all_messages]);
    }

    public function fetch_support_chat($ticket_id){
        $all_messages = SupportTicketMessage::where(['support_ticket_id' => $ticket_id])->get()->transform(function ($item){
            $item->attachment = !empty($item->attachment) ? asset('assets/uploads/ticket/'.$item->attachment) : null;

            return $item;
        });

        return response()->json($all_messages);
    }

    public function priority_change(Request $request)
    {
        $this->validate($request, ['priority' => 'required|string|max:191']);
        SupportTicket::findOrFail($request->id)->update([
            'priority' => $request->priority,
        ]);
        return response()->json(['success' => true]);
    }

    public function status_change(Request $request)
    {
        $this->validate($request, ['status' => 'required|string|max:191']);
        SupportTicket::findOrFail($request->id)->update([
            'status' => $request->status,
        ]);
        return response()->json(['success' => true]);
    }

    public function trackOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|numeric',
            'email' => 'required|email'
        ]);

        $sell_info = ProductOrder::where('id', $request->order_id)
            ->where('email', $request->email)
            ->first();

        if ($sell_info) {
            try {
                \Mail::to($sell_info->email)->send(new TrackOrder(
                    $sell_info
                ));

                return response()->json(["payment_status" => ucwords($sell_info->payment_status),"order_status" => ucwords($sell_info->status)]);
            } catch (\Exception $e) {
                return response()->json(["msg" => "Server error"]);
            }
        }

        return response()->json(["msg" => __('No order found for the given information.')]);
    }

    public function send_support_chat(Request $request,$ticket_id){
        $this->validate($request, [
            'user_type' => 'required|string|max:191',
            'message' => 'required',
            'send_notify_mail' => 'nullable|string',
            'file' => 'nullable|mimes:zip,jpg,jpeg,png,gif',
        ]);

        $ticket_info = SupportTicketMessage::create([
            'support_ticket_id' => $ticket_id,
            'type' => $request->user_type,
            'message' => $request->message,
            'notify' => $request->send_notify_mail ? 'on' : 'off',
            'attachment' => null,
        ]);

        if ($request->hasFile('file')) {
            $uploaded_file = $request->file;
            $file_extension = $uploaded_file->getClientOriginalExtension();
            $file_name = pathinfo($uploaded_file->getClientOriginalName(), PATHINFO_FILENAME) . time() . '.' . $file_extension;
            $uploaded_file->move('assets/uploads/ticket', $file_name);
            $ticket_info->attachment = $file_name;
            $ticket_info->save();
        }

        $ticket = $ticket_info->toArray();
        $ticket["attachment"] = empty($ticket["attachment"]) ? null : asset('assets/uploads/ticket' . $ticket["attachment"]);

        return response()->json($ticket);
    }

    public function storeShippingAddress(Request $request){
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|string|max:191',
            'phone' => 'required|string|max:191',
            'country' => 'required|string|max:191',
            'state' => 'required|string|max:191',
            'city' =>  'nullable|string|max:191',
            'zipcode' => 'nullable|string|max:191',
            'address' => 'nullable|string|max:191',
        ]);

        $user_id = auth('sanctum')->user()->id;

        $user_shipping_address = ShippingAddress::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'user_id' => $user_id ?? null,
            'country_id' => $request->country,
            'state_id' => $request->state,
            'city' => $request->city,
            'zip_code' => $request->zipcode,
            'address' => $request->address,
        ]);

        return response()->json(['success' => true,"address" => $user_shipping_address]);
    }

    public function viewTickets(Request $request,$id= null)
    {
        $all_messages = SupportTicketMessage::where(['support_ticket_id'=>$id])->get()->transform(function($item){
            $item->attachment = !empty($item->attachment) ? asset('assets/uploads/ticket/'.$item->attachment) : null;
            return $item;
        });
        $q = $request->q ?? '';
        return response()->json([
            'ticket_id'=>$id,
            'all_messages' =>$all_messages,
            'q' =>$q,
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
             'ticket_id' => 'required',
             'user_type' => 'required|string|max:191',
             'message' => 'required',
             'file' => 'nullable|mimes:jpg,png,jpeg,gif',
         ]);

         $ticket_info = SupportTicketMessage::create([
             'support_ticket_id' => $request->ticket_id,
             'type' => $request->user_type,
             'message' => $request->message,
         ]);

         if ($request->hasFile('file')){
             $uploaded_file = $request->file;
             $file_extension = $uploaded_file->extension();
             $file_name =  pathinfo($uploaded_file->getClientOriginalName(),PATHINFO_FILENAME).time().'.'.$file_extension;
             $uploaded_file->move('assets/uploads/ticket',$file_name);
             $ticket_info->attachment = $file_name;
             $ticket_info->save();
         }

         return response()->json([
             'message'=>__('Message Send Success'),
             'ticket_id'=>$request->ticket_id,
             'user_type' =>$request->user_type,
             'ticket_info' => $ticket_info,
         ]);
    }

    public function get_department(){
        $data = SupportDepartment::select("id","name","status")->where(['status' => 'publish'])->get();
        return response()->json(["data" => $data]);
    }

    public function createTicket(Request $request){
        $uesr_info = auth('sanctum')->user()->id;
        $request->validate([
            'title' => 'required|string|max:191',
            'subject' => 'required|string|max:191',
            'priority' => 'required|string|max:191',
            'description' => 'required|string',
            'departments' => 'required|string',
        ],[
            'title.required' => __('title required'),
            'subject.required' =>  __('subject required'),
            'priority.required' =>  __('priority required'),
            'description.required' => __('description required'),
            'departments.required' => __('departments required'),
        ]);

        $ticket = SupportTicket::create([
            'title' => $request->title,
            'via' => $request->via,
            'operating_system' => null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'description' => $request->description,
            'subject' => $request->subject,
            'status' => 'open',
            'priority' => $request->priority,
            'user_id' => $uesr_info,
            'admin_id' => null,
            'departments' => $request->departments
        ]);

        $msg = get_static_option('support_ticket_success_message') ?? __('Thanks for contact us, we will reply soon');

        return response()->json(["msg" => $msg,"ticket" => $ticket]);
    }

    public function delete_shipping_address(ShippingAddress $shipping){
        if(empty($shipping)){ return response()->json(["msg" => "Shipping zone not found on the server."])->setStatusCode(404); }

        $bool = $shipping->user_id == auth('sanctum')->id() ? $shipping->delete() : false;
        $msg = $bool ? "Successfully Deleted Shipping Zone" : "You are not eligible to delete this shipping address";

        return response()->json(["msg" => $msg]);
    }
}
