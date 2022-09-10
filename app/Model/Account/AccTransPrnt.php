<?php

namespace App\Model\Account;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccTransPrnt extends Model
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

  	public function accYear()
    {
        return $this->belongsTo('App\Model\Account\AccYear');
    }

    public function accPeriods()
    {
        return $this->belongsTo('App\Model\Account\AccPeriod');
    }
}
