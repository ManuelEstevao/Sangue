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
    $request->validate([
        'email' => 'required|email',
        'senha' => 'required',
    ]);

    $credentials = [
        'email' => $request->email,
        'password' => $request->senha
    ];

    if(Auth::attempt($credentials, $request->remember)) {
        $request->session()->regenerate();
        
        $user = Auth::user();
        
        return match($user->tipo_usuario) {
            'centro' => redirect()->route('centro.Dashbord'),
            'doador' => redirect()->route('doador.Dashbord'),
            default => redirect()->intended('/')
        };
    }

    return back()->withInput()
        ->withErrors(['error' => 'Credenciais inválidas ou conta não encontrada']);
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Sessão terminada com sucesso!');
    }
}