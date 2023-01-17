<section class="flash-store-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container container-one">
        <div class="section-title theme-one text-left">
            <h2 class="title"> {{$data['title']}} </h2>
            <div class="append-flash"></div>
        </div>
        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="global-slick-init recent-slider nav-style-one slider-inner-margin" data-appendArrows=".append-flash" data-infinite="true" data-arrows="true" data-dots="false" data-slidesToShow="4" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500"
                     data-prevArrow='<div class="prev-icon"><i class="las la-angle-left"></i></div>' data-nextArrow='<div class="next-icon"><i class="las la-angle-right"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 4}},{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 1} }]'>
                    @foreach($data['products'] as $product)
                        @php
                            if ($loop->odd) {
                                    $delay = 1;
                                    $class = 'fadeInDown';
                                }
                            else {
                                $delay = 2;
                                $class = 'fadeInUp';
                            }

                            $img_data = get_attachment_image_by_id($product->image_id, 'grid');
                            $img = !empty($img_data) ? $img_data['img_url'] : '';
                            $alt = !empty($img_data) ? $img_data['img_alt'] : '';

                            $discount = null;
                            $campaign_price = $product?->campaign_product?->campaign_price;
                            if ($product->sale_price)
                            {
                                $discount = 100-round(($campaign_price / $product->sale_price)*100, 2);
                            }
                        @endphp
                        <div class="slick-slider-items wow {{$class}}" data-wow-delay=".{{$delay}}s">
                            <div class="global-card no-shadow radius-0 pb-0">
                            <div class="global-card-thumb">
                                <a href="{{route('tenant.shop.product.details', $product->slug)}}">
                                    <img class="lazyloads" data-src="{{$img}}" alt="{{$alt}}">
                                </a>
                                <div class="global-card-thumb-badge right-side">
                                    @if($discount != null)
                                        <span class="global-card-thumb-badge-box bg-color-two"> {{$discount.'%'}} {{__('Off')}} </span>
                                    @endif
                                </div>

                                @include('tenant.frontend.shop.partials.product-options')
                            </div>
                            <div class="global-card-contents">
                                <div class="global-card-contents-flex">
                                    <h5 class="global-card-contents-title">
                                        <a href="{{route('tenant.shop.product.details', $product->slug)}}"> {{Str::words($product->name, 4)}} </a>
                                    </h5>
                                    {!! render_product_star_rating_markup_with_count($product) !!}
                                </div>
                                <div class="price-update-through mt-3">
                                    {!! campaign_product_prices($product, $campaign_price, 'color-two') !!}
                                </div>
                            </div>
                        </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
