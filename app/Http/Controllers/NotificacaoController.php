<?php

namespace App\Http\Controllers;
use App\Models\Notificacao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon; 

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

    public function marcarComoLida($notificacaoId)
    {
        $notificacao = Notificacao::where('id_user', Auth::id())
            ->findOrFail($notificacaoId);

        $notificacao->update([
            'lida' => true,
        ]);

        return response()->json(['success' => true]);
    }

        public function historico()
    {
        $notificacoes = Auth::user()->notificacoes()
            ->orderBy('data_envio', 'desc')
            ->paginate(15);

        return view('centro.notificacoes', compact('notificacoes'));
    }

    public function marcarTodasComoLidas()
{
    Notificacao::where('id_user', Auth::id())
        ->where('lida', false)
        ->update(['lida' => true]);

    return response()->json(['success' => true]);
}
    
}
