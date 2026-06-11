<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_is_visible_without_login(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Rozliczaj wydatki w grupie prosto i przejrzyście.');
    }

    public function test_dashboard_is_visible_without_login(): void
    {
        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Zarządzaj grupami i wspólnymi rachunkami.');
    }

    public function test_groups_are_browsable_without_login(): void
    {
        $owner = User::factory()->create();
        $group = Group::create([
            'name' => 'Publiczna grupa',
            'description' => 'Widoczna bez logowania',
            'owner_id' => $owner->id,
        ]);
        $group->users()->attach($owner->id);

        $this->get(route('groups.index'))
            ->assertOk()
            ->assertSee('Publiczna grupa')
            ->assertDontSee('Dodaj nowa grupe');

        $this->get(route('groups.show', $group))
            ->assertOk()
            ->assertSee('Publiczna grupa')
            ->assertDontSee($owner->email)
            ->assertDontSee('Dodaj wydatek');
    }

    public function test_regular_user_does_not_see_admin_link(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertDontSee('Panel admina');
    }

    public function test_admin_sees_admin_link(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Panel admina');
    }

    public function test_regular_user_is_redirected_from_admin_panel_with_message(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error', 'Dostep tylko dla administratora. Zaloguj sie na konto admina.');
    }
}
