<?php

namespace App\Model\Subcontract\Embelishment;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoEmbPrintQcDtl extends Model
{
	use MsUpdater;
	use SoftDeletes;
	protected $guarded = [];
	protected $dates = ['deleted_at'];
}
