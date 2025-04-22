<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RespostaSolicitacao;
use App\Models\Solicitacao;
use App\Models\Centro;
use App\Models\Doacao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Estoque;
use Carbon\Carbon; 


class SolicitacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $centroLogado = Auth::user()->centro;
        $hoje = Carbon::today();
            
        // 1. Obter tipos sanguíneos em estoque do centro logado
        $tiposEstoqueCentro = $centroLogado->estoque()
        ->where('quantidade', '>', 0)
        ->pluck('tipo_sanguineo');

    // 2. Solicitações do próprio centro
    $solicitacoesProprias = Solicitacao::with(['centroSolicitante', 'respostas'])
        ->where('id_centro', $centroLogado->id_centro);

    // 3. Solicitações externas compatíveis
    $solicitacoesExternas = Solicitacao::where('id_centro', '!=', $centroLogado->id_centro)
        ->whereIn('tipo_sanguineo', $tiposEstoqueCentro)
        ->where(function($query) use ($hoje, $centroLogado) {
            $query->where('status', 'Pendente') // Sem prazo para Pendentes
                ->orWhere(function($q) use ($hoje, $centroLogado) {
                    // Apenas para Atendidas: prazo válido + participação do centro
                    $q->where('status', 'Atendida')
                      ->whereDate('prazo', '>=', $hoje)
                      ->whereHas('respostas', function($subQuery) use ($centroLogado) {
                          $subQuery->where('id_centro', $centroLogado->id_centro);
                      });
                });
        });
    // 4. Unir e paginar
    $solicitacoes = $solicitacoesProprias->union($solicitacoesExternas)
        ->orderBy('created_at', 'desc')
        ->paginate(6);

        $respostas = RespostaSolicitacao::with(['solicitacao', 'centroDoador'])
        ->whereIn('id_sol', $solicitacoes->pluck('id_sol'))
        ->get();

        return view('centro.solicitacao', compact('solicitacoes','respostas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    
    $validated = $request->validate([
        'tipo_sanguineo' => 'required',
        'quantidade' => 'required|integer|min:1',
        'urgencia' => 'required',
        'prazo' => 'required|date',
        'motivo' => 'nullable|string',
    ]);

    $user = Auth::user();
    $centro = $user->centro;

    Solicitacao::create([
        'id_centro' => $centro->id_centro,
        'tipo_sanguineo' => $validated['tipo_sanguineo'],
        'quantidade' => $validated['quantidade'],
        'urgencia' => $validated['urgencia'],
        'prazo' => $validated['prazo'],
        'motivo' => $validated['motivo'] ?? null,
        'status' => 'Pendente',
    ]);


    return redirect()->back()->with('success', 'Solicitação criada com sucesso!');
}

public function dadosOferta($id)
{
    $solicitacao = Solicitacao::findOrFail($id);
    $centro = Auth::user()->centro;

    $estoque = Estoque::where('id_centro', $centro->id_centro)
                ->where('tipo_sanguineo', $solicitacao->tipo_sanguineo)
                ->first();

    return response()->json([
        'tipo_sanguineo' => $solicitacao->tipo_sanguineo,
        'estoque_disponivel' => $estoque ? ($estoque->quantidade - $estoque->quantidade_reservada) : 0
    ]);
}

public function responder(Request $request, $id)
{
    // Validação do input
    $validated = $request->validate([
        'quantidade' => 'required|integer|min:1'
    ]);

    $solicitacao = Solicitacao::findOrFail($id);
    $centro = Auth::user()->centro;

    // Recupera o estoque para o tipo sanguíneo solicitado
    $estoque = Estoque::where('id_centro', $centro->id_centro)
                ->where('tipo_sanguineo', $solicitacao->tipo_sanguineo)
                ->first();

    if (!$estoque) {
        return redirect()->back()->withErrors(['msg' => 'Estoque não encontrado para esse tipo sanguíneo.']);
    }
    
    // Calcula o estoque disponível: total menos reservado
    $disponivel = $estoque->quantidade - $estoque->quantidade_reservada;
    if ($validated['quantidade'] > $disponivel) {
        return redirect()->back()->withErrors(['quantidade' => 'Quantidade ofertada excede o estoque disponível.']);
    }

    DB::transaction(function () use ($validated, $solicitacao, $centro, $estoque) {
        // Registrar a resposta com status "Pendente"
        RespostaSolicitacao::create([
            'id_sol' => $solicitacao->id_sol,  
            'id_centro' => $centro->id_centro,
            'quantidade_aceita' => $validated['quantidade'],
            'status' => 'Aceito'
        ]);

        // Reservar a quantidade no estoque: incrementa o campo "quantidade_reservada"
        Estoque::where('id_centro', $centro->id_centro)
            ->where('tipo_sanguineo', $solicitacao->tipo_sanguineo)
            ->increment('quantidade_reservada', $validated['quantidade']);
    });

    return redirect()->route('centro.solicitacao.index')
                    ->with('success', 'Oferta enviada com sucesso! A quantidade foi reservada até a confirmação.');
}
public function detalhesResposta($idResposta)
{
    try {
        $resposta = RespostaSolicitacao::with([
            'solicitacao.centroSolicitante:id_centro,nome,latitude,longitude',
            'centroDoador:id_centro,nome,latitude,longitude,telefone,endereco',
            'centroDoador.estoque:id_centro,tipo_sanguineo,quantidade,quantidade_reservada'
        ])->findOrFail($idResposta);

        if (!Auth::user()->centro) {
            return response()->json([
                'error' => 'Usuário não associado a um centro.'
            ], 403);
        }

        $tipoSanguineo = $resposta->solicitacao->tipo_sanguineo;
        $estoque = $resposta->centroDoador->estoque
            ->where('tipo_sanguineo', $tipoSanguineo)
            ->first();

        return response()->json([
            'id_resposta'          => $resposta->id_resposta,  
            'tipo_sanguineo'       => $tipoSanguineo,
            'quantidade_solicitada'=> $resposta->solicitacao->quantidade,
            'quantidade_oferecida' => $resposta->quantidade_aceita,
            'prazo'                => $resposta->solicitacao->prazo,
            'status'               => strtolower($resposta->status),
            'data_reserva'         => $resposta->created_at,
            'centro_doador'        => [
                'nome'      => $resposta->centroDoador->nome,
                'latitude'  => $resposta->centroDoador->latitude,
                'longitude' => $resposta->centroDoador->longitude,
                'telefone'  => $resposta->centroDoador->telefone,
                'endereco'  => $resposta->centroDoador->endereco,
            ],
            'centro_solicitante'   => [
                'nome'      => $resposta->solicitacao->centroSolicitante->nome ?? 'Não disponível',
                'latitude'  => $resposta->solicitacao->centroSolicitante->latitude ?? 0,
                'longitude' => $resposta->solicitacao->centroSolicitante->longitude ?? 0,
            ],
            'estoque'              => [
                'disponivel' => $estoque ? $estoque->quantidade : 0,
                'reservado'  => $estoque ? $estoque->quantidade_reservada : 0,
            ],
            'eh_solicitante'       => Auth::user()->centro->id_centro === $resposta->solicitacao->id_centro,
        ]);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'error' => 'Resposta não encontrada.',
            'details' => "ID {$idResposta} não existe"
        ], 404);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erro interno ao carregar detalhes',
            'details' => $e->getMessage()
        ], 500);
    }
}


