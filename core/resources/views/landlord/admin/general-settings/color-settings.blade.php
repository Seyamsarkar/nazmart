@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Color Settings')}} @endsection

@section('style')
    <x-colorpicker.css/>
@endsection

@section('content')
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{__('Color Settings')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>
                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.general.color.settings')}}">
                    @csrf

                    @if(is_null(tenant()))
                        <x-colorpicker.input value="{{get_static_option('main_color_one','#F04751')}}" name="main_color_one" label="{{__('Site Main Color One')}}"/>
                        <x-colorpicker.input value="{{get_static_option('main_color_two','#FF805D')}}" name="main_color_two" label="{{__('Site Main Color Two')}}"/>
                        <x-colorpicker.input value="{{get_static_option('main_color_three','#599A8D')}}" name="main_color_three" label="{{__('Site Main Color Three')}}"/>
                        <x-colorpicker.input value="{{get_static_option('main_color_four','#1E88E5')}}" name="main_color_four" label="{{__('Site Main Color Four')}}"/>

                        <x-colorpicker.input value="{{get_static_option('secondary_color','#F7A3A8')}}" name="secondary_color" label="{{__('Site Secondary Color One')}}"/>
                        <x-colorpicker.input value="{{get_static_option('secondary_color_two','#ffdcd2')}}" name="secondary_color_two" label="{{__('Site Secondary Color Two')}}"/>

                        <x-colorpicker.input value="{{get_static_option('section_bg_1','#FFFBFB')}}" name="section_bg_1" label="{{__('Section Background Color One')}}"/>
                        <x-colorpicker.input value="{{get_static_option('section_bg_2','#FFF6EE')}}" name="section_bg_2" label="{{__('Section Background Color Two')}}"/>
                        <x-colorpicker.input value="{{get_static_option('section_bg_3','#F4F8FB')}}" name="section_bg_3" label="{{__('Section Background Color Three')}}"/>
                        <x-colorpicker.input value="{{get_static_option('section_bg_4','#F2F3FB')}}" name="section_bg_4" label="{{__('Section Background Color Four')}}"/>
                        <x-colorpicker.input value="{{get_static_option('section_bg_5','#F9F5F2')}}" name="section_bg_5" label="{{__('Section Background Color Five')}}"/>
                        <x-colorpicker.input value="{{get_static_option('section_bg_6','#E5EFF8')}}" name="section_bg_6" label="{{__('Section Background Color Six')}}"/>

                        <x-colorpicker.input value="{{get_static_option('heading_color','#333333')}}" name="heading_color" label="{{__('Site Heading Color')}}"/>

                        <x-colorpicker.input value="{{get_static_option('body_color','#666666')}}" name="body_color" label="{{__('Site Body Color')}}"/>

                        <x-colorpicker.input value="{{get_static_option('light_color','#666666')}}" name="light_color" label="{{__('Site Light Color')}}"/>
                        <x-colorpicker.input value="{{get_static_option('extra_light_color','#888888')}}" name="extra_light_color" label="{{__('Site Extra Light Color')}}"/>

                        <x-colorpicker.input value="{{get_static_option('review_color','#FABE50')}}" name="review_color" label="{{__('Site Review Color')}}"/>

                        <x-colorpicker.input value="{{get_static_option('new_color','#5AB27E')}}" name="new_color" label="{{__('Site New Color')}}"/>
                    @endif

                    @if(tenant())
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3">
                            @include('landlord.admin.general-settings.tenant.theme.color-settings-theme-01', ['suffix' => 'theme_one'])
                            @include('landlord.admin.general-settings.tenant.theme.color-settings-theme-02', ['suffix' => 'theme_two'])
                            @include('landlord.admin.general-settings.tenant.theme.color-settings-theme-03', ['suffix' => 'theme_three'])
                        </div>
                    @endif

                    <button type="submit" class="btn btn-gradient-primary me-2">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<x-colorpicker.js/>
@endsection

