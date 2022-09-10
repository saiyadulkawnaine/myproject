<?php

namespace App\Model\Util;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use MsUpdater;
    use SoftDeletes;
	protected $guarded = [];
	protected $dates = ['deleted_at'];
	
	public function floors()
    {
        return $this->belongsToMany('App\Model\Util\Floor');
    }
}
