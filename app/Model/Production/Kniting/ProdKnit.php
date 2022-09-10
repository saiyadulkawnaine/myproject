<?php

namespace App\Model\Production\Kniting;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdKnit extends Model
{
    use MsUpdater;
  	use SoftDeletes;
  	protected $guarded = [];
  	protected $dates = ['deleted_at'];

  	public function getShiftNameAttribute()
  	{
	    return config('bprs.shiftname.'.$this->shift_id);
  	}

  	/*public function getProdDateAttribute($value)
	{
	    return date('d-M-Y',strtotime($value));
	}*/
  	
}
