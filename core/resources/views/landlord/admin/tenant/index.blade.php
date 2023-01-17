@php
    $route_name ='landlord';
@endphp

@extends($route_name.'.admin.admin-master')
@section('title') {{__('All Tenants')}} @endsection

@section('style')
    <x-datatable.css/>
    <x-summernote.css/>
@endsection

@section('content')
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{__('All Tenants')}}</h4>

                <h5 class="text-danger">{{__('( If you delete any user and if it\'s associated with any package than everything regarding with this user will be deleted )')}}</h5>

                <x-error-msg/>
                <x-flash-msg/>

                <x-datatable.table>
                    <x-slot name="th">
                        <th>{{__('ID')}}</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Email')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">
                       @foreach($all_users as $user)
                           <tr>
                               <td>{{$user->id}}</td>
                               <td>{{$user->name}}</td>
                               <td>{{$user->email}}
                                   @if($user->email_verified === 0)
                                    <i class="text-danger mdi mdi-close-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Email Not Verified')}}"></i>
                                   @else
                                    <i class="text-success mdi mdi-check-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Email  Verified')}}"></i>
                                   @endif
                               </td>
                               <td>
                                   <x-delete-popover url="{{route('landlord.admin.tenant.delete',$user->id)}}" popover="{{__('Delete')}}"/>

                                   <x-link-with-popover url="{{route('landlord.admin.tenant.edit.profile',$user->id)}}" popover="{{__('Edit')}}">
                                       <i class="mdi mdi-account-edit"></i>
                                   </x-link-with-popover>


                                    <x-modal.button target="tenant_password_change" extra="user_change_password_btn" type="info" dataid="{{$user->id}}">
                                        {{__('Change Password')}}
                                    </x-modal.button>

                                   @php
                                   $select = '<option value="" selected disabled>Select a subdomain</option>';
                                        foreach($user->tenant_details ?? [] as $tenant)
                                        {
                                             $select .= '<option value="'.$tenant->id.'">'.optional($tenant->domain)->domain.'</option>';
                                        }
                                        $select .= '<option value="custom_domain__dd">Add new subdomain</option>';
                                   @endphp
                                   <x-modal.button target="user_add_subscription" extra="user_add_subscription" type="primary" dataid="{{$user->id}}" select-markup="{{$select}}">
                                       {{__('Assign Subscription')}}
                                   </x-modal.button>

                                   <x-modal.button target="send_mail_to_tenant_modal" extra="send_mail_to_tenant_btn" type="success" dataid="{{$user->email}}">
                                       {{__('Send Mail')}}
                                   </x-modal.button>


                                   @if($user->email_verified < 1)
                                       <form action="{{route(route_prefix().'admin.tenant.resend.verify.mail')}}" method="post" enctype="multipart/form-data">
                                           @csrf
                                           <input type="hidden" name="id" value="{{$user->id}}">
                                           <button class="btn btn-secondary mb-3 mr-1 btn-sm " type="submit">{{__('Send Verify Mail')}}</button>
                                       </form>
                                   @endif

                                   <x-link-with-popover url="{{route('landlord.admin.tenant.details',$user->id)}}" class="dark">
                                       {{__('View Details')}}
                                   </x-link-with-popover>
                               </td>
                           </tr>
                       @endforeach
                    </x-slot>
                </x-datatable.table>
            </div>
        </div>
    </div>


