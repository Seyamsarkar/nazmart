@extends('landlord.frontend.frontend-page-master')
@section('title')
    {{$order_details->title}}
@endsection

@section('page-title')
    {{__('Order For')}} {{' : '.$order_details->title}}
@endsection

@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/common/css/toastr.css')}}">

    <style>
        .add_new-domain {
            margin-bottom: 10px;
        }

        .add_new-domain i {
            border: 2px solid #00000052;
            padding: 0 20px;
            font-size: 30px;
            border-radius: 5px;
            color: #00000073;
        }

        .payment-gateway-wrapper ul {
            flex-wrap: wrap;
            display: flex;
        }

        .payment-gateway-wrapper ul li {
            max-width: 100px;
            cursor: pointer;
            box-sizing: border-box;
            height: 50px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .payment-gateway-wrapper ul li {
            margin: 3px;
            border: 1px solid #ddd;
        }


        .payment-gateway-wrapper ul li.selected:after, .payment-gateway-wrapper ul li.selected:before {
            visibility: visible;
            opacity: 1;
        }

        .payment-gateway-wrapper ul li:before {
            border: 2px solid var(--main-color-one);
            position: absolute;
            right: 0;
            top: 0;
            width: 100%;
            height: 100%;
            content: '';
            visibility: hidden;
            opacity: 0;
            transition: all .3s;
        }

        .payment-gateway-wrapper ul li.selected:after, .payment-gateway-wrapper ul li.selected:before {
            visibility: visible;
            opacity: 1;
        }

        .payment-gateway-wrapper ul li:after {
            position: absolute;
            right: 0;
            top: 0;
            width: 15px;
            height: 15px;
            background-color: var(--main-color-one);
            content: "\f00c";
            font-weight: 900;
            color: #fff;
            font-family: 'Line Awesome Free';
            font-weight: 900;
            font-size: 10px;
            line-height: 10px;
            text-align: center;
            padding-top: 2px;
            padding-left: 2px;
            visibility: hidden;
            opacity: 0;
            transition: all .3s;
        }

        .plan_warning small {
            font-size: 15px;
        }

        .order-btn:disabled {
            background-color: transparent;
            color: var(--main-color-one);
            border: 2px solid var(--main-color-one);
        }

        .loader.loader_page_single {
            z-index: 999999;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            width: 100%;
            background: rgba(255, 255, 255, .9);
            position: fixed;
            display: none;
        }

        .loader_bottom_title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translateX(-50%);
            margin-top: 80px;
            width: 100%;
            text-align: center;
        }

        .alert_list_inline {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .alert_list_inline .close {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            color: red;
            font-size: 20px;
            height: 30px;
            width: 30px;
            border: 0;
            outline: none;
        }
        .input-group-text{
            background: #fff;
        }
    </style>
@endsection

@section('content')
    @php
        $user_lang = get_user_lang();
    @endphp

    {{--     Specific loader--}}
    <div class="loader loader_page_single">
        <div class="loader_wrapper">
            <div class="loader-01">
                <span class="loader__icon loader__icon--one"></span>
                <span class="loader__icon loader__icon--two"></span>
                <span class="loader__icon loader__icon--three"></span>
                <span class="loader__icon loader__icon--four"></span>
            </div>
            <h3 class="loader_bottom_title"></h3>
        </div>
    </div>

    <section class="order-service-page-content-area padding-top-70 padding-bottom-100">
        <div class="container">
            <div class="row gx-5 reorder-xs justify-content-between">
                <div class="col-lg-8 mt-4">
                    <div class="order-content-area">
                        <h3 class="signin-contents-title">{{get_static_option('order_page_'.$user_lang.'_form_title')}}</h3>

                        @if(count($payment_old_data) > 0)
                            <h5 class="text-left mt-1 mb-4 mt-4 alert alert-primary plan_warning">
                                @if(count($payment_old_data) == 1)
                                    <small>{{__('You have already subscribed a plan. If you purchase any package than your old package will be replaced with extended validity!!')}}</small>
                                @else
                                    <small>{{__('You have already subscribed multiple plans. If you purchase any package than your old package will be replaced with extended validity!!')}}</small>
                                @endif
                            </h5>
                        @endif

                        <x-flash-msg/>
                        <x-error-msg/>

                        @if(!auth()->guard('web')->check())
                            <div class="login-form custom--form mt-4">
                                <form action="" method="POST" enctype="multipart/form-data"
                                      class="contact-page-form style-01" id="login_form_order_page">
                                    <div class="alert alert-warning alert-block text-center">
                                        <strong>{{ __('You must login or create an account to order your package!') }}</strong>
                                    </div>
                                    <div class="error-wrap"></div>
                                    <div class="form-group single-input">
                                        <input type="text" name="username" class="form-control form--control"
                                               placeholder="{{__('Username')}}">
                                    </div>
                                    <div class="form-group single-input mt-4">
                                        <input type="password" name="password" class="form-control form--control"
                                               placeholder="{{__('Password')}}">
                                    </div>
                                    <div class="form-group btn-wrapper single-input mt-4">
                                        <button class="cmn-btn cmn-btn-bg-1 w-100" id="login_btn"
                                                type="submit">{{__('Login')}}</button>
                                    </div>
                                    <div class="form-group single-input mt-4 rmber-area">
                                        <div class="box-wrap">
                                            <div class="custom-control custom-checkbox">
                                                <div class="checkbox-inlines">
                                                    <input type="checkbox" name="remember"
                                                           class="custom-control-input check-input" id="remember">
                                                    <label class="custom-control-label checkbox-label"
                                                           for="remember">{{__('Remember Me')}}</label>
                                                </div>
                                            </div>
                                            <a href="{{route('tenant.user.forget.password')}}"
                                               class="forgot-btn color-one">{{__('Forgot Password?')}}</a>
                                        </div>
                                    </div>
                                    <div class="form-group single-input mt-4">
                                        <a class="d-block"
                                           href="{{route('tenant.user.register')}}">{{__('Create new account?')}}</a>
                                    </div>
                                </form>
                            </div>
                        @else

                            <form action="{{ route('landlord.frontend.order.payment.form') }}" method="post"
                                  enctype="multipart/form-data"
                                  class="contact-page-form style-01 custom--form order-form">
                                @csrf
                                @php
                                    $custom_fields = unserialize($order_details->custom_fields);
                                    $payment_gateway = !empty($custom_fields['selected_payment_gateway']) ? $custom_fields['selected_payment_gateway'] : '';
                                    $name = auth()->guard('web')->check() ? auth()->guard('web')->user()->name : '';
                                    $email = auth()->guard('web')->check() ? auth()->guard('web')->user()->email : '';
                                @endphp
                                <input type="hidden" name="payment_gateway" value=""
                                       class="payment_gateway_passing_clicking_name">
                                <input type="hidden" name="package_id" value="{{$order_details->id}}">

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group single-input">
                                            <label for="name" class="label-title mb-3">{{__('Type Name')}}</label>
                                            <input type="text" id="name" name="name"
                                                   value="{{ auth()->guard('web')->user()->name }}"
                                                   class="form-control form--control"
                                                   placeholder="Name" readonly>
                                        </div>
                                        <div class="form-group single-input mt-4">
                                            <label for="email" class="label-title mb-3">{{__('Type Email')}}</label>
                                            <input type="email" id="email" name="email"
                                                   value="{{ auth()->guard('web')->user()->email }}"
                                                   class="form-control form--control" placeholder="Your Email" readonly>
                                        </div>

                                        <div class="form-group single-input mt-4">
                                            @auth('web')
                                                @php
                                                    $user = Auth::guard('web')->user();
                                                @endphp
                                            @endauth

                                            <label for="subdomain"
                                                   class="label-title mb-3">{{__('Add new subdomain')}}</label>
                                            <select class="form-select form--control subdomain" id="subdomain"
                                                    name="subdomain">
                                                <option
                                                    value="custom_domain__dd"
                                                    selected>{{__('Add new subdomain')}}</option>
                                                @foreach($user->tenant_details ?? [] as $tenant)
                                                    <option
                                                        value="{{$tenant->id}}">{{optional($tenant->domain)->domain}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group custom_subdomain_wrapper mt-3 mb-4">
                                            <label for="custom-subdomain"
                                                   class="label-title mb-3">{{__('Add new subdomain')}}</label>

                                            <div class="input-group mb-3">
                                                @php
                                                    $base_url = str_replace(['http://','https://'], '', url('/'));
                                                @endphp
                                                <input type="text" class="form-control custom_subdomain" id="custom-subdomain" name="custom_subdomain" placeholder="{{__('Subdomain')}}" aria-label="Subdomain" aria-describedby="basic-addon2" autocomplete="off" value="">
                                                <span class="input-group-text" id="basic-addon2">.{{$base_url}}</span>
                                            </div>

                                            <div id="subdomain-wrap"></div>
                                        </div>

                                        @if($order_details->price != 0)
                                            {!! render_payment_gateway_for_form() !!}
                                        @endif

                                        <div class="form-group single-input d-none manual_transaction_id mt-4">
                                            @if(!empty($payment_gateways))
                                                <p>{{json_decode($payment_gateways->credentials)->description ?? ''}}</p>
                                            @endif

                                            <input type="text" name="trasaction_id"
                                                   class="form-control form--control mt-2"
                                                   placeholder="Transaction ID">

                                            <input type="file" name="trasaction_attachment"
                                                   class="form-control form--control mt-2"
                                                   placeholder="Transaction ID" accept="image/*">
                                        </div>

                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group btn-wrapper mt-4">
                                            <button class="boxed-btn btn-saas btn-block order-btn cmn-btn cmn-btn-bg-1"
                                                    type="submit">{{__('Order Package')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4 mt-4">
                    <div class="single-price-plan-item">
                        <div class="price-header">
                            <h3 class="title mb-4">{{$order_details->title}}</h3>
                            <div class="price-wrap"><span
                                    class="price">{{amount_with_currency_symbol($order_details->price)}}</span>{{$order_details->period}}
                            </div>
                        </div>

                        <div class="price-body features-view-all mt-4">
                            <ul class="features">
                                @if(!empty($order_details->page_permission_feature))
                                    <li class="check"> {{ sprintf(__('Page Create %d'),$order_details->page_permission_feature )}}</li>
                                @endif

                                @if(!empty($order_details->blog_permission_feature))
                                    <li class="check"> {{ sprintf(__('Blog Create %d'),$order_details->blog_permission_feature )}}</li>
                                @endif

                                @if(!empty($order_details->product_permission_feature))
                                    <li class="check"> {{ sprintf(__('Product Create %d'),$order_details->product_permission_feature )}}</li>
                                @endif

                                @if(!empty($order_details->storage_permission_feature))
                                    <li class="check"> {{ sprintf(__('Storage Limit %d MB'),$order_details->storage_permission_feature )}}</li>
                                @endif

                                @foreach($order_details->plan_features as $key=> $item)
                                    @if($loop->first)
                                        <p class="mt-4 font-weight-bold">{{__('Features')}}</p>
                                    @endif
                                    <li class="check"> {{str_replace('_', ' ',ucfirst($item->feature_name)) ?? ''}}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="price-footer pb-0">
                            @php
                                $validity = match ($order_details->type)
                                {
                                    0 => __('1 Month'),
                                    1 => __('1 Year'),
                                    2 => __('Lifetime')
                                }
                            @endphp
                            <h4 class="mt-4">
                                <span>{{__('Validity:')}}</span>
                                <span>{{$validity}}</span>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{global_asset('assets/common/js/toastr.min.js')}}"></script>
    <x-custom-js.landloard-unique-subdomain-check :name="'custom_subdomain'"/> //subdomain check

    <script>
        (function ($) {
            "use strict";
            $(document).ready(function ($) {
                    $(document).on('click', '#order_pkg_btn', function () {
                        var name = $("#order_name").val()
                        var email = $("#order_email").val()
                        sessionStorage.pkg_user_name = name;
                        sessionStorage.pkg_user_email = email;
                    })

                $(document).on('click', '#login_btn', function (e) {
                    e.preventDefault();

                    var formContainer = $('#login_form_order_page');
                    var el = $(this);
                    var username = formContainer.find('input[name="username"]').val();
                    var password = formContainer.find('input[name="password"]').val();
                    var remember = formContainer.find('input[name="remember"]').val();

                    el.text('{{__("Please Wait")}}');

                    $.ajax({
                        type: 'POST',
                        url: "{{route('landlord.user.ajax.login')}}",
                        data: {
                            _token: "{{csrf_token()}}",
                            username: username,
                            password: password,
                            remember: remember,
                        },
                        success: function (data) {
                            if (data.status == 'invalid') {
                                el.text('{{__("Login")}}')
                                formContainer.find('.error-wrap').html('<div class="alert alert-danger">' + data.msg + '</div>');
                            } else {
                                formContainer.find('.error-wrap').html('');
                                el.text('{{__("Login Success.. Redirecting ..")}}');
                                location.reload();
                            }
                        },
                        error: function (data) {
                            var response = data.responseJSON.errors
                            formContainer.find('.error-wrap').html('<ul class="alert alert-danger"></ul>');
                            $.each(response, function (value, index) {
                                formContainer.find('.error-wrap ul').append('<li>' + index + '</li>');
                            });
                            el.text('{{__("Login")}}');
                        }
                    });
                });

                var defaulGateway = $('#site_global_payment_gateway').val();
                $('.payment-gateway-wrapper ul li[data-gateway="' + defaulGateway + '"]').addClass('selected');

                $(document).on('click', '.payment-gateway-wrapper > ul > li', function (e) {
                    e.preventDefault();

                    let gateway = $(this).data('gateway');
                    let manual_transaction_div = $('.manual_transaction_id');
                    let summernot_wrap_div = $('.summernot_wrap');

                    if (gateway === 'manual_payment') {
                        manual_transaction_div.fadeIn();
                        summernot_wrap_div.fadeIn();
                        manual_transaction_div.removeClass('d-none');
                    } else {
                        manual_transaction_div.addClass('d-none');
                        summernot_wrap_div.fadeOut();
                        manual_transaction_div.fadeOut();
                    }

                    $(this).addClass('selected').siblings().removeClass('selected');
                    $('.payment-gateway-wrapper').find(('input')).val($(this).data('gateway'));
                    $('.payment_gateway_passing_clicking_name').val($(this).data('gateway'));
                });

                $(document).on('change', '#guest_logout', function (e) {
                    e.preventDefault();
                    var infoTab = $('#nav-profile-tab');
                    var nextBtn = $('.next-step-button');
                    if ($(this).is(':checked')) {
                        $('.login-form').hide();
                        infoTab.attr('disabled', false).removeClass('disabled');
                        nextBtn.show();
                    } else {
                        $('.login-form').show();
                        infoTab.attr('disabled', true).addClass('disabled');
                        nextBtn.hide();
                    }
                });

                $(document).on('click', '.next-step-button', function (e) {
                    var infoTab = $('#nav-profile-tab');
                    infoTab.attr('disabled', false).removeClass('disabled').addClass('active').siblings().removeClass('active');
                    $('#nav-profile').addClass('show active').siblings().removeClass('show active');
                });

                let custom_subdomain_wrapper = $('.custom_subdomain_wrapper');
                $(document).on('change', '#subdomain', function (e) {
                    $('.order-btn').attr('disabled', false)

                    let el = $(this);
                    let subdomain_type = el.val();

                    if (subdomain_type == 'custom_domain__dd') {
                        custom_subdomain_wrapper.slideDown();
                    } else {
                        custom_subdomain_wrapper.slideUp();
                        custom_subdomain_wrapper.find('input').val('');
                    }
                });


                $(document).on('click', '.order-btn', function () {
                    $('.loader').show();
                    $('.loader .loader_bottom_title').text('{{__('Your account is on its way. Why don’t you grab a coffee?')}}');
                    $(this).attr('disabled', true);
                    $('.order-form').trigger('submit');
                });
            });
        })(jQuery);
    </script>
@endsection
