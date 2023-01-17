<html>
<head>
    {!! load_google_fonts() !!}
    {!! render_favicon_by_id(get_static_option('site_favicon')) !!}
    <title> {{get_static_option('site_'.get_default_language().'_title')}}
        - {{get_static_option('site_'.get_default_language().'_tag_line')}}</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
<div class="stripe-payment-wrapper">
    <div class="srtipe-payment-inner-wrapper">
        <input type="hidden" name="order_id" id="order_id_input" value="{{$stripe_data['order_id']}}"/>
        <div class="btn-wrapper">
            <button id="payment_submit_btn"></button>
        </div>
    </div>
</div>

<script>
    (function(){
    "use strict";
    // Create a Stripe client
    var stripe = Stripe("{{get_static_option('stripe_publishable_key')}}");
    var orderID = document.getElementById('order_id_input').value;
    var submitBtn = document.getElementById('payment_submit_btn');

    document.addEventListener('DOMContentLoaded',function (){
        submitBtn.click();
    },false);

    submitBtn.addEventListener('click', function () {
        // Create a new Checkout Session using the server-side endpoint you
        submitBtn.innerText = "{{__('Redirecting..')}}"
        submitBtn.disabled = true;
        // created in step 3.
        fetch("{{$stripe_data['route']}}", {
            headers: {
                "X-CSRF-TOKEN" : "{{csrf_token()}}",
                'Content-Type': 'application/json'
            },
            method: 'POST',
            body: JSON.stringify({'order_id': orderID })
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (session) {
                return stripe.redirectToCheckout({sessionId: session.id});
            })
            .then(function (result) {
                // If `redirectToCheckout` fails due to a browser or network
                // error, you should display the localized error message to your
                // customer using `error.message`.
                if (result.error) {
                    alert(result.error.message);
                }
            })
            .catch(function (error) {

            });
    });
    })();
</script>
</body>
</html>
