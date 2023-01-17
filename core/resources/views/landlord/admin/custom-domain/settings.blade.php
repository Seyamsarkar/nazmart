@extends('landlord.admin.admin-master')

@section('title')
    {{__('Custom Domain  Settings')}}
@endsection

@section('style')
    <x-summernote.css/>
    <x-media-upload.css/>
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12">
                <x-error-msg/>
                <x-flash-msg/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__("Custom Domain Settings")}}</h4>
                        <form action="{{route('landlord.admin.custom.domain.requests.settings')}}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-group  mt-3">
                                <label>{{__('Title')}}</label>
                                <input type="text" name="custom_domain_settings_title" class="form-control"
                                       value="{{get_static_option('custom_domain_settings_title')}}">
                            </div>

                            <div class="form-group">
                                <label>{{__('Description')}}</label>
                                <textarea name="custom_domain_settings_description" class="form-control" cols="30"
                                          rows="8">{{get_static_option('custom_domain_settings_description')}}</textarea>
                            </div>

                            <div class="form-group  mt-3">
                                <label>{{__('Table Info Data Title')}}</label>
                                <input type="text" name="custom_domain_table_title" class="form-control"
                                       value="{{get_static_option('custom_domain_table_title')}}">
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3 mt-3">
                                    <label>{{__('Type One')}}</label>
                                    <input type="text" readonly class="form-control" value="CNAME Record">
                                </div>
                                <div class="form-group col-md-3 mt-3">
                                    <label>{{__('Host One')}}</label>
                                    <input type="text" readonly class="form-control" value="www">
                                </div>

                                <div class="form-group col-md-3 mt-3">
                                    <label>{{__('Value One')}}</label>
                                    <input type="text" readonly class="form-control"
                                           value="{{getenv('CENTRAL_DOMAIN')}}">
                                </div>

                                <div class="form-group col-md-3 mt-3">
                                    <label>{{__('TTL One')}}</label>
                                    <input type="text" readonly class="form-control" value="Automatic">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3 mt-3">
                                    <label>{{__('Type Two')}}</label>
                                    <input type="text" readonly class="form-control" value="CNAME Record">
                                </div>
                                <div class="form-group col-md-3 mt-3">
                                    <label>{{__('Host Two')}}</label>
                                    <input type="text" readonly class="form-control" value="@">
                                </div>

                                <div class="form-group col-md-3 mt-3">
                                    <label>{{__('Value Two')}}</label>
                                    <input type="text" readonly class="form-control"
                                           value="{{getenv('CENTRAL_DOMAIN')}}">
                                </div>
                                <div class="form-group col-md-3 mt-3">
                                    <label>{{__('TTL Two')}}</label>
                                    <input type="text" readonly class="form-control" value="Automatic">
                                </div>

                            </div>
                            <div class="row">
                                <div>
                                    <p>{{__('Use this if you are using clouldflare')}}</p>
                                </div>

                                <div class="col-12">
                                    <div class="row">
                                        <div class="form-group col-md-3 mt-3">
                                            <input class="form-control" value="A Record" disabled>
                                        </div>

                                        <div class="form-group col-md-3 mt-3">
                                            <input class="form-control" value="@" disabled>
                                        </div>

                                        <div class="form-group col-md-3 mt-3">
                                            <input class="form-control" value="{{$_SERVER['SERVER_ADDR']}}" disabled>
                                        </div>

                                        <div class="form-group col-md-3 mt-3">
                                            <input class="form-control" value="Automatic" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <x-fields.media-upload name="custom_domain_settings_screem_show_image"
                                                   title="{{__('Screen Shot Example')}}"/>
                            <button type="submit" id="update"
                                    class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-summernote.js/>
    <x-media-upload.js/>
    <script>
        $(document).ready(function () {

        });
    </script>
@endsection
