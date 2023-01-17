@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Seo Settings')}} @endsection
@section('style')
    <x-media-upload.css/>
@endsection
@section('content')
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{__('Seo Identity')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>

                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.general.seo.settings')}}">
                    @csrf
                            <x-fields.input type="text" value="{{get_static_option('site_meta_title')}}" name="site_meta_title" label="{{__('Site Meta Author')}}"/>
                            <x-fields.textarea  value="{{get_static_option('site_meta_tags')}}" name="site_meta_tags" label="{{__('Site Meta Tags')}}" info="{{__('separate tags by comma (,)')}}"/>
                            <x-fields.textarea  value="{{get_static_option('site_meta_keywords')}}" name="site_meta_keywords" label="{{__('Site Meta Keywords')}}" info="{{__('separate tags by comma (,)')}}"/>
                            <x-fields.textarea value="{{get_static_option('site_meta_description')}}" name="site_meta_description" label="{{__('Site Meta Description')}}"/>
                            <h4 class="mb-3">{{__('OG Meta Info')}}</h4>
                            <x-fields.input type="text" value="{{get_static_option('site_og_meta_title')}}" name="site_og_meta_title" label="{{__('Og Meta Title')}}"/>
                            <x-fields.textarea  value="{{get_static_option('site_og_meta_description')}}" name="site_og_meta_description" label="{{__('Og Meta Description')}}"/>
                            <x-fields.media-upload name="site_og_meta_image" title="{{__('Site Og Image')}}" id="{{get_static_option('site_og_meta_image')}}"/>
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
