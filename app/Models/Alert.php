<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    // function queries(){
    //     return $this->hasMany('App\Models\AlertQuery','alert_id', 'id');
    // }
    function queries()
    {
        return $this->hasMany(AlertQuery::class);
    }
}
