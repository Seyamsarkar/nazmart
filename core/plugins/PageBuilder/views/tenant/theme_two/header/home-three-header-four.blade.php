
<div class="header-area header-btech header-bg" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!} data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="header-inner desktop-center">
                    <div class="inner-top padding-bottom-50 wow animate__animated animate__fadeInUp">
                        <a href="{{$data['button_url']}}" class="text-btn">{{$data['button_text']}}</a>
                        <h4 class="in-title">{{$data['button_right_title']}}</h4>
                    </div>
                    <h1 class="title wow animate__animated animate__fadeInUp animated">{{$data['inner_title']}}</h1>
                    <p class="advantage-para wow animate__animated animate__fadeInUp animated">{{$data['inner_description']}}</p>
                </div>
            </div>
        </div>
    </div>
</div>

