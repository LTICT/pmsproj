<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailControllerTest extends MyController
{
    public function sendWelcomeEmail(Request $request)
    {
        // Define the recipient email address and user data
        $email = 'leuld08@gmail.com'; // Replace with the recipient's email
        $data = [
            'name' => 'John Doe', // Replace with the recipient's name
            'email' => $email,
            'phone'=>'0912131415'
        ];

        // Send the email
        Mail::send('emails.custom_email', ['data' => $data], function ($message) use ($email) {
            $message->to($email)
                    ->subject('Welcome to Our Application');
        });

        return response()->json(['message' => 'Welcome email sent successfully!']);
    }
}