<?php

namespace App\Http\Controllers;

use App\Models\Doador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CadastroController extends Controller
{
    public function index()
    {
        $tiposSanguineos = Doador::getEnumValues('tipo_sanguineo');
        return view('cadastro', compact('tiposSanguineos'));
    }

    public function store(Request $request)
    {
        // Validação dos dados
        $validated = $request->validate([
            'bi'       => 'required|string|max:14|unique:doador,numero_bilhete',
            'nome'     => 'required|string|max:255',
            'data'     => 'required|date',
            'tisangue' => 'required|string',
            'email'    => 'required|email|unique:users,email',
            'contacto' => 'required',
            'senha'    => 'required|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            // 1. Criar o usuário
            $user = User::create([
                'email'        => $validated['email'],
                'password'     => Hash::make($validated['senha']),
                'tipo_usuario' => 'doador',
            ]);

            // 2. Criar o doador, mapeando os campos do formulário para os atributos do modelo
            Doador::create([
                'numero_bilhete'  => $validated['bi'],
                'nome'            => $validated['nome'],
                'data_nascimento' => $validated['data'],
                'tipo_sanguineo'  => $validated['tisangue'],
                'telefone'        => $validated['contacto'],
                'id_user'         => $user->id_user, // Conforme sua migração de users
            ]);

            // Confirma a transação
            DB::commit();

            // Autentica (loga) automaticamente o usuário recém-criado
            Auth::login($user);

            return redirect()->route('dador')->with('success', 'Cadastro realizado e usuário logado!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erro ao salvar dados: ' . $e->getMessage()])->withInput();
        }
    }
}
