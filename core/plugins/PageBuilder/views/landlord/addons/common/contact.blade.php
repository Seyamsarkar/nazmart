@php
    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();
@endphp

<section class="contact-map-area section-bg-1" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="contact-map-wrapper">
                    <div class="contact-map-wrapper-flex">
                        <div class="contact-map">
                            {!! $data['location'] !!}
                        </div>
                        <div class="contact-map-wrapper-contents bg-white">
                            <h2 class="contact-map-wrapper-contents-title title-bottom-border"> {{$data['title'] ?? 'Send us an Email'}} </h2>
                            @if(!empty($data['custom_form_id']))
                                @php $form_details = \App\Models\FormBuilder::find($data['custom_form_id']); @endphp

                                {!! \App\Helpers\FormBuilderCustom::render_form(optional($form_details)->id,null,null,'btn-default') !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@section('scripts')
    <x-custom-js.contact-form-store/>
@endsection

