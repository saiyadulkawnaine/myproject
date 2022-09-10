<?php

namespace App\Model\Account;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccChartCtrlHead extends Model
{
    use MsUpdater;
  	use SoftDeletes;
  	protected $guarded = [];
  	protected $dates = ['deleted_at'];
  	protected  $relationMethods = ['accTransChlds'];

  	public function accTransChlds()
    {
        return $this->hasMany('App\Model\Account\AccTransChld');
    }

    public function accChartSubGroups()
    {
        return $this->belongsTo('App\Model\Account\AccChartSubGroup');
    }
}
