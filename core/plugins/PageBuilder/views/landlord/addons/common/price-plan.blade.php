@php
    $selected_lang = \App\Facades\GlobalLanguage::user_lang_slug();

    if (str_contains($data['title'], '{h}') && str_contains($data['title'], '{/h}'))
    {
        $text = explode('{h}',$data['title']);

        $highlighted_word = explode('{/h}', $text[1])[0];

        $highlighted_text = '<span class="section-shape">'. $highlighted_word .'</span>';
        $final_title = '<h2 class="title">'.str_replace('{h}'.$highlighted_word.'{/h}', $highlighted_text, $data['title']).'</h2>';
    } else {
        $final_title = '<h2 class="title">'. $data['title'] .'</h2>';
    }
@endphp


<section class="pricing-area section-bg-1" data-padding-top="{{$data['padding_top']}}"
         data-padding-bottom="{{$data['padding_bottom']}}" id="{{$data['section_id']}}">
    <div class="container">
        <div class="section-title">
            {!! $final_title !!}
            <p class="section-para"> {{$data['subtitle']}} </p>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-lg-6 mt-4">
                <div class="pricing-tab-list center-text">
                    <ul class="tabs price-tab radius-10">
                        <li data-tab="tab-month" class="price-tab-list active"> {{__('Monthly')}}</li>
                        <li data-tab="tab-year" class="price-tab-list"> {{__('Yearly')}}</li>
                        <li data-tab="tab-lifetime" class="price-tab-list"> {{__('Lifetime')}}</li>
                    </ul>
                </div>
            </div>
        </div>
        @foreach($data['all_price_plan'] as  $plan_type => $plan_items)
            @php
                $id= '';
                $active = '';
                $period = '';
                if($plan_type == 0){
                    $id = 'month';
                    $active = 'show active';
                    $period = __('/mo');
                }elseif($plan_type == 1){
                    $id = 'year';
                     $period = __('/yr');
                }else{
                     $id = 'lifetime';
                      $period = __('/lt');
                }
            @endphp

            <div class="tab-content-item {{$active}}" id="tab-{{$id}}">
                <div class="row mt-4">
                    @foreach($plan_items as $key => $price_plan_item)
                        @php $featured_condition = $key == 1 ? 'active' : '' @endphp

                        <div class="col-lg-4 col-md-6 mt-4">
                            <div class="single-price radius-10 {{$featured_condition}}">
                                <span class="single-price-sub-title mb-5 radius-5"> {{$price_plan_item->package_badge}} </span>
                                <div class="single-price-top center-text">
                                    <span
                                        class="single-price-top-plan"> {{$price_plan_item->title}} </span>
                                    <h3 class="single-price-top-title mt-4"> {{amount_with_currency_symbol($price_plan_item->price)}}
                                        <sub>{{$period}}</sub></h3>
                                </div>
                                <ul class="single-price-list mt-4">
                                    @if(!empty($price_plan_item->page_permission_feature))
                                        <li class="single-price-list-item">
                                            <span class="check-icon"> <i class="las la-check"></i> </span>
                                            <span>
                                                <strong>
                                                    @if($price_plan_item->page_permission_feature < 0)
                                                        {{__('Page Unlimited')}}
                                                    @else
                                                         {{ sprintf(__('Page %d'),$price_plan_item->page_permission_feature )}}
                                                    @endif
                                                </strong>
                                            </span>
                                        </li>
                                    @endif

                                    @if(!empty($price_plan_item->product_permission_feature))
                                        <li class="single-price-list-item">
                                            <span class="check-icon"> <i class="las la-check"></i> </span>
                                            <span>
                                                <strong>
                                                    @if($price_plan_item->product_permission_feature < 0)
                                                        {{__('Product Unlimited')}}
                                                    @else
                                                        {{ sprintf(__('Product %d'),$price_plan_item->product_permission_feature )}}
                                                    @endif
                                                </strong>
                                            </span>
                                        </li>
                                    @endif

                                    @if(!empty($price_plan_item->blog_permission_feature))
                                        <li class="single-price-list-item">
                                            <span class="check-icon"> <i class="las la-check"></i> </span>
                                            <span>
                                                <strong>
                                                    @if($price_plan_item->blog_permission_feature < 0)
                                                        {{__('Blog Unlimited')}}
                                                    @else
                                                        {{ sprintf(__('Blog %d'),$price_plan_item->blog_permission_feature )}}
                                                    @endif
                                                </strong>
                                            </span>
                                        </li>
                                    @endif

                                        @if(!empty($price_plan_item->storage_permission_feature))
                                            <li class="single-price-list-item">
                                                <span class="check-icon"> <i class="las la-check"></i> </span>
                                                <span>
                                                    <strong>
                                                        @if($price_plan_item->storage_permission_feature < 0)
                                                            {{__('Storage Unlimited')}}
                                                        @else
                                                            {{ sprintf(__('Storage %d MB'),$price_plan_item->storage_permission_feature )}}
                                                        @endif
                                                    </strong>
                                                </span>
                                            </li>
                                        @endif
                                </ul>
                                <div class="btn-wrapper mt-4 mt-lg-5">
                                    @if($price_plan_item->has_trial == true)
                                        <div class="d-flex justify-content-center">
                                            <a href="{{route('landlord.frontend.plan.order',$price_plan_item->id)}}" class="cmn-btn cmn-btn-outline-one color-one w-100 mx-1">
                                                {{__('Buy Now')}} </a>

                                            <a href="{{route('landlord.frontend.plan.view',[$price_plan_item->id, 'trial'])}}" class="cmn-btn cmn-btn-outline-one color-one w-100 mx-1">
                                                {{__('Try Now')}} </a>
                                        </div>
                                    @else
                                        <a href="{{route('landlord.frontend.plan.order',$price_plan_item->id)}}" class="cmn-btn cmn-btn-outline-one color-one w-100">
                                            {{__('Buy Now')}} </a>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>
