<section class="promo-area padding-top-100">
    <div class="container container-one">
        <div class="row">
            @foreach($data['repeater_title_'] ?? [] as $key => $title)
                <div class="col-xxl-3 col-xl-4 col-md-6 mt-4">
                    <div class="single-promo center-text single-border promo-padding">
                        <div class="single-promo-icon">
                            <i class="{{$data['repeater_icon_'][$key] ?? ''}}"></i>
                        </div>
                        <div class="single-promo-contents mt-2">
                            <h4 class="single-promo-contents-title fw-500"> <a href="javascript:void(0)"> {{\App\Helpers\SanitizeInput::esc_html($title)}}</a> </h4>
                            <p class="single-promo-contents-para mt-2"> {{\App\Helpers\SanitizeInput::esc_html($data['repeater_subtitle_'][$key]) ?? ''}} </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
