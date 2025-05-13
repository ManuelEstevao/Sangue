<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;      


class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'tipo_usuario' => 'doador',
                'email'        => 'joao.silva@doador.com',
                'password'     => Hash::make(str_repeat('1', 8)),
                'created_at'   => now()->subMonths(1),
                'updated_at'   => now()->subMonths(1),
            ],
            [
                'tipo_usuario' => 'doador',
                'email'        => 'maria.santos@doador.com',
                'password'     => Hash::make(str_repeat('1', 8)),
                'created_at'   => now()->subMonths(1)->subDay(),
                'updated_at'   => now()->subMonths(1)->subDay(),
            ],
        ]);
    }
}
