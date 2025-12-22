<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

use GuzzleHttp\Cookie\CookieJar;
class SupersetController extends MyController
{
    public function getEmbedToken(Request $request)
    {
        $dashboardId = $request->query('dashboard_id');
        $dashboardId=9;
        $user = $request->user();
        //if (!$dashboardId || !$user) {
        if (!$dashboardId) {
            return response()->json(['error' => 'Missing dashboard or user info'], 400);
        }

        // Superset admin credentials (use .env)
        $supersetUrl = env('SUPERSET_BASE_URL', 'http://superset:8084');
        $username = env('SUPERSET_USERNAME');
        $password = env('SUPERSET_PASSWORD');

        // Step 1: Get access token
        $loginResponse = Http::post("$supersetUrl/api/v1/security/login", [
            'username' => "admin",
            'password' => "admin",
            'provider' => 'db',
            'refresh' => true,
        ]);

        if (!$loginResponse->successful()) {
            return response()->json(['error' => 'Failed to login to Superset'], 500);
        }

        $accessToken = $loginResponse->json('access_token');
//dd($accessToken);
        // Step 2: Generate guest token for embedding
        $issuedAt = time();
        $expireAt = $issuedAt + 3600;
        $tokenResponse = Http::withToken($accessToken)
            ->post("$supersetUrl/api/v1/security/guest_token/", [
                'user' => ['username' => 'guest'],   // maps to GUEST_ROLE_NAME
            'resources' => [
                ['type' => 'dashboard', 'id' => (string)$dashboardId]
            ],
            'rls' => []
            ]);

        if (!$tokenResponse->successful()) {
            return response()->json(['error' => 'Failed to get guest token'], 500);
        }

        return response()->json([
            'token' => $tokenResponse->json('token'),
            'dashboardUrl' => "$supersetUrl/embedded/$dashboardId",
        ]);
    }

    function generateSupersetGuestToken($dashboardId, string $secret, int $expirySeconds = 3600): string
    {
        $issuedAt = time();
        $expireAt = $issuedAt + $expirySeconds;

        $payload = [
            'user' => ['username' => 'guest'],   // maps to GUEST_ROLE_NAME
            'resources' => [
                ['type' => 'dashboard', 'id' => $dashboardId]
            ],
            'iat' => $issuedAt,
            'exp' => $expireAt
        ];
$guest = new GuestUser($payload);
return JWTAuth::fromUser($guest);
    }

    public function embedDashboard(Request $request)
    {
        $dashboardId="9afd9d83-133a-42a5-a7bb-8ffae9b03a64";
        $guestToken = $this->generateSupersetGuestToken(
            $dashboardId,
            env('GUEST_TOKEN_JWT_SECRET', 'dad5fFRpt6kSeidpdh816SiWaQh0dnktjKEFxTUC7APSb5vhvX69rHsA6zzdOhkh'),
            3600
        );
        return response()->json([
            'guest_token' => $guestToken,
            'embed_url' => "http://localhost:8084/embedded/dashboard/{$dashboardId}/?guest_token={$guestToken}"
        ]);
    }
    //START THIRD OPTION
public function getSupersetEmbedToken($dashboardId="9afd9d83-133a-42a5-a7bb-8ffae9b03a64")
{
    $supersetUrl = env('SUPERSET_BASE_URL', 'http://superset:8084');
    $username = env('SUPERSET_USERNAME', 'admin');
    $password = env('SUPERSET_PASSWORD', 'admin');
    
    $cookieJar = new CookieJar();
    
    // 1. Login first (without CSRF - some Superset versions allow this)
    $login = Http::withOptions(['cookies' => $cookieJar])
        ->post("$supersetUrl/api/v1/security/login", [
            'username' => $username,
            'password' => $password,
            'provider' => 'db',
            'refresh' => true,
        ]);
    
    if (!$login->ok()) {
        return response()->json(['error' => 'Login failed: ' . $login->body()], 401);
    }
    
    $accessToken = $login->json('access_token');
    
    // 2. Now get CSRF token with auth
    $csrf = Http::withOptions(['cookies' => $cookieJar])
        ->withToken($accessToken)
        ->get("$supersetUrl/api/v1/security/csrf_token/");
    
    if (!$csrf->ok()) {
        // Some Superset setups don't need CSRF for API when using token auth
        // Try guest token without CSRF
        return $this->tryGuestTokenWithoutCsrf($cookieJar, $accessToken, $supersetUrl, $dashboardId);
    }
    
    $csrfToken = $csrf->json('result');
    
    // 3. Generate guest token with CSRF
    $guest = Http::withOptions(['cookies' => $cookieJar])
        ->withToken($accessToken)
        ->withHeaders([
            'X-CSRFToken' => $csrfToken,
            'Referer' => $supersetUrl,
        ])
        ->post("$supersetUrl/api/v1/security/guest_token/", [
            'user' => [
                'username' => 'guest',
                'first_name' => 'guest',
                'last_name' => 'guest',
            ],
            'resources' => [
                ['type' => 'dashboard', 'id' => (string)$dashboardId]
            ],
            'rls' => []
        ]);
    
    if ($guest->ok()) {
        $guestToken = $guest->json('token');
        $embedUrl = "$supersetUrl/embedded/$dashboardId/?token=$guestToken";
        
        return response()->json([
            'success' => true,
            'token' => $guestToken,
            'embed_url' => $embedUrl,
        ]);
    }
    
    // Fallback: try without CSRF
    return $this->tryGuestTokenWithoutCsrf($cookieJar, $accessToken, $supersetUrl, $dashboardId);
}

private function tryGuestTokenWithoutCsrf($cookieJar, $accessToken, $supersetUrl, $dashboardId)
{
    $guest = Http::withOptions(['cookies' => $cookieJar])
        ->withToken($accessToken)
        ->post("$supersetUrl/api/v1/security/guest_token/", [
            'user' => [
                'username' => 'guest',
                'first_name' => 'guest',
                'last_name' => 'guest',
            ],
            'resources' => [
                ['type' => 'dashboard', 'id' => (string)$dashboardId]
            ],
            'rls' => []
        ]);
    
    if ($guest->ok()) {
        $guestToken = $guest->json('token');
        $embedUrl = "$supersetUrl/superset/dashboard/$dashboardId/?standalone=true&guest_token=$guestToken";
        
        return response()->json([
            'success' => true,
            'token' => $guestToken,
            'embed_url' => $embedUrl,
            'note' => 'Generated without CSRF token',
        ]);
    }
    
    return response()->json([
        'error' => 'Guest token failed: ' . $guest->body(),
        'status' => $guest->status(),
    ], 500);
}
    //END THIRD OPTION
}


class GuestUser implements JWTSubject
{
    public $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function getJWTIdentifier()
    {
        return $this->payload['user']['username'] ?? 'guest';
    }

    public function getJWTCustomClaims(): array
    {
        return $this->payload;
    }
}