<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdditionalFee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'additional_fee';


    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}
