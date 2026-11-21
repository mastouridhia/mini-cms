<?php

use App\Models\Post;
use App\Models\User;

beforeEach(function () {
    $this->withoutVite();
});

it('shows a published post to a guest', function () {
    $post = Post::factory()->published()->create();

    $this->get(route('posts.show', $post))
        ->assertOk()
        ->assertSee($post->title);
});

it('forbids a draft to a guest', function () {
    $draft = Post::factory()->for(User::factory())->create();

    $this->get(route('posts.show', $draft))
        ->assertForbidden();
});

it('shows a draft to its author only', function () {
    $author = User::factory()->create();
    $draft = Post::factory()->for($author)->create();

    $this->actingAs($author)
        ->get(route('posts.show', $draft))
        ->assertOk();

    $this->actingAs(User::factory()->create())
        ->get(route('posts.show', $draft))
        ->assertForbidden();
});

it('shows any draft to an admin', function () {
    $draft = Post::factory()->for(User::factory())->create();

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('posts.show', $draft))
        ->assertOk();
});

it('lists drafts only to their author', function () {
    $author = User::factory()->create();
    Post::factory()->for($author)->create(['title' => 'Brouillon secret']);
    Post::factory()->published()->create(['title' => 'Article public']);

    $this->get(route('posts.index'))
        ->assertOk()
        ->assertSee('Article public')
        ->assertDontSee('Brouillon secret');

    $this->actingAs(User::factory()->create())
        ->get(route('posts.index'))
        ->assertDontSee('Brouillon secret');

    $this->actingAs($author)
        ->get(route('posts.index'))
        ->assertSee('Brouillon secret');
});
