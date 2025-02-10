<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    // Mostra a view de login
    public function index()
    {
        return view('login');
    }

    // Lógica para autenticação do usuário
    public function login(Request $request)
    {
        // Validação dos campos enviados
        $credentials = $request->validate([
            'email' => 'required|email',
            'senha' => 'required',
        ]);

        // Mapeia o campo "senha" para "password" para compatibilidade com Auth::attempt
        $loginData = [
            'email'    => $credentials['email'],
            'password' => $credentials['senha'],
        ];

        // Tenta autenticar o usuário
        if (Auth::attempt($loginData)) {
            // Regenera a sessão para evitar session fixation
            $request->session()->regenerate();

            // Redireciona para a página desejada (ex.: dashboard)
            return redirect()->intended('dashboard')->with('success', 'Login efetuado com sucesso!');
        }

        // Se a autenticação falhar, retorna para o formulário com mensagem de erro
        return back()->withErrors([
            'error' => 'Email ou senha inválidos',
        ])->withInput($request->only('email'));
    }
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalida a sessão atual
        $request->session()->invalidate();

        // Regenera o token CSRF
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Sessão terminada com sucesso!');
    }
}
