<?php

namespace Tests\Feature\Livewire\Tables;

use App\Livewire\Tables\CustomerTable;
use Livewire\Livewire;
use Tests\TestCase;

class CustomerTableTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        $this->actingAs($this->createUser());

        Livewire::test(CustomerTable::class)
            ->assertStatus(200);
    }
}
