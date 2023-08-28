<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListBuilder extends Model
{
    use HasFactory;
    function details(){
        return $this->hasMany('App\Models\ListBuildersDetail','list_id', 'id');
    }
}
