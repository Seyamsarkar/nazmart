@php
    $user_lang = get_user_lang();
@endphp

<div id="features" class="hard-work-area learning-app" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section-title sass desktop-center margin-bottom-55">
                    <h3 class="title wow animate__animated animate__fadeInUp animated">{{$data['title']}}</h3>
                    <p class="wow animate__animated animate__fadeInUp animated">{{$data['subtitle']}}</p>
                </div>
                <div class="apps-download wow animate__animated animate__backInLeft padding-bottom-60">
                    <div class="download-img style-01">
                        <a href="{{$data['image_one_url']}}">
                           {!! render_image_markup_by_attachment_id($data['image_one']) !!}
                        </a>
                    </div>
                    <div class="download-img ">
                        <a href="{{$data['image_two_url']}}">
                            {!! render_image_markup_by_attachment_id($data['image_two']) !!}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="learning-image wow animate__animated animate__fadeInUp animated" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}></div>
            </div>
        </div>
    </div>
</div>
