@php
    $current_lang = \App\Helpers\LanguageHelper::user_lang_slug();
@endphp

<div id="faq" class="frequently-area " data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom'] ?? ''}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title desktop-center margin-bottom-70">
                    <h3 class="title style-01 wow animate__animated animate__fadeInUp animated">{{$data['title']}}</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                @php
                    $last_child = '';
                 @endphp
                @foreach($data['repeater_data']['repeater_title_'.$current_lang] ?? [] as $key => $title)
                    @if(!$loop->last)
                    <div class="faq-single-item style-01 wow animate__animated animate__fadeInUp animated">
                        <div class="content">
                            <h3 class="title">{{$title ?? ''}}</h3>
                            <p class="customer-para">{{$data['repeater_data']['repeater_description_'.$current_lang][$key] ?? ''}}</p>
                        </div>
                    </div>
                @endif

            @if($loop->last)
                @php
                    $ti = $title ?? '';
                    $sub = $data['repeater_data']['repeater_description_'.$current_lang][$key] ?? '';
                     $last_child.=
                           <<<DATA
                                <div class="faq-single-item style-01 wow animate__animated animate__fadeInUp animated">
                                    <div class="content">
                                        <h3 class="title">$ti</h3>
                                        <p class="customer-para">$sub</p>
                                    </div>
                                </div>
DATA;
                        @endphp

                    @endif
                @endforeach
            </div>


            <div class="col-lg-6">
            {!! $last_child !!}

                <div class="question-form-area wow animate__animated animate__fadeInUp animated">
                    <div class="form-message-show"></div>
                    <div class="header-content">
                        <h4 class="title">{{$data['newsletter_title']}}</h4>
                        <p>{{$data['newsletter_subtitle']}}</p>
                    </div>
                    <form class="question-form" action="{{route('tenant.frontend.subscribe.newsletter')}}">
                        @csrf

                        <div class="form-group">
                            <input type="email" name="email" class="form-control email" placeholder="Enter your email">
                        </div>
                        <button type="submit" class="submit-btn newsletter-submit-btn">{{$data['button_text']}}</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>


@section('scripts')
    <x-custom-js.newsletter-store/>
@endsection
