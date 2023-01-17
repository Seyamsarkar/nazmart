@extends(route_prefix().'admin.admin-master')
@section('style')
   <x-datatable.css/>
@endsection
@section('title')
    {{__('All Payment Logs')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <x-error-msg/>
                        <x-flash-msg/>
                        <h4 class="header-title">{{__('All Payment Logs')}}</h4>
                        <div class="bulk-delete-wrapper">
                            <div class="select-box-wrap">
                                <select name="bulk_option" id="bulk_option">
                                    <option value="">{{{__('Bulk Action')}}}</option>
                                    <option value="delete">{{{__('Delete')}}}</option>
                                </select>
                                <button class="btn btn-primary btn-sm" id="bulk_delete_btn">{{__('Apply')}}</button>
                            </div>
                        </div>
                        <div class="table-wrap table-responsive">
                            <table class="table table-default table-striped table-bordered">
                                <thead class="text-white" style="background-color: #b66dff">
                                <tr>
                                    <th class="no-sort">
                                        <div class="mark-all-checkbox">
                                            <input type="checkbox" class="all-checkbox">
                                        </div>
                                    </th>
                                    <th>{{__('ID')}}</th>
                                    <th>{{__('Payer Name')}}</th>
                                    <th>{{__('Payer Email')}}</th>
                                    <th>{{__('Package Name')}}</th>
                                    <th>{{__('Package Price')}}</th>
                                    <th>{{__('Package Gateway')}}</th>
                                    <th>{{__('Subdomain')}}</th>
                                    <th>{{__('Payment Status')}}</th>
                                    <th>{{__('Date')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($payment_logs as $data)
                                    @continue($data->status === 'trial')
                                    <tr>
                                        <td>
                                            <div class="bulk-checkbox-wrapper">
                                                <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{{$data->id}}">
                                            </div>
                                        </td>
                                        <td>{{$data->id}}</td>
                                        <td>{{$data->name}}</td>
                                        <td>{{$data->email}}</td>
                                        <td>{{$data->package_name}}</td>
                                        <td>{{amount_with_currency_symbol($data->package_price)}}</td>
                                        <td><strong>{{ucwords(str_replace('_',' ',$data->package_gateway))}}</strong></td>
                                        <td>{{$data->tenant_id}}</td>
                                        <td>
                                            @if($data->payment_status == 'pending')
                                                <span class="alert alert-warning text-capitalize">{{$data->payment_status}}</span>
                                            @else
                                                <span class="alert alert-success text-capitalize">{{$data->payment_status}}</span>
                                            @endif
                                        </td>
                                        <td>{{date_format($data->created_at,'d M Y')}}</td>
                                        <td>

                                            <x-delete-popover url="{{route(route_prefix().'admin.payment.delete',$data->id)}}"/>

                                            <a href="{{route(route_prefix().'admin.package.order.manage.view',$data->id)}}" class="btn btn-lg btn-primary btn-sm mb-3 mr-1 view_order_details_btn">
                                                <i class="las la-eye"></i>
                                            </a>

                                            @if($data->package_gateway == 'manual_payment' && $data->payment_status == 'pending')
                                                <x-approve-popover url="{{route(route_prefix().'admin.payment.approve',$data->id)}}"/>
                                            @endif
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
    </div>

    @include('landlord.admin.package-order-manage.portion.status-and-mail-send')

@endsection

@section('scripts')
   <x-datatable.js/>


@endsection

