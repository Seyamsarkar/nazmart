@extends('landlord.frontend.user.dashboard.user-master')
@section('page-title')
    {{__('User Home')}}
@endsection

@section('title')
    {{__('User Home')}}
@endsection

@section('style')
    <style>
        .badge {
            font-size: 15px;
        }
    </style>
@endsection

@section('section')
    @php
        $auth_user = Auth::guard('web')->user();
    @endphp
    <div class="row g-4">
        <div class="col-md-12">
            <div class="btn-wrapper mb-3 mt-2" style="float: right">
                @php
                    $price_page = get_page_slug(get_static_option('pricing_plan'));
                @endphp
                <a href="{{url($price_page)}}"
                   class="cmn-btn cmn-btn-bg-1 cmn-btn-small mx-2">{{__('Create a Website')}}</a>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 orders-child">
            <div class="single-orders">
                <div class="orders-flex-content">
                    <div class="icon">
                        <i class="las la-tasks"></i>
                    </div>
                    <div class="contents">
                        <h2 class="order-titles"> {{$package_orders ?? ''}} </h2>
                        <span class="order-para">{{__('Total Orders')}} </span>
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
                        <h2 class="order-titles"> {{$support_tickets ?? ''}} </h2>
                        <span class="order-para">{{__('Support Tickets')}} </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="subdomains custom_domain_wrap mt-4">
                <h4 class="custom_domain_title">{{__('Your Subdomains')}}</h4>
                <div class="payment custom_domain_table mt-4">
                    <table class="table table-bordered recent_payment_table">
                        <thead>
                        <th>{{__('ID')}}</th>
                        <th>{{__('Site')}}</th>
                        <th>{{__('Browse')}}</th>
                        </thead>
                        <tbody class="w-100">
                        @php
                            $user = Auth::guard('web')->user();
                        @endphp

                        @foreach($user->tenant_details ?? [] as $key => $data)
                            <tr>
                                <td>{{$key +1}}</td>
                                <td>
                                    {{optional($data->domain)->domain}}
                                </td>
                                <td>
                                    <a class="badge rounded bg-primary px-4 visitweb"
                                       href="{{tenant_url_with_protocol(optional($data->domain)->domain)}}">{{__('Visit Website')}}</a>
                                    <a class="badge rounded bg-danger px-4"
                                       href="{{tenant_url_with_protocol(optional($data->domain)->domain).'/admin'}}">{{__('Visit Admin Panel')}}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="custom_domain_wrap mt-4">
                <h4 class="custom_domain_title">{{__('Recent Orders')}}</h4>
                <div class="payment custom_domain_table mt-4">
                    <table class="table table-bordered recent_payment_table">
                        <thead>
                        <th>{{__('ID')}}</th>
                        <th>{{__('Order Name')}}</th>
                        <th>{{__('Package Name')}}</th>
                        <th>{{__('Amount')}}</th>
                        <th>{{__('Payment Status')}}</th>
                        <th>{{__('Start Date')}}</th>
                        <th>{{__('Expire Date')}}</th>
                        <th>{{__('Renew Taken')}}</th>
                        </thead>
                        <tbody class="w-100">
                        @foreach($recent_logs as $key=> $data)
                            <tr>
                                <td>{{$key +1}}</td>
                                <td>{{$data?->domain?->domain ?? 'Unsuccessful Transaction'}}</td>
                                <td>{{$data->package_name}}</td>
                                <td>{{ amount_with_currency_symbol($data->package_price) }}</td>
                                <td>{{ $data->payment_status }}</td>
                                <td>{{date('d-m-Y', strtotime($data->start_date))}}</td>
                                <td>{{$data->expire_date != null ? date('d-m-Y', strtotime($data->expire_date)) : __('Lifetime')}}</td>
                                <td>{{$data->renew_status}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection





