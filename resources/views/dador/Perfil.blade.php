@extends('dador.DashbordDador')
@section('title', 'Perfil - ConectaDador')
   
@section('styles')
    <style>

        .profile-section {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
            align-items: center;
            margin: 20px 0; /* Espaçamento vertical */
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid #e63946;
            padding: 3px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Sombra suave */
        }

        .blood-type-badge {
            background: #e63946;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 24px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card {
            border: none;
            border-radius: 8px;
            background: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1); /* Sombra da card */
            padding: 20px;
        }

     

        .form-field {
            display: flex;
            flex-direction: column;
        }

        .form-field label {
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

   

     

        .action-button {
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        .edit-button {
            background-color: #e63946;
            color: white;
        }

        .edit-button:hover {
            background-color: #c1272d;
        }
    </style>
@endsection

@section('conteudo')

            <!-- Seção de Perfil do Doador -->
                <div class="card">
                    <h2 class="mb-4">Perfil do Doador</h2>
                    <div class="profile-section">
                    <img src="{{ asset('assets/img/profile.png') }}" class="avatar">
                        <div>
                        @php
                            $doador = Auth::user()->doador; // Obtendo dados do doador
                            $nomeCompleto = $doador->nome;
                            $dataNascimento = \Carbon\Carbon::parse($doador->data_nascimento)->format('d/m/Y');
                            $genero = $doador->genero; // Supondo que "genero" é um campo no modelo Doador
                        @endphp
                            <h3>{{ $nomeCompleto }} <span class="blood-type-badge"><i class="ri-drop-fill"></i>O+</span></h3>
                            <p>Data de Nascimento: {{ $dataNascimento }}</p>
                            <p>Gênero: {{ $genero }}</p>
                            <p><i class="ri-mail-fill"></i> {{ Auth::user()->email }}</p>
                            <p><i class="ri-phone-fill"></i>  {{ $doador->telefone }}</p>
                            <p><i class="ri-map-pin-fill"></i> São Paulo, SP</p>
                        </div>
                    </div>

                    <!-- Botão para Editar -->
                    <button class="action-button edit-button" >Editar Informações</button>

                </div>
            </main>
        </div>
    </div>

@endsection
@section('scripts')
