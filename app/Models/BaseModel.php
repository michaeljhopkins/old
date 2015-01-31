<?php namespace Genair\Models;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model{
    public static function boot()
    {
        parent::boot();
        if(Auth::check()){
            static::creating(function ($model) {
                $id = Auth::user()->getAuthIdentifier();
                $model->created_by = $id;
                $model->updated_at = Carbon::now();
                $model->updated_by = $id;
            });
            static::updating(function ($model) {
                if (Auth::check()) {
                    $model->updated_at = Carbon::now();
                    $model->updated_by = Auth::user()->getAuthIdentifier();
                }
            });
        }
    }
}