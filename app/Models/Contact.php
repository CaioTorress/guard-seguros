<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'whatsapp',
        'email',
        'address',
        'city',
        'state',
        'zip_code',
        'business_hours_start',
        'business_hours_end'
    ];
}
