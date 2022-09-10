<?php

namespace App\Model\Marketing;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class StyleGmtColorSize extends Model
{
  	use MsUpdater;
  	use SoftDeletes;
  	protected $guarded = [];
  	protected $dates = ['deleted_at'];
	protected  $relationMethods = ['mktcostfabriccons','salesordergmtcolorsizes'];
	public function salesordergmtcolorsizes()
    {
        return $this->hasMany('App\Model\Sales\SalesOrderGmtColorSize');
    }
	
	public function mktcostfabriccons()
    {
        return $this->hasMany('App\Model\Marketing\MktCostFabricCon');

    }

}
