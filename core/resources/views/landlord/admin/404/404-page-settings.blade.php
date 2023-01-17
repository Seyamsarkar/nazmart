@extends(route_prefix().'admin.admin-master')

@section('title')
    {{__('404 Error Page Settings')}}
@endsection

@section('style')
    <x-media-upload.css/>
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-error-msg/>
                <x-flash-msg/>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__('404 Error Page Settings')}}</h4>
                        <form action="{{route(route_prefix().'admin.404.page.settings')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <x-fields.input type="text" value="{{get_static_option('error_404_page_subtitle')}}" name="error_404_page_subtitle" label="{{__('Title')}}"/>
                            <x-fields.input type="text" value="{{get_static_option('error_404_page_button_text')}}" name="error_404_page_button_text" label="{{__('Button Text')}}"/>
                            <x-fields.media-upload name="error_image" title="{{__('Site Logo')}}"/>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Settings')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-media-upload.js/>
@endsection
