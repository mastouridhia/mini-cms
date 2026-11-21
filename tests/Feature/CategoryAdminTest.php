<?php

use App\Models\Category;
use App\Models\Post;

beforeEach(function () {
    $this->withoutVite();
});

it('lists the categories with their post count', function () {
    $category = Category::factory()->create(['name' => 'Tutoriels']);
    Post::factory(2)->create(['category_id' => $category->id]);

    $this->get(route('admin.categories.index'))
        ->assertOk()
        ->assertSee('Tutoriels')
        ->assertSee('2');
});

it('creates a category and shows a flash message', function () {
    $this->post(route('admin.categories.store'), ['name' => 'Annonces', 'slug' => 'annonces'])
        ->assertRedirect(route('admin.categories.index'))
        ->assertSessionHas('success', 'Catégorie créée.');

    $this->assertDatabaseHas('categories', ['name' => 'Annonces', 'slug' => 'annonces']);
});

it('shows validation errors in French', function () {
    $this->post(route('admin.categories.store'), ['name' => '', 'slug' => ''])
        ->assertSessionHasErrors([
            'name' => 'Le champ nom est obligatoire.',
            'slug' => 'Le champ identifiant URL est obligatoire.',
        ]);

    expect(Category::count())->toBe(0);
});

it('refuses a slug already used by another category', function () {
    Category::factory()->create(['slug' => 'actualites']);

    $this->post(route('admin.categories.store'), ['name' => 'Nouvelles', 'slug' => 'actualites'])
        ->assertSessionHasErrors(['slug' => 'Ce slug est déjà utilisé par une autre catégorie.']);
});

it('keeps its own slug on update', function () {
    $category = Category::factory()->create(['name' => 'Ancien nom', 'slug' => 'garde-son-slug']);

    $this->put(route('admin.categories.update', $category), ['name' => 'Nouveau nom', 'slug' => 'garde-son-slug'])
        ->assertRedirect(route('admin.categories.index'))
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Nouveau nom']);
});

it('keeps the posts when a category is deleted', function () {
    $category = Category::factory()->create();
    $post = Post::factory()->create(['category_id' => $category->id]);

    $this->delete(route('admin.categories.destroy', $category))
        ->assertRedirect(route('admin.categories.index'))
        ->assertSessionHas('success', 'Catégorie supprimée.');

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    $this->assertDatabaseHas('posts', ['id' => $post->id, 'category_id' => null]);
});

it('blocks category writes when the site is read-only', function () {
    config(['site.read_only' => true]);

    $this->post(route('admin.categories.store'), ['name' => 'Bloquée', 'slug' => 'bloquee'])
        ->assertStatus(503);

    $this->assertDatabaseMissing('categories', ['slug' => 'bloquee']);
});

it('still lists the categories when the site is read-only', function () {
    config(['site.read_only' => true]);
    Category::factory()->create(['name' => 'Lisible']);

    $this->get(route('admin.categories.index'))
        ->assertOk()
        ->assertSee('Lisible');
});

it('shows the category not found page with the available categories', function () {
    Category::factory()->create(['name' => 'Tutoriels']);

    $this->get('/posts/categorie/inexistante')
        ->assertNotFound()
        ->assertSee('Catégorie introuvable')
        ->assertSee('Tutoriels');
});
