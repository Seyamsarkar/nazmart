@php
    $userlang = get_user_lang();
@endphp

<div class="contact-inner-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="content-area">
                    <div class="section-title padding-bottom-25">
                        <h4 class="title">{{$data['title']}}</h4>
                    </div>
                    @foreach($data['repeater_data']['repeater_info_'] ?? [] as $key => $info)
                      <div class="single-contact-item-02">
                        <div class="icon">
                            <i class="{{$data['repeater_data']['repeater_icon_'][$key] ?? ''}}"></i>
                        </div>
                        <div class="content">
                            <p class="details">{{$info ?? ''}}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6 offset-lg-3">
                <div class="contact-form style-01">
                    @if(!empty($data['custom_form_id']))
                        @php $form_details = \App\Models\FormBuilder::find($data['custom_form_id']); @endphp
                    @endif
                    {!! \App\Helpers\FormBuilderCustom::render_form(optional($form_details)->id,null,null,'btn-default') !!}
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        $(document).on('submit', '.custom-form-builder-ten', function (e) {
            e.preventDefault();
            var btn = $('#contact_form_btn');
            var form = $(this);
            var formID = form.attr('id');
            var msgContainer =  form.find('.error-message');
            var formSelector = document.getElementById(formID);
            var formData = new FormData(formSelector);
            msgContainer.html('');
            $.ajax({
                url: "{{route(route_prefix().'frontend.form.builder.custom.submit')}}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}",
                },
                beforeSend:function (){
                    btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> {{__("Submitting..")}}');
                },
                processData: false,
                contentType: false,
                data:formData,
                success: function (data) {
                    form.find('.ajax-loading-wrap').removeClass('show').addClass('hide');
                    msgContainer.html('<div class="alert alert-'+data.type+'">' + data.msg + '</div>');
                    btn.text('{{__("Submit Message")}}');
                    form[0].reset();

                },
                error: function (data) {

                    form.find('.ajax-loading-wrap').removeClass('show').addClass('hide');
                    var errors = data.responseJSON.errors;
                    var markup = '<ul class="alert alert-danger">';

                    $.each(errors,function (index,value){
                        markup += '<li>'+value+'</li>';})
                    markup += '</ul>';


                    msgContainer.html(markup);
                    btn.text('{{__("Submit Message")}}');
                }
            });
        });
    </script>

@endsection


