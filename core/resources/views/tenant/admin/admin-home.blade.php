@extends('tenant.admin.admin-master')
@section('title') {{__('Main Page')}} @endsection
@section('content')
    <div class="col-12 stretch-card">
        <div class="card">
      @if(!empty($current_package))
            <div class="main">
                <div class="alert border-left border-primary text-white text-center bg-gradient-info">
                    <strong>{{__('Current Package :')}} </strong> {{$current_package->package_name}}
                    <span class="badge badge-warning text-dark">
                        {{ \App\Enums\PricePlanTypEnums::getText(optional($current_package->package)->type ) }}
                    </span>

                    @if(optional(tenant()->payment_log)->status == 'trial')
                        @php
                            $days = get_trial_days_left(tenant());
                        @endphp

                        <strong class="text-capitalize"> ( {{optional(tenant()->payment_log)->status}} : {{$days ?? ''}} {{__('Days Left')}})</strong>
                        <a class="btn btn-dark btn-sm pull-right" href="{{route('landlord.homepage') .'#price_plan_section'}}" target="_blank">{{__('Renew Plan')}}</a
                    @else
                        @if($current_package->expire_date != null)
                            <strong> ( {{__('Expire Date :')}} {{$current_package->expire_date .' - '. $current_package->expire_date?->diffForHumans() ?? ''}} )</strong>

                            <a class="btn btn-dark btn-sm pull-right" href="{{route('landlord.homepage') .'#price_plan_section'}}" target="_blank">{{__('Renew Plan')}}</a>
                        @else
                            <strong> ( {{__('Expire Date :')}} {{__('Lifetime')}} )</strong>
                        @endif
                    @endif
                </div>
            </div>
      @endif
          <div class="card-body">
              <h4 class="card-title mb-4">{{__('Dashboard content')}}</h4>
                <div class="row g-4">
                    <div class="col-xl-4 col-md-6 stretch-card">
                        <div class="card bg-gradient-danger card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Admins')}}<i class="las la-user-shield mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$total_admin}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 stretch-card">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Users')}}<i class="las la-user-shield mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$total_user}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 stretch-card">
                        <div class="card bg-gradient-success card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Blogs')}}<i class="mdi mdi-diamond mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$all_blogs}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 stretch-card">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Products')}} <i class="mdi mdi-diamond mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$total_products}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 stretch-card">
                        <div class="card bg-gradient-primary card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Orders')}}<i class="mdi mdi-diamond mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$total_orders}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 stretch-card">
                        <div class="card bg-gradient-warning card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Sale')}} <i class="mdi mdi-cash mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{float_amount_with_currency_symbol($total_sale)}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="recent_order_wrap mt-4">
                            <h3 class=" text-center mb-4">{{__('Recent Order Logs')}}</h3>
                            <div class="recent_order_logs">
                                <table class="table table-responsive table-bordered">
                                    <thead class="text-white" style="background-color: #b66dff">
                                    <tr>
                                        <th> {{__('Order ID')}}</th>
                                        <th> {{__('Customer Name')}}</th>
                                        <th> {{__('Customer Email')}}</th>
                                        <th> {{__('Total Amount')}} </th>
                                        <th> {{__('Status')}} </th>
                                        <th> {{__('Payment Status')}} </th>
                                        <th> {{__('Payment gateway')}} </th>
                                        <th> {{__('Order Created')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($recent_order_logs as $key => $data)
                                        <tr>
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->name ?? '' }}</td>
                                            <td> {{$data->email}} </td>
                                            <td> {{amount_with_currency_symbol($data->total_amount)}} </td>
                                            <td>{{$data->status}}</td>
                                            @php
                                                $payment_status_color = match ($data->payment_status){
                                                    'success' => 'text-success',
                                                    'pending' => 'text-warning',
                                                    'failed' => 'text-danger'
                                                }
                                            @endphp
                                            <td class="{{$payment_status_color}}">{{$data->payment_status}}</td>
                                            <td>{{$data->payment_gateway}}</td>
                                            <td>{{$data->created_at->diffForHumans()}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
