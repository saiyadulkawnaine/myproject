<?php

namespace App\Model\Account;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;


class AccPeriod extends Model
{
    use MsUpdater;
  	use SoftDeletes;
  	protected $guarded = [];
  	protected $dates = ['deleted_at'];
  	protected  $relationMethods = ['accTransPrnts'];

  	public function accYear()
    {
        return $this->belongsTo('App\Model\Account\AccYear');
    }

    public function accTransPrnts()
    {
        return $this->hasMany('App\Model\Account\AccTransPrnt');
    }
}
