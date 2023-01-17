@extends('tenant.frontend.user.dashboard.user-master')
@section('page-title')
 {{__('User Dashboard')}}
@endsection

@section('title')
    {{__('User Dashboard')}}
@endsection

@section('section')
    <div class="row g-4">
        <div class="col-xl-6 col-md-6 orders-child">
            <div class="single-orders">
                <div class="orders-flex-content">
                    <div class="icon">
                        <i class="las la-shopping-cart"></i>
                    </div>
                    <div class="contents">
                        <h2 class="order-titles"> {{$package_orders}} </h2>
                        <span class="order-para">{{__('Total Orders')}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 orders-child">
            <div class="single-orders">
                <div class="orders-flex-content">
                    <div class="icon">
                        <i class="las la-money-bill"></i>
                    </div>
                    <div class="contents">
                        <h2 class="order-titles"> {{float_amount_with_currency_symbol($order_purchase)}} </h2>
                        <span class="order-para">{{__('Total Purchase')}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 orders-child">
            <div class="single-orders">
                <div class="orders-flex-content">
                    <div class="icon">
                        <i class="las la-undo-alt"></i>
                    </div>
                    <div class="contents">
                        <h2 class="order-titles"> {{$product_refunds}} </h2>
                        <span class="order-para">{{__('Product Refunds')}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 orders-child">
            <div class="single-orders">
                <div class="orders-flex-content">
                    <div class="icon">
                        <i class="las la-tasks"></i>
                    </div>
                    <div class="contents">
                        <h2 class="order-titles"> {{$support_tickets}} </h2>
                        <span class="order-para">{{__('Support Tickets')}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection





