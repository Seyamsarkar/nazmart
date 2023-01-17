<?php

namespace Modules\RefundModule\Entities;

use App\Models\ProductOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\CountryManage\Entities\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Entities\Product;
use Modules\TaxModule\Entities\CountryTax;

class RefundProduct extends Model
{
    protected $table = 'refund_products';

    protected $fillable = [
        'user_id',
        'order_id',
        'product_id',
        'status'
    ];

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function order(): HasOne
    {
        return $this->hasOne(ProductOrder::class, 'id', 'order_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
