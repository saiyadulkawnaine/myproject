<?php

namespace App\Model\Sample\Costing;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmpCostCm extends Model
{
  	use MsUpdater;
  	use SoftDeletes;
  	protected $guarded = [];
  	protected $dates = ['deleted_at'];

}