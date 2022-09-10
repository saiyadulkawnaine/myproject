<?php
 
namespace App\Traits\Eloquent;
 
trait MsUpdater { 
public static function boot(){
        parent::boot();
        static::creating(function($model)
        {
            $ip=request()->ip();
			$user = \Auth::user();           
            $model->created_by = $user->id;
            $model->updated_by = $user->id;
			$model->created_ip = $ip;
        });
        static::updating(function($model)
        {
			$ip=request()->ip();
			$user = \Auth::user();
			$model->updated_by = $user->id;
			$model->updated_ip = $ip;
        }); 
		/*
         * Deleting a model is slightly different than creating or deleting. For
         * deletes we need to save the model first with the deleted_by field
         * */
        static::deleting(function($model)  {
			foreach ($model->relationMethods as $relationMethod) {
				if ($model->$relationMethod()->count() > 0) {
					return false;
				}
			}
			$ip=request()->ip();
            $model->row_status = 0;
			$model->deleted_ip = $ip;
            $model->save();
        });      
    }
}