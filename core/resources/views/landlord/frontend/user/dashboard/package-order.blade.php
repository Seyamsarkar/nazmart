@extends('landlord.frontend.user.dashboard.user-master')
@section('title')
   {{__('Payment Logs')}}
@endsection

@section('page-title')
    {{__('Payment Logs')}}
@endsection

@section('section')
    @if(count($package_orders) > 0)
        <div class="custom_domain_table">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">{{__('Package Order Info')}}</th>
                    <th scope="col">{{__('Payment Status')}}</th>
                    <th scope="col">{{__('Action')}}</th>
                </tr>
                </thead>
                <tbody>-----------------------------
                @foreach($package_orders as $data)
                    <tr>
                        <td>
                            <div class="user-dahsboard-order-info-wrap">
                                <h5 class="title">{{$data->package_name}}</h5>
                                <div class="div package_order_details_data">
                                    <small class="d-block"><strong>{{__('Order ID:')}}</strong> #{{$data->id}}</small>
                                    <small class="d-block"><strong>{{__('Order Name:')}}</strong> {{$data->tenant_id.'.'.getenv('CENTRAL_DOMAIN')}}</small>
                                    <small class="d-block"><strong>{{__('Package Price:')}}</strong> {{amount_with_currency_symbol($data->package_price)}}</small>

                                    <small class="d-block"><strong>{{__('Order Status:')}}</strong>
                                        @if($data->status == 'pending')
                                            <span class="alert_status_single alert alert-warning text-capitalize alert-sm alert-small">{{__($data->status )}}</span>
                                        @elseif($data->status == 'cancel')
                                            <span class="alert_status_single alert alert-danger text-capitalize alert-sm alert-small">{{__($data->status)}}</span>
                                        @elseif($data->status == 'in_progress')
                                            <span class="alert_status_single alert alert-info text-capitalize alert-sm alert-small">{{str_replace('_',' ',$data->status)}}</span>
                                        @elseif($data->status == 'trial')
                                            <span class="alert_status_single lert alert-primary text-capitalize alert-sm alert-small">{{str_replace('_',' ',$data->status)}}</span>
                                        @else
                                            <span class="alert_status_single alert alert-success text-capitalize alert-sm alert-small">{{$data->status }}</span>
                                        @endif
                                    </small>

                                    <small class="d-block"><strong>{{__('Start Date:')}}</strong> {{date('d-m-Y',strtotime($data->start_date))}}</small>
                                    <small class="d-block"><strong>{{__('Expire Date:')}}</strong> {{$data->expire_date != null ? date('d-m-Y',strtotime($data->expire_date)) : __('Lifetime')}}</small>
                                    <small class="d-block"><strong>{{__('Renew Taken :')}}</strong> {{ $data->renew_status ?? 0 }}</small>

                                    @if($data->payment_status == 'complete')
                                        <form action="{{route(route_prefix().'frontend.package.invoice.generate')}}"  method="post">
                                            @csrf
                                            <input type="hidden" name="id" id="invoice_generate_order_field" value="{{$data->id}}">
                                            <button class="btn btn-secondary btn-xs btn-small margin-top-10" type="submit">{{__('Invoice')}}</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($data->payment_status != 'complete' && $data->status != 'cancel')

                                <span class="alert_status_single alert alert-warning text-capitalize alert-sm">{{$data->payment_status}}</span>
                                <a href="{{route(route_prefix().'frontend.order.confirm',$data->package_id)}}" class="btn btn-success btn-sm">{{__('Pay Now')}}</a>
                                <form action="{{route(route_prefix().'user.dashboard.package.order.cancel')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{$data->id}}">
                                    <button type="submit" class="btn btn-danger btn-sm margin-top-10">{{__('Cancel')}}</button>
                                </form>
                            @else
                                <span class="alert_status_single alert alert-success text-capitalize alert-sm" style="display: inline-block">{{$data->payment_status}}</span>
                            @endif
                        </td>

                        <td>
                            <div class="btn-wrapper">
                                <a href="{{route(route_prefix().'frontend.order.confirm',$data->package_id)}}" class="cmn-btn btn-success cmn-btn-small text-white" target="_blank">{{__('Renew Now')}}</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="blog-pagination">
            {{ $package_orders->links() }}
        </div>
    @else
        <div class="alert alert-warning">{{__('No Order Found')}}</div>
    @endif
@endsection

@section('scripts')
    <script>
        $('.close-bars, .body-overlay').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').removeClass('active');
        });
        $('.sidebar-icon').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').addClass('active');
        });
    </script>
@endsection
