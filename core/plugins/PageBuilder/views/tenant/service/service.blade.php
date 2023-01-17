@php
    $user_lang = get_user_lang();
@endphp

<div id="work" class="hard-work-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row">
            @foreach($data['service'] ?? [] as $item)
            <div class="col-lg-4 col-md-6">
                <div class="hard-single-item margin-bottom-30">
                    <div class="thumb water-effect" {!! render_background_image_markup_by_attachment_id($item->image) !!}>
                    </div>
                    <div class="content">
                        <a href="{{route('tenant.frontend.service.single',$item->slug)}}"><h4 class="title">{{$item->getTranslation('title',$user_lang)}}</h4></a>
                        <p class="catagory">{!!  Str::words($item->getTranslation('description',$user_lang),40) !!}</p>
                    </div>
                </div>
            </div>
             @endforeach
        </div>
    </div>
</div>
