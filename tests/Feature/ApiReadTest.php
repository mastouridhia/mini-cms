<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

it('lists published posts with the expected shape', function () {
    Post::factory(2)->published()->for(User::factory())->create();
    Post::factory()->create();

    $this->getJson('/api/posts')
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonStructure([
            'data' => [['id', 'title', 'slug', 'excerpt', 'published_at', 'url', 'author', 'category', 'tags']],
            'links' => ['first', 'last', 'prev', 'next'],
            'meta' => ['current_page', 'per_page', 'total'],
        ])
        ->assertJsonMissingPath('data.0.user_id')
        ->assertJsonMissingPath('data.0.body');
});

it('filters posts by category', function () {
    $php = Category::factory()->create(['slug' => 'php']);
    $other = Category::factory()->create(['slug' => 'autre']);
    $post = Post::factory()->published()->create(['category_id' => $php->id]);
    Post::factory()->published()->create(['category_id' => $other->id]);

    $this->getJson('/api/posts?category=php')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $post->id)
        ->assertJsonPath('data.0.category.slug', 'php');
});

it('refuses an unknown category slug in French', function () {
    $this->getJson('/api/posts?category=inexistante')
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['category' => 'La valeur choisie pour catégorie est invalide.']);
});

it('lists categories with their published post count', function () {
    $category = Category::factory()->create(['name' => 'Laravel']);
    Post::factory(2)->published()->create(['category_id' => $category->id]);
    Post::factory()->create(['category_id' => $category->id]);

    $this->getJson('/api/categories')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Laravel')
        ->assertJsonPath('data.0.posts_count', 2);
});

it('returns a JSON 404 for an unknown category', function () {
    $this->getJson('/api/categories/999')
        ->assertNotFound()
        ->assertJsonStructure(['message']);
});
