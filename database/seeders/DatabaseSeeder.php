<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\JobPosition;
use App\Models\TrainingType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Analista', 'Assistente', 'Coordenador', 'Gerente'] as $position) {
            JobPosition::query()->firstOrCreate(['name' => $position], ['active' => true]);
        }

        foreach (['Aperfeiçoamento', 'Capacitação', 'Certificação', 'Qualificação'] as $type) {
            TrainingType::query()->firstOrCreate(['name' => $type], ['active' => true]);
        }

        $email = env('TRAINOPS_ADMIN_EMAIL', 'admin@trainops.local');

        if (! User::query()->where('email', $email)->exists()) {
            $password = env('TRAINOPS_ADMIN_PASSWORD') ?: Str::password(24);

            User::query()->create([
                'name' => env('TRAINOPS_ADMIN_NAME', 'Administrador TrainOps'),
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make($password),
                'role' => UserRole::Admin,
                'department' => 'Administração',
                'active' => true,
            ]);

            $this->command?->warn("Administrador criado: {$email}");
            $this->command?->warn("Senha inicial (guarde agora): {$password}");
        }
    }
}
