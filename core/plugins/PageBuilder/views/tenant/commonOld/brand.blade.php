<div class="client-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title desktop-center padding-bottom-40 wow animate__animated animate__fadeInUp animated">
                    <h3 class="title">{{$data['title'] ?? ''}}</h3>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="client-area">
                    <div class="client-active-area">
                        @foreach($data['brands'] as $data)
                            <div class="single-brand">
                                <a href="{{$data->url ?? ''}}">{!! render_image_markup_by_attachment_id($data->image ?? '') !!}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
