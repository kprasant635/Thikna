<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_can_be_rendered(): void
    {
        $response = $this->get(route('contact'));

        $response->assertOk()
            ->assertSee('Get in Touch with')
            ->assertSee('SKOP-X')
            ->assertSee('Send Us a Message');
    }

    public function test_contact_form_validates_required_fields(): void
    {
        $response = $this->post(route('contact.submit'), []);

        $response->assertSessionHasErrors([
            'name',
            'email',
            'phone',
            'subject',
            'inquiry_type',
            'message',
        ]);
    }

    public function test_contact_form_can_be_submitted_successfully(): void
    {
        $response = $this->post(route('contact.submit'), [
            'name' => 'Rahul Sharma',
            'email' => 'rahul@example.com',
            'phone' => '+919876543210',
            'subject' => 'Course Inquiry',
            'inquiry_type' => 'Course & Learning',
            'message' => 'Hello, I want to know more about the available courses.',
        ]);

        $response->assertRedirect(route('contact'))
            ->assertSessionHas('success');
    }
}
