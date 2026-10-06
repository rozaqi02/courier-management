<?php

namespace Tests\Feature;

use App\Models\Courier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CourierTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_list_couriers_with_pagination(): void
    {
        Courier::factory()->count(15)->create();

        $response = $this->getJson('/api/couriers');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'current_page',
                'per_page',
                'total',
            ]);
    }

    #[Test]
    public function it_can_search_couriers_by_name(): void
    {
        Courier::factory()->create(['name' => 'Budiono Hadi Agung', 'level' => 3]);
        Courier::factory()->count(5)->create();

        $response = $this->getJson('/api/couriers?search=Budiono');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);
        $this->assertStringContainsString('Budiono', $data[0]['name']);
    }

    #[Test]
    public function it_can_filter_couriers_by_level(): void
    {
        Courier::factory()->create(['name' => 'Level Two', 'level' => 2]);
        Courier::factory()->create(['name' => 'Level Three', 'level' => 3]);
        Courier::factory()->create(['name' => 'Level One', 'level' => 1]);

        $response = $this->getJson('/api/couriers?level=2,3');

        $response->assertStatus(200);
        $data = $response->json('data');
        foreach ($data as $courier) {
            $this->assertContains($courier['level'], [2, 3]);
        }
    }

    #[Test]
    public function it_can_sort_couriers_by_name(): void
    {
        Courier::factory()->create(['name' => 'Zara']);
        Courier::factory()->create(['name' => 'Ahmad']);
        Courier::factory()->create(['name' => 'Budi']);

        $response = $this->getJson('/api/couriers?sort=name');

        $response->assertStatus(200);
        $data = $response->json('data');
        $names = array_column($data, 'name');
        $sorted = $names;
        sort($sorted);
        $this->assertEquals($sorted, $names);
    }

    #[Test]
    public function it_can_sort_couriers_by_created_at(): void
    {
        Courier::factory()->count(5)->create();

        $response = $this->getJson('/api/couriers?sort=created_at');

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
    }

    #[Test]
    public function it_can_create_a_courier(): void
    {
        $data = [
            'name'          => 'Ahmad Fauzi',
            'phone'         => '08123456789',
            'email'         => 'ahmad@example.com',
            'level'         => 2,
            'vehicle_type'  => 'motorcycle',
            'vehicle_plate' => 'B 5678 ABC',
            'address'       => 'Jakarta Timur',
            'status'        => 'active',
        ];

        $response = $this->postJson('/api/couriers', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Ahmad Fauzi']);

        $this->assertDatabaseHas('couriers', ['email' => 'ahmad@example.com']);
    }

    #[Test]
    public function it_validates_required_fields_on_create(): void
    {
        $response = $this->postJson('/api/couriers', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'level']);
    }

    #[Test]
    public function it_validates_level_range_on_create(): void
    {
        $response = $this->postJson('/api/couriers', [
            'name'  => 'Test Courier',
            'level' => 6, // invalid, max is 5
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['level']);
    }

    #[Test]
    public function it_can_show_a_courier(): void
    {
        $courier = Courier::factory()->create();

        $response = $this->getJson('/api/couriers/' . $courier->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $courier->id]);
    }

    #[Test]
    public function it_can_update_a_courier(): void
    {
        $courier = Courier::factory()->create(['name' => 'Old Name']);

        $response = $this->putJson('/api/couriers/' . $courier->id, [
            'name'  => 'New Name',
            'level' => 4,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'New Name']);

        $this->assertDatabaseHas('couriers', ['id' => $courier->id, 'name' => 'New Name']);
    }

    #[Test]
    public function it_validates_fields_on_update(): void
    {
        $courier = Courier::factory()->create();

        $response = $this->putJson('/api/couriers/' . $courier->id, [
            'level' => 10, // invalid
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['level']);
    }

    #[Test]
    public function it_can_delete_a_courier(): void
    {
        $courier = Courier::factory()->create();
        $id = $courier->id;

        $response = $this->deleteJson('/api/couriers/' . $id);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Courier deleted successfully.']);

        $this->assertDatabaseMissing('couriers', ['id' => $id]);
    }
}
