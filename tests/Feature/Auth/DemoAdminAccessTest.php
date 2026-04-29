<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DemoAdminAccessTest extends TestCase
{
    use RefreshDatabase;

    private function createDemoAdminUser(): User
    {
        $role = Role::findOrCreate('demo-admin', 'web');

        return tap(User::factory()->create([
            'email' => 'demo-admin@reza-inventory.test',
            'username' => 'demo_admin',
        ]), function (User $user) use ($role): void {
            $user->assignRole($role);
        });
    }

    public function test_demo_admin_can_authenticate_from_the_demo_login_button(): void
    {
        $demoUser = $this->createDemoAdminUser();

        $response = $this->post(route('demo.login'));

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('demo_mode', true);
        $this->assertAuthenticatedAs($demoUser);
    }

    public function test_demo_login_fails_when_seeded_demo_user_is_missing(): void
    {
        $response = $this->from('/login')->post(route('demo.login'));

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('demo_login');
        $this->assertGuest();
    }

    public function test_demo_mode_cannot_update_profile(): void
    {
        $demoUser = $this->createDemoAdminUser();

        $response = $this
            ->actingAs($demoUser)
            ->withSession(['demo_mode' => true])
            ->patch('/profile', [
                'name' => 'Blocked Demo',
                'email' => 'blocked@example.com',
                'username' => 'blocked_demo',
            ]);

        $response->assertForbidden();
    }

    public function test_demo_mode_cannot_update_password(): void
    {
        $demoUser = $this->createDemoAdminUser();

        $response = $this
            ->actingAs($demoUser)
            ->withSession(['demo_mode' => true])
            ->put('/password', [
                'current_password' => 'password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response->assertForbidden();
    }

    public function test_demo_mode_cannot_export_products_even_with_view_permission(): void
    {
        $demoUser = $this->createDemoAdminUser();
        Permission::findOrCreate('view product', 'web');
        $demoUser->givePermissionTo('view product');

        $response = $this
            ->actingAs($demoUser)
            ->withSession(['demo_mode' => true])
            ->get('/products/export');

        $response->assertForbidden();
    }
}
