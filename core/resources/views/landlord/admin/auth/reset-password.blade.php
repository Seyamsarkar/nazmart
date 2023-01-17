@extends('layouts.app')
@section('title')
    {{__('Reset Password')}}
@endsection
@section('site-title')
    {{__('Reset Password')}}
@endsection
@section('page-title')
    {{__('Reset Password')}}
@endsection

@section('content')
    <div class="row flex-grow">
        <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
                <div class="brand-logo text-center">
                    {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                </div>

                    <span class="singnin-subtitle"> {{__('Hello! Welcome')}} </span>
                    <h3 class="margin-bottom-30">{{__('Reset Password')}}</h3>

                <x-error-msg/>
                <x-flash-msg/>

                @php
                    if (is_null(tenant()))
                        {
                            $route = route('landlord.admin.reset.password.change');
                        } else {
                            $route = route('tenant.admin.reset.password.change');
                        }
                @endphp

                <form action="{{$route}}" method="post" enctype="multipart/form-data" class="contact-page-form style-01">
                    @csrf
                    <input type="hidden" name="token" value="{{$token}}">
                    <div class="form-group single-input">
                        <input type="text" id="username" class="form-control form--control mt-3" readonly value="{{$username}}" name="username">
                    </div>
                    <div class="form-group single-input">
                        <input type="password" id="password" class="form-control form--control mt-3" name="password" placeholder="Enter New Password">
                    </div>
                    <div class="form-group single-input">
                        <input type="password" id="password_confirmation"  class="form-control form--control mt-3" name="password_confirmation" placeholder="Confirm Password">
                    </div>
                    <div class="form-group btn-wrapper">
                        <button class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" id="reset-btn" type="submit">{{__('Reset Password')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (function($){
            "use strict";
            $(document).ready(function () {
                $(document).on('click', '#reset-btn', function (){
                    $(this).text('Resetting Your Password..');
                    $(this).attr('disabled', true);
                    $(this).closest('form').trigger('submit');
                });
            });
        })(jQuery);
    </script>
@endsection

