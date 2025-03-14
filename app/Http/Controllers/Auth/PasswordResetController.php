<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMail;

class PasswordResetController extends Controller
{
  public function sendResetLink(Request $request)
{
    // For GET requests, use query parameters
    $email = $request->input('email');

    // For POST requests, use request body
    if (!$email) {
        $email = $request->json('email');
    }

    // Validate the email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return response()->json(['message' => 'Invalid email'], 400);
    }

    // Check if the email exists in the users table
    $user = DB::table('tbl_users')->where('email', $email)->first();
    if (!$user) {
        return response()->json(['message' => 'Email not found'], 404);
    }

    // Generate a secure token
    $token = Str::random(60);

    // Save the token in the password_reset_tokens table
    DB::table('password_reset_tokens')->insert([
        'email' => $email,
        'token' => $token,
        'created_at' => now(),
        'expires_at' => now()->addMinutes(60), // Token expires in 1 hour
    ]);

    // Send the password reset email
    $resetLink = "http://localhost:8000/api/reset-password?token=$token";
    Mail::to($email)->send(new PasswordResetMail($resetLink));

    return response()->json(['message' => 'Password reset link sent']);
}

    // Endpoint 2: Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        $token = $request->input('token');
        $newPassword = $request->input('new_password');

        // Find the token in the database
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();

        if (!$tokenRecord) {
            return response()->json(['message' => 'Invalid token'], 400);
        }
       
        // Update the user's password
        DB::table('tbl_users')
            ->where('email', $tokenRecord->email)
            ->update(['password' => Hash::make($newPassword)]);

        // Invalidate the token
        DB::table('password_reset_tokens')
            ->where('token', $token)
            ->delete();

        return response()->json(['message' => 'Password reset successfully']);
    }
}