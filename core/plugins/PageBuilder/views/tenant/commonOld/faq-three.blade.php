@php
    $current_lang = \App\Helpers\LanguageHelper::user_lang_slug();
@endphp

<div id="faq" class="frequently-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="content margin-bottom-55">
                    <h3 class="title wow animate__animated animate__fadeInUp animated">{{$data['title']}}</h3>
                    <p class="wow animate__animated animate__fadeInUp animated">{{$data['subtitle']}}</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="accordion-wrapper wow animate__animated animate__fadeInUp animated">
                    <!-- accordion wrapper -->
                    @php
                        $parent_random_string = \Illuminate\Support\Str::random(20);
                    @endphp
                    <div id="accordion-{{$parent_random_string}}">

                        @foreach($data['repeater_data']['repeater_title_'.$current_lang] ?? [] as $key => $title)
                            @php
                                $child_random_string = \Illuminate\Support\Str::random(18);
                            @endphp
                        <div class="card">
                            <div class="card-header" id="headingOwo">
                                <h5 class="mb-0">
                                    <a class="collapsed white style-01" role="button" data-toggle="collapse" data-target="#collapseOwo-{{$child_random_string}}" aria-expanded="false" aria-controls="collapseOwo-{{$child_random_string}}">
                                        {{$title ?? ''}}
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseOwo-{{$child_random_string}}" class="collapse {{$loop->first ? 'show' : ''}}" data-parent="#accordion-{{$parent_random_string}}">
                                <div class="card-body style-02">
                                    {{$data['repeater_data']['repeater_description_'.$current_lang][$key] ?? ''}}
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
