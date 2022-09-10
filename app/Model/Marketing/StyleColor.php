<?php

namespace App\Model\Marketing;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class StyleColor extends Model
{
  	use MsUpdater;
  	use SoftDeletes;
  	protected $guarded = [];
  	protected $dates = ['deleted_at'];
	protected  $relationMethods = [];
	public function style()
    {
        return $this->belongsTo('App\Model\Marketing\Style');
    }

}