{{--Assign Subscription Modal--}}
    <div class="modal fade" id="user_add_subscription" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Assign Subscription')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>

                <form action="{{route('landlord.admin.tenant.assign.subscription')}}" id="user_add_subscription_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="subs_user_id" id="subs_user_id">
                        <input type="hidden" name="subs_pack_id" id="subs_pack_id">

                        <div class="form-group">
                            <label for="">{{__('Select A Package')}}</label>
                            <select class="form-control package_id_selector" name="package">
                                <option value="">{{__('Select Package')}}</option>
                                @foreach(\App\Models\PricePlan::all() as $price)
                                    <option value="{{$price->id}}" data-id="{{$price->id}}">
                                        {{$price->title}} {{ '('.amount_with_currency_symbol($price->price).')' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="subdomain">{{__('Subdomain')}}</label>
                            <select class="form-select subdomain" id="subdomain" name="subdomain">

                            </select>
                        </div>

                        <div class="form-group custom_subdomain_wrapper mt-3">
                            <label for="custom-subdomain">Add new subdomain</label>
                            <input class="form--control custom_subdomain" id="custom-subdomain" type="text" autocomplete="off" value="{{old('subdomain')}}"
                                   placeholder="{{__('Subdomain')}}" style="border:0;border-bottom: 1px solid #595959">
                            <div id="subdomain-wrap"></div>
                        </div>

                        <div class="form-group">
                            <label for="">{{__('Payment Status')}}</label>
                            <select class="form-control" name="payment_status">
                                    <option value="pending">{{__('Pending')}}</option>
                                    <option value="complete">{{__('Complete')}}</option>
                            </select>
                            <small class="text-primary">{{__('You can set payment status pending or complete from here')}}</small>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

{{--Change Password Modal--}}
<div class="modal fade" id="tenant_password_change" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Change Admin Password')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>

                <form action="{{route('landlord.admin.tenant.change.password')}}" id="user_password_change_modal_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="ch_user_id" id="ch_user_id">
                        <div class="form-group">
                            <label for="password">{{__('Password')}}</label>
                            <input type="password" class="form-control" name="password" placeholder="{{__('Enter Password')}}">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">{{__('Confirm Password')}}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="{{__('Confirm Password')}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Change Password')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
{{--Send Mail Modal--}}
<div class="modal fade" id="send_mail_to_tenant_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Send Mail To Subscriber')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route(route_prefix().'admin.tenant.send.mail')}}" id="send_mail_to_subscriber_edit_modal_form"  method="post">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="email">{{__('Email')}}</label>
                            <input type="text" readonly class="form-control"  id="email" name="email" placeholder="{{__('Email')}}">
                        </div>
                        <div class="form-group">
                            <label for="edit_icon">{{__('Subject')}}</label>
                            <input type="text" class="form-control"  id="subject" name="subject" placeholder="{{__('Subject')}}">
                        </div>
                        <div class="form-group">
                            <label for="message">{{__('Message')}}</label>
                            <input type="hidden" name="message" >
                            <div class="summernote"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button id="submit" type="submit" class="btn btn-primary">{{__('Send Mail')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <x-datatable.js/>
    <x-summernote.js/>
    <x-custom-js.landloard-unique-subdomain-check :name="'custom_subdomain'"/>
    //subdomain check

    <script>
        $(document).ready(function(){
            $(document).on('click','.user_change_password_btn',function(e){
                e.preventDefault();
                var el = $(this);

                var form = $('#user_password_change_modal_form');
                form.find('#ch_user_id').val(el.data('id'));
            });


            //Assign Subscription Modal Code
            $(document).on('click','.user_add_subscription',function(){
                let user_id = $(this).data('id');
                $('#subs_user_id').val(user_id);
                $("#user_add_subscription #subdomain").html($(this).data('select-markup'));
            });

            $(document).on('change','.package_id_selector',function (){
                let el = $(this);
                let form = $('.user_add_subscription_form');
                 $('#subs_pack_id').val(el.val());
            });


            let custom_subdomain_wrapper = $('.custom_subdomain_wrapper');
            custom_subdomain_wrapper.hide();
            $(document).on('change', '#subdomain', function (e){
                let el = $(this);
                let subdomain_type = el.val();

                if(subdomain_type == 'custom_domain__dd')
                {
                    custom_subdomain_wrapper.slideDown();
                    custom_subdomain_wrapper.find('#custom-subdomain').attr('name', 'custom_subdomain');
                } else {
                    custom_subdomain_wrapper.slideUp();
                    custom_subdomain_wrapper.removeAttr('#custom-subdomain').attr('name', 'custom_subdomain');
                }
            });
        });
    </script>

    <script>
        (function ($){
            "use strict";
            $(document).ready(function () {

                    $(document).on('click','.send_mail_to_tenant_btn',function(){
                        var el = $(this);
                        var email = el.data('id');

                        var form = $('#send_mail_to_subscriber_edit_modal_form');
                        form.find('#email').val(email);
                    });
                $('.summernote').summernote({
                    height: 300,   //set editable area's height
                    codemirror: { // codemirror options
                        theme: 'monokai'
                    },
                    callbacks: {
                        onChange: function(contents, $editable) {
                            $(this).prev('input').val(contents);
                        }
                    }
                });
            });

        })(jQuery)
    </script>
@endsection

