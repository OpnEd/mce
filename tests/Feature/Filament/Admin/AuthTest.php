<?php

it('does not allow guests to the admin panel', function () {
    $this->get('/admin')
    ->assertRedirect(route('filament.admin.auth.login'));
});

it('does not allow users with unverified emails to access the admin panel', function () {
    
});
/* namespace Tests\Feature\Filament\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    /*public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
} */
