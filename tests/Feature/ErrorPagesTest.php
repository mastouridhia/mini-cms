<?php

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
