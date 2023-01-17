<section class="collection-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container container-one">
        <div class="collection-wrapper">
            <div class="row">
                <div class="col-lg-6">
                    <div class="single-collection section-bg-2">
                        <div class="single-collection-thumb">
                            {!! render_image_markup_by_attachment_id($data['left_image'] ?? '') !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="single-collection section-bg-2">
                        <div class="single-collection-thumb">
                            {!! render_image_markup_by_attachment_id($data['right_image'] ?? '') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="collection-wrapper-contents center-text bg-white">
                <span class="collection-wrapper-contents-subtitle color-two"> {{$data['subtitle'] ?? ''}} </span>
                <h2 class="collection-wrapper-contents-title mt-2"> {{$data['title'] ?? ''}} </h2>
                <div class="btn-wrapper mt-4">
                    <a href="{{empty($data['button_url']) ? route('tenant.campaign.index', $data['campaign']) : $data['button_url']}}" class="cmn-btn cmn-btn-bg-2 radius-0"> {{$data['button_text'] ?? ''}} </a>
                </div>
            </div>
        </div>
    </div>
</section>
