<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleTable extends Model
{
    use HasFactory;
    public function makby(){
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }


    protected $appends = ['time_explode'];

    public function getTimeExplodeAttribute()
    {
        $res = null;
        if($this->time){
            $res = explode(',', $this->time);
        }
        return $res;
    }

    public function scopeSearch($query, $val='')
    {
        return $query
        ->where('title', 'LIKE', '%'.$val.'%')
        ->where('time', 'LIKE', '%'.$val.'%'); 
    }
}
