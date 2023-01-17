@extends('layouts.app')

@section('title')
    {{__('Forget Password')}}
@endsection

@section('page-title')
   {{__('Forget Password')}}
@endsection

@section('content')
    <div class="row flex-grow">
        <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
                <div class="brand-logo">
                    {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                </div>
                <h4>{{__('Hello! let us get started')}}</h4>
                <h6 class="font-weight-light">{{__('Forget Password ?')}}</h6>

                <x-error-msg/>
                <x-flash-msg/>

                @php
                    if (is_null(tenant()))
                        {
                            $route = route('landlord.admin.forget.password');
                        }
                    else {
                        $route = route('tenant.admin.forget.password');
                    }
                @endphp
                <form action="{{$route}}" method="post" enctype="multipart/form-data" class="contact-page-form style-01">
                    @csrf
                    <div class="form-group single-input">
                        <input type="text" name="username" class="form-control form--control" placeholder="{{__('Username')}}">
                    </div>
                    <div class="form-group btn-wrapper mt-4">
                        <button type="submit" id="send" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">{{__('Send Reset Mail')}}</button>
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
            $(document).on('click', '#send', function (){
                $(this).text('Sending..');
                $(this).attr('disabled', true);
                $(this).closest('form').trigger('submit');
            });
        });
        })(jQuery);
    </script>
@endsection
