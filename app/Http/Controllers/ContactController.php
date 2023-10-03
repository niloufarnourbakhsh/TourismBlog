<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactUsMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class ContactController extends Controller
{
    public function create()
    {
        return view('Users.contact-us');
    }

    public function submit(ContactRequest $request)
    {

        Mail::to(env('MAIL_FROM_ADDRESS'))
        ->send(new ContactUsMail($request->name,$request->email,$request->body));
        Session::flash('contact-us','پیام شما ارسال شد');
        return redirect()->back();
    }
}
