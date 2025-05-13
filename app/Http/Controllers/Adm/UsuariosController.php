<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
   

    // Lista de usuários
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('ADM.usuario', compact('users'));
    }

    // Formulário de criação
    public function create()
    {
        return view('ADM.usuarios.create');
    }

    // Armazena novo usuário
    public function store(Request $request)
    {
        $data = $request->validate([
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|confirmed|min:8',
            'tipo_usuario'  => 'required|in:doador,centro,admin',
        ]);

        User::create([
            'email'         => $data['email'],
            'password'      => bcrypt($data['password']),
            'tipo_usuario'  => $data['tipo_usuario'],
        ]);

        return redirect()->route('admin.usuarios.index')
                         ->with('success', 'Usuário criado com sucesso.');
    }

    // Formulário de edição
    public function edit(User $user)
    {
        return view('admin.usuarios.edit', compact('user'));
    }

    // Atualiza usuário existente
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'email'         => 'required|email|unique:users,email,'.$user->id_user.',id_user',
            'password'      => 'nullable|confirmed|min:8',
            'tipo_usuario'  => 'required|in:doador,centro,admin',
        ]);

        $user->email = $data['email'];
        $user->tipo_usuario = $data['tipo_usuario'];
        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }
        $user->save();

        return back()->with('success', 'Usuário atualizado com sucesso.');
    }

    // Remove usuário
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Usuário excluído com sucesso.');
    }
}
