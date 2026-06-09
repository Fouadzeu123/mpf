<?php

namespace Database\Seeders;

use App\Enums\MemberStatus;
use App\Enums\UserRole;
use App\Models\Member;
use App\Models\User;
use App\Services\MemberCodeService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ChurchSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'ngoulafoyanguemonesime@gmail.com'],
            [
                'name' => 'Apotre Onesime Ngoula Foyanguem',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin,
                'email_verified_at' => now(),
            ],
        );

        User::updateOrCreate(
            ['email' => 'fouadzeuboris@gmail.com'],
            [
                'name' => 'Fouadzeu Boris',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin,
                'email_verified_at' => now(),
            ],
        );

        User::updateOrCreate(
            ['email' => 'secretaire@mpf.local'],
            [
                'name' => 'Secrétaire MPF',
                'password' => Hash::make('password'),
                'role' => UserRole::Secretaire,
                'email_verified_at' => now(),
            ],
        );

        User::updateOrCreate(
            ['email' => 'protocole@mpf.local'],
            [
                'name' => 'Protocole MPF',
                'password' => Hash::make('password'),
                'role' => UserRole::Protocole,
                'email_verified_at' => now(),
            ],
        );

        User::updateOrCreate(
            ['email' => 'ancienne@mpf.local'],
            [
                'name' => 'Ancienne d\'église MPF',
                'password' => Hash::make('password'),
                'role' => UserRole::Ancienne,
                'email_verified_at' => now(),
            ],
        );

        if (Member::count() === 0) {
            $codeService = app(MemberCodeService::class);
            $code = 'MEM-000001';

            Member::create([
                'member_code' => $code,
                'first_name' => 'Jean',
                'last_name' => 'Dupont',
                'birth_date' => '1991-05-15',
                'gender' => 'M',
                'profession' => 'Enseignant',
                'phone' => '677123456',
                'department' => 'Chorale',
                'status' => MemberStatus::Active,
                'qr_code' => $code,
                'password' => Hash::make('mpf-000001'),
                'address_description' => 'Quartier Bastos, Yaoundé',
            ]);
        }
    }
}
