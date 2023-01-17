<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductShippingReturnPolicy extends Model
{
    use HasFactory;

    protected $fillable = ["product_id","shipping_return_description"];

    protected $table = 'product_shipping_return_policies';
}
