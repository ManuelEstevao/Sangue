<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estoque;
use Illuminate\Support\Facades\DB;
use App\Models\AjusteEstoque;


class EstoqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $centroId = auth()->user()->centro->id_centro;
        $estoque = Estoque::where('id_centro', $centroId)->get();
        $totalEstoque = $estoque->sum('quantidade');
        
        $tiposSanguineos = [
            'A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'
        ];
        
        $movimentacoes = DB::table('ajuste_estoque')
        ->where('id_centro', auth()->user()->centro->id_centro)
        ->orderByDesc('created_at')
        ->limit(5)
        ->get();

        return view('centro.estoque', [
            'estoque' => $estoque,
            'tiposSanguineos' => $tiposSanguineos,
            'movimentacoes' => $movimentacoes,
            'totalEstoque'=>$totalEstoque
        ]);
    }

    public function ajuste(Request $request)
    {
        
        // Validação dos dados enviados
        $validated = $request->validate([
            'tipo_sanguineo' => 'required',
            'quantidade'     => 'required|integer|min:1',
            'operacao'       => 'required|in:+,-',
            'motivo'         => 'required',
            'observacao'     => 'nullable|string|max:500', 
        ]);

       
    
        $centroId = auth()->user()->centro->id_centro; 
    
        $estoque = Estoque::where('id_centro', $centroId)
                          ->where('tipo_sanguineo', $validated['tipo_sanguineo'])
                          ->first();

                          
                          
        $operacao = $validated['operacao'];
                          $quantidade = $validated['quantidade'];          
        if ($estoque) {
                           
                if ($operacao === '+') {
                         $estoque->quantidade += $quantidade;
                } else {
                                // Verifica se há estoque suficiente para remoção
                    if ($estoque->quantidade < $quantidade) {
                         return redirect()->back()->withErrors('Estoque insuficiente para remoção.');
                    }
                        $estoque->quantidade -= $quantidade;
                        }
                    
                            $estoque->save();
                        } else {
                            // Se não existe, só permite criar se a operação for de adição
                            if ($operacao === '+') {
                                $estoque = Estoque::create([
                                    'id_centro'      => $centroId,
                                    'tipo_sanguineo' => $validated['tipo_sanguineo'],
                                    'quantidade'     => $quantidade,
                                ]);
                            } else {
                                return redirect()->back()->withErrors('Estoque para o tipo sanguíneo selecionado não encontrado.');
                            }
                        }
    
            // Registrar a movimentação
            AjusteEstoque::create([
                'id_centro'      => $centroId,
                'tipo_sanguineo' => $validated['tipo_sanguineo'],
                'quantidade'     => ($operacao == '+') ? $quantidade : -$quantidade,
                'operacao'       => $validated['operacao'],
                'motivo'         => $validated['motivo'],
                'observacao'     => $request->input('observacao'),
            ]);
    
        return redirect()->back()->with('success', 'Ajuste realizado com sucesso!');
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
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
