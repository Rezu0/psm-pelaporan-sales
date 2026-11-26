<?php

namespace Database\Seeders;

use App\Models\Segment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Garmen', 'Furnitur', 'AMDK', 'UMKM'] as $nama) {
            Segment::create(['nama' => $nama]);
        }

        foreach (['admin', 'owner', 'sales'] as $role) {
            User::create([
                'uuid'      => Str::uuid(),
                'name'      => ucfirst($role),
                'email'     => $role . '@test.com',
                'password'  => Hash::make('password'),
                'role'      => $role,
                'is_active' => true,
            ]);
        }
    }
}