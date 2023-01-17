<?php

namespace Modules\Badge\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Badge extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'image', 'for', 'sale_count', 'type', 'status'];
    protected $table = 'badges';

    protected static function newFactory()
    {
        return \Modules\Badge\Database\factories\BadgeFactory::new();
    }
}
