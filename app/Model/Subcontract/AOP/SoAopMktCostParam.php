<?php

namespace App\Model\Subcontract\AOP;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoAopMktCostParam extends Model
{
    use MsUpdater;
    use SoftDeletes;
    protected $guarded = [];
    protected $dates = ['deleted_at'];
}
