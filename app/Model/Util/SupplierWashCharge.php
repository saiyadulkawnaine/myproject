<?php

namespace App\Model\Util;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierWashCharge extends Model
{
  use MsUpdater;
  use SoftDeletes;
  protected $guarded = [];
  protected $dates = ['deleted_at'];
}
