@extends('tenant.frontend.user.dashboard.user-master')

@section('title')
    {{__('Payment Logs')}}
@endsection

@section('section')
    @if(count($order_list) > 0)
        <div class="table-responsive">
            <!-- Order history start-->
            <div class="order-history-inner">
                <table>
                    <thead>
                    <tr>
                        <th>
                            {{__('Order ID')}}
                        </th>
                        <th>
                            {{__('Date')}}
                        </th>
                        <th>
                            {{__('Status')}}
                        </th>

                        <th>
                            {{__('Amount')}}
                        </th>
                        <th>
                            {{__('Action')}}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order_list as $data)
                        <tr class="completed">
                            <td class="order-numb">
                                #{{ $data->id ?? 0 }}
                            </td>
                            <td class="date">
                                {{ $data->created_at->format("d M, Y") }}
                            </td>
                            <td class="status">
                                <p>
                                    <span>Order Status</span>
                                    <span>{{ $data->status ?? ""}}</span>
                                </p>
                                <p>
                                    <span>Payment Status</span>
                                    <span>{{$data->payment_status ?? ""}}</span>
                                </p>
                            </td>

                            <td class="amount">
                                {{ amount_with_currency_symbol($data->total_amount) }}
                            </td>
                            <td class="table-btn">
                                <div class="btn-wrapper">
                                    <a href="{{ route('tenant.user.dashboard.package.order', $data->id) }}" class="btn-default rounded-btn"> view details</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Order history end-->
        </div>
        <div class="blog-pagination">
            {{ $order_list->links() }}
        </div>
    @else
        <div class="alert alert-warning">{{__('No Order Found')}}</div>
    @endif
@endsection
