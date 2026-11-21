<?php

use App\Models\Post;
use App\Models\Tag;

beforeEach(function () {
    $this->withoutVite();
});

it('lists the tags with their post count', function () {
    Tag::factory()->create(['name' => 'Laravel']);

    $this->get(route('admin.tags.index'))
        ->assertOk()
        ->assertSee('Laravel');
});

it('creates a tag and shows a flash message', function () {
    $this->post(route('admin.tags.store'), ['name' => 'Pest', 'slug' => 'pest'])
        ->assertRedirect(route('admin.tags.index'))
        ->assertSessionHas('success', 'Tag créé.');

    $this->assertDatabaseHas('tags', ['slug' => 'pest']);
});

it('refuses a slug already used by another tag', function () {
    Tag::factory()->create(['slug' => 'php']);

    $this->post(route('admin.tags.store'), ['name' => 'PHP 8', 'slug' => 'php'])
        ->assertSessionHasErrors(['slug' => 'Ce slug est déjà utilisé par un autre tag.']);
});

it('updates a tag and keeps its own slug', function () {
    $tag = Tag::factory()->create(['name' => 'Ancien', 'slug' => 'garde-slug']);

    $this->put(route('admin.tags.update', $tag), ['name' => 'Nouveau', 'slug' => 'garde-slug'])
        ->assertRedirect(route('admin.tags.index'))
        ->assertSessionHas('success', 'Tag mis à jour.');

    $this->assertDatabaseHas('tags', ['id' => $tag->id, 'name' => 'Nouveau']);
});

it('deletes a tag but keeps its posts', function () {
    $tag = Tag::factory()->create();
    $post = Post::factory()->create();
    $post->tags()->attach($tag);

    $this->delete(route('admin.tags.destroy', $tag))
        ->assertSessionHas('success', 'Tag supprimé.');

    $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    $this->assertDatabaseMissing('post_tag', ['tag_id' => $tag->id]);
    $this->assertDatabaseHas('posts', ['id' => $post->id]);
});
