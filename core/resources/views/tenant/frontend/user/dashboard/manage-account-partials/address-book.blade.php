@php
    $user_delivery_address = $user_details?->delivery_address;
@endphp

<div class="seller-profile-details-wrapper">
    <h3 class="title-seller"> {{__('Edit Billing Address')}} </h3>
    <form action="#" class="address_form">
        <div class="row margin-top-10">
            <div class="col-lg-12 col-md-12 margin-top-30">
                <div class="dashboard-address-details">
                    <h5 class="edit-title"> {{__('Billing Information')}} </h5>
                    <div class="single-dashboard-input">
                        <div class="single-info-input margin-top-30">
                            <label class="info-title"> {{__('Your Name')}}<span class="text-danger">*</span>  </label>
                            <input class="form--control" type="text" name="full_name" placeholder="Type Your Name" value="{{$user_delivery_address?->full_name}}">
                        </div>
                        <div class="single-info-input margin-top-30">
                            <label class="info-title"> {{__('Your Email')}}<span class="text-danger">*</span> </label>
                            <input class="form--control" type="email" name="email" placeholder="Type Your Email" value="{{$user_delivery_address?->email}}">
                        </div>
                    </div>
                    <div class="single-dashboard-input">
                        <div class="single-info-input margin-top-30">
                            <label class="info-title"> {{__('Phone Number')}}<span class="text-danger">*</span> </label>
                            <input class="form--control" type="text" placeholder="Type Your Number" name="phone" value="{{$user_delivery_address?->phone}}">
                        </div>
                    </div>
                    <div class="single-dashboard-input">
                        <div class="single-info-input margin-top-30">
                            <label class="info-title"> {{__('Your Country')}}<span class="text-danger">*</span> </label>
                            <select class="form--control" name="country" id="country">
                                <option value="" selected disabled>{{__('Select a country')}}</option>
                                @foreach($countries ?? [] as $country)
                                    <option value="{{$country->id}}" {{$user_delivery_address?->country_id == $country->id ? 'selected' : ''}}>{{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="single-info-input margin-top-30">
                            <label class="info-title"> {{__('Your State')}}<span class="text-danger">*</span> </label>
                            <select class="form--control" name="state" id="state">
                                <option value="">{{__('Select a state')}}</option>

                                @if($user_delivery_address?->state_id != null)
                                    <option value="{{$user_delivery_address?->state_id}}" selected>{{$user_delivery_address?->state?->name}}</option>
                                @endif
                            </select>
                        </div>
                        <div class="single-info-input margin-top-30">
                            <label class="info-title"> {{__('Your City')}}<span class="text-danger">*</span> </label>
                            <input class="form--control" type="text" placeholder="Type Your City" name="city" value="{{$user_delivery_address?->city}}">
                        </div>
                    </div>
                    <div class="single-dashboard-input">
                        <div class="single-info-input margin-top-30">
                            <label class="info-title"> {{__('Your Address')}}<span class="text-danger">*</span> </label>
                            <textarea class="form--control" id="address" cols="30" rows="10" name="address"> {{$user_delivery_address?->address}} </textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="btn-wrapper margin-top-10">
                    <button type="submit" class="btn-submit btn-bg-1 address-submit-btn"> {{__('Save Changes')}} </button>
                </div>
            </div>
        </div>
    </form>
</div>