public function confirmarRecebimento($idResposta)
{
    
    try {
        DB::transaction(function () use ($idResposta) {
            $resposta = RespostaSolicitacao::with(['solicitacao', 'centroDoador'])
                ->findOrFail($idResposta);

            if (Auth::user()->centro->id_centro !== $resposta->solicitacao->id_centro) {
                abort(403, 'Sem permissão para confirmar esta transferência');
            }
            if ($resposta->status === 'Concluido') {
                return response()->json([
                    'success' => false,
                    'code' => 'already_confirmed',
                    'message' => 'Esta transferência já foi confirmada anteriormente'
                ], 409);
            }

            // Atualiza o status da resposta para "Concluido" e registra a data de confirmação
            $resposta->update([
                'status' => 'Concluido',
                'data_confirmacao' => now()
            ]);

            // Atualiza o estoque do centro doador
            $estoqueDoador = Estoque::where([
                'id_centro' => $resposta->centroDoador->id_centro,
                'tipo_sanguineo' => $resposta->solicitacao->tipo_sanguineo
            ])->firstOrFail();

            // Libera a reserva: decrementa a quantidade reservada
            $estoqueDoador->quantidade_reservada -= $resposta->quantidade_aceita;
            $estoqueDoador->quantidade -= $resposta->quantidade_aceita;
            $estoqueDoador->save();

            // Atualiza o estoque do centro solicitante (dono da solicitação)
            // Primeiro, obtém o registro de estoque do centro solicitante para o mesmo tipo sanguíneo,
            // ou cria um novo registro se não existir.
            $estoqueSolicitante = Estoque::firstOrCreate(
                [
                    'id_centro' => $resposta->solicitacao->id_centro,
                    'tipo_sanguineo' => $resposta->solicitacao->tipo_sanguineo
                ],
                [
                    'quantidade' => 0,
                    'quantidade_reservada' => 0
                ]
            );

            // Incrementa a quantidade efetivamente recebida
            $estoqueSolicitante->quantidade += $resposta->quantidade_aceita;
            $estoqueSolicitante->save();

            // Atualiza o status da solicitação com base na soma das respostas confirmadas
            $this->atualizarStatusSolicitacao($resposta->solicitacao);
        });

        return response()->json(['success' => true])
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erro na confirmação',
            'details' => $e->getMessage()
        ], 500);
    }
}

