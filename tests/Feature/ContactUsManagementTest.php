<?php

namespace Tests\Feature;

use App\Mail\ContactUsMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactUsManagementTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function everyone_can_send_email_to_contact_to_the_admin()
    {
        $data = [
            'name' => 'nilloo',
            'email' => 'niloo@gmail.com',
            'message' => 'hiiii every body',
        ];
        $this->post('/contact-us', $data)
            ->assertSessionHas('contact-us')
            ->assertRedirectToRoute('contact.us');
    }
    /** @test */
    public function name_is_required_for_sending_an_email()
    {
        $data = [
            'name' => '',
            'email' => 'niloo@gmail.com',
            'message' => 'hiiii every body',
        ];
        $this->post('/contact-us', $data)->assertSessionHasErrors('name');
    }
    /** @test */
    public function email_is_required_for_sending_an_email()
    {
        $data = [
            'name' => 'Niloo',
            'email' => '',
            'message' => 'hiiii every body',
        ];
        $this->post('/contact-us', $data)->assertSessionHasErrors('email');
    }
    /** @test */
    public function message_is_required_for_sending_an_email()
    {
        $data = [
            'name' => 'Niloo',
            'email' => 'niloo@gmail.com',
            'message' => '',
        ];
        $this->post('/contact-us', $data)->assertSessionHasErrors('message');
    }
    /** @test */
    public function the_message_is_sent_as_an_email_to_the_admin()
    {
        $data = [
            'name' => 'nilloo',
            'email' => 'niloo@gmail.com',
            'message' => 'hiiii every body',
        ];
        Mail::fake();
        Mail::assertNothingSent();
        $this->post('/contact-us', $data)
            ->assertSessionHas('contact-us')
            ->assertRedirectToRoute('contact.us');
        Mail::to('nil.noorbakhsh@gmail.com')->send(new ContactUsMail($data['name'],$data['email'],$data['message']));
        Mail::assertSent(ContactUsMail::class);
        Mail::assertSent(ContactUsMail::class,function (ContactUsMail $email){
           return $email->hasTo('nil.noorbakhsh@gmail.com');
        });
    }
}
