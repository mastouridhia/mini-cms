<?php

use App\Models\Post;

beforeEach(function () {
    $this->withoutVite();
});

function postData(array $overrides = []): array
{
    return array_merge([
        'title' => 'Article de test',
        'slug' => 'article-de-test',
        'excerpt' => 'Un extrait court.',
        'body' => 'Contenu assez long pour passer la validation.',
        'is_published' => '0',
    ], $overrides);
}

it('creates a post from the form', function () {
    $this->post(route('posts.store'), postData())
        ->assertRedirect();

    $this->assertDatabaseHas('posts', [
        'title' => 'Article de test',
        'slug' => 'article-de-test',
        'excerpt' => 'Un extrait court.',
    ]);
});

it('shows the create form', function () {
    $this->get(route('posts.create'))
        ->assertOk()
        ->assertSee('Créer l’article');
});

it('does not create a post without a title', function () {
    $this->post(route('posts.store'), postData(['title' => '']))
        ->assertRedirect();

    $this->assertDatabaseMissing('posts', ['slug' => 'article-de-test']);
});

it('shows the edit form with the current values', function () {
    $post = Post::create(postData([
        'title' => 'Titre initial',
        'excerpt' => 'Extrait initial',
        'body' => 'Corps initial suffisamment long pour être validé.',
    ]));

    $this->get(route('posts.edit', $post))
        ->assertOk()
        ->assertSee('Modifier l’article')
        ->assertSee('value="Titre initial"', false)
        ->assertSee('value="article-de-test"', false)
        ->assertSee('Extrait initial')
        ->assertSee('Corps initial suffisamment long pour être validé.');
});

it('updates a post and keeps its own slug', function () {
    $post = Post::create(postData());

    $this->put(route('posts.update', $post), postData(['title' => 'Titre modifie']))
        ->assertRedirect(route('posts.show', $post));

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Titre modifie',
        'slug' => 'article-de-test',
    ]);
});

it('refuses the slug of another post', function () {
    Post::create(postData(['slug' => 'slug-deja-pris']));
    $post = Post::create(postData());

    $this->put(route('posts.update', $post), postData(['slug' => 'slug-deja-pris']))
        ->assertRedirect();

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'slug' => 'article-de-test',
    ]);
});

it('deletes a post', function () {
    $post = Post::create(postData());

    $this->delete(route('posts.destroy', $post))
        ->assertRedirect(route('posts.index'));

    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});

it('shows a confirmation before deleting a post', function () {
    $post = Post::create(postData());

    $this->get(route('posts.show', $post))
        ->assertOk()
        ->assertSee('Cette action est définitive', false)
        ->assertSee('Confirmer la suppression')
        ->assertSee(route('posts.destroy', $post), false)
        ->assertSee('value="DELETE"', false);
});

it('publishes a draft', function () {
    $post = Post::create(postData());

    $this->patch(route('posts.publication', $post))
        ->assertRedirect(route('posts.show', $post));

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'is_published' => true,
    ]);

    expect($post->fresh()->published_at)->not->toBeNull();
});