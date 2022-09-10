<?php

namespace App\Model\Commercial\Export;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpDocSubTransection extends Model
{
    use MsUpdater;
    use SoftDeletes;
    protected $guarded = [];
    protected $dates = ['deleted_at'];
}