<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverRoutesDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'driver_routes_detail';

    protected $guarded = [];
}
