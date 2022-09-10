<?php

namespace App\Model\System;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionUser extends Model
{
    use MsUpdater;
	//use SoftDeletes;
	protected $guarded = [];
	protected $dates = ['deleted_at'];
	protected $table = 'permission_user';
	protected  $relationMethods = [];
  
}
