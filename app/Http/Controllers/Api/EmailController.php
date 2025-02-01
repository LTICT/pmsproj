<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Mail\CustomEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class EmailController extends MyController
{
   public function __construct()
   {
    parent::__construct();
}
public function sendEmail(Request $request)
{
    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
    ];

    Mail::to($data['email'])->send(new CustomEmail($data));

    return response()->json(['message' => 'Email sent successfully!']);
}
}