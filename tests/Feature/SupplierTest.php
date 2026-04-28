<?php

namespace Tests\Feature;

use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cant_has_access()
    {
        $response = $this->get('suppliers/');

        $response
            ->assertStatus(302)
            ->assertRedirect('login/');
    }

    public function test_logged_user_has_access_to_url()
    {
        $this->withoutExceptionHandling();

        // Create Unit
        $this->createSupplier();
        $this->assertDatabaseCount('suppliers', 1)
            ->assertDatabaseHas('suppliers', ['name' => 'Thomann']);

        $user = $this->createAuthorizedUser(['view supplier']);
        $response = $this->actingAs($user)
            ->get('suppliers/');

        $response->assertStatus(200)
            ->assertViewIs('suppliers.index');
    }

    public function test_user_can_use_create_view()
    {
        $user = $this->createAuthorizedUser(['create supplier']);
        $response = $this->actingAs($user)->get('suppliers/create');

        $response->assertViewIs('suppliers.create');
    }

    public function test_user_can_see_edit_view()
    {
        $user = $this->createAuthorizedUser(['update supplier']);
        $supplier = $this->createSupplier();

        $response = $this->actingAs($user)->get('suppliers/'.$supplier->id.'/edit');

        $response
            ->assertStatus(200)
            ->assertViewIs('suppliers.edit');
    }

    public function test_user_can_see_show_view()
    {
        $user = $this->createAuthorizedUser(['view supplier']);
        $suppliers = $this->createSupplier();

        $response = $this->actingAs($user)->get('suppliers/'.$suppliers->id);

        $response
            ->assertStatus(200)
            ->assertViewIs('suppliers.show');
    }

    public function test_super_admin_can_delete_supplier_without_related_purchases()
    {
        $supplier = $this->createSupplier();

        $this->assertDatabaseHas('suppliers', ['name' => 'Thomann']);
        $this->assertDatabaseCount('suppliers', 1);

        $user = $this->createAuthorizedUser(['delete supplier']);
        $this->actingAs($user);

        $response = $this->delete('/suppliers/'. $supplier->id);

        $response->assertRedirect(route('suppliers.index'));
        $this->assertDatabaseCount('suppliers', 0);
    }

    public function test_supplier_with_related_purchases_cannot_be_deleted()
    {
        $supplier = $this->createSupplier();
        Purchase::factory()->create([
            'supplier_id' => $supplier->id,
        ]);

        $user = $this->createAuthorizedUser(['delete supplier']);

        $response = $this->actingAs($user)->delete('/suppliers/' . $supplier->id);

        $response
            ->assertRedirect(route('suppliers.index'))
            ->assertSessionHas('error', 'This supplier has related purchases. Deletion is not allowed.');

        $this->assertDatabaseCount('suppliers', 1);
        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id]);
    }
}
