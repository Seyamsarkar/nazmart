<!DOCTYPE html>
<html lang="{{ \App\Facades\GlobalLanguage::user_lang_slug() }}"
      dir="{{ \App\Facades\GlobalLanguage::user_lang_dir() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    @php
        $theme = '';
        $theme_number = '';
            if (tenant())
            {
                    switch (tenant()->theme_slug){
                    case 'theme-1';
                    $theme = 'theme-01';
                    $theme_number = 'theme_one';
                    break;

                    case 'theme-2';
                    $theme = 'theme-02';
                    $theme_number = 'theme_two';
                    break;

                    case 'theme-3';
                    $theme = 'theme-03';
                    $theme_number = 'theme_three';
                    break;
                }
            }
    @endphp

    {!! load_google_fonts($theme_number) !!}
    {!! render_favicon_by_id(get_static_option('site_favicon')) !!}

    <title>
        @if(!request()->routeIs('tenant.frontend.homepage'))
            @yield('title')
            -
            {{get_static_option('site_title')}}
        @else
            {{get_static_option('site_title')}}
            @if(!empty(get_static_option('site_tag_line')))
                - {{get_static_option('site_tag_line')}}
            @endif
        @endif
    </title>

    {!! render_favicon_by_id(filter_static_option_value('site_favicon', $global_static_field_data)) !!}
    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/animate.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/slick.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/nice-select.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/line-awesome.min.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/odometer.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/common.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/tenant/themes/css/'.$theme.'/'.$theme.'.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/helpers.css')}}">
    <link rel="stylesheet" href="{{ global_asset('assets/common/css/toastr.css') }}">
    <link rel="stylesheet" href="{{global_asset('assets/common/css/loader-01.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/style.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/custom-style.css')}}">

    @if(\App\Facades\GlobalLanguage::user_lang_dir() == 'rtl')
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/rtl.css')}}">
    @endif

    @if(request()->routeIs('tenant.frontend.homepage'))
        @include('tenant.frontend.partials.meta-data')
    @else
        @yield('meta-data')
    @endif

    @include('tenant.frontend.partials.css-variable', ['theme_number' => $theme_number])
    @yield('style')

    <style>
        :root{
            --form-bg-color: #F2F3F5;
        }

        /*----------------------------------------------
            # Order history
        ----------------------------------------------*/
        .order-history-inner {
            overflow-x: auto;
        }
        .order-history-inner table {
            border: 1px solid var(--form-bg-color);
            border-radius: 4px;
            width: 100%;
        }
        .order-history-inner table thead {
            background-color: var(--form-bg-color);
        }
        .order-history-inner table thead tr {
            display: table-row;
        }
        .order-history-inner table thead tr th {
            display: table-cell;
            font-size: 16px;
            line-height: 26px;
            color: #666666;
            text-transform: capitalize;
            padding: 15px 30px;
        }
        .order-history-inner table tbody tr {
            border: 1px solid var(--form-bg-color);
            display: table-row;
        }
        .order-history-inner table tbody tr td {
            display: table-cell;
            font-size: 16px;
            line-height: 26px;
            color: var(--heading-color);
            text-transform: capitalize;
            padding: 28px;
        }
        .order-history-inner table tbody tr td.order-numb {
            min-width: 135px;
        }
        .order-history-inner table tbody tr td.date {
            min-width: 160px;
        }
        .order-history-inner table tbody tr td.status {
            min-width: 160px;
        }
        .order-history-inner table tbody tr td.quantity {
            min-width: 130px;
        }
        .order-history-inner table tbody tr td.table-btn {
            min-width: 210px;
        }
        .order-history-inner table tbody tr td.amount {
            font-weight: 600;
            min-width: 150px;
        }
        .order-history-inner table tbody tr td.pay-method {
            text-transform: initial;
        }
        .order-history-inner table tbody tr.completed .status {
            color: var(--main-color-one);
        }
        .order-history-inner table tbody tr.pending .status {
            color: #F55D2C;
        }
        .order-history-inner table tbody tr.candeled .status {
            color: #DF0000;
        }

        /*---------------------------------------
            ## Button
        ---------------------------------------*/
        .btn-wrapper {
            display: block;
        }
        .btn-wrapper .btn-default {
            font-size: 16px;
            display: inline-block;
            text-align: center;
            font-weight: 400;
            cursor: pointer;
            border: 1px solid var(--main-color-one);
            background-color: var(--main-color-one);
            color: #fff;
            text-transform: capitalize;
            padding: 9px 30px 11px;
            -webkit-transition: all linear 0.2s;
            transition: all linear 0.2s;
            line-height: 20px;
        }
        .btn-wrapper .btn-default:hover {
            background-color: transparent;
            color: var(--main-color-one);
        }


        /*----------------------------------------------
            # Order status
        ----------------------------------------------*/
        .order-status-wrap .order-status-inner {
            width: 100%;
        }
        .order-status-wrap .order-status-inner tbody tr {
            border: 1px solid var(--form-bg-color);
            border-radius: 4px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            padding: 36px 50px;
            margin-bottom: 40px;
        }
        .order-status-wrap .order-status-inner tbody tr:last-child {
            margin-bottom: 0;
        }
        .order-status-wrap .order-status-inner tbody tr td .order-number {
            font-family: var(--heading-color);
            font-size: 20px;
            font-weight: 700;
            color: var(--heading-color);
            display: block;
            text-transform: capitalize;
        }
        .order-status-wrap .order-status-inner tbody tr td .price {
            font-family: var(--heading-color);
            font-size: 30px;
            font-weight: 500;
            color: var(--main-color-one);
            display: block;
            line-height: 44px;
            margin-top: 10px;
        }
        .order-status-wrap .order-status-inner tbody tr td .date,
        .order-status-wrap .order-status-inner tbody tr td .time {
            display: block;
            color: #999999;
        }
        .order-status-wrap .order-status-inner tbody tr td .ratings {
            color: #999999;
            display: block;
            margin-top: 10px;
        }
        .order-status-wrap .order-status-inner tbody tr td .btn-wrapper {
            position: relative;
            top: 50%;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
        }
        .order-status-wrap .order-status-inner tbody tr td .btn-wrapper .order-btn-custom {
            display: inline-block;
            font-weight: 600;
            background-color: var(--form-bg-color);
            color: #999999;
            padding: 7px 26px 9px;
            border-radius: 30px;
            text-transform: capitalize;
        }
        .order-status-wrap .order-status-inner tbody tr td .btn-wrapper .order-btn-custom:last-child {
            margin-left: 10px;
        }
        .order-status-wrap .order-status-inner tbody tr td .btn-wrapper .order-btn-custom.re-order {
            background-color: #F55D2C;
            color: #fff;
        }
        .order-status-wrap .order-status-inner tbody tr td:last-child {
            text-align: right;
        }
        .order-status-wrap .order-status-inner tbody tr.complete .ratings .icon {
            color: var(--secondary-color);
        }
        .order-status-wrap .order-status-inner tbody tr.complete .btn-wrapper .status {
            background-color: #00B106;
            color: #fff;
        }
        .order-status-wrap .order-status-inner tbody tr.pending .btn-wrapper .status {
            background-color: #F55D2C;
            color: #fff;
        }
        .order-status-wrap .order-status-inner tbody tr.rejected .btn-wrapper .status {
            background-color: #DF0000;
            color: #fff;
        }


        /*----------------------------------------------
            # Order details
        ----------------------------------------------*/
        .order-status-wrap {
            overflow-x: auto;
        }
        .order-status-wrap.order-details-page {
            background-color: var(--form-bg-color);
        }
        .order-status-wrap tbody tr td:nth-child(1) {
            min-width: 180px;
        }
        .order-status-wrap tbody tr td:nth-child(2) {
            min-width: 120px;
        }
        .order-status-wrap tbody tr td:nth-child(3) {
            min-width: 270px;
        }

        .internal-single-order-summery .internal-subject img{
            width: 100px;
        }
        .order-inner-content-wrap {
            border: 1px solid var(--form-bg-color);
            padding: 29px 30px 28px;
            border-radius: 5px;
            margin-bottom: 40px;
        }
        .order-inner-content-wrap .title {
            font-family: var(--heading-font);
            font-size: 24px;
            font-weight: 700;
            line-height: 35px;
            color: var(--heading-color);
            text-transform: capitalize;
            margin-bottom: 15px;
        }
        .order-inner-content-wrap .billing-info {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            margin-bottom: 25px;
        }
        .order-inner-content-wrap .billing-info .date-time {
            color: var(--heading-color);
        }
        .order-inner-content-wrap .billing-info .date-time .date {
            display: block;
        }
        .order-inner-content-wrap .billing-info .date-time .time {
            display: block;
        }
        .order-inner-content-wrap .billing-info .address {
            text-align: right;
            color: var(--heading-color);
        }
        .order-inner-content-wrap .billing-info .address .topic-title {
            font-size: 20px;
            font-weight: 600;
            line-height: 26px;
            text-transform: capitalize;
            margin-bottom: 25px;
        }
        .order-inner-content-wrap .billing-info .address .address {
            width: 190px;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery {
            font-size: 16px;
            line-height: 26px;
            text-transform: capitalize;
            color: #666666;
            padding-bottom: 1px;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .text-deep {
            font-weight: 600;
            color: var(--heading-color);
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .color-main {
            color: var(--main-color-one);
            font-size: 20px;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .content {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-flow: row wrap;
            flex-flow: row wrap;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            padding: 7px 0;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .content.ex {
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .internal-order-summery-list .internal-single-order-summery {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-flow: row wrap;
            flex-flow: row wrap;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            padding-bottom: 8px;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .internal-order-summery-list .internal-single-order-summery:last-child {
            padding-bottom: 7px;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .order-form {
            padding-top: 8px;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .order-form .form-check-input {
            margin-top: 7px;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .order-form .form-check-label {
            padding-left: 8px;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .order-form .form-check-label.cursor:hover {
            cursor: pointer;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .order-form .terms-and-cond {
            font-weight: 600;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .order-form .terms-and-cond a {
            color: var(--main-color-one);
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .order-form .payment-gateway-list {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            padding: 8px 0;
            margin-left: -28px;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .order-form .payment-gateway-list .single-gateway-item {
            margin-right: 10px;
            display: inline-block;
            position: relative;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .order-form .payment-gateway-list .single-gateway-item::before {
            position: absolute;
            content: "";
            width: 100%;
            height: 100%;
            border: 1px solid transparent;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .order-form .payment-gateway-list .single-gateway-item.selected {
            position: relative;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .order-form .payment-gateway-list .single-gateway-item.selected::before {
            position: absolute;
            content: "";
            width: 100%;
            height: 100%;
            border: 1px solid var(--main-color-one);
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .order-form .payment-gateway-list .single-gateway-item.selected::after {
            position: absolute;
            right: 0;
            top: 0;
            width: 10px;
            height: 10px;
            background-color: var(--main-color-one);
            content: "";
            font-weight: 900;
            color: #fff;
            font-family: "Font Awesome 5 Free";
            font-size: 5px;
            line-height: 7px;
            text-align: center;
            padding-top: 2px;
            padding-left: 2px;
            visibility: visible;
            opacity: 1;
            -webkit-transition: all 0.3s;
            transition: all 0.3s;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .order-form .payment-gateway-list .single-gateway-item:hover {
            cursor: pointer;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .order-form .ex-padding {
            padding-top: 20px;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .order-form .btn-wrapper {
            padding-top: 5px;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .order-form .btn-wrapper .btn-default {
            display: block;
            font-weight: 600;
            border-radius: 25px;
            height: 50px;
            line-height: 30px;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .order-form .btn-wrapper .btn-default:last-child {
            margin-top: 20px;
            background-color: #fff;
            color: var(--heading-color);
            border-color: #fff;
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery .order-form .btn-wrapper .btn-default:last-child:hover {
            background-color: transparent;
            border-color: var(--main-color-one);
            color: var(--main-color-one);
        }
        .order-inner-content-wrap .order-summery-list .single-order-summery.border-bottom {
            border-bottom: 1px solid #DDDDDD;
            padding-bottom: 9px;
            margin-bottom: 8px;
        }

    </style>

    @php
        $file = file_exists('assets/tenant/frontend/css/'.tenant()->id.'/dynamic-style.css');
    @endphp
    @if($file)
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/'. tenant()->id .'/dynamic-style.css')}}">
    @endif
</head>

<body class="{{$theme}}">

@include('tenant.frontend.partials.loader')
@include('tenant.frontend.partials.navbar')

<div class="search-suggestion-overlay"></div>
