@extends(route_prefix().'admin.admin-master')

@section('title') {{__('All Refund Request')}} @endsection
@section('style')
  <x-datatable.css/>
  <style>
      button.low,
      button.status-open{
          display: inline-block;
          background-color: #6bb17b;
          padding: 3px 10px;
          border-radius: 4px;
          color: #fff;
          text-transform: capitalize;
          border: none;
          font-weight: 600;
      }
      button.high,
      button.status-close{
          display: inline-block;
          background-color: #c66060;
          padding: 3px 10px;
          border-radius: 4px;
          color: #fff;
          text-transform: capitalize;
          border: none;
          font-weight: 600;
      }
      button.medium {
          display: inline-block;
          background-color: #70b9ae;
          padding: 3px 10px;
          border-radius: 4px;
          color: #fff;
          text-transform: capitalize;
          border: none;
          font-weight: 600;
      }
      button.urgent {
          display: inline-block;
          background-color: #bfb55a;
          padding: 3px 10px;
          border-radius: 4px;
          color: #fff;
          text-transform: capitalize;
          border: none;
          font-weight: 600;
      }
      .refund_status {
          background: transparent;
          border: 1px solid #000;
          padding: 9px;
          border-radius: 5px;
      }
  </style>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-flash-msg/>
                <x-error-msg/>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="top-wrapp d-flex justify-content-between">
                            <div class="left-part">
                                <h4 class="header-title">{{__('All Refund')}}</h4>
                            </div>
                            <div class="btn-wrapper"><a href="{{route(route_prefix().'admin.refund.chat.new')}}" class="btn btn-primary">{{__('New Ticket')}}</a></div>
                        </div>
                        <div class="table-wrap table-responsive">
                            <table class="table table-default table-striped table-bordered">
                                <thead class="text-white" style="background-color: #b66dff">
                                <th>{{__('ID')}}</th>
                                <th>{{__('Product Name')}}</th>
                                <th>{{__('User')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($refunds as $data)
                                    <tr>
                                        <td>#{{$data->id}}</td>
                                        <td class="text">{{$data->product?->name}}</td>

                                        <td>
                                            {{optional($data->user)->name ?? __('anonymous')}}
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="status-{{$data->status}} dropdown-toggle refund_status" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{$data->status == 0 ? __('Not Refunded') : __('Refunded')}}
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item status_change" data-id="{{$data->id}}" data-val="1" href="#">{{__('Refunded')}}</a>
                                                    <a class="dropdown-item status_change" data-id="{{$data->id}}" data-val="0" href="#">{{__('Not Refunded')}}</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <x-view-icon :url="route(route_prefix().'admin.refund.product.show',$data->product_id)"/>
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
@endsection

@section('scripts')
    <x-datatable.js/>
        <script>
         $(document).ready(function(){
            $(document).on('click','.status_change',function (e){
                e.preventDefault();
                //get value
                var status = $(this).data('val');
                var id = $(this).data('id');
                var currentStatus =  $(this).text();
                currentStatus = currentStatus.trim();

                $(this).parent().prev('button').removeClass('status-'+currentStatus).addClass('status-'+status).text(currentStatus);
                //ajax call
                $.ajax({
                    'type': 'post',
                    'url' : "{{route('tenant.admin.refund.status.change')}}",
                    'data' : {
                        _token : "{{csrf_token()}}",
                        status : status,
                        id : id,
                    },
                    success: function (data){
                        $(this).parent().prev('button').removeClass(currentStatus).addClass(status).text(status);
                    }
                })
            });


        });


    </script>
@endsection
