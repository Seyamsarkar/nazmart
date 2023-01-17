<div class="seller-profile-details-wrapper">
    <div class="seller-profile-edit-flex">
        <h3 class="title-seller"> {{__('Profile Information')}} </h3>
    </div>
    <div class="dashboard-address-details margin-top-50">
        <ul class="details-list">
            <li class="lists">
                <span class="list-span"> {{__('Username:')}} </span>
                <span class="list-strong"> {{$user_details->username}} </span>
            </li>
            <li class="lists">
                <span class="list-span"> {{__('Name:')}} </span>
                <span class="list-strong"> {{$user_details->name}} </span>
            </li>
            <li class="lists">
                <span class="list-span"> {{__('Email:')}} </span>
                <span class="list-strong"> {{$user_details->email}}</span>
            </li>
            <li class="lists">
                <span class="list-span"> {{__('Phone:')}} </span>
                <span class="list-strong"> {{$user_details->mobile}} </span>
            </li>
            <li class="lists">
                <span class="list-span"> {{__('Company:')}} </span>
                <span class="list-strong"> {{$user_details->company}} </span>
            </li>
            <li class="lists">
                <span class="list-span"> {{__('City:')}} </span>
                <span class="list-strong"> {{$user_details->city}} </span>
            </li>
            <li class="lists">
                <span class="list-span"> {{__('State:')}} </span>
                <span class="list-strong"> {{$user_details->state}}</span>
            </li>
            <li class="lists">
                <span class="list-span"> {{__('Country:')}} </span>
                <span class="list-strong"> {{$user_details->country}}</span>
            </li>
        </ul>
        <ul class="details-list column-count-one">
            <li class="lists">
                <span class="list-span"> {{__('Address:')}} </span>
                <span class="list-strong">  {{$user_details->address}} </span>
            </li>
        </ul>
    </div>

</div>

@php
    $delivery_address = $user_details?->delivery_address;
@endphp
<div class="seller-profile-details-wrapper padding-top-80">
    <div class="seller-profile-edit-flex">
        <h3 class="title-seller"> {{__('Billing Information')}} </h3>
    </div>
    <div class="dashboard-address-details margin-top-50">
        <ul class="details-list">
            <li class="lists">
                <span class="list-span"> {{__('Name:')}} </span>
                <span class="list-strong"> {{$delivery_address?->full_name}} </span>
            </li>
            <li class="lists">
                <span class="list-span"> {{__('Phone:')}} </span>
                <span class="list-strong"> {{$delivery_address?->phone}} </span>
            </li>
            <li class="lists">
                <span class="list-span"> {{__('Email:')}} </span>
                <span class="list-strong"> {{$delivery_address?->email}} </span>
            </li>
            <li class="lists">
                <span class="list-span"> {{__('City:')}} </span>
                <span class="list-strong"> {{$delivery_address?->city}} </span>
            </li>
            <li class="lists">
                <span class="list-span"> {{__('State:')}} </span>
                <span class="list-strong"> {{$delivery_address?->state?->name}} </span>
            </li>
            <li class="lists">
                <span class="list-span"> {{__('Country:')}} </span>
                <span class="list-strong"> {{$delivery_address?->country?->name}} </span>
            </li>
        </ul>
        <ul class="details-list column-count-one">
            <li class="lists">
                <span class="list-span"> {{__('Address:')}} </span>
                <span class="list-strong"> {{$delivery_address?->address}} </span>
            </li>
        </ul>
    </div>
</div>
