<div class="template-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}"id="{{$data['section_id']}}">
    <div class="container-max">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title margin-bottom-60">
                    <h2 class="title">{{$data['title']}}</h2>
                    <p>{{$data['subtitle']}}</p>
                </div>
            </div>
        </div>
        <div class="row">
          @foreach($data['repeater_data']['repeater_image_'] ?? [] as $key => $image)
            <div class="col-lg-4 col-sm-6">
                <div class="single-demo-item">
                    <a href="{{$data['repeater_data']['repeater_image_url_'][$key] ?? ''}}">
                       {!! render_image_markup_by_attachment_id($image ?? '') !!}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
