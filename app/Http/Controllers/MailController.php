<?php

namespace App\Http\Controllers;

use App\Mail\PromoteMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index()
    {
        return view('mail');
    }

    public function mailme()
    {
        Mail::to('ricardo.alvarez@drogueriadigital.co')
            ->send(new PromoteMail('Paola Suarez'));
    }

    public function sendMail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        Mail::to($request->input('email'))->send(new PromoteMail('Paola Suarez'));

        return back()->with('success', 'Email sent successfully!');
    }
}
