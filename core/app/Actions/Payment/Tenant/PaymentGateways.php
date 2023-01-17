<?php

namespace App\Actions\Payment\Tenant;

use App\Events\TenantRegisterEvent;
use App\Mail\PlaceOrder;
use App\Mail\TenantCredentialMail;
use App\Models\PaymentGateway;
use App\Models\PaymentLogs;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Xgenious\Paymentgateway\Facades\XgPaymentGateway;

class PaymentGateways
{
    public function paypal_ipn()
    {
        $paypal_credential_from_database = PaymentGateway::where('name', 'paypal')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $sandbox_client_id = $decoded->sandbox_client_id ?? '';
        $sandbox_client_secret = $decoded->sandbox_client_secret ?? '';
        $sandbox_app_id = $decoded->sandbox_app_id ?? '';

        $live_client_id = $decoded->live_client_id ?? '';
        $live_client_secret = $decoded->live_client_secret ?? '';
        $live_app_id = $decoded->live_app_id ?? '';

        $checked_client_id = empty($live_client_id) ? $sandbox_client_id : $live_client_id;
        $checked_client_secret = empty($live_client_secret) ? $sandbox_client_secret : $live_client_secret;
        $checked_app_id = empty($live_app_id) ? $sandbox_app_id : $live_app_id;

        $test_mode = $paypal_credential_from_database->test_mode == 1;

        $paypal = XgPaymentGateway::paypal();
        $paypal->setClientId($checked_client_id);
        $paypal->setClientSecret($checked_client_secret);
        $paypal->setEnv($test_mode);
        $paypal->setAppId($checked_app_id);

        $payment_data = XgPaymentGateway::paypal()->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = wrap_random_number($payment_data['order_id']);
            $this->tenant_create_event_with_credential_mail($payment_data['order_id']);

            return route('landlord.frontend.order.payment.success', $order_id);
        }
        return $this->cancel_page();
    }

    public function razorpay_ipn()
    {
        $paypal_credential_from_database = PaymentGateway::where('name', 'razorpay')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);
        $api_key = $decoded->api_key ?? '';
        $api_secret = $decoded->api_secret ?? '';

        $test_mode = $paypal_credential_from_database->test_mode == 1;

        $razorpay = XgPaymentGateway::razorpay();
        $razorpay->setApiKey($api_key);
        $razorpay->setApiSecret($api_secret);
        $razorpay->setEnv($test_mode);

        $payment_data = $razorpay->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = wrap_random_number($payment_data['order_id']);
            $this->tenant_create_event_with_credential_mail($payment_data['order_id']);

            return route('landlord.frontend.order.payment.success', $order_id);
        }
        return $this->cancel_page();
    }

    public function paytm_ipn()
    {
        $paypal_credential_from_database = PaymentGateway::where('name', 'paytm')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $merchant_id = $decoded->merchant_mid ?? '';
        $merchant_key = $decoded->merchant_key ?? '';
        $merchant_website = $decoded->merchant_website ?? '';
        $channel = $decoded->channel ?? '';
        $industry_type = $decoded->industry_type ?? '';

        $test_mode = $paypal_credential_from_database->test_mode == 1;

        $paytm = XgPaymentGateway::paytm();
        $paytm->setMerchantId($merchant_id);
        $paytm->setMerchantKey($merchant_key);
        $paytm->setMerchantWebsite($merchant_website);
        $paytm->setChannel($channel);
        $paytm->setIndustryType($industry_type);
        $paytm->setEnv($test_mode);

        $payment_data = $paytm->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = wrap_random_number($payment_data['order_id']);
            $this->tenant_create_event_with_credential_mail($payment_data['order_id']);

            return route('landlord.frontend.order.payment.success', $order_id);
        }
        return $this->cancel_page();
    }

    public function mollie_ipn()
    {
        $paypal_credential_from_database = PaymentGateway::where('name', 'mollie')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);
        $public_key = $decoded->public_key ?? '';

        $global_currency = get_static_option('site_global_currency');
        $inr_exchange_rate = get_static_option('site_' . strtolower($global_currency) . '_to_inr_exchange_rate');
        $checked_currency_rate = empty($inr_exchange_rate) ? 74 : $inr_exchange_rate;

        $test_mode = $paypal_credential_from_database->test_mode == 1;

        $mollie = XgPaymentGateway::mollie();
        $mollie->setApiKey($public_key);
        $mollie->setCurrency($global_currency);
        $mollie->setEnv($test_mode); //env must set as boolean, string will not work
        $mollie->setExchangeRate($checked_currency_rate); // if INR not set as currency

        $payment_data = $mollie->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = wrap_random_number($payment_data['order_id']);
            $this->tenant_create_event_with_credential_mail($payment_data['order_id']);

            return route('landlord.frontend.order.payment.success', $order_id);
        }
        return $this->cancel_page();
    }

    public function stripe_ipn()
    {

        $paypal_credential_from_database = PaymentGateway::where('name', 'stripe')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $public_key = $decoded->public_key ?? '';
        $secret_key = $decoded->secret_key ?? '';

        $global_currency = get_static_option('site_global_currency');

        $test_mode = $paypal_credential_from_database->test_mode == 1;

        $stripe = XgPaymentGateway::stripe();
        $stripe->setPublicKey($public_key);
        $stripe->setSecretKey($secret_key);
        $stripe->setCurrency($global_currency);
        $stripe->setEnv($test_mode);

        $payment_data = $stripe->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = wrap_random_number($payment_data['order_id']);
            $this->tenant_create_event_with_credential_mail($payment_data['order_id']);

            return route('landlord.frontend.order.payment.success', $order_id);
        }
        return $this->cancel_page();
    }

    public function flutterwave_ipn()
    {
        $paypal_credential_from_database = PaymentGateway::where('name', 'flutterwave')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);


        $public_key = $decoded->public_key ?? '';
        $secret_key = $decoded->secret_key ?? '';
        $global_currency = get_static_option('site_global_currency');

        $test_mode = $paypal_credential_from_database->test_mode == 1;


        $flutterwave = XgPaymentGateway::flutterwave();
        $flutterwave->setPublicKey($public_key);
        $flutterwave->setSecretKey($secret_key);
        $flutterwave->setCurrency($global_currency);
        $flutterwave->setEnv($test_mode);  //env must set as boolean, string will not work

        $payment_data = $flutterwave->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = wrap_random_number($payment_data['order_id']);
            $this->tenant_create_event_with_credential_mail($payment_data['order_id']);

            return route('landlord.frontend.order.payment.success', $order_id);
        }
        return $this->cancel_page();
    }

    public function paystack_ipn()
    {
        $paypal_credential_from_database = PaymentGateway::where('name', 'paystack')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $public_key = $decoded->public_key ?? '';
        $secret_key = $decoded->secret_key ?? '';
        $marchant_email = $decoded->marchant_email ?? '';

        $test_mode = $paypal_credential_from_database->test_mode == 1;

        $paystack = XgPaymentGateway::paystack();
        $paystack->setPublicKey($public_key);
        $paystack->setSecretKey($secret_key);
        $paystack->setMerchantEmail($marchant_email);
        $paystack->setEnv($test_mode);

        $payment_data = $paystack->ipn_response();


        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = wrap_random_number($payment_data['order_id']);
            $this->tenant_create_event_with_credential_mail($payment_data['order_id']);

            return route(route_prefix() . 'frontend.order.payment.success', $order_id);
        }
        return $this->cancel_page();
    }

    public function midtrans_ipn()
    {
        $paypal_credential_from_database = PaymentGateway::where('name', 'midtrans')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $server_key = $decoded->server_key ?? '';
        $client_key = $decoded->server_key ?? '';

        $test_mode = $paypal_credential_from_database->test_mode == 1;

        $midtrans = XgPaymentGateway::midtrans();
        $midtrans->setClientKey($client_key);
        $midtrans->setServerKey($server_key);
        $midtrans->setEnv($test_mode); //true mean sandbox mode , false means live

        $payment_data = $midtrans->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = wrap_random_number($payment_data['order_id']);
            $this->tenant_create_event_with_credential_mail($payment_data['order_id']);

            return route('landlord.frontend.order.payment.success', $order_id);
        }
        return $this->cancel_page();
    }

    public function payfast_ipn()
    {
        $paypal_credential_from_database = PaymentGateway::where('name', 'payfast')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $merchant_id = $decoded->merchant_id ?? '';
        $merchant_key = $decoded->merchant_key ?? '';
        $passphrase = $decoded->passphrase ?? '';

        $global_currency = get_static_option('site_global_currency') ?? "IDR";

        $test_mode = $paypal_credential_from_database->test_mode == 1;

        $payfast = XgPaymentGateway::payfast();
        $payfast->setMerchantId($merchant_id);
        $payfast->setMerchantKey($merchant_key);
        $payfast->setPassphrase($passphrase);
        $payfast->setCurrency($global_currency);
        $payfast->setEnv($test_mode); //env must set as boolean, string will not work

        $payment_data = $payfast->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = wrap_random_number($payment_data['order_id']);
            $this->tenant_create_event_with_credential_mail($payment_data['order_id']);

            return route('landlord.frontend.order.payment.success', $order_id);
        }
        return $this->cancel_page();
    }

    public function cashfree_ipn()
    {
        $paypal_credential_from_database = PaymentGateway::where('name', 'cashfree')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $app_id = $decoded->app_id ?? '';
        $secret_key = $decoded->secret_key ?? '';

        $test_mode = $paypal_credential_from_database->test_mode == 1;

        $cashfree = XgPaymentGateway::cashfree();
        $cashfree->setAppId($app_id);
        $cashfree->setSecretKey($secret_key);
        $cashfree->setEnv($test_mode);

        $payment_data = $cashfree->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = wrap_random_number($payment_data['order_id']);
            $this->tenant_create_event_with_credential_mail($payment_data['order_id']);

            return route('landlord.frontend.order.payment.success', $order_id);
        }
        return $this->cancel_page();

    }

    public function instamojo_ipn()
    {
        $paypal_credential_from_database = PaymentGateway::where('name', 'instamojo')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $client_id = $decoded->client_id ?? '';
        $client_secret = $decoded->client_secret ?? '';

        $test_mode = $paypal_credential_from_database->test_mode == 1;

        $instamojo = XgPaymentGateway::instamojo();
        $instamojo->setClientId($client_id);
        $instamojo->setSecretKey($client_secret);
        $instamojo->setEnv($test_mode);

        $payment_data = $instamojo->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = wrap_random_number($payment_data['order_id']);
            $this->tenant_create_event_with_credential_mail($payment_data['order_id']);

            return route('landlord.frontend.order.payment.success', $order_id);
        }
        return $this->cancel_page();
    }

    public function marcadopago_ipn()
    {
        $paypal_credential_from_database = PaymentGateway::where('name', 'marcadopago')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $client_id = $decoded->client_id ?? '';
        $client_secret = $decoded->client_secret ?? '';

        $test_mode = $paypal_credential_from_database->test_mode == 1;

        $marcadopago = XgPaymentGateway::marcadopago();
        $marcadopago->setClientId($client_id);
        $marcadopago->setClientSecret($client_secret);
        $marcadopago->setEnv($test_mode);

        $payment_data = $marcadopago->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = wrap_random_number($payment_data['order_id']);

            $this->tenant_create_event_with_credential_mail($payment_data['order_id']);

            return route('landlord.frontend.order.payment.success', $order_id);
        }
        return $this->cancel_page();
    }

    public function squareup_ipn()
    {
        $paypal_credential_from_database = PaymentGateway::where('name', 'squareup')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $location_id = $decoded->location_id ?? '';
        $access_token = $decoded->access_token ?? '';
        $setApplicationId = $decoded->setApplicationId ?? '';

        $global_currency = get_static_option('site_global_currency');
        $setCurrency = $global_currency ?? '';

        $test_mode = $paypal_credential_from_database->test_mode == 1;

        $squareup = XgPaymentGateway::squareup();
        $squareup->setLocationId($location_id);
        $squareup->setAccessToken($access_token);
        $squareup->setApplicationId($setApplicationId);
        $squareup->setCurrency($setCurrency);
        $squareup->setEnv($test_mode);

        $payment_data = $squareup->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = wrap_random_number($payment_data['order_id']);
            $this->tenant_create_event_with_credential_mail($payment_data['order_id']);

            return route('landlord.frontend.order.payment.success', $order_id);
        }
        return $this->cancel_page();
    }

    public function cinetpay_ipn()
    {
        $paypal_credential_from_database = PaymentGateway::where('name', 'cinetpay')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $setAppKey = $decoded->apiKey ?? '';
        $setSiteId = $decoded->site_id ?? '';

        $test_mode = $paypal_credential_from_database->test_mode == 1;

        $cinetpay = XgPaymentGateway::cinetpay();
        $cinetpay->setAppKey($setAppKey);
        $cinetpay->setSiteId($setSiteId);
        $cinetpay->setEnv($test_mode); //env must set as boolean, string will not work

        $payment_data = $cinetpay->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = wrap_random_number($payment_data['order_id']);
            $this->tenant_create_event_with_credential_mail($payment_data['order_id']);

            return route('landlord.frontend.order.payment.success', $order_id);
        }
        return $this->cancel_page();
    }


    public function paytabs_ipn()
    {
        $paypal_credential_from_database = PaymentGateway::where('name', 'paytabs')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $setProfileId = $decoded->profile_id ?? '';
        $setRegion = $decoded->region ?? '';
        $setServerKey = $decoded->server_key ?? '';

        $global_currency = get_static_option('site_global_currency');
        $setCurrency = $global_currency ?? '';

        $paytabs = XgPaymentGateway::paytabs();
        $paytabs->setProfileId($setProfileId);
        $paytabs->setRegion($setRegion);
        $paytabs->setServerKey($setServerKey);
        $paytabs->setCurrency($setCurrency);

        $payment_data = $paytabs->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = wrap_random_number($payment_data['order_id']);
            $this->tenant_create_event_with_credential_mail($payment_data['order_id']);

            return route('landlord.frontend.order.payment.success', $order_id);
        }
        return $this->cancel_page();
    }


    public function billplz_ipn()
    {
        $paypal_credential_from_database = PaymentGateway::where('name', 'billplz')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $setKey = $decoded->key ?? '';
        $setVersion = $decoded->version ?? '';
        $setXsignature = $decoded->x_signature ?? '';
        $setCollectionName = $decoded->collection_name ?? '';

        $global_currency = get_static_option('site_global_currency');
        $setCurrency = $global_currency ?? '';

        $test_mode = $paypal_credential_from_database->test_mode == 1;

        $billplz = XgPaymentGateway::billplz();
        $billplz->setKey($setKey);
        $billplz->setVersion($setVersion);
        $billplz->setXsignature($setXsignature);
        $billplz->setCollectionName($setCollectionName);
        $billplz->setCurrency($setCurrency);
        $billplz->setEnv($test_mode);

        $payment_data = $billplz->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = wrap_random_number($payment_data['order_id']);
            $this->tenant_create_event_with_credential_mail($payment_data['order_id']);

            return route('landlord.frontend.order.payment.success', $order_id);
        }
        return $this->cancel_page();
    }


    public function zitopay_ipn()
    {
        $paypal_credential_from_database = PaymentGateway::where('name', 'zitopay')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $setUsername = $decoded->username ?? '';

        $global_currency = get_static_option('site_global_currency');
        $setCurrency = $global_currency ?? '';

        $exchange_rate = get_static_option('site_' . strtolower($global_currency) . '_to_inr_exchange_rate') ?? 74;

        $test_mode = $paypal_credential_from_database->test_mode == 1;

        $zitopay = XgPaymentGateway::zitopay();
        $zitopay->setUsername($setUsername);
        $zitopay->setCurrency($setCurrency);
        $zitopay->setEnv($test_mode);
        $zitopay->setExchangeRate($exchange_rate); // if INR not set as currency

        $payment_data = $zitopay->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = wrap_random_number($payment_data['order_id']);
            $this->tenant_create_event_with_credential_mail($payment_data['order_id']);

            return route('landlord.frontend.order.payment.success', $order_id);
        }
        return $this->cancel_page();
    }


    private function update_database($order_id, $transaction_id)
    {
        PaymentLogs::where('id', $order_id)->update([
            'transaction_id' => $transaction_id,
            'payment_status' => 'complete',
            'updated_at' => Carbon::now()
        ]);
    }

    public function send_order_mail($order_id)
    {
        $package_details = PaymentLogs::where('id', $order_id)->first();
        $all_fields = [];//unserialize($package_details->custom_fields,['class'=> false]);
        unset($all_fields['package']);
        $all_attachment = [];//unserialize($package_details->attachment,['class'=> false]);
        $order_mail = get_static_option('order_page_form_mail') ? get_static_option('order_page_form_mail') : get_static_option('site_global_email');

        try {
            Mail::to($order_mail)->send(new PlaceOrder($all_fields, $all_attachment, $package_details, "admin", 'regular'));
            Mail::to($package_details->email)->send(new PlaceOrder($all_fields, $all_attachment, $package_details, 'user', 'regular'));

        } catch (\Exception $e) {
            return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
        }
    }

    private function tenant_create_event_with_credential_mail($order_id)
    {
        $log = PaymentLogs::findOrFail($order_id);
        $user = User::where('id', $log->user_id)->first();
        $tenant = Tenant::find($log->tenant_id);

        if (!empty($log) && $log->payment_status == 'complete' && is_null($tenant)) {
            event(new TenantRegisterEvent($user, $log->tenant_id));
            try {
                $raw_pass = '12345678';
                $credential_password = $raw_pass;
                $credential_email = $user->email;
                $credential_username = 'super_admin';

                Mail::to($credential_email)->send(new TenantCredentialMail($credential_username, $credential_password));

            } catch (\Exception $e) {

            }

        } else if (!empty($log) && $log->payment_status == 'complete' && !is_null($tenant) && $log->is_renew == 0) {
            try {
                $raw_pass = '12345678';
                $credential_password = $raw_pass;
                $credential_email = $user->email;
                $credential_username = 'super_admin';

                Mail::to($credential_email)->send(new TenantCredentialMail($credential_username, $credential_password));

            } catch (\Exception $e) {

            }

        }

        return true;
    }

    protected function cancel_page()
    {
        return to_route('landlord.frontend.order.payment.cancel.static');
    }
}
