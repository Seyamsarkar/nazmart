@extends('tenant.frontend.user.dashboard.user-master')

@section('title')
    {{__('Refund List')}}
@endsection

@section('section')
    <style>
        .refund_conversation{
            border-radius: 0;
        }
        .refund_conversation:hover{
            background: var(--main-color-one);
            border-color: var(--main-color-one);
        }
        .refund_conversation i{
            font-size: 25px;
            vertical-align: bottom;
        }
    </style>

    <div class="parent">
        <dvi class="d-block text-end">
            <a class="btn btn-primary refund_conversation" href="{{route('tenant.user.dashboard.refund.chat.list')}}">
                <i class="lar la-comment-alt"></i>
                {{__('Refund Conversation')}}
            </a>
        </dvi>

        <div class="table-responsive mt-4">
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
                            {{__('Product')}}
                        </th>

                        <th>
                            {{__('Amount')}}
                        </th>
                        <th>
                            {{__('Status')}}
                        </th>
                        <th>
                            {{__('Action')}}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($refund_list as $data)
                        <tr class="completed">
                            <td class="order-numb">
                                #{{ $data->id ?? 0 }}
                            </td>
                            <td class="date">
                                {{ $data->created_at->format("d M, Y") }}
                            </td>
                            <td class="status">
                                <p>
                                    <span>{{$data->product?->name}}</span>
                                </p>
                            </td>

                            @php
                                $user = Auth::guard('web')->user();
                                $order_product = \App\Models\OrderProducts::where(['order_id' => $data->order_id, 'product_id' => $data->product?->id])->first();
                            @endphp
                            <td class="amount">
                                {{ amount_with_currency_symbol($order_product->price) }}
                            </td>
                            <td>
                                {{$data->status == 1 ? 'Refunded' : 'Not Refunded'}}
                            </td>
                            <td class="table-btn">
                                <div class="btn-wrapper">
                                    <a href="{{ route('tenant.user.dashboard.package.order', $data->order?->id) }}" class="btn-default rounded-btn"> {{__('View details')}}</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Order history end-->
        </div>
    </div>
@endsection
