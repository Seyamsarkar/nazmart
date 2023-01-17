@php
    $user_lang = get_user_lang();
@endphp

<div id="stream" class="build-area-02" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="stream-app-area">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8">
                    <div class="section-title desktop-center white margin-bottom-55">
                        <h3 class="title social-title wow animate__animated animate__fadeInUp">{{$data['title']}}</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
                <div class="col-lg-4 col-md-6">
                    <div class="single-icon-box-08 margin-bottom-30 wow animate__animated animate__fadeInUp animated">
                        <div class="icon">
                            <div class="shape" {!! render_background_image_markup_by_attachment_id($data['repeater_data']['repeater_bg_image_'.$user_lang][$key] ?? '') !!}></div>
                            <i class="{{$data['repeater_data']['repeater_icon_'.$user_lang][$key] ?? ''}}"></i>
                        </div>
                        <div class="content">
                            <a href="{{$data['repeater_data']['repeater_title_url_'.$user_lang][$key]}}"> <h3 class="title">{{$title ?? ''}}</h3></a>
                            <p class="audio-pera">{{$data['repeater_data']['repeater_description_'.$user_lang][$key] ?? ''}}</p>
                        </div>
                    </div>
                </div>
                 @endforeach
            </div>
        </div>
    </div>
</div>
