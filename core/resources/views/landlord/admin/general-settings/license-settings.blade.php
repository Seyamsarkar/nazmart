@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('License Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                <x-error-msg/>
                <x-flash-msg/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__("License Settings")}}</h4>
                        @if('verified' == get_static_option('item_license_status'))
                            <div class="alert alert-success">{{__('Your Application is Registered')}}</div>
                        @endif
                        <form action="{{route(route_prefix().'admin.general.license.settings')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="item_purchase_key">{{__('Purchase Key')}}</label>
                                <input type="text" name="item_purchase_key"  class="form-control" value="{{get_static_option('item_purchase_key')}}" id="item_purchase_key">
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Submit Information')}}</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
