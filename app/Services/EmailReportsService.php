<?php

namespace App\Services;

use App\Mail\ReportErrorMail;
use Illuminate\Support\Facades\Mail;

class EmailReportsService
{
    private const Backends = [
        'caio.torres@ateliedepropaganda.com.br'
    ];

    public static function reportError(string $message, string $context)
    {
        foreach( self::Backends as $backend ) {

            Mail::to($backend)->send(new ReportErrorMail($message, $context));
        }
    }
}