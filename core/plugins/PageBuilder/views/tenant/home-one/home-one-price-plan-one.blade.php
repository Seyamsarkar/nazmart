
<div class="price-plan-area price-bg" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!} data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section-title brand desktop-center padding-bottom-50">
                    <h3 class="title">{{$data['title']}}</h3>
                </div>
            </div>
        </div>
 @php
     $selected_lang = get_user_lang();
 @endphp
        <div class="row">
            @foreach($data['all_price_plan'] as $show)
            <div class="col-lg-4 col-md-6">
                <div class="single-price-plan-02 wow animate__animated animate__fadeInUp">
                    <div class="price-header">
                        <h4 class="title">{{$show->getTranslation('title',$selected_lang)}}</h4>
                    </div>
                    <div class="price-wrap">
                        <span class="price">{{__('$ ' . $show->price)}}</span><span class="month">{{__('/mo')}}</span>
                    </div>
                    <div class="price-body">
                        <ul>
                            @php
                                $single_feat = $show->getTranslation('features',$selected_lang)
                            @endphp

                            @foreach(explode("\n",$single_feat) as $key=> $item)
                                <li> {{$item}}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="price-footer">
                        <div class="btn-wrapper">
                            <a href="{{route('tenant.frontend.plan.order',$show->id)}}" class="boxed-btn btn-startup">{{$data['button_text']}}</a>
                        </div>
                    </div>
                </div>
            </div>
             @endforeach
        </div>
    </div>
</div>
