<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use Illuminate\Support\Facades\Auth;
use App\Models\Doacao;
use App\Models\Centro;
use App\Models\Doador;
use App\Models\Notificacao;
use Carbon\Carbon; 
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Writer;

class DashDadorController extends Controller
{
    public function index()
    {
        // Obtém o usuário autenticado
        $user = Auth::user();
        $doador = Doador::where('id_user', $user->id_user)->first();
        $doadorId = Auth::id();
        $doador = Auth::user()->doador;

    
        // Se o usuário ainda não é um doador, evita erro
        if (!$doador) {
            return redirect()->route('home')->with('error', 'Doador não encontrado.');
        }

        $centros = Centro::all();

        // Obtém a próxima doação agendada
        $proximaDoacao = Agendamento::where('id_doador', $doador->id_doador)
            ->whereDate('data_agendada', '>=', now()->toDateString()) 
            ->where('status', 'Agendado')
            ->orderBy('data_agendada', 'asc')
            ->first();


           
            

        // Doações através do relacionamento com agendamento
        $doacoesQuery = Doacao::whereHas('agendamento', function($query) use ($doador) {
            $query->where('id_doador', $doador->id_doador);
        });

        // Total de doações aprovadas
        $totalDoacoes = $doacoesQuery->clone()
            ->where('status', 'Aprovado')
            ->count();

        // Obtém a última doação realizada
        $ultimaDoacao = $doacoesQuery->clone()
            ->where('status', 'Aprovado')
            ->orderByDesc('data_doacao')
            ->first();

        // 🔹 Definir intervalo mínimo baseado no gênero
        $intervaloMinimo = ($doador->genero == 'Masculino') ? 90 : 120;

        // Definir a próxima data possível de doação com base na última
        $proximaDataPermitida = $ultimaDoacao ? Carbon::parse($ultimaDoacao->data_doacao)->addDays($intervaloMinimo) : null;

        // Obtém os últimos agendamentos do usuário
        $agendamentos = Agendamento::where('id_doador', $doador->id_doador)
            ->latest('data_agendada') 
            ->paginate(2);
            
            $notificacoes = Auth::user()->notificacoes()
        ->where('lida', false)
        ->with(['agendamento.centro'])
        ->orderBy('data_envio', 'desc')
        ->get();

       $qrData = [
    'doador' => [
        'nome_completo' => $doador->nome,
        'tipo_sanguineo' => $doador->tipo_sanguineo,
        'total_doacoes' => $doador->doacoes->where('status', 'Aprovado')->count()
    ],
    'historico_doacoes' => $doador->doacoes->sortByDesc('data_doacao')
        ->take(10)
        ->map(function($doacao) {
            return [
                'data_doacao' => $doacao->data_doacao->isoFormat('DD [de] MMMM [de] YYYY'), // Ex: 15 de março de 2024
                'local' => $doacao->agendamento->centro->nome,
                'volume' => $doacao->volume_coletado . 'ml'
            ];
        })->values()->toArray()
];

// Configuração para codificação correta
$qrContent = json_encode($qrData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
   $rendererStyle = new RendererStyle(450, 5); // Tamanho e margem
$renderer = new ImageRenderer($rendererStyle, new SvgImageBackEnd());
$writer = new Writer($renderer);

// Gerar QR Code com dados compactados

$qrCode = base64_encode($writer->writeString($qrContent));

        // Retorna a view com os dados
        return view('dador.dashbord', [
            'proximaDoacao' => $proximaDoacao,
            'totalDoacoes' => $totalDoacoes,
            'ultimaDoacao' => $ultimaDoacao ? $ultimaDoacao->data_doacao : null,
            'proximaDataPermitida' => $proximaDataPermitida,
            'agendamentos' => $agendamentos,
            'centros' => $centros,
            'notificacoes'=> $notificacoes,
            'qrCode' => $qrCode,
            'doador' => $doador

        ]);
    }

    public function verificarDoacao($codigo)
{
    try {
        $id_doador = $this->validarCodigo($codigo);
        $doador = Doador::with(['doacoes' => function($query) {
            $query->orderByDesc('data_doacao')
                  ->with('agendamento.centro');
        }])->findOrFail($id_doador);

        return response()->json([
            'nome' => $doador->nome,
            'tipo_sanguineo' => $doador->tipo_sanguineo,
            'historico' => $doador->doacoes->map(function($doacao) {
                return [
                    'data' => $doacao->data_doacao->format('d/m/Y'),
                    'local' => $doacao->agendamento->centro->nome,
                    'volume' => $doacao->volume_coletado . 'ml'
                ];
            })
        ]);
    } catch (\Exception $e) {
        return response()->json(['erro' => $e->getMessage()], 404);
    }
}

private function validarCodigo($codigo)
{
    $doadores = Doador::all();
    
    foreach ($doadores as $doador) {
        $hash = hash_hmac('sha256', $doador->id_doador, config('app.key'));
        if (hash_equals($hash, $codigo)) {
            return $doador->id_doador;
        }
    }
    
    throw new \Exception('Código inválido');
}
}
