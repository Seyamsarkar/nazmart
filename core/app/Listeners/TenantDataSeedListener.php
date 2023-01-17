<?php

namespace App\Listeners;

use App\Events\TenantRegisterEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use App\Helpers\Payment\DatabaseUpdateAndMailSend\LandlordPricePlanAndTenantCreate;

class TenantDataSeedListener
{
    public function __construct()
    {
        //
    }

    public function handle(TenantRegisterEvent $event)
    {
        try{
            //database migrate
            $command = 'tenants:migrate --force --tenants='.$event->subdomain;
            Artisan::call($command);

        }catch(\Exception $e){
            $message = $e->getMessage();

            if(str_contains($message,'Duplicate entry')){
                abort(460,__('Tenant database demo data already imoprted'));
            }

            if(str_contains($message,'does not exist')){
                abort(461,__('Tenant does not exists'));
            }
        }

        $command = 'tenants:seed --force --tenants='.$event->subdomain;
        Artisan::call($command);
    }
}
