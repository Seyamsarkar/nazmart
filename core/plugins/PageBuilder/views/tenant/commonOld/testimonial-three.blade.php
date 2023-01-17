@php
    $user_lang = get_user_lang();
@endphp

<section class="testimonial-area blue"data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="btech-testimonial">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section-title desktop-center padding-bottom-55 ">
                        <h4 class="title wow animate__animated animate__fadeInUp animated">{{$data['title']}}</h4>
                        <p class="wow animate__animated animate__fadeInUp animated">{{$data['subtitle']}}</p>
                    </div>
                </div>
            </div>
        </div>

      <div class="row">
         <div class="col-xl-10">
           <div class="testimonial-carousel-four">
              @foreach($data['testimonial'] ?? [] as $key => $item)
                <div class="single-testimonial-item-10 ">
                    <div class="thumb">
                       {!! render_image_markup_by_attachment_id($item->image) !!}
                    </div>
                    <div class="content-wrap">
                        <div class="icon">

                            <i class="{{$data['icon']}}"></i>
                        </div>
                        <div class="content">
                            <p class="description">{{\Illuminate\Support\Str::words($item->getTranslation('description',$user_lang),20)}}</p>
                            <div class="author-details">
                                <div class="author-meta">
                                    <h4 class="title">{{$item->getTranslation('name',$user_lang)}}</h4>
                                    <div class="designation">{{$item->getTranslation('designation',$user_lang)}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               @endforeach
            </div>
            </div>
        </div>
    </div>
</section>
