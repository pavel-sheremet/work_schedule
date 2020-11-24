<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificVacation extends Model
{
    protected $fillable = ['date_time_start', 'date_time_end'];

    use HasFactory;
}
