<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the login page loads correctly.
     *
     * @return void
     */
    public function test_login_page_loads_correctly()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test user cannot login with incorrect credentials.
     *
     * @return void
     */
    public function test_user_cannot_login_with_incorrect_credentials()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test auth middleware prevents guests from accessing logout route.
     *
     * @return void
     */
    public function test_auth_middleware_prevents_guests_from_accessing_logout_route()
    {
        // Ensure we're a guest
        $this->assertGuest();

        // Try to access logout route
        $response = $this->post('/logout');
        $response->assertRedirect('/login');
    }

    /**
     * Test validation rules for login.
     *
     * @return void
     */
    public function test_validation_rules_for_login()
    {
        // Test with empty fields
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);

        // Test with invalid email
        $response = $this->post('/login', [
            'email' => 'not-an-email',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
