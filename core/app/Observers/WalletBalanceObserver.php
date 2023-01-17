<?php

namespace App\Observers;

use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Entities\WalletSettings;

class WalletBalanceObserver
{

    public function updated()
    {
        WalletSettings::latest()->first()->update([
            'minimum_amount' => 500
        ]);
    }
}
