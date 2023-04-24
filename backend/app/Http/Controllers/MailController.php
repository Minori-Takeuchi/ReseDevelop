<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\SendInformationMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\MailRequest;

class MailController extends Controller
{
    public function sendmail(MailRequest $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'error' => 'Not found email'
            ], 400);
        }

        $mail = new SendInformationMail($user);
        Mail::to($user->email)->send($mail);

        return response()->json([
            'message' => 'Email sent successfully'
        ], 200);
    }
}
