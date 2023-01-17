@php
    if (str_contains($data['title'], '{h}') && str_contains($data['title'], '{/h}'))
    {
        $text = explode('{h}',$data['title']);

        $highlighted_word = explode('{/h}', $text[1])[0];

        $highlighted_text = '<span class="title-shapes">'. $highlighted_word .'</span>';
        $final_title = '<h2 class="about-contents-title">'.str_replace('{h}'.$highlighted_word.'{/h}', $highlighted_text, $data['title']).'</h2>';
    } else {
        $final_title = '<h2 class="about-contents-title">'. $data['title'] .'</h2>';
    }
@endphp

<section class="about-area section-bg-1 padding-top-95 padding-bottom-60">
    <div class="container">
        <div class="row justify-content-center flex-column-reverse flex-lg-column">
            <div class="col-lg-12 mt-4">
                <div class="about-contents">
                    <div class="about-contents-flex">
                        {!! $final_title !!}
                        <p class="about-contents-para"> {{$data['description']}} </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-10 mt-4">
                <div class="about-thumb center-text">
                    {!! render_image_markup_by_attachment_id($data['image']) !!}
                </div>
            </div>
        </div>

    </div>
</section>
