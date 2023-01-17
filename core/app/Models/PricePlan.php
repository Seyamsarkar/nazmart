<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;
use Stancl\Tenancy\Contracts\SyncMaster;
use Stancl\Tenancy\Database\Concerns\CentralConnection;
use Stancl\Tenancy\Database\Concerns\ResourceSyncing;

class PricePlan extends Model
{
    use HasFactory;

    protected $fillable = ['title','features','type','status','price','free_trial','faq',
        'page_permission_feature','blog_permission_feature','product_permission_feature','storage_permission_feature', 'package_badge'];

    protected $casts = [
        'type' => 'integer',
        'status' => 'integer'
    ];

    public function plan_features()
    {
        return $this->hasMany(PlanFeature::class,'plan_id','id');
    }
}
