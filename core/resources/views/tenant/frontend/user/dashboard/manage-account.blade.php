@extends('tenant.frontend.user.dashboard.user-master')
@section('title')
    {{__('Manage My Account')}}
@endsection

@section('section')
    <style>
        .media-upload-btn-wrapper .btn-info{
            color: #fff;
            width: 50%;
            border-radius: 0;
            background-color: var(--main-color-one);
            border-color: var(--main-color-one);
        }
        .media-upload-btn-wrapper .img-wrap{
            margin: 0;
        }

        .dashboard-profile-flex label[for=image]{
            font-weight: bold;
            color: #0b0b0b;
        }

        .nav-link{
            color: #0b0b0b;
        }
        .nav-link:hover {
            color: var(--main-color-one);
        }
    </style>

    <ul class="nav nav-pills mb-3" id="v-pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="v-pills-manage-tab" data-bs-toggle="pill"
                    data-bs-target="#v-pills-manage" type="button" role="tab" aria-controls="v-pills-manage"
                    aria-selected="true">{{__('Manage My Account')}}</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile"
                    type="button" role="tab" aria-controls="v-pills-profile"
                    aria-selected="false">{{__('My Profile')}}</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="v-pills-address-tab" data-bs-toggle="pill" data-bs-target="#v-pills-address"
                    type="button" role="tab" aria-controls="v-pills-address"
                    aria-selected="false">{{__('Address Book')}}</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="v-pills-password-tab" data-bs-toggle="pill" data-bs-target="#v-pills-password"
                    type="button" role="tab" aria-controls="v-pills-password" aria-selected="false">{{__('Change Password')}}
            </button>
        </li>
    </ul>
    <div class="tab-content mt-5" id="v-pills-tabContent">
        <div class="tab-pane fade show active" id="v-pills-manage" role="tabpanel" aria-labelledby="v-pills-manage-tab">
            @include('tenant.frontend.user.dashboard.manage-account-partials.manage-my-account')
        </div>
        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
            @include('tenant.frontend.user.dashboard.manage-account-partials.my-profile')
        </div>
        <div class="tab-pane fade" id="v-pills-address" role="tabpanel" aria-labelledby="v-pills-address-tab">
            @include('tenant.frontend.user.dashboard.manage-account-partials.address-book')
        </div>
        <div class="tab-pane fade" id="v-pills-password" role="tabpanel" aria-labelledby="v-pills-password-tab">
            @include('tenant.frontend.user.dashboard.manage-account-partials.change-password')
        </div>
    </div>

    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-media-upload.js/>

    <script>
        $(function () {
            $(document).on('click', '.attachment-preview .user-thumb', function () {
                $('.media_upload_form_btn').trigger('click');
            });

            var selectdCountry = "{{$user_details->country}}";
            $('#country option[value="' + selectdCountry + '"]').attr('selected', true);

            $(document).on('change', 'select[name=country]', function () {
                let el = $(this);
                let country = el.val();

                $.ajax({
                    url: '{{route('tenant.user.dashboard.get.state.ajax')}}',
                    type: 'GET',
                    data: {
                        country_id: country
                    },
                    success: function (data) {
                        $('select[name=state]').html(data.markup);
                    }
                })
            });

            $(document).on('click', '.address-submit-btn', function (e) {
                e.preventDefault();
                let name = $('.address_form input[name=full_name]').val();
                let email = $('.address_form input[name=email]').val();
                let phone = $('.address_form input[name=phone]').val();
                let country = $('.address_form select[name=country]').val();
                let state = $('.address_form select[name=state]').val();
                let city = $('.address_form input[name=city]').val();
                let address = $('.address_form textarea[name=address]').val();

                $.ajax({
                    type: 'POST',
                    url: '{{route('tenant.user.home.address.update')}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        name: name,
                        email: email,
                        phone: phone,
                        country: country,
                        state: state,
                        city: city,
                        address: address
                    },
                    beforeSend: function () {
                        $('.loader').show();
                    },
                    success: function (data) {
                        if (data.msg) {
                            toastr.success(data.msg)
                            $('.loader').hide();
                        }
                    },
                    error: function () {
                    }
                })
            });

            $(document).on('submit', 'form.profile-edit-form', function (e) {
                e.preventDefault();

                let el = $(this);
                let form = new FormData(e.target);

                $.ajax({
                    url: '{{route('tenant.user.profile.update')}}',
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: form,
                    beforeSend: function () {
                        $('.loader').show();
                    },
                    success: function (data) {
                        if (data.msg) {
                            toastr.success(data.msg);
                        }
                        $('.loader').hide();
                    },
                    error: function (data) {
                        var response = JSON.parse(data.responseText);
                        $.each(response.errors, function (key, value) {
                            toastr.error(value);
                        });

                        $('.loader').hide();
                    }
                })
            });

            $(document).on('submit', '.change_password_form', function (e){
                e.preventDefault();

                let formData = new FormData(e.target);

                $.ajax({
                    url: '{{route('tenant.user.password.change')}}',
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    beforeSend: function (){
                        $('.loader').show();
                    },
                    success: function (data){
                        $('.loader').hide();

                        if (data.type === 'success')
                        {
                            toastr.success(data.msg)
                            toastr.warning('We\'re logging you out for the security reason and redirecting to login page');

                            setTimeout(()=>{
                                location.href = data.url;
                            }, 3000)
                        } else {
                            toastr.error(data.msg);
                        }
                    },
                    error: function (data){
                        $('.loader').hide();

                        var response = JSON.parse(data.responseText);
                        $.each(response.errors, function (key, value) {
                            toastr.error(value);
                        });
                    }
                });
            })
        })
    </script>
@endsection
