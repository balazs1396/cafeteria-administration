<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['start_month', 'annual_value'];

    protected $casts = [ 'annual_value' => 'string' ];
}
