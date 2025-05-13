<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Centro; 
use App\Models\Doacao; 
use App\Models\User;
use App\Models\Doador;
use App\Models\Agendamento;
use App\Models\Campanha;
use App\Models\Solicitacao;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; 
class PerfilCentroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $centro = Auth::user()->centro;
        return view('centro.perfil', compact('centro'));
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
    public function update(Request $request) 
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:100',
            'telefone' => 'required|string|max:20',
            'endereco' => 'required|string|max:255',
            'horario_abertura' => 'required',
            'horario_fechamento' => 'required',
            'capacidade_maxima' => 'required',
            'endereco' => 'required',
            'telefone' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $centro = Auth::user()->centro;

    if ($request->hasFile('foto')) {
        // Deletar foto antiga se existir
        if ($centro->foto) {
            Storage::disk('public')->delete('centros/' . $centro->foto); 
        }

        // Armazenar nova foto
        $fileName = time() . '_' . $request->file('foto')->getClientOriginalName();
        $path = $request->file('foto')->storeAs(
            'centros', 
            $fileName,
            'public'
        );
        
        $validated['foto'] = $fileName;
    }
    
    $centro->update($validated);

        return redirect()->route('centro.perfil')->with([
            'success' => 'Perfil atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    if (!Hash::check($request->current_password, Auth::user()->password)) {
        return response()->json([
            'errors' => [
                'current_password' => ['Senha atual incorreta']
            ]
        ], 422);
    }

    Auth::user()->update([
        'password' => Hash::make($request->new_password)
    ]);

    return response()->json(['success' => true]);
}
}
