@extends('tenant.admin.admin-master')
@section('title') {{__('Email Settings')}} @endsection

@section('content')
    <div class="col-12 stretch-card">
        <div class="row g-4">
            <div class="col-lg-12">
                <x-error-msg/>
                <x-flash-msg/>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">{{__('Email Settings')}}</h4>

                        <form class="forms-sample" method="post" action="{{route('tenant.admin.general.email.settings')}}">
                            @csrf
                            <div class="row">
                               <x-fields.input type="email" value="{{get_static_option('tenant_site_global_email')}}" name="tenant_site_global_email" label="{{__('Site Global Email')}}" info="{{__('you will get all mail to this email, also this will be in your user form address in all the mail send from the system.')}}"/>
                            </div>

                            <button type="submit" class="btn btn-gradient-primary me-2">{{__('Save Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

