<?php

namespace App\Model\Marketing;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Eloquent\MsUpdater;
use Illuminate\Database\Eloquent\SoftDeletes;

class Style extends Model
{
    use MsUpdater;
    use SoftDeletes;
    protected $guarded = [];
    protected $dates = ['deleted_at'];
    protected  $relationMethods = ['style_color','style_size','style_gmts'];

    public function style_color()
    {
      return $this->hasMany('App\Model\Marketing\StyleColor');
    }
    public function style_size()
    {
      return $this->hasMany('App\Model\Marketing\StyleSize');
    }
    public function style_gmts()
    {
      return $this->hasMany('App\Model\Marketing\StyleGmts');
    }

    public function getDeptCategoryNameAttribute()
    {
      //$deptcategory=array_prepend(config('bprs.deptcategory'),'-Select-','');
      //return "{$deptcategory[$this->dept_category_id]}";
      return config('bprs.deptcategory.'.$this->dept_category_id);
    }
}
