<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Models\Questionario;
use App\Notifications\AgendamentoNotification;
use Illuminate\Validation\ValidationException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use PDF;

class QuestionarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id_agendamento)
    {
        try {
            // 1) Monte manualmente os booleanos (para não depender de validate())
            $fields = [
                'ja_doou_sangue',
                'problema_doacao_anterior',
                'tem_doenca_cronica',
                'fez_tatuagem_ultimos_12_meses',
                'fez_cirurgia_recente',
                'esta_gravida',
                'recebeu_transfusao_sanguinea',
                'tem_doenca_infecciosa',
                'usa_medicacao_continua',
                'tem_comportamento_de_risco',
                'teve_febre_ultimos_30_dias',
                'consumiu_alcool_ultimas_24_horas',
                'teve_malaria_ultimos_3meses',
                'nasceu_ou_viveu_angola',
                'esteve_internado'
            ];
            $data = ['id_agendamento' => $id_agendamento];
            foreach ($fields as $f) {
                $data[$f] = $request->has($f);
            }
    
            // 2) Persista
            Questionario::updateOrCreate(
                ['id_agendamento' => $id_agendamento],
                $data
            );
        
            $pdfUrl      = route('doador.questionario.comprovativo', $id_agendamento);
            $redirectUrl = route('direcoes', $id_agendamento);
    
            // 3) Retornar JSON com ambas as URLs
            return response()->json([
                'pdf_url'       => $pdfUrl,
                'redirect_url'  => $redirectUrl,
                'message'       => 'Pré Triagem concluída e comprovativo gerado com sucesso!'
            ]);
    
        } catch (\Throwable $e) {
            // loga a exceção completa
            \Log::error("Erro em QuestionarioController@store: {$e->getMessage()}", [
                'stack' => $e->getTraceAsString()
            ]);
            // devolve JSON de erro
            return response()->json([
                'message' => 'Erro interno ao processar questionário.'
            ], 500);
        }
    }
    
       

    public function comprovativo($id_agendamento)
{
    $agendamento = Agendamento::with(['doador', 'centro'])
        ->findOrFail($id_agendamento);

    // Gerar código de confirmação único
    $codigo = 'DSA-'.strtoupper(Str::random(6)).'-'.$agendamento->id_agendamento;

    $data = [
        'agendamento'        => $agendamento,
        'codigo_confirmacao' => $codigo,
    ];

    // Gera o PDF a partir da view Blade
    $pdf = Pdf::loadView('dador.pdf.comprovativo', $data)
        ->setPaper('a4')
        ->setOption('isHtml5ParserEnabled', true)
        ->setOption('isRemoteEnabled', true)
        ->setOption('defaultFont', 'DejaVu Sans')
        ->setOption('dpi', 150);

    return $pdf->stream("comprovativo_{$codigo}.pdf");
}

    
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
