<?php
namespace App\Services;

use App\Config\Env;

class CaptchaService
{
    public static function verify(string $token, string $action): bool
    {
        if (!$token) return false;

        $secret = Env::get('CAPTCHA_SECRET');
        if (!$secret) return false;

        $response = file_get_contents(
            'https://www.google.com/recaptcha/api/siteverify',
            false,
            stream_context_create([
                'http' => [
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => http_build_query([
                        'secret'   => $secret,
                        'response' => $token,
                    ])
                ]
            ])
        );

        if ($response === false) {
            return false;
        }

        $data = json_decode($response, true);

        if (!is_array($data)) {
            return false;
        }

        $success = $data['success'] ?? false;
        $score   = $data['score'] ?? 0;
        $act     = $data['action'] ?? '';

        return $success && $act === $action && $score >= 0.5;
    }
}
