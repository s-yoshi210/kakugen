<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyKakugen extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function kakugen()
    {
        return $this->hasOne('App\Models\Kakugen', 'id', 'kakugen_id');
    }
}
