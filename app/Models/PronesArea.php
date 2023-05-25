<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PronesArea extends Model
{
    use HasFactory;

    protected $table = 'prones_area';
    protected $fillable = [
        'id_type',
        'id_province',
        'id_city',
        'name',
        'lat',
        'long'
    ];
}
