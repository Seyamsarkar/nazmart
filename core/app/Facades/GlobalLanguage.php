<?php

namespace App\Facades;

use App\Helpers\LanguageHelper;
use Illuminate\Support\Facades\Facade;


/**
 * @see LanguageHelper
 * @method user_lang()
 * @method all_languages()
 * */
class GlobalLanguage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'GlobalLanguage';
    }
}
