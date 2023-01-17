<!-- Team area starts -->
<section class="team-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container container-one">
        <div class="section-title theme-one text-left">
            <h2 class="title"> {{$data['title']}} </h2>
            <div class="append-team"></div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12 mt-4">
                <div class="global-slick-init feedback-right-slider nav-style-one slider-inner-margin" data-appendArrows=".append-team" data-infinite="true" data-arrows="true" data-dots="false" data-slidesToShow="4" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500"
                     data-prevArrow='<div class="prev-icon"><i class="las la-angle-left"></i></div>' data-nextArrow='<div class="next-icon"><i class="las la-angle-right"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 4}},{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 3}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768,"settings": {"slidesToShow": 2}},{"breakpoint": 480, "settings": {"slidesToShow": 1} }]'>
                    @foreach($data['repeater']['repeater_name_'] ?? [] as $key => $info)
                        @php
                            $image = $data['repeater']['repeater_image_'][$key];
                        @endphp
                        <div class="slick-slider-items">
                        <div class="single-team team-padding border-1">
                            <div class="single-team-thumb">
                                <a href="javascript:void(0)">
                                    {!! render_image_markup_by_attachment_id($image) !!}
                                </a>
                            </div>
                            <div class="single-team-content mt-3">
                                <h4 class="single-team-content-title fw-500"> <a href="javascript:void(0)"> {{$data['repeater']['repeater_name_'][$key]}} </a> </h4>
                                <span class="single-team-content-title mt-1"> {{$data['repeater']['repeater_designation_'][$key]}} </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Team area ends -->