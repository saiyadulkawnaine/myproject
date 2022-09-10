<?php

namespace App\Model\Util;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use MsUpdater;
	use SoftDeletes;
	protected $guarded = [];
	protected $dates = ['deleted_at'];
	public function profitcenters()
    {
        return $this->belongsToMany('App\Model\Util\Profitcenter');
    }
	public function suppliers()
    {
        return $this->belongsToMany('App\Model\Util\Supplier');
    }
}
