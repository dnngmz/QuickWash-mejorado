<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Machine;
use App\Models\Reservation;
use Carbon\Carbon;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_view_machines()
    {
        $student = User::factory()->create(['role' => 'student']);
        
        $response = $this->actingAs($student)->get('/machines');

        $response->assertStatus(200);
    }

    public function test_staff_cannot_view_machines_as_student()
    {
        $staff = User::factory()->create(['role' => 'staff']);
        
        $response = $this->actingAs($staff)->get('/machines');

        $response->assertStatus(403);
    }

    public function test_student_can_create_reservation()
    {
        $student = User::factory()->create(['role' => 'student']);
        $machine = Machine::create(['name' => 'Test Machine']);

        $response = $this->actingAs($student)->post('/reservations', [
            'machine_id' => $machine->id,
            'reservation_date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'garments_quantity' => 5,
        ]);

        $response->assertRedirect('/reservations');
        $this->assertDatabaseHas('reservations', [
            'user_id' => $student->id,
            'machine_id' => $machine->id,
        ]);
    }

    public function test_cannot_have_more_than_three_active_reservations()
    {
        $student = User::factory()->create(['role' => 'student']);
        $machine = Machine::create(['name' => 'Test Machine']);

        // Create 3 active reservations
        for ($i = 0; $i < 3; $i++) {
            Reservation::create([
                'user_id' => $student->id,
                'machine_id' => $machine->id,
                'reservation_date' => Carbon::tomorrow()->addDays($i)->format('Y-m-d'),
                'start_time' => '10:00',
                'end_time' => '11:00',
                'garments_quantity' => 5,
                'status' => 'pending',
            ]);
        }

        $response = $this->actingAs($student)->post('/reservations', [
            'machine_id' => $machine->id,
            'reservation_date' => Carbon::tomorrow()->addDays(3)->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'garments_quantity' => 5,
        ]);

        $response->assertSessionHasErrors(['error']);
    }

    public function test_cannot_overlap_reservations()
    {
        $student = User::factory()->create(['role' => 'student']);
        $student2 = User::factory()->create(['role' => 'student']);
        $machine = Machine::create(['name' => 'Test Machine']);

        Reservation::create([
            'user_id' => $student->id,
            'machine_id' => $machine->id,
            'reservation_date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'garments_quantity' => 5,
            'status' => 'pending',
        ]);

        // Overlapping reservation
        $response = $this->actingAs($student2)->post('/reservations', [
            'machine_id' => $machine->id,
            'reservation_date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '11:00',
            'end_time' => '13:00',
            'garments_quantity' => 5,
        ]);

        $response->assertSessionHasErrors(['error']);
    }
}
