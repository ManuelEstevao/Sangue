<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'senha' => 'required',
        ]);

        $loginData = [
            'email'    => $credentials['email'],
            'password' => $credentials['senha'],
        ];

        if (Auth::attempt($loginData)) {
            $request->session()->regenerate();
            
            // Redirecionamento baseado no tipo de usuário
            $user = Auth::user();
            
            if ($user->tipo_usuario === 'centro') {
                return redirect()->route('centro.Dashbord');
            } elseif ($user->tipo_usuario === 'doador') {
                return redirect()->route('doador.Dashbord');
            }

            // Redirecionamento padrão caso o tipo não seja reconhecido
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'error' => 'Email ou senha inválidos',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Sessão terminada com sucesso!');
    }
}