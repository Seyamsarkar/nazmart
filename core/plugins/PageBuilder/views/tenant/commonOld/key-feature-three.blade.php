@php
    $user_lang = get_user_lang();
    $colors = ['style-01','style-04','style-02','style-03','style-05'];
@endphp

<div class="medi-care-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row">
            @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="how-it-single-item-03 wow animate__animated animate__fadeInUp">
                    <div class="icon {{$colors[$key % count($colors)]}}">
                        {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.$user_lang][$key]) !!}
                    </div>
                    <div class="content">
                        <a href="{{$data['repeater_data']['repeater_title_url_'.$user_lang][$key] ?? ''}}"><h4 class="title">{{$title ?? ''}}</h4></a>
                        <p class="medi-para">{{$data['repeater_data']['repeater_description_'.$user_lang][$key] ?? ''}}</p>
                    </div>
                </div>
            </div>
            @endforeach
          </div>
    </div>
</div>
