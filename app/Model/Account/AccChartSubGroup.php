<?php

namespace App\Model\Account;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccChartSubGroup extends Model
{
    use MsUpdater;
  	use SoftDeletes;
  	protected $guarded = [];
  	protected $dates = ['deleted_at'];
  	protected  $relationMethods = ['accChartCtrlHeads'];

  	public function accChartCtrlHeads()
    {
        return $this->hasMany('App\Model\Account\AccChartCtrlHead');
    }
}
