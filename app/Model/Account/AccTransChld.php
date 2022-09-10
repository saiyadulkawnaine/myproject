<?php

namespace App\Model\Account;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccTransChld extends Model
{
    use MsUpdater;
  	use SoftDeletes;
  	protected $guarded = [];
  	protected $dates = ['deleted_at'];

  	public function accTransPrnt()
    {
        return $this->belongsTo('App\Model\Account\AccTransPrnt');
    }

    public function accChartCtrlHeads()
    {
        return $this->belongsTo('App\Model\Account\AccChartCtrlHead');
    }
}
