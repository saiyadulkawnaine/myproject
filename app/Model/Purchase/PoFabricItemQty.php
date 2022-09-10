<?php

namespace App\Model\Purchase;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoFabricItemQty extends Model
{
  	use MsUpdater;
  	use SoftDeletes;
  	protected $guarded = [];
  	protected $dates = ['deleted_at'];
  	protected  $relationMethods = [];
	
	/*public function purfabrics()
    {
        return $this->belongsTo('App\Model\Purchase\PurFabric');
    }*/

}
