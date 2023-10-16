<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListBuilderQuery extends Model
{
    use HasFactory;


    function rules()
    {
        return $this->hasMany(ListBuilderRule::class);
    }
}
