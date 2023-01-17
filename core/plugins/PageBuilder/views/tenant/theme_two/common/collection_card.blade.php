
<section class="collection-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container container-one">
        <div class="collection-wrapper">
            <div class="row gy-4">
                @foreach($data['repeater']['repeater_title_'] ?? [] as $key => $info)
                    @php
                        $image_id = $data['repeater']['repeater_image_'][$key];
                        $image = get_attachment_image_by_id($image_id);
                        $image_url = !empty($image) ? $image['img_url'] : '#';

                        $background_color = $data['repeater']['repeater_background_color_'][$key] ?? '#FFFFFF';
                        $background_color = 'background-color:'.$background_color;
                    @endphp

                    <div class="col-lg-6">
                    <div class="single-collection-two collection-padding section-bg-4" style="{{$background_color}}">
                        <div class="single-collection-two-flex d-flex align-items-center">
                            <div class="single-collection-two-contents">
                                <h3 class="single-collection-two-contents-title fw-500"> {{$info}} </h3>
                                <a href="{{$data['repeater']['repeater_url_'] ?? '#'}}" class="shop-now-btn shop-now-border mt-4"> {{$data['repeater']['repeater_button_text_'][$key]}} </a>
                            </div>

                            <div class="single-collection-two-thumb">
                                <img src="{{$image_url}}" alt="img">
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
