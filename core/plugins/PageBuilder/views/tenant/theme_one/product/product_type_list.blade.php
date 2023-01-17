<section class="store-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container container-one">
        <div class="section-title theme-one text-left">
            <h2 class="title"> {{$data['title']}} </h2>
            <div class="section-title theme-one-btn">
                <a href="{{$data['view_all_url']}}" class="view-all"> {{__('View All')}} </a>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-lg-4 mt-4">
                <div class="store-tab-contents">
                    <ul class="tabs store-tabs">
                        <li data-tab="all" class="active"> {{__('All Category')}} </li>
                        @foreach($data['categories'] as $category)
                            <li data-tab="{{$category->slug}}" data-limit="{{$data['product_limit']}}"> {{$category->name}} </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8">
                <div class="tab-content-item active" id="all">
                    <div class="row">
                        @foreach($data['products'] as $product)
                            @php
                                $img_data = get_attachment_image_by_id($product->image_id, 'grid');
                                $img = !empty($img_data) ? $img_data['img_url'] : '';
                                $alt = !empty($img_data) ? $img_data['img_alt'] : '';

                                $data_info = get_product_dynamic_price($product);
                                $campaign_name = $data_info['campaign_name'];
                                $regular_price = $data_info['regular_price'];
                                $sale_price = $data_info['sale_price'];
                                $discount = $data_info['discount'];
                            @endphp

                            <div class="col-xxl-4 col-lg-6 col-md-6 mt-4">
                                <div class="global-card no-shadow radius-0 pb-0">
                                    <div class="global-card-thumb">
                                        <a href="{{route('tenant.shop.product.details', $product->slug)}}">
                                            <img class="lazyloads" data-src="{{$img}}" alt="{{$alt}}">
                                        </a>
                                        <div class="global-card-thumb-badge right-side">
                                            @if($discount != null)
                                                <span class="global-card-thumb-badge-box bg-color-two"> {{$discount.'% '. __('Off')}} </span>
                                            @endif
                                        </div>

                                        @include('tenant.frontend.shop.partials.product-options')
                                    </div>
                                    <div class="global-card-contents">
                                        <div class="global-card-contents-flex">
                                            <h5 class="global-card-contents-title"> <a href="{{route('tenant.shop.product.details', $product->slug)}}"> {{Str::words($product->name, 4)}} </a> </h5>
                                            {!! render_product_star_rating_markup_with_count($product) !!}
                                        </div>

                                        <div class="price-update-through mt-3">
                                            <div class="price-update-through mt-3">
                                                <span class="flash-prices color-two"> {{amount_with_currency_symbol($sale_price)}} </span>
                                                <span
                                                    class="flash-old-prices"> {{$regular_price != null ? amount_with_currency_symbol($regular_price) : ''}} </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @foreach($data['categories'] as $category)
                    <div class="tab-content-item" id="{{$category->slug}}"></div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@section('scripts')
    <script>
        $(function (){
            $(document).on('click', '.store-tabs li', function (e){
                e.preventDefault();

                let el = $(this);
                let tab = el.data('tab');
                let limit = el.data('limit');

                if(tab !== 'all')
                {
                    $.ajax({
                        type: 'GET',
                        url: "{{route('tenant.category.wise.product.one')}}",
                        data: {
                            category : tab,
                            limit : limit,
                        },
                        beforeSend: function (){
                            $('.loader').fadeIn(200);
                        },
                        success: function (data){
                            let id = data.category;
                            $('#'+id).html(data.markup);

                            $('.loader').fadeOut(200);
                        },
                        error: function (data){
                            console.log('error')
                        }
                    });
                }
            });
        });
    </script>
@endsection
