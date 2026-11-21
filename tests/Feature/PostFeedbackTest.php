<?php

use App\Models\Post;
use App\Models\User;

beforeEach(function () {
    $this->withoutVite();
    $this->actingAs(User::factory()->admin()->create());
});

function feedbackPostData(array $overrides = []): array
{
    return array_merge([
        'title' => 'Article avec retour',
        'slug' => 'article-avec-retour',
        'body' => 'Contenu assez long pour passer la validation.',
        'is_published' => '1',
    ], $overrides);
}

it('shows a flash message after creating a post', function () {
    $this->followingRedirects()
        ->post(route('posts.store'), feedbackPostData())
        ->assertOk()
        ->assertSee('Article créé.');
});

it('shows a flash message after deleting a post', function () {
    $post = Post::factory()->create();

    $this->followingRedirects()
        ->delete(route('posts.destroy', $post))
        ->assertOk()
        ->assertSee('Article supprimé.');
});

it('shows the custom page for an unknown post', function () {
    $this->get('/posts/999')
        ->assertNotFound()
        ->assertSee('Article introuvable');
});

it('keeps the default 404 page for an unknown category', function () {
    $this->get('/posts/categorie/inexistante')
        ->assertNotFound()
        ->assertDontSee('Article introuvable');
});

it('shows a read-only banner and refuses writes when the site is read-only', function () {
    config(['site.read_only' => true]);

    $this->get(route('posts.index'))
        ->assertOk()
        ->assertSee('Site en lecture seule');

    $this->post(route('posts.store'), feedbackPostData())
        ->assertStatus(503);

    $this->assertDatabaseMissing('posts', ['slug' => 'article-avec-retour']);
});

it('lists recent published posts on the not found page', function () {
    $published = Post::factory()->published()->create(['title' => 'Article publié récent']);
    Post::factory()->create(['title' => 'Brouillon caché']);

    $this->get('/posts/999')
        ->assertNotFound()
        ->assertSee('Articles récents')
        ->assertSee($published->title)
        ->assertDontSee('Brouillon caché');
});
