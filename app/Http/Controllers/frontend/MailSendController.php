<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use \App\Mail\SendMail;
use Illuminate\Http\Request;

class MailSendController extends Controller
{
    public function mailsend()
    {
        $details = [
            'title' => 'Title: Mahatat Al Alam',
            'body' => 'Body: Mahatat Al Alam'
        ];

        \Mail::to('cheenajamshaid@gmail.com')->send(new SendMail($details));
        return view('template.frontend.userdashboard.emails.thanks');
    }
}
