@php
    $user_lang = get_user_lang();
@endphp

<div class="batter-work-area bg-image" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="section-title desktop-center padding-bottom-50 wow animate__animated animate__fadeInUp animated">
                    <h4 class="title">{{$data['title']}}</h4>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
            <div class="col-lg-3 col-md-6">
                <div class="work-single-item-04">
                    <div class="icon-wrap">
                        <div class="icon">
                            <i class="{{$data['repeater_data']['repeater_icon_'.$user_lang][$key] ?? ''}}"></i>
                        </div>
                    </div>
                    <div class="content">
                        <a href="{{$data['repeater_data']['repeater_title_url_'.$user_lang][$key] ?? ''}}">
                            <h4 class="title">{{$title}}</h4>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="btn-wrapper desktop-center padding-top-50 wow animate__animated animate__fadeInUp animated">
            <a href="{{$data['button_url']}}" class="boxed-btn btn-work">{{$data['button_text']}}</a>
        </div>
    </div>
</div>
