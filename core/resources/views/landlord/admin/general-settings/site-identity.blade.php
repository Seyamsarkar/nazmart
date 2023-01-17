
@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Site Identity')}} @endsection
@section('style')
    <x-media-upload.css/>
@endsection
@section('content')
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{__('Site Identity')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>
                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.general.site.identity')}}">
                    @csrf
                    <x-fields.media-upload name="site_logo" title="{{__('Site Logo')}}"/>
                    <x-fields.media-upload name="site_white_logo" title="{{__('Site White Logo')}}"/>
                    <x-fields.media-upload name="site_favicon" title="{{__('Site Favicon')}}"/>
                    <button type="submit" class="btn btn-gradient-primary me-2">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection
@section('scripts')
    <x-media-upload.js/>
@endsection
