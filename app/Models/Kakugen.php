<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kakugen extends Model
{
    use HasFactory;

    protected $fillable = [
        'content', 'person_name'
    ];
}
