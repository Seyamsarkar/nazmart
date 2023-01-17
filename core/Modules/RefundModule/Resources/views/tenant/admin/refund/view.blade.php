@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Refund Product Details')}}
@endsection
@section('style')
    <x-media-upload.css/>
@endsection
@section('title')
    {{__('Order Details')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row g-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__('Order Details')}}</h4>
                        <x-link-with-popover url="{{route(route_prefix().'admin.refund.all')}}"
                                             class="info">{{__('All Refunds')}}</x-link-with-popover>

                        <!-- Order status start-->
                        <div class="order-status-wrap order-details-page">
                            <table class="order-status-inner">
                                <tbody>
                                <tr class="complete">
                                    <td>
                                        <span class="order-number"> {{__("Order")}} #{{ $order_product->id }}</span>
                                        <span class="price">{{ amount_with_currency_symbol($order_product->price) }}</span>
                                    </td>
                                    <td>
                                        <span class="">{{ $order_product->created_at?->format("M d, Y") }}</span>
                                        <span class="">{{ $order_product->created_at?->format("H:ia") }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-wrapper">
                                            <span class="order-btn-custom status">{{__('Order Status').': '.$order_details->status }}</span>
                                            <span class="order-btn-custom status">{{__('Payment Status').': '. $order_details->payment_status }}</span>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Order status end-->

                        <!-- Order summery start -->
                        <div class="order-inner-content-wrap">
                            <h4 class="title">{{__("order details")}}</h4>
                            <div class="billing-info">
                                <div class="date-time">
                                    <span class="">{{ $order_product->created_at?->format("M d, Y") }}</span>
                                    <span class="">{{ $order_product->created_at?->format("H:ia") }}</span>
                                </div>

                                <div class="address">
                                    <h5 class="topic-title">{{__("billing information")}}</h5>
                                    <p>
                                        <span>{{__('Country:')}}</span>
                                        <span>{{$order_details->getCountry?->name}}</span>
                                    </p>
                                    <p>
                                        <span>{{__('State:')}}</span>
                                        <span>{{$order_details->getState?->name}}</span>
                                    </p>
                                    <p>
                                        <span>{{__('City:')}}</span>
                                        <span>{{$order_details->city}}</span>
                                    </p>
                                    <p class="address">
                                        <span>{{__('Address:')}}</span>
                                        <span>{{ $order_details->address }}</span>
                                    </p>
                                </div>
                            </div>

                            <ul class="order-summery-list">
                                <li class="single-order-summery border-bottom">
                                    <div class="content border-bottom ex">
                                    <span class="subject text-deep">
                                        {{__("product")}}
                                    </span>
                                        <span class="object text-deep">
                                        {{__("subtotal")}}
                                    </span>
                                    </div>

                                    <ul class="internal-order-summery-list">
                                            <li class="internal-single-order-summery">
                                            <span class="internal-subject">{!! render_image_markup_by_attachment_id($product->image_id) !!} {{ $product?->name }}
                                                <i class="las la-times icon"></i>
                                                <span class="times text-deep">{{ $order_product->quantity }}</span>
                                            </span>
                                                <span class="internal-object">
                                                {{ amount_with_currency_symbol(($order_product->price * $order_product->quantity) ?? 0) }}
                                            </span>
                                            </li>
                                    </ul>
                                </li>

                                <li class="single-order-summery border-bottom">
                                    <div class="content total">
                                    <span class="subject text-deep color-main">
                                        {{__("total")}}
                                    </span>
                                        <span class="object text-deep color-main">
                                        {{ amount_with_currency_symbol($order_product->price * $order_product->quantity) }}
                                    </span>
                                    </div>
                                </li>
                                <li class="single-order-summery">
                                    <div class="content total">
                                    <span class="subject text-deep">
                                        {{__("payment method")}}
                                    </span>
                                        <span class="object">
                                        {{ $order_details->payment_gateway ?? __("Cash on delivery") }}
                                    </span>
                                    </div>
                                </li>
                            </ul>
                            <!-- Order summery end     -->
                        </div>
                        <!-- Order summery end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-media-upload.js/>
@endsection
