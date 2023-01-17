@extends('landlord.frontend.frontend-page-master')

@section('title')
    {{__('Register')}}
@endsection

@section('page-title')
    {{__('Register')}}
@endsection

@section('content')

    <div class="sign-in-area-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-8">
                    <div class="sign-up register">
                        <h4 class="title">{{__('sign up')}}</h4>
                        <div class="form-wrapper">
                            <x-error-msg/>
                            <x-flash-msg/>
                            <form action="{{route('landlord.user.register.store')}}" method="post" enctype="multipart/form-data" class="contact-page-form style-01">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{__('Name')}}<span
                                                        class="required">*</span></label>
                                            <input type="text" class="form-control" name="name" id="exampleInputEmail1"
                                                   placeholder="Type your Name" value="{{old('name')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{__('Username')}}<span
                                                        class="required">*</span></label>
                                            <input type="text" class="form-control" name="username" id="exampleInputEmail1"
                                                   placeholder="Type Last Name" value="{{old('username')}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{__('Email Address')}}<span class="required">*</span></label>
                                    <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Type your mail">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{__('Country')}}<span class="required">*</span></label>
                                   {!! get_country_field('country','country','form-control') !!}
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{__('City')}}<span class="required">*</span></label>
                                    <input type="text" name="city" class="form-control" id="exampleInputEmail1" placeholder="Type your city" value="{{old('city')}}">
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{__('Password')}}<span
                                                        class="required">*</span></label>
                                            <input type="password" name="password" class="form-control" id="exampleInputPassword1"
                                                   placeholder="Password">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{__('Confirmed Password')}}<span
                                                        class="required">*</span></label>
                                            <input type="password" name="password_confirmation" class="form-control" id="exampleInputPassword1"
                                                   placeholder="Confirmed Password">
                                        </div>
                                    </div>
                                </div>

                                <div class="btn-wrapper">
                                    <button type="submit" class="btn-default rounded-btn">{{__('sign up')}}</button>
                                </div>
                            </form>
                            <p class="info">{{__('Already have an Account?')}} <a href="{{route('landlord.user.login')}}" class="active">{{__('Sign in')}}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script>
        (function($){
        "use strict";
        $(document).ready(function () {
            <x-btn.custom :id="'register'" :title="__('Please Wait..')"/>
        });
        })(jQuery);
    </script>
@endsection
