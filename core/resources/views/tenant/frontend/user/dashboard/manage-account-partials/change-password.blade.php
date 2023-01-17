@php
    $user_delivery_address = $user_details?->delivery_address;
@endphp

<div class="seller-profile-details-wrapper">
    <h3 class="title-seller"> {{__('Edit Billing Address')}} </h3>
    <form action="#" class="change_password_form">
        @csrf
        <div class="row margin-top-10">
            <div class="col-lg-12 col-md-12 margin-top-30">
                <div class="dashboard-address-details">
                    <h5 class="edit-title"> {{__('Billing Information')}} </h5>
                    <div class="single-dashboard-input">
                        <div class="single-info-input margin-top-30">
                            <label class="info-title"> {{__('Old Password')}}<span class="text-danger">*</span>  </label>
                            <input class="form--control" type="password" name="old_password" id="old_password" placeholder="Old Password">
                        </div>
                        <div class="single-info-input margin-top-30">
                            <label class="info-title"> {{__('New Password')}}<span class="text-danger">*</span>  </label>
                            <input class="form--control" type="password" name="password" id="password" placeholder="New Password">
                        </div>
                        <div class="single-info-input margin-top-30">
                            <label class="info-title"> {{__('Confirm Password')}}<span class="text-danger">*</span>  </label>
                            <input class="form--control" type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="btn-wrapper margin-top-10">
                    <button type="submit" class="btn-submit btn-bg-1 save-password-btn"> {{__('Save Changes')}} </button>
                </div>
            </div>
        </div>
    </form>
</div>
