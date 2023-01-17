@extends('tenant.frontend.frontend-page-master')

@section('title')
    {{__('User Login')}}
@endsection

@section('page-title')
    {{__('User Login')}}
@endsection

@section('content')
    <!-- sign-in area start -->
    <div class="sign-in-area-wrapper" data-padding-top="50" data-padding-bottom="50">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="sign-in register">
                        <h4 class="title">sign in</h4>
                        <div class="form-wrapper">
                            <x-error-msg/>
                            <x-flash-msg/>
                            <form action="" method="post" enctype="multipart/form-data" class="account-form" id="login_form_order_page">
                                <div class="error-wrap"></div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{__('Username')}}<span class="required">*</span></label>
                                    <input type="text" name="username" class="form-control" id="exampleInputEmail1" placeholder="Type your username">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{__('Password')}}<span class="required">*</span></label>
                                    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                                </div>

                                <div class="form-group form-check">
                                    <div class="box-wrap">
                                        <div class="left">
                                            <input type="checkbox" name="remember" class="form-check-input" id="exampleCheck1">
                                            <label class="form-check-label" for="exampleCheck1">{{__('Remember me')}}</label>
                                        </div>
                                        <div class="right">
                                            <a href="{{route('tenant.user.forget.password')}}">{{__('Forgot Password?')}}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-wrapper">
                                    <button type="submit" id="login_btn" class="btn-default rounded-btn">{{__('Sign In')}}</button>
                                </div>

                            </form>
                            <p class="info">{{__("Don'/t have an account")}} <a href="{{route('tenant.user.register')}}" class="active">{{__('Sign up')}}</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- sign-in area end -->
@endsection
@section('scripts')
   <x-custom-js.ajax-login/>
@endsection
