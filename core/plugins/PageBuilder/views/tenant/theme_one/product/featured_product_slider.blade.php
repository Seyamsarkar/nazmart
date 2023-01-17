<section class="featured-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container-one">
        <div class="section-title theme-one text-left">
            <h2 class="title"> {{__('Featured Product ')}}</h2>
            <div class="append-featured"></div>
        </div>

        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="global-slick-init recent-slider nav-style-one slider-inner-margin" data-appendArrows=".append-featured" data-infinite="true" data-arrows="true" data-dots="false" data-slidesToShow="4" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500"
                     data-prevArrow='<div class="prev-icon"><i class="las la-angle-left"></i></div>' data-nextArrow='<div class="next-icon"><i class="las la-angle-right"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 4}},{"breakpoint": 1600,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 1} }]'>
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

                            $data = get_product_dynamic_price($product);
                            $campaign_name = $data['campaign_name'];
                            $regular_price = $data['regular_price'];
                            $sale_price = $data['sale_price'];
                            $discount = $data['discount'];
                        @endphp

                    <div class="slick-slider-items wow {{$class}}" data-wow-delay=".{{$delay}}s">
                        <div class="global-card no-shadow radius-0 pb-0">
                            <div class="global-card-thumb">
                                <a href="{{route('tenant.shop.product.details', $product->slug)}}">
                                    {!! render_image_markup_by_attachment_id($product->image_id,) !!}
                                </a>

                                @if($discount != null)
                                    <div class="global-card-thumb-badge right-side">
                                        <span class="global-card-thumb-badge-box bg-color-two"> {{$discount.'%'}} {{__('Off')}} </span>

                                        @if(!empty($product->badge))
                                            <span class="global-card-thumb-badge-box bg-color-new"> {{$product?->badge?->name}} </span>
                                        @endif
                                    </div>
                                @endif

                                @include('tenant.frontend.shop.partials.product-options')
                            </div>
                            <div class="global-card-contents">
                                <div class="global-card-contents-flex">
                                    <h5 class="global-card-contents-title"> <a href="{{route('tenant.shop.product.details', $product->slug)}}"> {{Str::words($product->name, 4)}} </a> </h5>
                                    {!! render_product_star_rating_markup_with_count($product) !!}
                                </div>
                                <div class="price-update-through mt-3">
                                    <span class="flash-prices color-two"> {{amount_with_currency_symbol($sale_price)}} </span>
                                    <span
                                        class="flash-old-prices"> {{$regular_price != null ? amount_with_currency_symbol($regular_price) : ''}} </span>
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
