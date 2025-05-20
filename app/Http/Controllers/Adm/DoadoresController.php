<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doador;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DoadoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private function filtrarDoadores(Request $request)
    {
        $search = $request->input('search');
        $tipo   = $request->input('tipo_sanguineo');

        return Doador::join('users', 'doador.id_user', '=', 'users.id_user')
            ->select([
                'doador.id_doador',
                'doador.nome',
                'users.email',
                'doador.tipo_sanguineo',
                'doador.telefone',
                'doador.data_cadastro as created_at',
            ])
            ->when($search, function($q, $search) {
                $q->where('doador.nome', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%");
            })
            ->when($tipo, function($q, $tipo) {
                $q->where('doador.tipo_sanguineo', $tipo);
            })
            ->orderBy('doador.nome');
    }
    

    /**
     * Listagem paginada de Doadores.
     */
    public function index(Request $request)
    {
        $doadores = $this->filtrarDoadores($request)
                         ->paginate(15)
                         ->appends($request->only('search', 'tipo_sanguineo'));

        return view('ADM.listaD', compact('doadores'));
    }

    /**
     * Exporta a lista (com filtros aplicados) para PDF.
     */
    public function exportarPdf(Request $request)
{
    $doadores = $this->filtrarDoadores($request)->get();

    $pdf = Pdf::loadView('ADM.pdf.listaD', compact('doadores'));
    
    return $pdf->download('Doadores cadastrados '.now()->format('Ymd_His').'.pdf');
}

    public function checkBilheteUnique(Request $request)
{
    $exists = Doador::where('numero_bilhete', $request->numero_bilhete)->exists();
    return response()->json(['exists' => $exists]);
}
public function checkEmailUnique(Request $request)
{
    try {
        $exists = User::where('email', $request->email)->exists();
        return response()->json(['exists' => $exists], 200);
    } catch (\Exception $e) {
        // Loga o erro de SQL ou outro
        \Log::error('checkEmailUnique error: '.$e->getMessage());
        return response()->json([
          'error' => 'Erro interno ao verificar e-mail'
        ], 500);
    }
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
        // Validação dos dados
        $validated = $request->validate([
            'nome'                  => 'required',
            'email'                 => 'required',
            'password'              => 'required',
            'numero_bilhete'        => 'required',
            'data_nascimento'       => 'required',
            'genero'                => 'required',
            'tipo_sanguineo'        => 'required',
            'telefone'              => 'required',
            'peso'                  => 'nullable',
            'endereco'              => 'nullable',
            'foto'                  => 'nullable',
        ]);

        

        DB::transaction(function() use ($validated, $request) {
            // 1) Criar usuário associado
            $user = User::create([
                'email'         => $validated['email'],
                'password'      => Hash::make($validated['password']),
                'tipo_usuario'  => 'doador',
            ]);

            // 2) Preparar dados do doador
            $doadorData = [
                'id_user'           => $user->id_user,
                'numero_bilhete'    => $validated['numero_bilhete'],
                'nome'              => $validated['nome'],
                'data_nascimento'   => $validated['data_nascimento'],
                'genero'            => $validated['genero'],
                'tipo_sanguineo'    => $validated['tipo_sanguineo'],
                'telefone'          => $validated['telefone'],
                'peso'              => $validated['peso'] ?? null,
                'endereco'          => $validated['endereco'] ?? null,
                // 'foto' será preenchido abaixo se houver upload
            ];

            // 3) Processar upload da foto, se informado
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('fotos_doadores', 'public');
                $doadorData['foto'] = $path;
            }

            // 4) Criar registro de doador
            Doador::create($doadorData);
        });

        return redirect()
            ->route('listaD')
            ->with('success', 'Doador criado com sucesso!');
    }


   public function perfil($id)
{
    $d = Doador::findOrFail($id);
    $nasc = Carbon::parse($d->data_nascimento);
    return response()->json([
        'nome'            => $d->nome,
        'tipo_sanguineo'  => $d->tipo_sanguineo,
        'telefone'        => $d->telefone,
        'data_nascimento' => $nasc->format('d/m/Y'),
        'endereco'        => $d->endereco,
        'foto'            => $d->foto, 
    ]);
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
         $doadores = Doador::with('user')->findOrFail($id);
        return view('ADM.listaD', compact('doadores'));
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
