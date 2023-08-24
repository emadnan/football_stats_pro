<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    function details(){
        return $this->hasMany('App\Models\AlertsDetail','alerts_id', 'id');
    }
}
