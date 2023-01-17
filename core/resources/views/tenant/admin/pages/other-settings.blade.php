@extends(route_prefix().'admin.admin-master')

@section('title') {{__('Other Settings')}} @endsection

@section('style')

@endsection
@section('content')
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{__('Other Settings')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>
                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.other.settings')}}">
                    @csrf
                    <x-lang-tab>
                        @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                            <x-slot :name="$lang->slug">
                                <x-fields.input type="text" value="{{get_static_option('home_one_header_button_'.$lang->slug.'_text')}}" name="home_one_header_button_{{ $lang->slug}}_text" label="{{__('Home One Header Button Text')}}"/>
                                <x-fields.input type="text" value="{{get_static_option('home_one_header_button_'.$lang->slug.'_url')}}" name="home_one_header_button_{{ $lang->slug}}_url" label="{{__('Home One Header Button URL')}}"/>

                                <x-fields.input type="text" value="{{get_static_option('home_two_header_button_'.$lang->slug.'_text')}}" name="home_two_header_button_{{ $lang->slug}}_text" label="{{__('Home Two Header Button Text')}}"/>
                                <x-fields.input type="text" value="{{get_static_option('home_two_header_button_'.$lang->slug.'_url')}}" name="home_two_header_button_{{ $lang->slug}}_url" label="{{__('Home Two Header Button URL')}}"/>

                                <x-fields.input type="text" value="{{get_static_option('home_three_header_button_'.$lang->slug.'_text')}}" name="home_three_header_button_{{ $lang->slug}}_text" label="{{__('Home Three Header Button Text')}}"/>
                                <x-fields.input type="text" value="{{get_static_option('home_three_header_button_'.$lang->slug.'_url')}}" name="home_three_header_button_{{ $lang->slug}}_url" label="{{__('Home Three Header Button URL')}}"/>

                                <x-fields.input type="text" value="{{get_static_option('home_four_header_button_'.$lang->slug.'_text')}}" name="home_four_header_button_{{ $lang->slug}}_text" label="{{__('Home Four Header Button Text')}}"/>
                                <x-fields.input type="text" value="{{get_static_option('home_four_header_button_'.$lang->slug.'_url')}}" name="home_four_header_button_{{ $lang->slug}}_url" label="{{__('Home Four Header Button URL')}}"/>

                                <x-fields.input type="text" value="{{get_static_option('home_five_header_button_'.$lang->slug.'_text')}}" name="home_five_header_button_{{ $lang->slug}}_text" label="{{__('Home Five Header Button Text')}}"/>
                                <x-fields.input type="text" value="{{get_static_option('home_five_header_button_'.$lang->slug.'_url')}}" name="home_five_header_button_{{ $lang->slug}}_url" label="{{__('Home Five Header Button URL')}}"/>

                                <x-fields.input type="text" value="{{get_static_option('home_six_header_button_'.$lang->slug.'_text')}}" name="home_six_header_button_{{ $lang->slug}}_text" label="{{__('Home Six Header Button Text')}}"/>
                                <x-fields.input type="text" value="{{get_static_option('home_six_header_button_'.$lang->slug.'_url')}}" name="home_six_header_button_{{ $lang->slug}}_url" label="{{__('Home Six Header Button URL')}}"/>

                            </x-slot>
                        @endforeach
                    </x-lang-tab>

                    <button type="submit" class="btn btn-gradient-primary me-2">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')

@endsection
