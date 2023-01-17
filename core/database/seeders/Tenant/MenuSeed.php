<?php

namespace Database\Seeders\Tenant;

use App\Mail\TenantCredentialMail;
use App\Models\Admin;
use App\Models\Language;
use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Mail;

class MenuSeed extends Seeder
{
    public function run()
    {

        Menu::create([
            'content' => json_encode($this->menu_content()),
            'title' => 'Primary Menu',
            'status' => 'default',
        ]);
    }


    private function menu_content()
    {
        $data = array(
                0 =>
                    array(
                        'ptype' => 'pages',
                        'id' => 1,
                        'antarget' => '',
                        'icon' => '',
                        'menulabel' => '',
                        'pid' => 1,
                    ),
                1 =>
                    array(
                        'ptype' => 'pages',
                        'id' => 2,
                        'antarget' => '',
                        'icon' => '',
                        'menulabel' => '',
                        'pid' => 2,
                    ),
                2 =>
                    array(
                        'ptype' => 'pages',
                        'id' => 3,
                        'antarget' => '',
                        'icon' => '',
                        'menulabel' => '',
                        'pid' => 3,
                    ),
                3 =>
                    array(
                        'ptype' => 'pages',
                        'id' => 4,
                        'antarget' => '',
                        'icon' => '',
                        'menulabel' => '',
                        'pid' => 4,
                    ),
                4 =>
                    array(
                        'ptype' => 'pages',
                        'id' => 5,
                        'antarget' => '',
                        'icon' => '',
                        'menulabel' => '',
                        'pid' => 5,
                    ),
            );

        return $data;
    }
}
