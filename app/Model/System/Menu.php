<?php

namespace App\Model\System;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use MsUpdater;
	use SoftDeletes;
	protected $guarded = [];
	protected $dates = ['deleted_at'];
  public function gmtsparts()
    {
        return $this->belongsToMany('App\Model\Util\Gmtspart');
    }
}
