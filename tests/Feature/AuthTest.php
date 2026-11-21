<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->withoutVite();
});

it('registers an author with a hashed password', function () {
    $this->post(route('register.store'), [
        'name' => 'Amira',
        'email' => 'amira@example.com',
        'password' => 'motdepasse123',
        'password_confirmation' => 'motdepasse123',
    ])->assertRedirect(route('posts.index'));

    $user = User::where('email', 'amira@example.com')->firstOrFail();

    expect($user->role)->toBe('author');
    expect($user->password)->not->toBe('motdepasse123');
    expect(Hash::check('motdepasse123', $user->password))->toBeTrue();
    $this->assertAuthenticatedAs($user);
});

it('refuses a registration with a mismatched confirmation', function () {
    $this->post(route('register.store'), [
        'name' => 'Amira',
        'email' => 'amira@example.com',
        'password' => 'motdepasse123',
        'password_confirmation' => 'autrechose123',
    ])->assertSessionHasErrors(['password' => 'La confirmation du champ mot de passe ne correspond pas.']);

    $this->assertGuest();
    $this->assertDatabaseMissing('users', ['email' => 'amira@example.com']);
});

it('logs in with the right password', function () {
    $user = User::factory()->create(['email' => 'auteur@example.com']);

    $this->post(route('login.store'), ['email' => 'auteur@example.com', 'password' => 'password'])
        ->assertRedirect(route('posts.index'));

    $this->assertAuthenticatedAs($user);
});

it('shows a neutral message for a wrong password', function () {
    User::factory()->create(['email' => 'auteur@example.com']);

    $this->from(route('login'))
        ->post(route('login.store'), ['email' => 'auteur@example.com', 'password' => 'mauvais'])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors(['email' => 'Ces identifiants ne correspondent à aucun compte.']);

    $this->assertGuest();
});

it('logs out and shows a message', function () {
    $this->actingAs(User::factory()->create());

    $this->followingRedirects()
        ->post(route('logout'))
        ->assertOk()
        ->assertSee('Vous êtes déconnecté.');

    $this->assertGuest();
});
