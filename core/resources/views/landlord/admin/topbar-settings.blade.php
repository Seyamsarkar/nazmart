@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Topbar Settings')}} @endsection

@section('content')
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{__('Topbar Settings')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>
                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.topbar.settings')}}">
                    @csrf
                    <x-fields.input name="topbar_twitter_url" value="{{get_static_option('topbar_twitter_url')}}" label="{{__('Twitter URL')}}"/>
                    <x-fields.input name="topbar_linkedin_url" value="{{get_static_option('topbar_linkedin_url')}}" label="{{__('Linkedin URL')}}"/>
                    <x-fields.input name="topbar_facebook_url" value="{{get_static_option('topbar_facebook_url')}}" label="{{__('Facebook URL')}}"/>
                    <x-fields.input name="topbar_youtube_url" value="{{get_static_option('topbar_youtube_url')}}" label="{{__('Youtube URL')}}"/>
                    <x-fields.switcher name="landlord_frontend_language_show_hide" value="{{get_static_option('landlord_frontend_language_show_hide')}}"  label="{{__('Enable/Disable Language')}}"/>
                    <button type="submit" class="btn btn-gradient-primary me-2">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
