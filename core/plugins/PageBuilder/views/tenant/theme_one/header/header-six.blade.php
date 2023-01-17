
<div class="header-area header-bg-05 header-learning" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!} data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="utility-bg-img  wow animate__animated animate__zoomIn" data-parallax='{"x": 150, "y": 100}' {!! render_background_image_markup_by_attachment_id($data['image']) !!}></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="header-inner">
                    <!-- header inner -->
                    <h1 class="title style-01 wow animate__animated animate__fadeInUp animated">{{$data['title']}}</h1>
                    <p class="learn-para wow animate__animated animate__fadeInUp animated">{{$data['subtitle']}}</p>

                    <div class="btn-wrapper padding-bottom-80 wow animate__animated animate__fadeInUp animated">

                        <a class="boxed-btn learn-btn learn-btn-02" href="{{$data['button_url_one']}}">
                            <i aria-hidden="true" class="fas fa-star"></i>{{$data['button_text_one']}}</a>

                        <a class="boxed-btn learn-btn-02" href="{{$data['button_url_two']}}">
                            <i aria-hidden="true" class="fas fa-star"></i>{{$data['button_text_two']}}</a>
                    </div>

                    <div class="client-area wow animate__animated animate__fadeInUp animated">
                        @foreach($data['repeater_data']['repeater_image_'] ?? [] as $key => $image)
                        <div class="client-item">
                            <div class="single-brand padding-bottom-25">

                                <a href="{{$data['repeater_data']['repeater_url_'][$key] ?? ''}}">
                                    {!! render_image_markup_by_attachment_id($image ?? '') !!}
                                </a>
                            </div>
                        </div>
                         @endforeach
                    </div>
                </div>
                <!-- //.header inner -->
            </div>
        </div>
    </div>
</div>
