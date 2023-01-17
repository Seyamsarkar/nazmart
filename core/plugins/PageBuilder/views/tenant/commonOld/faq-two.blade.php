@php
    $current_lang = \App\Helpers\LanguageHelper::user_lang_slug();
@endphp

<div class="accoridions" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center padding-bottom-50">
            <div class="col-lg-8 col-md-12">
                <div class="section-title desktop-center">
                    <h2 class="title">{{$data['title']}}</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion-wrapper">
                    <!-- accordion wrapper -->
                    @php $parent_rand = \Illuminate\Support\Str::random(25); @endphp
                    <div id="accordion-{{$parent_rand}}">
                        @foreach($data['repeater_data']['repeater_title_'.$current_lang] ?? [] as $key=> $title)
                            @php $accordion_rand = \Illuminate\Support\Str::random(25); @endphp
                        <div class="card">
                            <div class="card-header" id="headingOwo">
                                <h5 class="mb-0">
                                    <a class="collapsed white" role="button" data-toggle="collapse" data-target="#collapse_{{$accordion_rand}}" aria-expanded="false" aria-controls="collapse_{{$accordion_rand}}">
                                       {{$title ?? ''}}
                                    </a>
                                </h5>
                            </div>
                            <div id="collapse_{{$accordion_rand}}" class="collapse @if($data['show_hide'] && $loop->first) show @endif" data-parent="#accordion-{{$parent_rand}}">
                                <div class="card-body style-01">
                                    {{$data['repeater_data']['repeater_description_'.$current_lang][$key] ?? '' }}
                                </div>
                            </div>
                        </div>
                         @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
