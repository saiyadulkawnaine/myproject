<?php

namespace App\Model\Bom;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetYarnDyeingCon extends Model
{
  	use MsUpdater;
  	use SoftDeletes;
  	protected $guarded = [];
  	protected $dates = ['deleted_at'];

}
