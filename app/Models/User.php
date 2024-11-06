<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    // Los campos que pueden ser asignados en masa
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'second_last_name',
        'country',
        'identification_type',
        'identification_number',
        'email',
        'hire_date',
        'area',
        'status',
    ];

    // Si no necesitas usar los timestamps, puedes agregarlo de esta manera:
    // public $timestamps = false;
}
