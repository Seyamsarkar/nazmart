<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SupportDepartment extends Model
{
    protected $table = 'support_departments';
    protected $fillable = ['name','status'];
}
