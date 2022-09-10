<?php

namespace App\Model\Util;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class Floor extends Model
{
    use MsUpdater;
    use SoftDeletes;
	protected $guarded = [];
	protected $dates = ['deleted_at'];
	
	public function departments()
    {
        return $this->belongsToMany('App\Model\Util\Department');
    }
	public function sections()
    {
        return $this->belongsToMany('App\Model\Util\Section');
    }
}
