<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
class SupersetController extends MyController
{
    public function getEmbedToken(Request $request)
    {
        $dashboardId = $request->query('dashboard_id');
        $dashboardId=9;
        $user = $request->user();

        if (!$dashboardId || !$user) {
            return response()->json(['error' => 'Missing dashboard or user info'], 400);
        }

        // Superset admin credentials (use .env)
        $supersetUrl = env('SUPERSET_BASE_URL', 'http://superset:8084');
        $username = env('SUPERSET_USERNAME');
        $password = env('SUPERSET_PASSWORD');

        // Step 1: Get access token
        $loginResponse = Http::post("$supersetUrl/api/v1/security/login", [
            'username' => $username,
            'password' => $password,
            'provider' => 'db',
            'refresh' => true,
        ]);

        if (!$loginResponse->successful()) {
            return response()->json(['error' => 'Failed to login to Superset'], 500);
        }

        $accessToken = $loginResponse->json('access_token');
//dd($accessToken);
        // Step 2: Generate guest token for embedding
        $tokenResponse = Http::withToken($accessToken)
            ->post("$supersetUrl/api/v1/guest_token/", [
                'resources' => [
                    [
                        'type' => 'dashboard',
                        'id' => (string) $dashboardId,
                    ],
                ],
                'rls' => [],
                'user' => [
                    'username' => $user->name,
                    'first_name' => $user->name,
                    'last_name' => '',
                ],
            ]);

        if (!$tokenResponse->successful()) {
            return response()->json(['error' => 'Failed to get guest token'], 500);
        }

        return response()->json([
            'token' => $tokenResponse->json('token'),
            'dashboardUrl' => "$supersetUrl/embedded/$dashboardId",
        ]);
    }

    function generateSupersetGuestToken(int $dashboardId, string $secret, int $expirySeconds = 3600): string
    {
        $issuedAt = time();
        $expireAt = $issuedAt + $expirySeconds;

        $payload = [
            'user' => ['username' => 'Guest'],   // maps to GUEST_ROLE_NAME
            'resources' => [
                ['type' => 'dashboard', 'id' => $dashboardId]
            ],
            'iat' => $issuedAt,
            'exp' => $expireAt
        ];
$guest = new GuestUser($payload);
//$secret = 'bB@5#sT8!W9q2$kN3zLx7RpD4fM6eA1';
  //  JWTAuth::factory()->setSecret($secret);

return JWTAuth::fromUser($guest); 
        //return JWTAuth::encode($payload, $secret, 'HS256');
        //return JWTAuth::customClaims($payload)->fromSubject('guest');
    }

    public function embedDashboard(Request $request)
    {
        $dashboardId=9;
        $guestToken = $this->generateSupersetGuestToken(
            $dashboardId,
            env('GUEST_TOKEN_JWT_SECRET', 'bB@5#sT8!W9q2$kN3zLx7RpD4fM6eA1'),
            3600
        );
        return response()->json([
            'guest_token' => $guestToken,
            'embed_url' => "http://localhost:8084/embedded/dashboard/{$dashboardId}/?guest_token={$guestToken}"
        ]);
    }
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