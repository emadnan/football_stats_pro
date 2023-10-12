<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertQuery extends Model
{
    use HasFactory;
    function rules()
    {
        return $this->hasMany(AlertRules::class);
    }
}
