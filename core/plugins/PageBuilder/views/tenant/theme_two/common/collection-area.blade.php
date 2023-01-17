@php
    $class = '';
    if ($data['align_image'] == 'right')
    {
        $class = 'flex-row-reverse';
    }
@endphp

<section class="arrival-area arrival-left-right-margin section-bg-3" data-padding-top="{{$data['padding_top']}}"
         data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row {{$class}} g-5 align-items-center">
            <div class="col-lg-6">
                <div class="single-arrival-wrapper-thumb">
                    <div class="single-arrival-thumb">
                        {!! render_image_markup_by_attachment_id($data['image'] ?? '') !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="single-arrival">
                    <div class="single-arrival-contents single-arrival-contents-width">
                        <h2 class="single-arrival-contents-title fw-400"> {{$data['title'] ?? ''}} </h2>
                        <p class="single-arrival-contents-para mt-4"> {{$data['subtitle'] ?? ''}} </p>
                        <div class="btn-wrapper">
                            <a href="{{empty($data['button_url']) ? route('tenant.campaign.index', $data['campaign']) : $data['button_url']}}"
                               class="cmn-btn cmn-btn-bg-3 radius-0 mt-4 mt-lg-5"> {{$data['button_text'] ?? 'Shop Now'}} </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
