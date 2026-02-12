<?php

namespace App\Services;

use App\Models\Log as Model;
use App\Services\EmailReportsService;
use App\Utils\BecomeString;
use Illuminate\Support\Facades\Auth;

class Log
{
    public static function Error(string $message, array|string $context, $email = null)
    {
        if (is_array($context))
            $context = BecomeString::ByArray($context);

        self::creating('error', $message, $context, $email);

        EmailReportsService::reportError($message, $context);
    }

    public static function Warning(string $message, array|string $context = '', $email = null)
    {
        self::creating('warning', $message, $context, $email);
    }

    public static function Info(string $message, array|string $context = '', $email = null)
    {
        self::creating('info', $message, $context, $email);
    }

    public static function Email($context, string $mail)
    {
        self::Info('Email enviado', $context, $mail);
    }

    private static function creating(string $level, string $message, string|array $context, $email = null)
    {
        if (is_array($context))
            $context = BecomeString::ByArray($context);

        Model::create([
            'message' => $message,
            'level' => $level,
            'context' => $context,
            'endpoint' => request()->method(),
            'path' => url()->full(),
            'user' => self::getEmail($email),
        ]);
    }

    private static function getEmail($email): string
    {
        if (is_string($email))
            return $email;

        return Auth::user()
            ? Auth::user()->email
            : 'Sistema';
    }
}
