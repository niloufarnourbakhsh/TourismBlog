<?php

namespace Tests\Unit;

use App\Mail\ContactUsMail;
use Illuminate\Support\Facades\Mail;

use Tests\TestCase;

class ContactUsTest extends TestCase
{

    /** @test */
    public function send_mail_for_contact_us_page()
    {
        $name = 'niloo';
        $email = 'niloo@gmail.com';
        $body = 'hiiii every body';
        Mail::fake();
        Mail::assertNothingSent();
        Mail::to('nil.noorbakhsh@gmail.com')->send(new ContactUsMail($name,$email,$body));
        Mail::assertSent(ContactUsMail::class);
    }

    /** @test */
    public function check_what_we_see_in_view_after_sending_mail()
    {
        $name = 'niloo';
        $email = 'niloo@gmail.com';
        $body = 'hiiii every body';
        Mail::fake();
        Mail::assertNothingSent();
        $mail=new ContactUsMail($name,$email,$body);
        $mail->assertSeeInText($name);
        $mail->assertSeeInText($email);
        $mail->assertSeeInText($body);
    }
}
