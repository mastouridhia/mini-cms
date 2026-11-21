<?php

use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

beforeEach(function () {
    $this->withoutVite();
});

it('redirects a guest from the back office to login', function () {
    $this->get(route('admin.tags.index'))
        ->assertRedirect(route('login'));
});

it('forbids the category and tag back office to an author', function () {
    $author = User::factory()->create();

    $this->actingAs($author)
        ->get(route('admin.categories.index'))
        ->assertForbidden()
        ->assertSee('Réservé aux administrateurs.');

    $this->actingAs($author)
        ->get(route('admin.tags.index'))
        ->assertForbidden()
        ->assertSee('Réservé aux administrateurs.');
});

it('forbids an author to create a tag', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('admin.tags.store'), ['name' => 'Piratage', 'slug' => 'piratage'])
        ->assertForbidden();

    $this->assertDatabaseMissing('tags', ['slug' => 'piratage']);
});

it('forbids an author to delete a tag', function () {
    $tag = Tag::factory()->create();

    $this->actingAs(User::factory()->create())
        ->delete(route('admin.tags.destroy', $tag))
        ->assertForbidden();

    $this->assertDatabaseHas('tags', ['id' => $tag->id]);
});

it('opens the back office to an admin', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get(route('admin.categories.index'))->assertOk();
    $this->actingAs($admin)->get(route('admin.tags.index'))->assertOk();

    $this->actingAs($admin)
        ->get(route('home'))
        ->assertOk()
        ->assertSee(route('admin.categories.index'))
        ->assertSee(route('admin.tags.index'));
});

it('hides the back office links from an author', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('home'))
        ->assertOk()
        ->assertDontSee(route('admin.categories.index'))
        ->assertDontSee(route('admin.tags.index'));
});

it('grants manage-tags to admins only', function () {
    expect(Gate::forUser(User::factory()->admin()->create())->allows('manage-tags'))->toBeTrue();
    expect(Gate::forUser(User::factory()->create())->allows('manage-tags'))->toBeFalse();
});