public function verificarStatusResposta($id)
{
    try {
        $resposta = RespostaSolicitacao::with('solicitacao')
            ->findOrFail($id);

        // Verificar permissão
        if (Auth::user()->centro->id_centro !== $resposta->solicitacao->id_centro) {
            return response()->json([
                'error' => 'Acesso não autorizado'
            ], 403);
        }

        return response()->json([
            'confirmado' => $resposta->status === 'Concluido',
            'data_confirmacao' => $resposta->data_confirmacao
        ])->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'error' => 'Resposta não encontrada'
        ], 404);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erro interno do servidor',
            'details' => $e->getMessage()
        ], 500);
    }
}

 /**
     * Atualiza o status da solicitação com base na soma das respostas confirmadas.
     * Se o total confirmado for igual ou maior que a quantidade solicitada → 'Atendida'
     * Se for maior que zero → 'Parcial'
     * Caso contrário → 'Pendente'
     */
    private function atualizarStatusSolicitacao(Solicitacao $solicitacao)
    {
        $solicitacao->load('respostas');
        $totalConfirmado = $solicitacao->respostas->where('status', 'Concluido')->sum('quantidade_aceita');
    
        if ($totalConfirmado >= $solicitacao->quantidade) {
            $solicitacao->status = 'Atendida';
        } elseif ($totalConfirmado > 0) {
            $solicitacao->status = 'Parcial';
        } else {
            $solicitacao->status = 'Pendente';
        }
    
        $solicitacao->save();
    }

    public function listarRespostas($id)
{
    try {
        $solicitacao = Solicitacao::with(['respostas.centroDoador', 'centroSolicitante'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $solicitacao->respostas->map(function($resposta) use ($solicitacao) {
                return [
                    'id_resposta' => $resposta->id_resposta,
                    'quantidade_aceita' => $resposta->quantidade_aceita,
                    'centro_doador' => [
                        'nome' => $resposta->centroDoador->nome,
                        'endereco' => $resposta->centroDoador->endereco,
                        'telefone' => $resposta->centroDoador->telefone,
                        'latitude' => $resposta->centroDoador->latitude,
                        'longitude' => $resposta->centroDoador->longitude,
                    ],
                    'solicitacao' => [
                        'tipo_sanguineo' => $solicitacao->tipo_sanguineo,
                        'quantidade' => $solicitacao->quantidade,
                        'prazo' => $solicitacao->prazo,
                        'centro_solicitante' => [
                            'nome' => $solicitacao->centroSolicitante->nome,
                            'latitude' => $solicitacao->centroSolicitante->latitude,
                            'longitude' => $solicitacao->centroSolicitante->longitude,
                        ]
                    ]
                ];
            })
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro ao carregar respostas: ' . $e->getMessage()
        ], 500);
    }
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    $solicitacao = Solicitacao::findOrFail($id);
    return response()->json($solicitacao);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $solicitacao = Solicitacao::findOrFail($id);
    $centroUsuario = Auth::user()->centro;

    // Verificação reforçada de autorização
    if ($solicitacao->id_centro != $centroUsuario->id_centro) {
        abort(403, 'Você não é o solicitante desta requisição');
    }

    $validated = $request->validate([
        'tipo_sanguineo' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
        'quantidade' => 'required|integer|min:1', 
        'urgencia' => 'required', 
        'prazo' => [
            'required',
            'date',
            function ($attribute, $value, $fail) use ($solicitacao) {
                $novaData = Carbon::parse($value);
                if ($novaData->lt(now())) {
                    $fail('O prazo não pode ser anterior à data atual');
                }
            }
        ],
        'motivo' => 'required|string|max:500',
    ]);

    // Converter formato de data para o MySQL
    $validated['prazo'] = Carbon::parse($validated['prazo'])->format('Y-m-d H:i:s');

    try {
        $solicitacao->update($validated);
        return redirect()->route('centro.solicitacao')
                        ->with('success', 'Solicitação atualizada com sucesso!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Erro ao atualizar a doação: ' . $e->getMessage());
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idsol)
{
    try {
        $solicitacao = Solicitacao::findOrFail($idsol);
      
        // Verificar permissões (exemplo)
        if ($solicitacao->id_centro != auth()->user()->centro->id_centro) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso não autorizado'
            ], 403);
        }

        $solicitacao->delete();

        return response()->json([
            'success' => true,
            'message' => 'Solicitação excluída com sucesso'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro ao excluir solicitação: ' . $e->getMessage()
        ], 500);
    }
}
}
