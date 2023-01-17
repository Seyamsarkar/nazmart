<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Stancl\Tenancy\Database\Concerns\CentralConnection;
use Stancl\Tenancy\Database\Concerns\ResourceSyncing;
use Tzsk\Payu\Models\HasTransactions;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'menus';
    protected $fillable = ['title','content','status', 'unique_key'];
}
