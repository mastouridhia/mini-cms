<?php

use App\Models\Post;
use App\Models\User;

beforeEach(function () {
    $this->withoutVite();
});

it('shows the styled 404 page inside the site layout', function () {
    $this->get('/page-qui-n-existe-pas')
        ->assertNotFound()
        ->assertSee('Page introuvable')
        ->assertSee('Mini-CMS - Atelier Framework Côté Serveur');
});

it('shows the styled 503 page with the read-only message', function () {
    config(['site.read_only' => true]);

    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.categories.store'), ['name' => 'Test', 'slug' => 'test'])
        ->assertStatus(503)
        ->assertSee('Service indisponible')
        ->assertSee('Le site est en lecture seule pour le moment.');
});

it('shows the styled 403 page in French', function () {
    $post = Post::factory()->for(User::factory())->create();

    $this->actingAs(User::factory()->create())
        ->get(route('posts.edit', $post))
        ->assertForbidden()
        ->assertSee('Accès refusé')
        ->assertSee("Vous n'avez pas le droit d'effectuer cette action.");
});
