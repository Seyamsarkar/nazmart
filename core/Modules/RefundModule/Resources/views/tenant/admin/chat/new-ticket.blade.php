@extends(route_prefix().'admin.admin-master')

@section('title') {{__('New Refund Message')}} @endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12">
                <x-error-msg/>
                <x-flash-msg/>
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex justify-content-between">
                            <h4 class="header-title mb-4">{{__("New Refund Message")}}</h4>
                            <a href="{{route(route_prefix().'admin.refund.chat.all')}}" class="btn btn-primary">{{__('All Refund Message')}}</a>
                        </div>

                        <form action="{{route(route_prefix().'admin.refund.chat.new')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>{{__('Title')}}</label>
                                <input type="text" class="form-control" name="title" placeholder="{{__('Title')}}">
                            </div>
                            <div class="form-group">
                                <label>{{__('Subject')}}</label>
                                <input type="text" class="form-control" name="subject" placeholder="{{__('Subject')}}">
                            </div>
                            <div class="form-group">
                                <label>{{__('User')}}</label>
                                <select name="user_id" class="form-control nice-select wide">
                                    @foreach($all_users as $user)
                                    <option value="{{$user->id}}">{{$user->username}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <br>
                            <div class="form-group">
                                <label>{{__('Description')}}</label>
                                <textarea name="description"class="form-control" cols="30" rows="10" placeholder="{{__('Description')}}"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Submit Message')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

