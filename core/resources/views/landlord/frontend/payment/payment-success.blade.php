@extends('landlord.frontend.frontend-page-master')
@section('title')

    {{__('Payment Success For:')}} {{$payment_details->package_name}}
@endsection
@section('page-title')
   {{$payment_details->package_name}}
@endsection
@section('content')

    <div class="error-page-content" data-padding-bottom="100">
        <div class="container">
            @if(empty($domain))
                <div class="alert alert-danger text-bold text-center mt-2">
                    <i class="las la-info-circle"></i>
                    {{__('Your website is not ready yet, you will get notified by email when it is ready.')}}
                </div>
            @endif
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="order-success-area margin-bottom-80 text-center pt-5">
                        <h1 class="title">{{get_static_option('site_order_success_page_' . $user_select_lang_slug . '_title')}}</h1>
                        <p class="order-page-description">{{get_static_option('site_order_success_page_' . $user_select_lang_slug . '_description')}}</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="single-billing-items">
                        <h2 class="billing-title">{{__('Order Details')}}</h2>
                        <ul class="billing-details mt-4">
                            <li><strong>{{__('Order ID:')}}</strong> #{{$payment_details->id}}</li>
                            <li class="text-capitalize"><strong>{{__('Payment Package:')}}</strong> {{$payment_details->package_name}}</li>
                            <li class="text-capitalize"><strong>{{__('Payment Package Type:')}}</strong> {{ \App\Enums\PricePlanTypEnums::getText(optional($payment_details->package)->type) }}</li>
                            <li class="text-capitalize"><strong>{{__('Payment Gateway:')}}</strong>
                                @php
                                    $gateway = str_replace('_', ' ',$payment_details->package_gateway);
                                @endphp
                                {{$gateway}}
                            </li>
                            <li class="text-capitalize"><strong>{{__('Payment Status:')}}</strong> {{$payment_details->payment_status}}</li>
                            <li><strong>{{__('Transaction ID:')}}</strong> {{$payment_details->transaction_id}}</li>
                        </ul>
                    </div>
                    <div class="single-billing-items mt-4">
                        <h2 class="billing-title">{{__('Billing Details')}}</h2>
                        <ul class="billing-details mt-4">
                            <li><strong>{{__('Name')}}</strong> {{$payment_details->name}}</li>
                            <li><strong>{{__('Email')}}</strong> {{$payment_details->email}}</li>
                        </ul>
                    </div>
                    <div class="btn-wrapper mt-5">
                        <a href="{{route('landlord.homepage')}}" class="cmn-btn cmn-btn-bg-1 ">{{__('Back To Home')}}</a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single-price-plan-item">
                        <div class="price-header">
                            <h3 class="title">{{ $payment_details->package_name}}</h3>
                            <div class="price-wrap mt-4"><span class="price">{{amount_with_currency_symbol($payment_details->package_price)}}</span>{{ $payment_details->type ?? '' }}</div>
                            <h5 class="title text-primary mt-2">{{__('Start Date :')}}{{$payment_details->start_date ?? ''}}</h5>
                            <h5 class="title text-danger mt-2">{{__('Expire Date :')}}{{$payment_details->expire_date?->format('d-m-Y H:m:s') ?? 'Life Time'}}</h5>
                        </div>
                        <div class="price-body mt-4">
                            <ul class="features">
                                @if(!empty(optional($payment_details->package)->page_permission_feature))
                                    <li class="check"> {{ sprintf(__('Page Create %d'),optional($payment_details->package)->page_permission_feature )}}</li>
                                @endif

                                @if(!empty(optional($payment_details->package)->blog_permission_feature))
                                    <li class="check"> {{ sprintf(__('Blog Create %d'),optional($payment_details->package)->blog_permission_feature )}}</li>
                                @endif

                                @if(!empty(optional($payment_details->package)->service_permission_feature))
                                    <li class="check"> {{ sprintf(__('Service Create %d'),optional($payment_details->package)->service_permission_feature )}}</li>
                                @endif

                                @foreach(optional($payment_details->package)->plan_features as $key=> $item)
                                    <li class="check"> {{str_replace('_', ' ',ucfirst($item->feature_name)) ?? ''}}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="price-footer pb-0">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
