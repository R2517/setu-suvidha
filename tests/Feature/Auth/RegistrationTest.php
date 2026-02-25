<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'state' => 'महाराष्ट्र (Maharashtra)',
            'district' => 'पुणे (Pune)',
            'taluka' => 'पुणे शहर',
            'village' => 'कसबा पुणे',
            'address_line1' => 'Shop No 1, Main Road',
            'email' => 'test@example.com',
            'mobile' => '9876543210',
            'password' => 'password',
            'password_confirmation' => 'password',
            'promo_code' => 'TEST50',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
