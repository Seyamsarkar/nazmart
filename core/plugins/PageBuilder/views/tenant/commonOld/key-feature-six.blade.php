@php
    $user_lang = get_user_lang();
    $colors = ['style-01','style-04','style-02','style-03','style-05'];
@endphp


<div class="organizations-header-section" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="organizations-header-wrap bg-image" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section-title brand white desktop-center padding-bottom-45">
                        <p>{{$data['title']}}</p>
                        <h3 class="title">{{$data['subtitle']}}</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
                <div class="col-lg-4 col-md-6">
                    <div class="single-icon-box-04">
                        <div class="icon {{$colors[$key % count($colors)]}}">
                            <i class="{{$data['repeater_data']['repeater_icon_'.$user_lang][$key] ?? ''}}"></i>
                        </div>
                        <div class="content">
                            <h3 class="title">{{$title ?? ''}}</h3>
                            <p>{{$data['repeater_data']['repeater_description_'.$user_lang][$key] ?? ''}}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
