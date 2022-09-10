<?php

namespace App\Model\Util;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profitcenter extends Model
{
  use MsUpdater;
  use SoftDeletes;
  protected $guarded = [];
  protected $dates = ['deleted_at'];
  public function companies()
  {
        return $this->belongsToMany('App\Model\Util\Company');
  }
  public function itemclasses()
  {
        return $this->belongsToMany('App\Model\Util\Itemclass');
  }
}
