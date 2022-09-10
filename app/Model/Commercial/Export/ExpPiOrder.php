<?php

namespace App\Model\Commercial\Export;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpPiOrder extends Model
{
    use MsUpdater;
    //use SoftDeletes; Commented From 15/5/2022
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    protected  $relationMethods = ['expinvoiceorders'];

    public function expinvoiceorders()
    {
        return $this->hasMany('App\Model\Commercial\Export\ExpInvoiceOrder');
    }

    public function exppis()
    {
        return $this->belongsTo('App\Model\Commercial\Export\ExpPi');
    }
}
