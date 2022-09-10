<?php

namespace App\Model\Subcontract\Dyeing;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlDyeingItemQty extends Model
{
    use MsUpdater;
  	use SoftDeletes;
  	protected $guarded = [];
  	protected $dates = ['deleted_at'];
}
