<section class="store-area" data-padding-top="{{$data['padding_top']}}"
         data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container container-one">
        <div class="section-title theme-two">
            <h2 class="title fw-400"> {{$data['title']}} </h2>
            <p class="para"> {{$data['subtitle']}} </p>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12 mt-4">
                <div class="store-isotope">
                    @php
                        $all = !empty($data['categories']) ? $data['categories']->pluck('id')->toArray() : '';
                        $allIds = implode(',', $all);
                    @endphp
                    <ul class="store-isotope-list filter-list store-tabs">
                        <li class="list active" data-limit="{{$data['product_limit']}}"
                            data-tab="all" data-all-id="{{$allIds}}"> {{__('All')}} </li>
                        @foreach($data['categories'] as $category)
                            <li class="list" data-tab="{{$category->slug}}"
                                data-limit="{{$data['product_limit']}}"> {{$category->name}} </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="row gy-5 mt-3 markup_wrapper">
            @foreach($data['products'] as $product)
                @php
                    $data_info = get_product_dynamic_price($product);
                    $campaign_name = $data_info['campaign_name'];
                    $regular_price = $data_info['regular_price'];
                    $sale_price = $data_info['sale_price'];
                    $discount = $data_info['discount'];
                @endphp
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="global-card center-text no-shadow radius-0 pb-0">
                        <div class="global-card-thumb">
                            <a href="{{route('tenant.shop.product.details', $product->slug)}}">
                                {!! render_image_markup_by_attachment_id($product->image_id) !!}
                            </a>
                            <div class="global-card-thumb-badge right-side">
                                @if($discount != null)
                                    <span
                                        class="global-card-thumb-badge-box bg-color-two"> {{$discount.'% '. __('Off')}} </span>
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
                                <span
                                    class="flash-prices color-three"> {{amount_with_currency_symbol($sale_price)}} </span>
                                <span
                                    class="flash-old-prices"> {{$regular_price != null ? amount_with_currency_symbol($regular_price) : ''}} </span>
                            </div>
                            <div class="btn-wrapper">
                                @if($product->inventory_detail_count < 1)
                                    <a href="javascript:void(0)" data-product_id="{{ $product->id }}"
                                       class="add-to-cart-btn cmn-btn cmn-btn-outline-three radius-0 mt-3"> {{__('Add to Cart')}} </a>
                                @else
                                    <a href="javascript:void(0)"
                                       data-action-route="{{ route('tenant.products.single-quick-view', $product->slug) }}"
                                       class="product-quick-view-ajax cmn-btn cmn-btn-outline-three radius-0 mt-3"> {{__('Add to Cart')}} </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@section("scripts")
    <script>
        $(function () {
            $(document).on('click', '.store-tabs .list', function (e) {
                e.preventDefault();

                let el = $(this);
                let tab = el.data('tab');
                let limit = el.data('limit');
                let allId = el.data('all-id');

                $.ajax({
                    type: 'GET',
                    url: "{{route('tenant.category.wise.product.two')}}",
                    data: {
                        category: tab,
                        limit: limit,
                        allId: allId
                    },
                    beforeSend: function () {
                        $('.loader').fadeIn(200);
                    },
                    success: function (data) {
                        let tab = $('li.list[data-tab='+data.category+']');
                        let markup_wrapper = $('.markup_wrapper');

                        $('li.list').removeClass('active');
                        tab.addClass('active');
                        markup_wrapper.hide();
                        markup_wrapper.html(data.markup);
                        markup_wrapper.fadeIn();
                        $('.loader').fadeOut(200);
                    },
                    error: function (data) {
                        console.log('error')
                    }
                });
            });
        });
    </script>
@endsection
