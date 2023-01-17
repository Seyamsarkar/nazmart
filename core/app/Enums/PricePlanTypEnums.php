<?php

namespace App\Enums;

class PricePlanTypEnums
{
    const MONTHLY = 0;
    const YEARLY = 1;
    const LIFETIME = 2;

    public static function getText(int $const)
    {
        if ($const == self::MONTHLY){
            return __('Monthly');
        }elseif ($const == self::YEARLY){
            return __('Yearly');
        }elseif ($const == self::LIFETIME){
            return __('Lifetime');
        }
    }
}
