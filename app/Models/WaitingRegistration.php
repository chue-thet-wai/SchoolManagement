<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaitingRegistration extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'waiting_registration';

    protected $guarded = [];
}
