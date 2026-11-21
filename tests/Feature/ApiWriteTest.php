<?php

use App\Models\Post;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

function apiPostData(array $overrides = []): array
{
    return array_merge([
        'title' => 'Article via API',
        'slug' => 'article-via-api',
        'body' => 'Contenu assez long pour passer la validation.',
        'is_published' => true,
    ], $overrides);
}

it('refuses to create a post without a token', function () {
    $this->postJson('/api/posts', apiPostData())
        ->assertUnauthorized();

    $this->assertDatabaseMissing('posts', ['slug' => 'article-via-api']);
});

it('creates a post owned by the token user', function () {
    $author = User::factory()->create();
    Sanctum::actingAs($author);

    $this->postJson('/api/posts', apiPostData())
        ->assertCreated()
        ->assertJsonPath('data.slug', 'article-via-api')
        ->assertJsonPath('data.author', $author->name);

    $this->assertDatabaseHas('posts', ['slug' => 'article-via-api', 'user_id' => $author->id]);
});

it('returns French validation errors for an invalid post', function () {
    Sanctum::actingAs(User::factory()->create());

    $this->postJson('/api/posts', apiPostData(['title' => '']))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title' => 'Le champ titre est obligatoire.']);
});

it('forbids updating the post of someone else before validating', function () {
    $post = Post::factory()->for(User::factory())->create();
    Sanctum::actingAs(User::factory()->create());

    $this->putJson("/api/posts/{$post->id}", ['title' => ''])
        ->assertForbidden();
});

it('lets the owner delete their post', function () {
    $author = User::factory()->create();
    $post = Post::factory()->for($author)->create();
    Sanctum::actingAs($author);

    $this->deleteJson("/api/posts/{$post->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});

it('gives a token at login that opens the user endpoint', function () {
    User::factory()->create(['email' => 'auteur@example.com']);

    $token = $this->postJson('/api/login', ['email' => 'auteur@example.com', 'password' => 'password'])
        ->assertCreated()
        ->assertJsonPath('token_type', 'Bearer')
        ->json('token');

    $this->withToken($token)
        ->getJson('/api/user')
        ->assertOk()
        ->assertJsonPath('email', 'auteur@example.com');
});

it('answers a wrong password with a neutral message', function () {
    User::factory()->create(['email' => 'auteur@example.com']);

    $this->postJson('/api/login', ['email' => 'auteur@example.com', 'password' => 'mauvais'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email' => 'Ces identifiants ne correspondent à aucun compte.']);
});

it('revokes the token at logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api')->plainTextToken;

    $this->withToken($token)
        ->postJson('/api/logout')
        ->assertNoContent();

    expect($user->tokens()->count())->toBe(0);
});
