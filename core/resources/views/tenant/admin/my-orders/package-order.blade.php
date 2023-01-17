@extends(route_prefix().'admin.admin-master')
@section('title')
   {{__('My Payment Logs')}}
@endsection

@section('style')
    <x-datatable.css/>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <x-admin.header-wrapper>
                        <x-slot name="left">
                            <h4 class="card-title">{{__('Your Package Order Payment logs')}} {{__('(from main site)')}}</h4>
                        </x-slot>
                        <x-slot name="right" class="d-flex">
                            <x-link-with-popover url="{{route('landlord.homepage') .'#price_plan_section'}}" >
                                {{__('Buy a Plan')}}
                            </x-link-with-popover>
                        </x-slot>

                        <x-error-msg/>
                        <x-flash-msg/>
                    </x-admin.header-wrapper>


                    <div class="table-wrap table-responsive">
                        <table class="table table-default table-striped table-bordered">
                            <thead class="text-white" style="background-color: #b66dff">
                            <tr>
                                <th scope="col">{{__('SL #')}}</th>
                                <th scope="col">{{__('Package Order Info')}}</th>
                                <th scope="col">{{__('Payment Status')}}</th>
                                <th scope="col">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($package_orders ?? [] as $key => $data)
                                <tr>
                                    <td>{{$data->id}}</td>
                                    <td>
                                        <div class="user-dahsboard-order-info-wrap">
                                            <h5 class="title">{{$data->package_name}}</h5>
                                            <div class="div">
                                                <small class="d-block"><strong>{{__('Order ID:')}}</strong> #{{$data->id}}</small>
                                                <small class="d-block"><strong>{{__('Package Price:')}}</strong> {{amount_with_currency_symbol($data->package_price)}}</small>
                                                <small class="d-block"><strong>{{__('Order Status:')}}</strong>
                                                    @if($data->status == 'pending')
                                                        <span class="alert alert-warning text-capitalize alert-sm alert-small customAlert2">{{__($data->status )}}</span>
                                                    @elseif($data->status == 'cancel')
                                                        <span class="alert alert-danger text-capitalize alert-sm alert-small customAlert2">{{__($data->status)}}</span>
                                                    @elseif($data->status == 'in_progress')
                                                        <span class="alert alert-info text-capitalize alert-sm alert-small customAlert2">{{str_replace('_',' ',$data->status)}}</span>
                                                    @else
                                                        <span class="alert alert-success text-capitalize alert-sm alert-small customAlert2">{{$data->status }}</span>
                                                    @endif
                                                </small>

                                                <small class="d-block"><strong>{{__('Order Date:')}}</strong> {{date_format($data->created_at,'D m Y')}}</small>
                                                <small class="d-block"><strong>{{__('Start Date:')}}</strong> {{$data->start_date ?? ''}}</small>
                                                <small class="d-block"><strong>{{__('Expire Date:')}}</strong> {{$data->expire_date ?? 'Lifetime'}}</small>
                                                @if($data->payment_status == 'complete')
                                                    <form action="{{route(route_prefix().'my.package.invoice.generate')}}"  method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" id="invoice_generate_order_field" value="{{$data->id}}">
                                                        <button class="btn btn-success btn-xs btn-small margin-top-10" type="submit">{{__('Invoice')}}</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="flexItems">
                                        @if($data->payment_status == 'pending' || $data->payment_status == null && $data->status != 'cancel')
                                            <span class="alert alert-warning text-capitalize alert-sm paymentBtn">{{$data->payment_status ?? __('Pending')}}</span>
                                            <a href="{{route('landlord.frontend.order.confirm',$data->package_id)}}" target="_blank" class="btn btn-success btn-sm mx-2">{{__('Pay Now')}}</a>
                                            <form action="{{route('tenant.admin.package.order.cancel')}}" method="post" class="">
                                                @csrf
                                                <input type="hidden" name="package_id" value="{{$data->id}}">
                                                <button type="submit" class="btn btn-danger btn-sm my-2">{{__('Cancel')}}</button>
                                            </form>
                                        @else
                                            <span class="alert alert-success text-capitalize alert-sm" style="display: inline-block">{{$data->payment_status ?? __('Complete')}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{route('landlord.frontend.plan.order',$data->package_id)}}" target="_blank">{{__('Renew')}}</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <x-datatable.js/>
@endsection

