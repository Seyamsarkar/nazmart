
<!-- Trendy area starts -->
<section class="trendy-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container container-one">
        <div class="section-title theme-two">
            <h2 class="title fw-400"> {{$data['title'] ?? 'Title'}} </h2>
            <p class="para"> {{$data['subtitle'] ?? ''}} </p>
        </div>
        <div class="row gy-4 mt-3">
            @foreach($data['products'] as $product)
                @php
                    $data = get_product_dynamic_price($product);
                    $campaign_name = $data['campaign_name'];
                    $regular_price = $data['regular_price'];
                    $sale_price = $data['sale_price'];
                    $discount = $data['discount'];
                @endphp

                <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="global-card center-text no-shadow radius-0 pb-0">
                    <div class="global-card-thumb">
                        <a href="{{route('tenant.shop.product.details', $product->slug)}}">
                            {!! render_image_markup_by_attachment_id($product->image_id, 'rounded', 'grid') !!}
                        </a>

                        <div class="global-card-thumb-badge right-side">
                            @if($discount != null)
                                <span
                                    class="global-card-thumb-badge-box bg-color-two"> {{$discount}}% {{__('off')}} </span>
                            @endif
                            @if(!empty($product->badge))
                                <span
                                    class="global-card-thumb-badge-box bg-color-new"> {{$product?->badge?->name}} </span>
                        @endif
                        </div>

                        @include('tenant.frontend.shop.partials.product-options')
                    </div>
                    <div class="global-card-contents">
                        <h5 class="global-card-contents-title">
                            <a href="{{route('tenant.shop.product.details', $product->slug)}}"> {{Str::words($product->name, 4)}} </a>
                        </h5>

                        {!! render_product_star_rating_markup_with_count($product) !!}

                        <div class="price-update-through mt-3">
                            <span class="flash-prices color-three"> {{amount_with_currency_symbol($sale_price)}} </span>
                            <span class="flash-old-prices"> {{$regular_price != null ? amount_with_currency_symbol($regular_price) : ''}} </span>
                        </div>

                        <div class="btn-wrapper">
                            @if($product->inventory_detail_count < 1)
                                <a href="javascript:void(0)" data-product_id="{{ $product->id }}" class="cmn-btn cmn-btn-outline-three radius-0 mt-3 add-to-cart-btn"> {{__('Add to Cart')}} </a>
                            @else
                                <a href="javascript:void(0)" data-action-route="{{ route('tenant.products.single-quick-view', $product->slug) }}" class="cmn-btn cmn-btn-outline-three radius-0 mt-3 product-quick-view-ajax"> {{__('Add to Cart')}} </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- Trendy area end -->
