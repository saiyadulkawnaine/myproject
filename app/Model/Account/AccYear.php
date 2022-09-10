<?php

namespace App\Model\Account;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;


class AccYear extends Model
{
    use MsUpdater;
  	use SoftDeletes;
  	protected $guarded = [];
  	protected $dates = ['deleted_at'];
  	protected  $relationMethods = ['accPeriods','accTransPrnts'];

  	public function accPeriods()
    {
        return $this->hasMany('App\Model\Account\AccPeriod');
    }
    public function accTransPrnts()
    {
        return $this->hasMany('App\Model\Account\AccTransPrnt');
    }
}
