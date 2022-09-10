<?php

namespace App\Model\HRM;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeHRStatus extends Model
{
    use MsUpdater;
  	//use SoftDeletes;
  	protected $guarded = [];
  	protected $dates = ['deleted_at'];
  	protected  $relationMethods = [];
}
