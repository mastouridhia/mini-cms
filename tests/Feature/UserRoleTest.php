<?php

use App\Models\User;

beforeEach(function () {
    $this->withoutVite();
});

it('lets an admin promote an author', function () {
    $admin = User::factory()->admin()->create();
    $author = User::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee($author->email);

    $this->actingAs($admin)
        ->patch(route('admin.users.role', $author), ['role' => 'admin'])
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success');

    expect($author->fresh()->isAdmin())->toBeTrue();
});

it('forbids an admin to change their own role', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->patch(route('admin.users.role', $admin), ['role' => 'author'])
        ->assertForbidden();

    expect($admin->fresh()->isAdmin())->toBeTrue();
});
