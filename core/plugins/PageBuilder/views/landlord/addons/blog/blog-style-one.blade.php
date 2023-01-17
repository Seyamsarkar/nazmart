<section class="blog-area section-bg-1" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}"  id="{{$data['section_id']}}">
    <div class="container">
        <div class="load-more-items-wrapper">
            <div class="row load-more-items" id="load-more-items">
                @forelse($data['blogs'] ?? [] as $key=> $blog)
                    <div class="col-lg-4 col-md-6 mt-4">
                        <div class="single-blog">
                            <div class="single-blog-thumb">
                                <a href="{{route('landlord.frontend.blog.single',$blog['slug'])}}">
                                    {!! render_image_markup_by_attachment_id($blog->image ?? '', '', 'full', false) !!}
                                </a>
                            </div>
                            <div class="single-blog-contents mt-4">
                                <h2 class="single-blog-contents-title"> <a href="{{route('landlord.frontend.blog.single',$blog['slug'])}}"> {{Str::words($blog->title)}} </a> </h2>
                                <div class="single-blog-contents-bottom mt-4">
                                    <a href="{{route('landlord.frontend.blog.single',$blog['slug'])}}" class="reading-btn"> {{__('Keep Reading')}} <i class="las la-arrow-right"></i> </a>
                                    <span class="min-reading"> {{$blog->created_at->format('d M Y')}} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-lg-12 col-md-12">
                        <h4 class="text-center text-warning">{{__('No Data Available')}}</h4>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="btn-wrapper click-attr center-text mt-4 mt-lg-5">
            <a href="javascript:void(0)" class="cmn-btn cmn-btn-bg-1" id="load_more"
               data-category="{{$data['category_id']}}"
               data-order="{{$data['order']}}"
               data-order_by="{{$data['order_by']}}"
            > {{__('Load More')}} </a>
        </div>
    </div>
</section>
