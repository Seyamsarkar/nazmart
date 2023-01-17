
<div class="header-area header-medical" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="business-bg-img desktop-center wow animate__animated animate__fadeInRight" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}></div>
    <div class="medi-shape-01" {!! render_background_image_markup_by_attachment_id($data['shape_image_one'] ?? '') !!}></div>
    <div class="medi-shape-02" {!! render_background_image_markup_by_attachment_id($data['shape_image_two'] ?? '') !!}></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="header-inner desktop-center">
                    <!-- header inner -->
                    <h1 class="title wow animate__animated animate__fadeInUp animated">{{$data['title']}}</h1>
                    <p class="wow animate__animated animate__fadeInUp animated">{{$data['subtitle']}}</p>
                    <div class="btn-wrapper padding-top-30 wow animate__animated animate__fadeInUp animated">
                        <a href="{{$data['button_url']}}" class="boxed-btn medical-btn">{{$data['button_text']}}</a>
                    </div>
                </div>
                <!-- //.header inner -->
            </div>
        </div>
    </div>
</div>
