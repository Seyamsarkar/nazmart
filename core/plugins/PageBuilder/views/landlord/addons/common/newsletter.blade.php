@php
    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();
@endphp


<section class="getupdate-area section-bg-1" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}" id="{{$data['section_id']}}">
    <div class="container">
        <div class="getupdate-wrapper getupdate-bg radius-20">
            <div class="getupdate-shape">
                {!! render_image_markup_by_attachment_id($data['bg_image']) !!}
            </div>
            <div class="getupdate-content center-text">
                <h2 class="getupdate-content-title"> {{$data['title']}} </h2>
                <p class="getupdate-content-para mt-4"> {{$data['subtitle']}} </p>
                <form action="" class="getupdate-content-form mt-4" id="landlord-newsletter-form">
                    <div class="getupdate-content-form-single radius-5">
                        <input type="email" class="getupdate-content-form-single-input radius-5" name="email" placeholder="{{$data['input_text'] ?? __('Your Email Here')}}">
                        <button type="submit"> {{$data['button_text'] ?? __('Sign Up')}} </button>
                    </div>
                </form>
                <div class="form-message-show mt-4"></div>
            </div>
        </div>
    </div>
</section>
