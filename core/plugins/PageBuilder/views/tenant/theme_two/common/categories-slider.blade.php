<div class="single-product-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container container-one">
        <div class="row">
            <div class="col-lg-12">
                <div class="global-slick-init recent-slider nav-style-one slider-inner-margin" data-infinite="true" data-arrows="true" data-dots="false" data-slidesToShow="6" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="las la-angle-left"></i></div>'
                     data-nextArrow='<div class="next-icon"><i class="las la-angle-right"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 5}},{"breakpoint": 1400,"settings": {"slidesToShow": 4}},{"breakpoint": 1200,"settings": {"slidesToShow": 3}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 576, "settings": {"slidesToShow": 1} }]'>
                    @foreach($data['categories_info'] as $info)
                        <div class="slick-slider-item">
                            <div class="single-product-item center-text single-product-item-padding single-product-item-border">
                                <a href="{{route('tenant.shop.category.products', ['category', $info->slug])}}" class="single-product-item-thumb">
                                    {!! render_image_markup_by_attachment_id($info->image_id) !!}
                                </a>
                                <div class="single-product-item-contents mt-3">
                                    <h3 class="single-product-item-contents-title fw-400">
                                        <a href="{{route('tenant.shop.category.products', ['category', $info->slug])}}"> {{$info->name}} </a>
                                    </h3>

                                    @if($data['product_count'] == 'on')
                                        <span class="single-product-item-contents-subtitle mt-2"> {{count($info->product_categories)}} {{__('Items')}} </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
