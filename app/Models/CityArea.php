<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityArea extends Model
{
    use HasFactory;

    protected $table = 'cities_area';
    protected $fillable = [
        'id_province',
        'name',
        'notes',
        'lat',
        'long'
    ]; 
}
