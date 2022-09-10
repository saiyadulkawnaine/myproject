<?php

namespace App\Model\Commercial\Export;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpPi extends Model
{
    use MsUpdater;
    use SoftDeletes;
    protected $guarded = [];
    protected $dates = ['deleted_at'];
    protected  $relationMethods = ['exppiorders','explcscpis'];

    public function exppiorders()
    {
        return $this->hasMany('App\Model\Commercial\Export\ExpPiOrder');
    }

    public function explcscpis()
    {
        return $this->hasMany('App\Model\Commercial\Export\ExpLcScPi');
    }
}
