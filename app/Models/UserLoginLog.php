<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoginLog extends Model
{
    use HasFactory;

    public function scopeSearch($query, $val='')
    {
        return $query
        ->where('login_id', 'LIKE', '%'.$val.'%')
        ->Orwhere('ip', 'LIKE', '%'.$val.'%')
        ->Orwhere('os', 'LIKE', '%'.$val.'%')
        ->Orwhere('browser', 'LIKE', '%'.$val.'%')
        ->Orwhere('device', 'LIKE', '%'.$val.'%'); 
    }
}
