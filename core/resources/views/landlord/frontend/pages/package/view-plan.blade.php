@extends('landlord.frontend.frontend-page-master')

@section('title')
    {{$order_details->title}}
@endsection

@section('page-title')
    {{__('View Plan')}} {{' : '.$order_details->title}}
@endsection

@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/common/css/toastr.css')}}">

    <style>
        .theme-wrapper {
            border: 1px solid transparent;
            outline: 1px solid transparent;
        }

        .selected_theme {
            transition: 0.3s;
            border-color: var(--main-color-one);
            outline-color: var(--main-color-one);
            padding: 10px;
        }

        .selected_text {
            top: 0;
            left: 11px;
            background-color: var(--main-color-one);
            padding: 15px;
            position: absolute;
            color: white;
            transition: 0.3s;
        }

        .selected_text i {
            font-size: 30px;
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
        .site-logo{
            width: 50%;
            margin-bottom: 30px;
        }

    </style>
@endsection

@section('content')
    @php
        $user_lang = get_user_lang();
        $user = Auth::guard('web')->user();
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

    <section class="order-service-page-content-area padding-100">
        <div class="container">
            @if(session()->get('trial-register'))
                <div class="alert alert-success text-center mb-3">
                    <p>{{session()->get('trial-register')}}</p>
                </div>

                @php
                    session()->forget('trial-register')
                @endphp
            @endif
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8">
                    <div class="section-title margin-bottom-60">
                        <h2 class="title">{{$order_details->title}}</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-3 col-lg-4 col-md-5 col-sm-8">
                    <div class="price-plan-left-wrap">
                        <div class="price-header">
                            <h3 class="title"></h3>
                            <div class="price-wrap">
                                <span class="price">{{amount_with_currency_symbol($order_details->price)}}</span><br>
                                <span
                                    class="price-month mt-3">{{\App\Enums\PricePlanTypEnums::getText($order_details->type)}}</span>
                            </div>
                        </div>
                        <div class="price-footer btn-wrapper">
                            @if($trial)
                                @php
                                    if(Auth::guard('web')->check() != true)
                                        {
                                            $button_attr = 'data-bs-target="#loginModal"';
                                        } else {
                                            $button_attr = 'data-bs-target="#trialModal"';
                                        }
                                @endphp
                                <a href="javascript:void(0)" class="cmn-btn cmn-btn-outline-one color-one mt-4"
                                   data-bs-toggle="modal" {!! $button_attr !!}>{{__('Start Trial')}}</a>

                            @else
                                <a href="{{route('landlord.frontend.plan.order',$order_details->id)}}"
                                   class="cmn-btn cmn-btn-outline-one color-one mt-4">{{__('Buy Now')}}</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-8 col-md-7 col-sm-8">
                    <div class="single-price-plan-item price-plan-two">
                        <div class="price-body features-view-all">
                            <div class="row">
                                <div class="col-6">
                                    <ul class="features">
                                        <p class="font-weight-bold">{{__('Limitations')}}</p>

                                        @if(!empty($order_details->page_permission_feature))
                                            <li class="check"> {{ sprintf(__('Page %d'),$order_details->page_permission_feature )}}</li>
                                        @endif

                                        @if(!empty($order_details->blog_permission_feature))
                                            <li class="check"> {{ sprintf(__('Blog %d'),$order_details->blog_permission_feature )}}</li>
                                        @endif

                                        @if(!empty($order_details->product_permission_feature))
                                            <li class="check"> {{ sprintf(__('Product %d'),$order_details->product_permission_feature )}}</li>
                                        @endif

                                        @if(!empty($order_details->storage_permission_feature))
                                            <li class="check"> {{ sprintf(__('Storage %d MB'),$order_details->storage_permission_feature )}}</li>
                                        @endif
                                    </ul>
                                </div>

                                <div class="col-6">
                                    <ul class="features">
                                        @foreach($order_details->plan_features as $key=> $item)
                                            @if($loop->first)
                                                <p class="font-weight-bold">{{__('Features')}}</p>
                                            @endif
                                            <li class="check"> {{str_replace('_', ' ',ucfirst($item->feature_name)) ?? ''}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        $faq_items = !empty($order_details->faq) ? unserialize($order_details->faq,['class' => false]) : ['title' => []];
                         $rand_number = rand(9999,99999999);
                    @endphp
                    @if(!empty(current($faq_items['title'])) )
                        <div class="accordion-wrapper mt-5">
                            <h3 class="title my-3">{{__('General Question')}}</h3>
                            <div id="accordion_{{$rand_number}}" class="mt-4">
                                @foreach($faq_items['title'] as $faq)
                                    @php
                                        $aria_expanded = 'false';
                                    @endphp
                                    <div class="card my-2" itemscope itemprop="mainEntity"
                                         itemtype="https://schema.org/Question">
                                        <div class="card-header" id="headingOne_{{$loop->index}}"
                                             itemprop="name">
                                            <h5 class="mb-0">
                                                <a data-bs-toggle="collapse"
                                                   data-bs-target="#collapseOne_{{$loop->index}}" role="button"
                                                   aria-expanded="{{$aria_expanded}}"
                                                   aria-controls="collapseOne_{{$loop->index}}">
                                                    {{purify_html($faq)}}
                                                </a>
                                            </h5>
                                        </div>

                                        <div id="collapseOne_{{$loop->index}}"
                                             class="collapse {{$loop->index == 0 ? 'show' : ''}} "
                                             aria-labelledby="headingOne_{{$loop->index}}"
                                             data-parent="#accordion_{{$rand_number}}" itemscope
                                             itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                            <div class="card-body" itemprop="text">
                                                {{purify_html($faq_items['description'][$loop->index] ?? '')}}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <div class="row my-5">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <h3>{{__('Select Theme')}}</h3>

                    <div class="row theme-row mt-5">
                        @foreach($themes as $theme)
                            <div class="col-3" style="position: relative">
                                <div class="theme-wrapper {{$loop->first ? 'selected_theme' : ''}}"
                                     data-theme="{{$theme->slug}}" data-name="{{$theme->title}}">
                                    <div class="theme-wrapper-bg" style="background-image: url({{get_theme_image($theme->slug)}})"></div>
                                    <h4 class="text-center mt-2">{{$theme->title}}</h4>

                                    @if($loop->first)
                                        <h4 class="selected_text"><i class="las la-check-circle"></i></h4>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="auth-form-light text-left p-5">
                        <div class="brand-logo site-logo">
                            {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                        </div>
                        <h4>{{__('Hello! let us get started')}}</h4>
                        <h6 class="font-weight-light">{{__('Sign in to continue.')}}</h6>
                        <x-error-msg/>
                        <x-flash-msg/>
                        <div id="msg-wrapper"></div>
                        <div class="error-box mt-3 d-none">
                            <ul class="alert alert-danger"></ul>
                        </div>
                        <form class="pt-3" action="" method="post" id="login_form_order_page">
                            <div class="form-group single-input">
                                <input type="email" name="username" class="form-control form--control form-control-lg"
                                       placeholder="{{__('Username')}}">
                            </div>
                            <div class="form-group single-input mt-4">
                                <input type="password" name="password"
                                       class="form-control form--control form-control-lg"
                                       placeholder="{{__('Password')}}">
                            </div>
                            <div class="btn-wrapper mt-4">
                                <button type="submit"
                                        class="cmn-btn cmn-btn-bg-1 cmn-btn-small"
                                        id="login_btn">{{__('SIGN IN')}}</button>
                            </div>
                            <div class="mt-4 d-flex flex-wrap justify-content-between align-items-center">
                                <div class="checkbox-inlines">
                                    <input class="check-input" type="checkbox" id="check15">
                                    <label class="form-check-label text-muted" id="check15">
                                        {{__('Keep me signed in')}}
                                    </label>
                                </div>
                                <a href="{{route('tenant.user.forget.password')}}" class="auth-link text-black">{{__('Forgot password?')}}</a>
                            </div>
                            <div class="text-left mt-4 font-weight-light"> {{__('Do not have an account?')}} <a
                                    href="{{route('landlord.user.register')}}?p={{$order_details->id}}" class="color-one">{{__('Create')}}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::guard('web')->check())
        <div class="modal fade" id="trialModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{__('Start Trial')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="error-wrap"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <h5>{{__('Personal Information')}}</h5>
                                <hr>
                                <p><strong>{{__('Name:')}}</strong> <span>{{$user->name}}</span></p>
                                <p><strong>{{__('Email:')}}</strong> <span>{{$user->email}}</span></p>
                                <p class="mt-4">{{__('Subdomain:')}} <input class="form--control" type="text"
                                                                            name="subdomain" autocomplete="off" value=""
                                                                            placeholder="{{__('Example.Nazmat.com')}}"
                                                                            style="border:0;border-bottom: 1px solid #595959">
                                </p>
                                <div id="subdomain-wrap"></div>
                            </div>

                            <div class="col-6">
                                <h5>{{__('Package Information')}}</h5>
                                <hr>
                                <p><strong>{{__('Plan:')}}</strong> <span>{{$order_details->title}}</span></p>
                                <p><strong>{{__('Price:')}}</strong>
                                    <span>{{amount_with_currency_symbol($order_details->price)}}</span></p>
                                <p><strong>{{__('Trial:')}}</strong> <span>{{$order_details->trial_days}} Days</span>
                                </p>
                                <p class="modal_theme"><strong>{{__('Theme:')}}</strong> <span></span></p>
                            </div>

                            <form action="" class="mt-5" method="POST">
                                <input type="hidden" name="user_id" id="user-id" value="{{$user->id}}">
                                <input type="hidden" name="order_id" id="order-id" value="{{$order_details->id}}">
                                <input type="hidden" name="theme_slug" id="theme-slug" value="theme-1">

                                <div class="parent d-flex justify-content-end btn-wrapper">
                                    <button type="button" class="cmn-btn cmn-btn-bg-1 cmn-btn-small"
                                            id="create-trial-account-button">
                                        {{__('Create Website')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script src="{{global_asset('assets/common/js/toastr.min.js')}}"></script>
    <x-custom-js.landloard-unique-subdomain-check :name="'subdomain'"/>
    //subdomain check

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
                        type: 'post',
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
                            formContainer = $('.error-box');
                            formContainer.find('li').remove();
                            formContainer.removeClass('d-none');

                            $.each(response, function (value, index) {
                                formContainer.find('ul').append('<li>' + index + '</li>');
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
                    if (gateway === 'manual_payment') {
                        $('.manual_transaction_id').removeClass('d-none');
                    } else {
                        $('.manual_transaction_id').addClass('d-none');
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

                $(document).on('click', '#create-trial-account-button', function (e) {
                    e.preventDefault();
                    var timer = "";
                    var submit_button = $('#create-trial-account-button');
                    var text = ['Creating Account...', 'Creating Database...', 'Creating Designs...', 'Getting Ready...', 'Your Account is Ready...'];

                    let i = 1;

                    function buttonLoop(isRunning) {
                        if (isRunning) {
                            timer = setTimeout(function () {
                                submit_button.text(text[i]);
                                if (i < 5) {
                                    buttonLoop(true);
                                }
                                i++;
                            }, 1500)
                        } else {
                            clearTimeout(timer);
                        }
                    }

                    let user_id = $('#user-id').val();
                    let order_id = $('#order-id').val();
                    let subdomain = $('input[name=subdomain]').val();
                    let theme = $('input[name=theme_slug]').val();

                    let formContainer = $('.modal-body');

                    let loader = $('.loader');
                    $.ajax({
                        type: 'post',
                        url: "{{route('landlord.frontend.trial.account')}}",
                        data: {
                            _token: "{{csrf_token()}}",
                            user_id: user_id,
                            order_id: order_id,
                            subdomain: subdomain,
                            theme: theme
                        },
                        beforeSend: function () {
                            loader.show();
                            loader.find('.loader_bottom_title').text('{{__('Your trial account is on its way. Why don’t you grab a coffee?')}}');
                            submit_button.prop('disabled', true);
                            submit_button.text(text[0]);

                            buttonLoop(true);
                        },
                        success: function (data) {
                            buttonLoop(false);

                            if (data.type === 'success') {
                                loader.find('.loader_bottom_title').fadeOut();
                                loader.find('.loader_bottom_title').fadeIn();
                                loader.find('.loader_bottom_title').text('{{__('Yeaah! Your account is ready. Lets check it out')}}');
                                submit_button.parent().after(text);
                                setTimeout(function () {
                                    location.href = data.url;
                                }, 3000);
                            } else if (data.type === 'danger') {
                                loader.hide();
                                formContainer.find('.error-wrap').html('<ul class="alert alert-danger"></ul>');
                                formContainer.find('.error-wrap ul').append('<li>' + data.msg + '</li>')
                                $('input[name=subdomain]').val('');

                                submit_button.text('Create Website');
                                submit_button.prop('disabled', false);
                            }
                        },
                        error: function (data) {
                            $('.loader').hide();

                            let i = 0;
                            buttonLoop(false);

                            submit_button.text('Create Account');
                            submit_button.prop('disabled', false);

                            var response = data.responseJSON.errors
                            formContainer.find('.error-wrap').html('<ul class="alert alert-danger"></ul>');
                            $.each(response, function (value, index) {
                                formContainer.find('.error-wrap ul').append('<li> <span>' + ++i + '.</span> ' + index + '</li>');
                            });
                        }
                    });
                });

                let row = $('.theme-row');
                let col = row.children('.col-3').first();
                let name = col.find('.theme-wrapper').data('name');
                $('p.modal_theme').find('span').text(name);


                $(document).on('click', '.theme-wrapper', function (e) {
                    let el = $(this);
                    let theme_slug = el.data('theme');
                    let theme_name = el.data('name');

                    $('.theme-wrapper').removeClass('selected_theme');
                    el.addClass('selected_theme');

                    let text = '<h4 class="selected_text"><i class="las la-check-circle"></i></h4>';
                    $('.selected_text').remove();
                    el.append(text);

                    $('input#theme-slug').val(theme_slug);
                    $('p.modal_theme').find('span').text(theme_name);
                });
            });
        })(jQuery);
    </script>
@endsection
