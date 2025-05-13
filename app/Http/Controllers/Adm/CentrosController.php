<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Centro;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class CentrosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private function filtrarCentros(Request $request)
    {
        return Centro::query()
            ->when($request->input('search'), function($q, $search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('endereco', 'like', "%{$search}%");
            })
            ->orderBy('nome');
    }

    /**
     * Listagem paginada de Centros.
     */
    public function index(Request $request)
    {
        $query = $this->filtrarCentros($request);

        $centros = $query
            ->paginate(15)
            ->appends($request->only('search'));

        return view('ADM.listaC', compact('centros'));
    }

    /**
     * Exporta a lista (com filtros aplicados) para PDF.
     */
    public function exportarPdf(Request $request)
    {
        $centros = $this->filtrarCentros($request)->get();

        $pdf = Pdf::loadView('ADM.pdf.listaC', compact('centros'));
        return $pdf->download('Centros de doação.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */

     public function mapa()
     {
        $centros = Centro::all([
            'id_centro', 'nome', 'endereco', 'telefone',
            'latitude', 'longitude'
        ]);
         return view('ADM.mapaC', compact('centros'));
     }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validação
        $data = $request->validate([
            'nome'               => 'required',
            'email'              => 'required',
            'password'           => 'required',
            'endereco'           => 'required|string|max:200',
            'telefone'           => 'required|string|max:16',
            'latitude'           => 'required',
            'longitude'          => 'required',
            'capacidade_maxima'  => 'required|integer|min:1',
            'horario_abertura'   => 'required|date_format:H:i',
            'horario_fechamento' => 'required|date_format:H:i',
            'foto'               => 'nullable|image|max:2048',
        ]);

        // cria usuário do tipo 'centro'
        $user = User::create([
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'tipo_usuario' => 'centro',
        ]);

        // prepara dados do Centro
        $centroData = [
            'nome'               => $data['nome'],
            'endereco'           => $data['endereco'],
            'telefone'           => $data['telefone'],
            'latitude'           => $data['latitude'],
            'longitude'          => $data['longitude'],
            'capacidade_maxima'  => $data['capacidade_maxima'],
            'horario_abertura'   => $data['horario_abertura'],
            'horario_fechamento' => $data['horario_fechamento'],
            'id_user'            => $user->id_user,
        ];

        // trata upload de foto, se houver
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('centros', 'public');
            $centroData['foto'] = $path;
        }

        // cria o Centro
        Centro::create($centroData);

        return redirect()->route('centros.lista')
                         ->with('success', 'Centro de Doação criado com sucesso!');
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
