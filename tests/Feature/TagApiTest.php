<?php

use App\Models\Post;
use App\Models\Tag;

it('lists the tags with their published post count', function () {
    $tag = Tag::factory()->create(['name' => 'Laravel']);
    Post::factory()->published()->create()->tags()->attach($tag);
    Post::factory()->create()->tags()->attach($tag);

    $this->getJson('/api/tags')
        ->assertOk()
        ->assertJsonStructure(['data' => [['id', 'name', 'slug', 'posts_count']]])
        ->assertJsonPath('data.0.name', 'Laravel')
        ->assertJsonPath('data.0.posts_count', 1);
});

it('filters the posts by tag', function () {
    $tag = Tag::factory()->create(['slug' => 'pest']);
    $tagged = Post::factory()->published()->create();
    $tagged->tags()->attach($tag);
    Post::factory()->published()->create();

    $this->getJson('/api/posts?tag=pest')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $tagged->id);
});

it('shows one tag', function () {
    $tag = Tag::factory()->create(['slug' => 'blade']);

    $this->getJson("/api/tags/{$tag->id}")
        ->assertOk()
        ->assertJsonPath('data.slug', 'blade')
        ->assertJsonPath('data.posts_count', 0);
});

it('returns a JSON 404 for an unknown tag', function () {
    $this->getJson('/api/tags/999999')
        ->assertNotFound()
        ->assertJsonStructure(['message']);
});
