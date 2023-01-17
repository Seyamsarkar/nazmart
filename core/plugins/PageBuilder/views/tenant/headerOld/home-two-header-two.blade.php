
<div class="header-area audio-header header-social" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="sass-bg-img wow animate__animated animate__zoomIn" data-parallax='{"x": 220, "y": 150}' {!! render_background_image_markup_by_attachment_id($data['right_bg_image']) !!}></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="header-inner">
                    <h1 class="title style-01 wow animate__animated animate__fadeInUp">{{$data['title']}}</h1>
                    <div class="btn-wrapper padding-top-30">
                        <a href="{{$data['button_url']}}" class="boxed-btn audio-btn style-02">{{$data['button_text']}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
