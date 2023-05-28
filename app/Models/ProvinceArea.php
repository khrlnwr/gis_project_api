<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvinceArea extends Model
{
    use HasFactory;
    protected $table = 'provinces_area';
    protected $fillable = [
        'id',
        'name',
        'notes',
        'lat',
        'long'
    ];
}
