@extends('landlord.frontend.frontend-page-master')

@section('title')
    {{__('Register')}}
@endsection

@section('page-title')
    {{__('Register')}}
@endsection

@section('content')
    <style>
        .payment-gateway-wrapper ul{
            display: flex;
        }
        .payment-gateway-wrapper ul li img{
            width: 100%;
        }
    </style>

    <section class="signup-area padding-top-100 padding-bottom-100">
        <div class="container">
            <div class="signin-wrappers style-02">
                <div id="msg-wrapper"></div>

                <div class="signin-contents">
                    <span class="singnin-subtitle"> {{__('Hello! Welcome')}} </span>
                    <h2 class="single-title"> {{__('Sign Up')}} </h2>
                    <form class="login-form padding-top-20" action="#" method="POST">
                        <div class="single-input">
                            <label class="label-title mb-3"> {{__('Name')}} </label>
                            <input class="form--control" type="text" name="name" placeholder="Type First Name"
                                   value="{{old('name')}}">
                        </div>
                        <div class="single-input mt-4">
                            <label class="label-title mb-3"> {{__('User Name')}} </label>
                            <input class="form--control" type="text" placeholder="Type User Name" name="username" value="{{old('username')}}">
                        </div>
                        <div class="single-input mt-4">
                            <label class="label-title mb-3"> {{__('Email Address')}} </label>
                            <input class="form--control" type="email" name="email" placeholder="Type Email" value="{{old('email')}}">
                        </div>

                        <x-fields.country-select name="country" label="{{__('Country')}}"/>

                        <div class="input-flex-item">
                            <div class="single-input mt-4">
                                <label class="label-title mb-3"> {{__('Create Password')}} </label>
                                <input class="form--control" type="password" name="password" placeholder="Type Password">
                                <div class="icon toggle-password">
                                    <div class="show-icon"><i class="las la-eye-slash"></i></div>
                                    <span class="hide-icon"> <i class="las la-eye"></i> </span>
                                </div>
                            </div>
                            <div class="single-input mt-4">
                                <label class="label-title mb-3"> {{__('Confirm Password')}} </label>
                                <input class="form--control" type="password" name="password_confirmation" placeholder="Confirm Password">
                                <div class="icon toggle-password">
                                    <div class="show-icon"><i class="las la-eye-slash"></i></div>
                                    <span class="hide-icon"> <i class="las la-eye"></i> </span>
                                </div>
                            </div>
                        </div>

                        <div class="checkbox-inlines mt-4">
                            @php
                                $terms_condition_page = get_page_slug(get_static_option('terms_condition')) ?? '#';
                                $privacy_policy_page = get_page_slug(get_static_option('privacy_policy')) ?? '#';
                            @endphp
                            <input class="check-input agree" name="terms_condition" type="checkbox" id="check15">
                            <label class="checkbox-label" for="check15">{{__('By creating an account, you agree to the')}}
                                <a class="color-one" href="{{$terms_condition_page}}" target="_blank"> {{__('terms and conditions')}}</a> {{__('and')}}
                                        <a class="color-one" href="{{$privacy_policy_page}}" target="_blank"> {{__('privacy policy')}} </a> </label>
                        </div>
                        <button class="submit-btn w-100 mt-4" type="submit" id="register_button"> {{__('Sign Up Now')}} </button>
                        <span class="account color-light mt-3"> {{__('Already have an account?')}}
                            <a class="color-one" href="{{route('landlord.user.login')}}"> {{__('Login')}} </a>
                        </span>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    {{--    Register Via Axax--}}
    <script>
        var registerFormButton = document.getElementById('register_button');
        registerFormButton.addEventListener('click', function (event) {
            event.preventDefault();

            document.getElementById("register_button").disabled = true;

            var msgWrap = document.getElementById('msg-wrapper');
            msgWrap.innerHTML = '';
            registerFormButton.innerText = "{{__('Creating your account')}}"

            let terms = '';
            let checkbox = $('.agree');
            if (checkbox[0].checked)
            {
                terms = 'on';
            }

            axios({
                url: "{{route('landlord.user.register.store')}}",
                method: 'post',
                responseType: 'json',
                data: {
                    name: document.querySelector('input[name="name"]').value,
                    email: document.querySelector('input[name="email"]').value,
                    username: document.querySelector('input[name="username"]').value,
                    password: document.querySelector('input[name="password"]').value,
                    country: document.querySelector('select[name="country"]').value,
                    password_confirmation: document.querySelector('input[name="password_confirmation"]').value,
                    terms_condition: terms,
                    _token: '{{csrf_token()}}'
                }
            }).then(function (response) {

                let $pf_name = $('.name').val();
                let pf_email = $('.email').val();

                registerFormButton.innerText = "{{__('Redirecting..')}}"

                let plan = '{{$plan_id ?? ''}}';
                if (plan != '')
                {
                    @php
                        session()->put('trial-register', __('Account Registration Successful'))
                    @endphp
                    location.href = '{{route('landlord.frontend.plan.view', [$plan_id, 'trial'])}}';
                } else {
                    location.href = '{{route('landlord.user.home')}}';
                }
            }).catch(function (error) {
                registerFormButton.innerText = "{{__('Register')}}"

                if (error.response.status === 422) {
                    var responseData = error.response.data.errors;
                    var child = '<ul class="alert alert-danger">'
                    Object.entries(responseData).forEach(function (value) {
                        child += '<li>' + value[1] + '</li>';
                    });
                    child += '</ul>'
                    msgWrap.innerHTML = child;
                } else {
                    var responeMsg = error.response.data.message;
                    var child = '<ul class="alert alert-danger"><li>' + responeMsg + '</li></ul>';
                    msgWrap.innerHTML = child;
                }

                document.getElementById("register_button").disabled = false;
            });
        })
    </script>
@endsection