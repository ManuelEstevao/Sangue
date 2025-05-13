<?php

namespace Database\Seeders;
use App\Models\Doador;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;     
use Illuminate\Support\Facades\Hash; 

class DoadorSeeder extends Seeder
{
    public function run(): void
    {
        // Buscando os IDs dos dois usuários que criamos
        $users = DB::table('users')
            ->whereIn('email', [
                'joao.silva@doador.com',
                'maria.santos@doador.com'
            ])
            ->pluck('id_user');

        // Criar um doador para cada usuário
        foreach ($users as $idx => $userId) {
            DB::table('doador')->insert([
                'numero_bilhete'   => 'BILH'.str_pad($idx+1, 10, '0', STR_PAD_LEFT),
                'nome'             => $idx===0 ? 'João Silva' : 'Maria Santos',
                'data_nascimento'  => $idx===0 ? '1985-04-10' : '1992-08-25',
                'genero'           => $idx===0 ? 'Masculino' : 'Feminino',
                'tipo_sanguineo'   => $idx===0 ? 'O+' : 'A-',
                'telefone'         => $idx===0 ? '923123456' : '923654321',
                'peso'             => $idx===0 ? 78.5 : 65.2,
                'endereco'         => $idx===0 ? 'Av. República, Luanda' : 'Rua das Flores, Luanda',
                'foto'             => null,
                'data_cadastro'    => now()->subWeeks(3)->toDateTimeString(),
                'id_user'          => $userId,
            ]);
        }
    }
}
