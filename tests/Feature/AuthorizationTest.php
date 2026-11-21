<?php

use App\Models\Post;
use App\Models\User;

beforeEach(function () {
    $this->withoutVite();
});

function authorizationPostData(array $overrides = []): array
{
    return array_merge([
        'title' => 'Titre modifié',
        'slug' => 'titre-modifie',
        'body' => 'Contenu assez long pour passer la validation.',
        'is_published' => '1',
    ], $overrides);
}

it('redirects a guest to the login page', function () {
    $this->get(route('posts.create'))
        ->assertRedirect(route('login'));
});

it('attaches a new post to its author', function () {
    $author = User::factory()->create();

    $this->actingAs($author)
        ->post(route('posts.store'), authorizationPostData())
        ->assertRedirect();

    $this->assertDatabaseHas('posts', ['slug' => 'titre-modifie', 'user_id' => $author->id]);
});

it('lets an author edit their own post', function () {
    $author = User::factory()->create();
    $post = Post::factory()->for($author)->create();

    $this->actingAs($author)
        ->get(route('posts.edit', $post))
        ->assertOk();

    $this->actingAs($author)
        ->put(route('posts.update', $post), authorizationPostData())
        ->assertRedirect(route('posts.show', $post));

    expect($post->fresh()->title)->toBe('Titre modifié');
});

it('forbids an author to edit the post of someone else', function () {
    $post = Post::factory()->for(User::factory())->create();

    $this->actingAs(User::factory()->create())
        ->get(route('posts.edit', $post))
        ->assertForbidden();
});

it('forbids an author to update the post of someone else', function () {
    $post = Post::factory()->for(User::factory())->create(['title' => 'Original']);

    $this->actingAs(User::factory()->create())
        ->put(route('posts.update', $post), authorizationPostData())
        ->assertForbidden();

    expect($post->fresh()->title)->toBe('Original');
});

it('forbids an author to delete the post of someone else', function () {
    $post = Post::factory()->for(User::factory())->create();

    $this->actingAs(User::factory()->create())
        ->delete(route('posts.destroy', $post))
        ->assertForbidden();

    $this->assertDatabaseHas('posts', ['id' => $post->id]);
});

it('lets an admin update any post', function () {
    $post = Post::factory()->for(User::factory())->create();

    $this->actingAs(User::factory()->admin()->create())
        ->put(route('posts.update', $post), authorizationPostData())
        ->assertRedirect(route('posts.show', $post));

    expect($post->fresh()->title)->toBe('Titre modifié');
});

it('opens the categories back office to an admin only', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.categories.index'))
        ->assertForbidden();

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.categories.index'))
        ->assertOk();
});

it('lists only the posts of the logged in author, drafts included', function () {
    $author = User::factory()->create();
    Post::factory()->for($author)->create(['title' => 'Mon brouillon']);
    Post::factory()->for(User::factory())->published()->create(['title' => 'Article d’un autre']);

    $this->actingAs($author)
        ->get(route('my-posts'))
        ->assertOk()
        ->assertSee('Mon brouillon')
        ->assertDontSee('Article d’un autre');
});

it('redirects a guest who opens my posts to the login page', function () {
    $this->get(route('my-posts'))
        ->assertRedirect(route('login'));
});
