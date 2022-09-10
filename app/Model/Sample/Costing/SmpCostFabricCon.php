<?php

namespace App\Model\Sample\Costing;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmpCostFabricCon extends Model
{
  	use MsUpdater;
  	use SoftDeletes;
  	protected $guarded = [];
  	protected $dates = ['deleted_at'];
	
	public function stylegmtcolorsizes()
    {
        return $this->belongsTo('App\Model\Marketing\StyleGmtColorSize');
    }

}
