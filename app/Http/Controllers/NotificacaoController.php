<?php

namespace App\Http\Controllers;
use App\Models\Notificacao;

use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
    public function index()
    {
        $notificacoes = Notificacao::where('user_id', Auth::id())
        ->latest()
        ->take(3)
        ->get();
    
    $naoLidas = Notificacao::where('user_id', Auth::id())
        ->where('lida', false)
        ->count();
    
    return view('centro.main', compact('notificacoes', 'naoLidas'));
    }
}
