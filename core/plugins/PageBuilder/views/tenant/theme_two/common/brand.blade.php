<div class="brand-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container container-one">
        <div class="row">
            <div class="col-lg-12">
                <div class="global-slick-init slider-inner-margin dot-style-one dot-color-two" data-infinite="true" data-arrows="false" data-dots="true" data-slidesToShow="{{$data['item_pagination']}}" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-responsive='[{"breakpoint": 1600,"settings": {"slidesToShow": 5}},{"breakpoint": 1200,"settings": {"slidesToShow": 4}},{"breakpoint": 992,"settings": {"slidesToShow": 3}},{"breakpoint": 768, "settings": {"slidesToShow": 3}},{"breakpoint": 576, "settings": {"slidesToShow": 2}}]'>
                    @foreach($data['brands'] as $brand)
                        <div class="slick-slider-item">
                        <div class="single-brand">
                            <a href="javascript:void(0)" class="single-brand-item">
                                {!! render_image_markup_by_attachment_id($brand->image_id) !!}
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
