@php
    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();
@endphp

<section class="counter-area section-bg-1" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}" id="{{$data['section_id']}}">
    <div class="container">
        <div class="counter-wrapper counter-wrapper-border bg-white">
            <div class="row">
                @foreach($data['repeater_data']['repeater_title_'.$current_lang] as $key => $info)
                    <div class="col-lg-3 col-md-4 col-sm-6 mt-4">
                    <div class="single-counter center-text">
                        <div class="single-counter-count border-counter">
                            <span class="odometer" data-odometer-final="{{$data['repeater_data']['repeater_number_'.$current_lang][$key]}}"></span>
                            <h4 class="single-counter-count-title">+</h4>
                        </div>
                        <p class="single-counter-para color-light mt-3"> {{$data['repeater_data']['repeater_title_'.$current_lang][$key]}}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
