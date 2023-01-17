@extends('landlord.frontend.user.dashboard.user-master')

@section('title')
    {{__('Change Password')}}
@endsection

@section('page-title')
    {{__('Change Password')}}
@endsection


@section('section')

        <div class="parent my-5">
            <h2 class="title">{{__('Change Password')}}</h2>
            <form action="{{route('landlord.user.password.change')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group single-input mt-4">
                    <label for="old_password" class="label-title">{{__('Old Password')}}</label>
                    <input type="password" class="form-control form--control" id="old_password" name="old_password"
                           placeholder="{{__('Old Password')}}">
                </div>
                <div class="form-group single-input mt-4">
                    <label for="password" class="label-title">{{__('New Password')}}</label>
                    <input type="password" class="form-control form--control" id="password" name="password"
                           placeholder="{{__('New Password')}}">
                </div>
                <div class="form-group single-input mt-4">
                    <label for="password_confirmation" class="label-title">{{__('Confirm Password')}}</label>
                    <input type="password" class="form-control form--control" id="password_confirmation"
                           name="password_confirmation" placeholder="{{__('Confirm Password')}}">
                </div>
                <div class="btn-wrapper mt-4">
                    <button id="save" type="submit" class="cmn-btn cmn-btn-bg-1 cmn-btn-small">{{__('Save changes')}}</button>
                </div>
            </form>
        </div>


@endsection

@section('scripts')
    <script>
        <x-btn.save/>
    </script>

    <script>
        $('.close-bars, .body-overlay').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').removeClass('active');
        });
        $('.sidebar-icon').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').addClass('active');
        });
    </script>

@endsection
