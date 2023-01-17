@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Order Details')}} @endsection
@section('style')
    <x-media-upload.css/>
@endsection
@section('title')
    {{__('Order Details')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex justify-content-between">
                            <div class="left-wrapper">
                                <h4 class="header-title mb-4">{{__('Order Details')}}</h4>
                            </div>
                            <div class="right-wrapper">
                                <x-link-with-popover url="{{route(route_prefix().'admin.package.order.manage.all')}}" class="info">{{__('All Orders')}}</x-link-with-popover>
                            </div>
                        </div>
                        <div class="table-wrap table-responsive">
                            <table class="table table-default table-striped table-bordered">
                                <thead class="text-white" style="background-color: #b66dff">
                                <tr>
                                    <th>{{__('ID')}}</th>
                                    <th>{{__('Details')}}</th>
                                    <th>{{__('Payment Status')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{$order->id}}</td>
                                    <td>
                                        <div class="parent d-flex justify-content-start">
                                            <strong class="text-dark ">{{__('Package Name :')}}</strong>
                                            <span class="text-primary mx-2">{{$order->package_name}}</span><br><br>
                                        </div>

                                        <div class="parent d-flex justify-content-start">
                                            <strong class="text-dark ">{{__('Package Price :')}}</strong>
                                            <span class="text-primary mx-2">{{amount_with_currency_symbol($order->package_price)}}</span><br><br>
                                        </div>

                                        <div class="parent d-flex justify-content-start">
                                            <strong class="text-dark ">{{__('Payment Gateway :')}}</strong>
                                            <span class="text-primary mx-2 text-capitalize">{{str_replace('_',' ',$order->package_gateway)}}</span><br><br>
                                        </div>

                                        <div class="parent d-flex justify-content-start">
                                            <strong class="text-dark ">{{__('Order User Name :')}}</strong>
                                            <span class="text-primary mx-2"> {{$order->name}}</span><br><br>
                                        </div>

                                        <div class="parent d-flex justify-content-start">
                                            <strong class="text-dark ">{{__('Order User Email :')}}</strong>
                                            <span class="text-primary mx-2">{{$order->email}}</span><br><br>
                                        </div>

                                        <div class="parent d-flex justify-content-start">
                                            <strong class="text-dark ">{{__('Subdomain :')}}</strong>
                                            <span class="text-primary mx-2">{{$order->tenant_id}}</span><br><br>
                                        </div>

                                        <div class="parent d-flex justify-content-start">
                                            <strong class="text-dark ">{{__('Order Date :')}}</strong>
                                            <span class="text-primary mx-2">{{date_format($order->created_at,'d M Y')}}</span><br><br>
                                        </div>

                                        @if(!empty($all_custom_fields))
                                            <strong class="mb-2 text-secondary mt-4">{{__('(Custom Fields)')}}</strong>
                                            @foreach($all_custom_fields ?? [] as $key=> $field)
                                                <div class="att mb-2 mt-2">
                                                    <strong class="text-dark ">{{ ucfirst($key) . ' : ' }}</strong>
                                                    <span>{{$field}}</span><br>
                                                </div>
                                            @endforeach
                                        @endif

                                        @if($order->status != 'trial')
                                            <div class="parent d-flex justify-content-start">
                                                <strong class="text-dark ">{{__('Attachment :')}}</strong>
                                                <span class="text-primary mx-2">
                                                    <a href="{{global_asset('assets/landlord/uploads/payment_attachments/'.$order->attachments)}}" target="_blank">
                                                        <img class="rounded" src="{{global_asset('assets/landlord/uploads/payment_attachments/'.$order->attachments)}}" alt="" style="width: 100px;height: 50px">
                                                    </a>
                                                </span><br><br>
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        @if($order->payment_status == 'complete')
                                            <span class="alert alert-success text-capitalize">{{$order->payment_status}}</span>
                                        @else
                                            <span class="alert alert-warning text-capitalize">{{$order->payment_status ?? __('Pending')}}</span>
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
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

