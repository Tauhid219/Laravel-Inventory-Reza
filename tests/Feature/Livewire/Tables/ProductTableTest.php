<?php

namespace Tests\Feature\Livewire\Tables;

use App\Livewire\Tables\ProductTable;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProductTableTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        $user = $this->createUser();

        Role::findOrCreate('super-admin', 'web');
        $user->assignRole('super-admin');

        $this->actingAs($user);

        Livewire::test(ProductTable::class)
            ->assertStatus(200);
    }
}
