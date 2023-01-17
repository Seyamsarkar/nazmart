
<div class="header-bottom-area audio-bottom-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 offset-lg-2 col-md-6">
                <div class="single-icon-box-07 margin-bottom-30 wow animate__animated animate__fadeInUp animated">
                    <div class="icon">
                        <i class="{{$data['icon_one']}}"></i>
                    </div>
                    <a href="{{$data['title_url']}}"><h4 class="title">{{$data['title']}}</h4></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="single-icon-box-07 margin-bottom-30 wow animate__animated animate__fadeInUp animated">
                    <div class="icon">
                       {!! render_image_markup_by_attachment_id($data['image']) !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="single-icon-box-07 margin-bottom-30 wow animate__animated animate__fadeInUp animated">
                    <div class="icon">
                        <i class="{{$data['icon_two']}}"></i>
                    </div>
                    <a href="{{$data['title_two_url']}}"><h4 class="title">{{$data['title_two']}}</h4></a>
                </div>
            </div>
        </div>
    </div>
</div>
