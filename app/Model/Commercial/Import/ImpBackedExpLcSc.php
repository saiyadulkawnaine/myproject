<?php

namespace App\Model\Commercial\Import;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class ImpBackedExpLcSc extends Model
{
    use MsUpdater;
    //use SoftDeletes;
    protected $guarded = [];
    protected $dates = ['deleted_at'];
    protected  $relationMethods = [];
}
