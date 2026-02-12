<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;



class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'level',
        'context',
        'path',
        'endpoint',
        'user'
    ];


    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d/m/Y H:i:s');
    }

}
