@extends('tenant.frontend.user.dashboard.user-master')
@section('title')
    {{__('Refund Chat')}}
@endsection

@section('section')
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
    </style>

    <div class="text-end">
        <a href="{{route('tenant.frontend.support.ticket')}}" class="btn btn-primary rounded-0 margin-bottom-30" data-bs-toggle="modal" data-bs-target="#newChat">{{__('New Chat')}}</a>
    </div>
        @if(count($all_chats) > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{__('ID')}}</th>
                        <th>{{__('Title')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Action')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($all_chats as $data)
                        <tr>
                            <td>#{{$data->id}}</td>
                            <td>{{$data->title}}
                            <p>{{__('created at:')}} <small>{{$data->created_at->format('D, d M Y')}}</small></p>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <div class="badge bg-primary">{{$data->status}}</div>
                                </div>
                            </td>
                            <td>
                                <a href="{{route('tenant.user.dashboard.chat.message.show',$data->id)}}"  class="btn btn-info text-white btn-xs" target="_blank">
                                    <i class="las la-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="blog-pagination">
                {{ $all_chats->links() }}
            </div>
        @else
            <div class="alert alert-warning">{{__('Nothing Found')}}</div>
        @endif

    <!-- Modal -->
    <div class="modal fade" id="newChat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('Refund Request Message')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('tenant.user.dashboard.refund.chat.list')}}" method="post" class="support-ticket-form-wrapper" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                        <input type="hidden" name="via" value="{{__('website')}}">
                        <div class="form-group">
                            <label>{{__('Title')}}</label>
                            <input type="text" class="form-control" name="title" placeholder="{{__('Title')}}">
                        </div>
                        <div class="form-group">
                            <label>{{__('Subject')}}</label>
                            <input type="text" class="form-control" name="subject" placeholder="{{__('Subject')}}">
                        </div>

                        <div class="form-group">
                            <label>{{__('Description')}}</label>
                            <textarea name="description" class="form-control" cols="30" rows="10" placeholder="{{__('Description')}}"></textarea>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                    <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (function (){
            "use strict";

            $(document).on('click','.status_change',function (e){
                e.preventDefault();
                //get value
                var status = $(this).data('val');
                var id = $(this).data('id');
                var currentStatus =  $(this).parent().prev('button').text();
                currentStatus = currentStatus.trim();
                $(this).parent().prev('button').removeClass('status-'+currentStatus).addClass('status-'+status).text(status);
                //ajax call
                $.ajax({
                    'type': 'post',
                    'url' : "{{route('tenant.user.dashboard.support.ticket.status.change')}}",
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


        })(jQuery);
    </script>
@endsection
