<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SupersetController extends MyController
{
    public function getEmbedToken(Request $request)
    {
        $dashboardId = $request->query('dashboard_id');
        $user = $request->user();

        if (!$dashboardId || !$user) {
            return response()->json(['error' => 'Missing dashboard or user info'], 400);
        }

        // Superset admin credentials (use .env)
        $supersetUrl = env('SUPERSET_BASE_URL', 'http://superset:8088');
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
}
