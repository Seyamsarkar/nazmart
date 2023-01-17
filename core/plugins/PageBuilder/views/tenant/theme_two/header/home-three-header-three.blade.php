
<div class="header-area header-customer" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="business-bg-img wow animate__animated animate__zoomIn" data-parallax='{"x": 220, "y": 100}' {!! render_background_image_markup_by_attachment_id($data['right_bg_image']) !!}></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="header-inner">
                    <!-- header inner -->
                    <h1 class="title wow animate__animated animate__fadeInUp animated">{{$data['title']}}</h1>
                    <div class="btn-wrapper padding-top-30">
                        <a href="{{$data['button_url']}}" class="boxed-btn btn-business style-02">{{$data['button_text']}}</a>
                    </div>
                </div>
                <!-- //.header inner -->
            </div>
        </div>
    </div>
</div>
