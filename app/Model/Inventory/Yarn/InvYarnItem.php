<?php

namespace App\Model\Inventory\Yarn;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvYarnItem extends Model
{
    use MsUpdater;
  	use SoftDeletes;
  	protected $guarded = [];
  	protected $dates = ['deleted_at'];
    protected  $relationMethods = [];

    public function setLotAttribute($value)
    {
        $this->attributes['lot'] = strtoupper($value);
    }
    public function setBrandAttribute($value)
    {
        $this->attributes['brand'] = strtoupper($value);
    }
}
