<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\AnnualBudget;
use App\Models\Course;
use App\Models\TrainingType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TrainOpsSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register', [])->assertNotFound();
    }

    public function test_viewer_cannot_mutate_courses(): void
    {
        $viewer = User::factory()->create(['role' => UserRole::Viewer]);
        $type = TrainingType::query()->create(['name' => 'Certificação']);

        $this->actingAs($viewer)
            ->post('/courses', [
                'training_type_id' => $type->id,
                'name' => 'Segurança',
                'workload_hours' => 16,
                'active' => true,
            ])
            ->assertForbidden();
    }

    public function test_admin_can_create_employee_with_a_hashed_password(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($admin)
            ->post('/employees', [
                'name' => 'Pessoa Teste',
                'email' => 'pessoa@example.com',
                'department' => 'Operações',
                'job_position_id' => null,
                'role' => UserRole::Manager->value,
                'active' => true,
                'password' => 'SenhaMuitoForte@2026',
            ])
            ->assertSessionHasNoErrors();

        $employee = User::query()->where('email', 'pessoa@example.com')->firstOrFail();
        $this->assertNotSame('SenhaMuitoForte@2026', $employee->password);
        $this->assertTrue(Hash::check('SenhaMuitoForte@2026', $employee->password));
    }

    public function test_training_rejects_a_budget_from_another_year(): void
    {
        $manager = User::factory()->create(['role' => UserRole::Manager]);
        $employee = User::factory()->create();
        $type = TrainingType::query()->create(['name' => 'Aperfeiçoamento']);
        $course = Course::query()->create([
            'training_type_id' => $type->id,
            'name' => 'Laravel',
            'workload_hours' => 40,
            'active' => true,
        ]);
        $budget = AnnualBudget::query()->create(['year' => 2026, 'amount' => 10000]);

        $this->actingAs($manager)
            ->post('/trainings', [
                'employee_id' => $employee->id,
                'course_id' => $course->id,
                'annual_budget_id' => $budget->id,
                'institution' => 'Escola Técnica',
                'starts_at' => '2027-01-10',
                'ends_at' => '2027-01-20',
                'status' => 'planned',
                'registration_cost' => 100,
                'lodging_cost' => 0,
                'transport_cost' => 0,
                'transfer_cost' => 0,
                'daily_allowance_cost' => 0,
            ])
            ->assertSessionHasErrors('annual_budget_id');
    }

    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/login');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }
}
