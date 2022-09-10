<?php

namespace App\Model\Purchase;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoFabricItem extends Model
{
    use MsUpdater;
    use SoftDeletes;
    protected $guarded = [];
    protected $dates = ['deleted_at'];
    protected  $relationMethods = [];
    /*protected  $relationMethods = ['purfabricqties'];
    public function purchaseorder()
    {
      return $this->belongsTo('App\Model\Purchase\PurchaseOrder');
    }

    public function purfabricqties()
    {
      return $this->hasMany('App\Model\Purchase\PurFabricQty');
    }*/

}
