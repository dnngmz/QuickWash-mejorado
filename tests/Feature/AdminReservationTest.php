<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Machine;
use App\Models\Reservation;
use Carbon\Carbon;

class AdminReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_view_all_reservations()
    {
        $staff = User::factory()->create(['role' => 'staff']);
        
        $response = $this->actingAs($staff)->get('/admin/reservations');

        $response->assertStatus(200);
    }

    public function test_student_cannot_view_admin_reservations()
    {
        $student = User::factory()->create(['role' => 'student']);
        
        $response = $this->actingAs($student)->get('/admin/reservations');

        $response->assertStatus(403);
    }

    public function test_staff_can_update_reservation_status()
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $student = User::factory()->create(['role' => 'student']);
        $machine = Machine::create(['name' => 'Test Machine']);

        $reservation = Reservation::create([
            'user_id' => $student->id,
            'machine_id' => $machine->id,
            'reservation_date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'garments_quantity' => 5,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($staff)->patch("/admin/reservations/{$reservation->id}", [
            'status' => 'in_progress'
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('in_progress', $reservation->fresh()->status);
    }
}
