<?php

namespace App\Model\Inventory\Yarn;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvYarnIsuItem extends Model
{
    use MsUpdater;
  	use SoftDeletes;
  	protected  $guarded = [];
  	protected  $dates = ['deleted_at'];
    protected  $relationMethods = [];
}
