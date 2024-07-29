<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationMail;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_registration_form_displays_correctly()
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    public function test_user_can_register_with_valid_data()
    {
        $formData = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'g-recaptcha-response' => 'dummy-recaptcha-token',
        ];

        $response = $this->post(route('register'), $formData);

        $response->assertRedirect(route('verification.notice'));
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
            'name' => 'Test User',
        ]);

        Mail::assertSent(VerificationMail::class, function ($mail) use ($formData) {
            return $mail->hasTo($formData['email']);
        });
    }

    public function test_user_cannot_register_with_invalid_data()
    {
        $formData = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirmation' => 'different-password',
            'g-recaptcha-response' => '',
        ];

        $response = $this->post(route('register'), $formData);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'g-recaptcha-response']);
    }

    public function test_email_verification_form_displays_correctly()
    {
        $response = $this->get(route('verify.form'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.verify');
    }

    public function test_user_can_verify_email_with_valid_code()
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'verification_code' => '123456',
        ]);

        $formData = [
            'email' => 'testuser@example.com',
            'verification_code' => '123456',
        ];

        $response = $this->post(route('verify.email'), $formData);

        $response->assertRedirect(route('verification.success'));
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_user_cannot_verify_email_with_invalid_code()
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'verification_code' => '123456',
        ]);

        $formData = [
            'email' => 'testuser@example.com',
            'verification_code' => 'wrong-code',
        ];

        $response = $this->post(route('verify.email'), $formData);

        $response->assertRedirect(route('verification.error'));
        $this->assertNull($user->fresh()->email_verified_at);
    }
}
