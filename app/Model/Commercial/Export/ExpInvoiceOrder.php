<?php

namespace App\Model\Commercial\Export;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpInvoiceOrder extends Model
{
    use MsUpdater;
    use SoftDeletes;
    protected $guarded = [];
    protected $dates = ['deleted_at'];
    protected  $relationMethods = [];

     public function exppiorders()
    {
        return $this->belongsTo('App\Model\Commercial\Export\ExpPiOrder');
    }
    
}
