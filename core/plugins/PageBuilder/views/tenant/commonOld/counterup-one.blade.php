@php
    $user_lang = get_user_lang();
@endphp

<div class="counterup-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title desktop-center padding-bottom-50">
                    <h2 class="title">{{$data['title']}}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <ul class="counter-item-list">
                    @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
                    <li class="single-counterup-01">
                        <div class="content">
                            <div class="count-wrap"><span class="count-num">{{$data['repeater_data']['repeater_number_'.$user_lang][$key] ?? ''}}</span></div>
                            <h4 class="title">{{$title ?? ''}}</h4>
                        </div>
                    </li>
                      @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
