@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Basic Settings')}} @endsection
@section('style')
    <x-media-upload.css/>
@endsection
@section('content')
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{__('Basic Settings')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>
                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.general.basic.settings')}}">
                    @csrf
                    <x-fields.input type="text" value="{{get_static_option('site_title')}}" name="site_title" label="{{__('Site Title')}}"/>
                    <x-fields.input type="text" value="{{get_static_option('site_tag_line')}}" name="site_tag_line" label="{{__('Site Tag Line')}}"/>
                    <x-fields.textarea type="text" value="{{get_static_option('site_footer_copyright_text')}}" name="site_footer_copyright_text" label="{{__('Footer Copyright Text')}}" info="{{__('{copy} Will replace by & and {year} will be replaced by current year.')}}"/>
                    <div class="form-group">
                        @php
                            $list = DateTimeZone::listIdentifiers();
                        @endphp
                        <label for="timezone">{{__('Select Timezone')}}</label>
                        <select class="form-control" name="timezone" id="timezone">
                            @foreach($list as $zone)
                                <option value="{{$zone}}" {{$zone == get_static_option('timezone') ? 'selected' : ''}}>{{$zone}}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted"></small>
                    </div>

                    <x-fields.switcher value="{{get_static_option('dark_mode_for_admin_panel')}}" name="dark_mode_for_admin_panel" label="{{__('Enable/Disable Dark Mode For Admin Panel')}}"/>
                    <x-fields.switcher value="{{get_static_option('maintenance_mode')}}" name="maintenance_mode" label="{{__('Enable/Disable Maintenance Mode')}}"/>
                    <x-fields.switcher value="{{get_static_option('language_selector_status')}}" name="language_selector_status" label="{{__('Show/Hide Language Selector In Frontend')}}"/>
                    <x-fields.switcher value="{{get_static_option('guest_order_system_status')}}" name="guest_order_system_status" label="{{__('Show/Hide Guest Order System')}}"/>
                    <x-fields.switcher value="{{get_static_option('user_email_verify_status')}}" name="user_email_verify_status" label="{{__('Disable/Enable User Email Verify')}}" info="{{__('if you keep it no, it will allow user to register without being ask for email verify.')}}"/>

                    <x-fields.media-upload name="placeholder_image" title="{{__('Placeholder Image')}}"/>
                    
                    <button type="submit" class="btn btn-gradient-primary mt-5 me-2">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection
@section('scripts')
    <x-media-upload.js/>
@endsection
