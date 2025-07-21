<?php namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsSender
{
    public function sendSms(string $to, string $content)
    {
        $url = 'http://localhost:8087/send';
        $params = [
            'username' => 'foo',
            'password' => 'bar',
            'to'       => $to,
            'content'  => $content,
            'from'     => '6146',
        ];

        try {
            $response = Http::get($url, $params);

            if ($response->successful()) {
                return $response->body();
            } else {
                return "Failed: " . $response->status();
            }
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
