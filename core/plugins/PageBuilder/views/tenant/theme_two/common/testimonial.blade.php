<section class="feedback-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container container-one">
        <div class="row align-items-center">
            <div class="col-lg-12 mt-4">
                <div class="feedback-slide-right">
                    <div class="global-slick-init feedback-right-slider dot-style-one dot-color-three slider-inner-margin" data-infinite="true" data-centerMode="true" data-centerPadding='0px' data-arrows="false" data-dots="true" data-slidesToShow="3" data-swipeToSlide="true"
                         data-autoplay="false" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="las la-angle-left"></i></div>' data-nextArrow='<div class="next-icon"><i class="las la-angle-right"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 3}},{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768,"settings": {"slidesToShow": 1}},{"breakpoint": 480, "settings": {"slidesToShow": 1} }]'>
                        @foreach($data['testimonial'] ?? [] as $item)
                            <div class="slick-slider-items">
                            <div class="single-feedback-two center-text theme-two">
                                <div class="single-feedback-two-contents">
                                    <div class="single-feedback-two-contents-bottom-quote">
                                        <i class="las la-quote-left"></i>
                                    </div>
                                    <p class="single-feedback-two-contents-para-two mt-3"> {{$item->description}} </p>
                                    <div class="single-feedback-two-contents-bottom feedback-two-border">
                                        <div class="single-feedback-two-contents-bottom-details">
                                            <h3 class="single-feedback-two-contents-bottom-details-title mt-3"> <a href="javascript:void(0)"> {{$item->name}} </a> </h3>
                                            {!! render_star_rating_markup($item->rating) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
