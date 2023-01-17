@extends('landlord.frontend.user.dashboard.user-master')
@section('title')
    {{__('Email Verify')}}
@endsection
@section('page-title')
    {{__('Email Verify')}}
@endsection
@section('content')

    <section class="login-page-area padding-top-120 padding-bottom-120">
        <div class="container-max">
            <div class="row justify-content-center">
               <div class="col-lg-6">
                   <div class="contact-form-wrapper wrapper-verify">
                       <h2 class="title">{{__("Verify your email")}}</h2>
                       <div class="custom-form mt-4">
                           <x-flash-msg/>
                           <x-error-msg/>
                           <form action="{{route('landlord.user.email.verify')}}" method="post">
                               @csrf
                               <div class="form-group single-input">
                                   <label class="label-title">{{__('Verify Code')}}</label>
                                   <input type="text" name="verify_code" class="form--control" placeholder="{{__('type verify code')}}">
                               </div>
                               <div class="btn-wrapper mt-4">
                                   <button type="submit" id="login_button" class="cmn-btn cmn-btn-bg-1 cmn-btn-small">{{__('Confirm')}}</button>
                               </div>
                               <div class="extra-wrap margin-top-20">
                                   <span>{{__('Do not get code?')}} <a href="{{route('landlord.user.email.verify.resend')}}" class="color-one">{{__('Resend code')}}</a></span>
                               </div>
                           </form>
                       </div>
                   </div>
               </div>
            </div>
        </div>
    </section>
@endsection

