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

        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false,
            stream_context_create([
                'http' => [
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => http_build_query([
                        'secret'   => $secret,
                        'response' => $token
                    ])
                ]
            ])
        );
        $data = json_decode($response, true);
        return $data['success'] && $data['action'] === $action && $data['score'] >= 0.5;
    }
}
