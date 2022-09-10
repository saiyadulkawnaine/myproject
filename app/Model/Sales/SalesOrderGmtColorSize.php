<?php

namespace App\Model\Sales;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrderGmtColorSize extends Model
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
