<section class="brand-carousel padding-bottom-120" id="{{$data['section_id']}}">
    <div class="container-max">
        <div class="row">
            <div class="col-lg-12">
                <div class="global-carousel-init owl-carousel" data-tabletitem="3" data-mobileitem="2" data-desktopitem="5" data-loop="false" data-dots="true" data-margin="60">
                       @foreach($data['brands'] as $data)
                            <div class="single-brand-item">
                                <a href="{{$data->url ?? ''}}">
                                    {!! render_image_markup_by_attachment_id($data->image) !!}
                                </a>
                            </div>
                        @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
