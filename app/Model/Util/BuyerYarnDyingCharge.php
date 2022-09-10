<?php

namespace App\Model\Util;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyerYarnDyingCharge extends Model
{
  use MsUpdater;
  use SoftDeletes;
  protected $guarded = [];
  protected $dates = ['deleted_at'];
}
