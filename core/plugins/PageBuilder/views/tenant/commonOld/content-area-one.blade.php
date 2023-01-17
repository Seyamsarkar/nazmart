@php
    $user_lang = get_user_lang();

@endphp

<!--Create Content-->
<div class="create-content-area-02" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="bg-image" {!! render_background_image_markup_by_attachment_id($data['shape_image']) !!}></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="section-title padding-bottom-25 wow animate__animated animate__fadeInUp animated">
                    <h4 class="title">{{$data['title']}}</h4>
                </div>
                <ul class="icon-list-style wow animate__animated animate__fadeInUp">
                    @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
                    <li>
                        <div class="icon">
                            <i aria-hidden="true" class="{{$data['repeater_data']['repeater_icon_'.$user_lang][$key] ?? ''}}"></i>
                        </div>
                        <div class="content">
                            <h4 class="title">{{$title ?? ''}}</h4>
                            <p>{{$data['repeater_data']['repeater_subtitle_'.$user_lang][$key] ?? ''}}</p>
                        </div>
                    </li>
                    @endforeach
                    <div class="btn-wrapper padding-top-30 wow animate__animated animate__fadeInUp animated">
                        <a href="{{$data['button_url']}}" class="boxed-btn medical-btn">{{$data['button_text']}}</a>
                    </div>
                </ul>

            </div>
            <div class="col-lg-6 offset-lg-2">
                {!! render_image_markup_by_attachment_id($data['image'],'trip-img wow animate__animated animate__fadeInRight animated') !!}
            </div>
        </div>
    </div>
</div>
