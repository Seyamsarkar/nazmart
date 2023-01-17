@php
    $user_lang = get_user_lang();
@endphp
<div class="apps-month-area apps-bg" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title desktop-center padding-bottom-75 wow animate__animated animate__fadeInUp animated">
                    <h4 class="title">{{$data['title']}}</h4>
                    <p class="apps-pera">{{$data['subtitle']}}</p>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="apps-month-img wow animate__ animate__backInUp bg-image animated" data-parallax='{"x": 50, "y": 20}' {!! render_background_image_markup_by_attachment_id($data['left_image']) !!}></div>
            </div>
            <div class="col-lg-6">
                @foreach($data['repeater_data']['repeater_title_'. $user_lang] ?? [] as $key => $title)
                <div class="apps-month-single-item padding-bottom-40">
                    <div class="icon wow animate__animated animate__heartBeat animated">
                        <i class="{{$data['repeater_data']['repeater_icon_'. $user_lang][$key] ?? ''}}"></i>
                    </div>
                    <div class="content wow animate__animated animate__fadeInUp animated">
                        <a href="{{$data['repeater_data']['repeater_title_url_'. $user_lang][$key] ?? ''}}"> <h3 class="title">{{$title ?? ''}}</h3></a>
                        <p class="apps-pera">{{$data['repeater_data']['repeater_subtitle_'. $user_lang][$key] ?? ''}}</p>
                    </div>
                </div>
                 @endforeach
            </div>
        </div>
    </div>
</div>
