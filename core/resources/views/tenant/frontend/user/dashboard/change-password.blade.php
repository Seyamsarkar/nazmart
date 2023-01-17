@extends('tenant.frontend.user.dashboard.user-master')

@section('title')
{{__('Change Password')}}
@endsection

@section('site-title')
    {{__('Change Password')}}
@endsection

@section('section')
        <div class="parent my-5">
            <h2 class="title">{{__('Change Password')}}</h2>
            <form id="password_form" action="{{route('tenant.user.password.change')}}" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="old_password">{{__('Old Password')}}</label>
                    <input type="password" class="form-control" id="old_password" name="old_password"
                           placeholder="{{__('Old Password')}}">
                </div>
                <div class="form-group">
                    <label for="password">{{__('New Password')}}</label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="{{__('New Password')}}">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">{{__('Confirm Password')}}</label>
                    <input type="password" class="form-control" id="password_confirmation"
                           name="password_confirmation" placeholder="{{__('Confirm Password')}}">
                </div>
                <div class="btn-wrapper">
                    <button id="save" type="submit" class="btn-default boxed-btn">{{__('Save changes')}}</button>
                </div>
            </form>
        </div>
@endsection

@section('scripts')
    <script>
        <x-btn.save/>

        $(document).on('click', '#save', function (e){
            e.preventDefault();

            let form = $('#password_form');
            let old_password = form.find('#old_password').val();
            let password = form.find('#password').val();
            let password_confirmation = form.find('#password_confirmation').val();

            $.ajax({
                url: '{{route('tenant.user.password.change')}}',
                type: 'POST',
                data: {
                    'old_password': old_password,
                    'password': password,
                    'password_confirmation': password_confirmation,
                    '_token': "{{ csrf_token() }}"
                },
                beforeSend: function (){
                    $('.loader').show();
                },
                success: function (data){
                    if (data.type === 'success')
                    {
                        toastr.success(data.msg);
                        setTimeout(()=>{
                            location.href = data.url;
                        }, 2000);
                    }

                    $('.loader').hide();
                },
                error: function (data){
                    var response = data.responseJSON.errors

                    $.each(response,function (value,index){
                        toastr.error(index);
                    });

                    $('.loader').hide();
                    $(this).text("{{__('Save Changes')}})");
                }
            })
        });
    </script>
@endsection
