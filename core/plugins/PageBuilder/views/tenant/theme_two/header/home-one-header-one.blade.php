
<div class="header-area header-bg-04  header-utility" {!! render_background_image_markup_by_attachment_id($data['inner_bg_one']) !!} data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="utility-bg-img wow animate__animated animate__zoomIn" data-parallax='{"x": 100, "y": 60}' {!! render_background_image_markup_by_attachment_id($data['right_bg_image']) !!}></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="header-inner">
                    <!-- header inner -->

                    <h1 class="title wow animate__animated animate__fadeInUp">{{$data['title']}}</h1>
                    <p class="utility-para">{{$data['subtitle']}}</p>
                    <div class="apps-download wow animate__animated animate__backInLeft">
                        <div class="download-img style-01">
                            <a href="{{$data['apple_store_url']}}"> {!! render_image_markup_by_attachment_id($data['apple_store_image']) !!}</a>
                        </div>
                        <div class="download-img">
                            <a href="{{$data['play_store_url']}}"> {!! render_image_markup_by_attachment_id($data['play_store_image']) !!}</a>
                        </div>
                    </div>
                </div>
                <!-- //.header inner -->
            </div>
        </div>
    </div>
</div>

