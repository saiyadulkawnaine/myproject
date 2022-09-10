<?php
namespace App\Model\Marketing;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class StyleSampleCs extends Model
{
    use MsUpdater;
  	use SoftDeletes;
  	protected $guarded = [];
  	protected $dates = ['deleted_at'];
}
