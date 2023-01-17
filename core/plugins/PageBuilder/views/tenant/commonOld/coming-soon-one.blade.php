@php
    $user_lang = get_user_lang();
@endphp

@section('style')
    <style>
        .flash-countdown {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
        }

        .flash-countdown .single-box {
            height: 62px;
            width: 65px;
            background-color: #5f2eca;
            margin: 20px 7px 0 7px;
            border-radius: 3px;
            color: #fff;
            text-align: center;
        }

        .flash-countdown .single-box .item {
            display: block;
            font-family: var(--heading-font);
            font-weight: 500;
            font-size: 25px;
        }

        .flash-countdown .single-box .item:first-child {
            margin-top: 3px;
        }

        .flash-countdown .single-box .label {
            font-size: 12px;
            font-weight: 400;
            margin-top: -4px;
        }

        .flash-countdown.index-02 {
            margin-bottom: 30px;
        }

        .flash-countdown.index-02 .single-box {
            background-color: #464646;
        }
    </style>
@endsection

<div class="breadcrumb-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="shape"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="breadcrumb-inner desktop-center">

                    <h2 class="page-title style-01">{{$data['title']}}</h2>
                    <div class="flash-countdown-1 flash-countdown" data-date="{{ $data['coming_date'] ?? '' }}">
                        <div class="single-box">
                            <span class="counter-days item"></span>
                            <span class="label item">{{__('Days')}}</span>
                        </div>
                        <div class="single-box">
                            <span class="counter-hours item"></span>
                            <span class="label item">{{__('Hour')}}</span>
                        </div>
                        <div class="single-box">
                            <span class="counter-minutes item"></span>
                            <span class="label item">{{__('Min')}}</span>
                        </div>
                        <div class="single-box">
                            <span class="counter-seconds item"></span>
                            <span class="label item">{{__('Sec')}}</span>
                        </div>
                    </div>
                    <form class="notify-form" action="{{route('tenant.frontend.subscribe.newsletter')}}">
                        @csrf
                        <div class="form-group">
                            <input type="email" class="form-control email" placeholder="Your email address">
                        </div>
                        <button type="submit" class="submit-btn newsletter-submit-btn">{{$data['button_text']}}</button>
                    </form>
                    <div class="form-message-show mt-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script src="{{global_asset('assets/common/js/loopcounter.js')}}"></script>
    <x-custom-js.newsletter-store/>
    <script>
       $(document).ready(function(){
           loopcounter('flash-countdown-1');
       });
    </script>
@endsection



