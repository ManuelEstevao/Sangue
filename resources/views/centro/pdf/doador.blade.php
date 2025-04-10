<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lista de Doadores - {{ $centro->nome ?? 'Centro de Coleta' }}</title>
    <style>
        /* Estilo Global */
        body {
            margin: 0;
            
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            width: 100%;
            height: 100%;
            color: #333;
        }
        .container {
            width: 94%;
            margin: auto;
            background: #fff;
            
        }
        /* Cabeçalho com informações do centro */
        .header {
            border-bottom: 2px solid rgba(198, 66, 66, 0.95);
            padding-bottom: 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            color: rgba(198, 66, 66, 0.95);
        }
        .header p {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }
        .header .info {
            margin-top: 10px;
        }
        .header .info span {
            display: inline-block;
            margin: 0 15px;
            font-size: 14px;
            color: #777;
        }
        /* Título da tabela */
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 22px;
        }
        /* Estilo da Tabela */
        .table-container {
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table thead {
            background-color: rgba(198, 66, 66, 0.95);
        }
        table thead th {
            color: #fff;
            padding: 15px;
            text-align: left;
            font-size: 14px;
        }
        table tbody td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            font-size: 13px;
        }
        table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        table tbody tr:hover {
            background-color: #e9e9e9;
        }
        /* Rodapé */
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #aaa;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Cabeçalho com informações do centro e do usuário -->
        <div class="header">
            <h1>{{ $centro->nome ?? 'Centro de Coleta' }}</h1>
            <p>{{ $centro->endereco ?? 'Endereço não informado' }}</p>
            <div class="info">
                <span>Telefone: {{ $centro->telefone ?? 'N/A' }}</span>
                <span>Email: {{ $centro->user->email ?? 'N/A' }}</span>
            </div>
        </div>

        <h2>Lista de Doadores</h2>

        <!-- Tabela de Doadores -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Idade</th>
                        <th>Gênero</th>
                        <th>Tipo Sanguíneo</th>
                        <th>Telefone</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doadores as $d)
                    <tr>
                        <td>{{ $d->nome }}</td>
                        <td>{{ \Carbon\Carbon::parse($d->data_nascimento)->age }}</td>
                        <td>{{ ucfirst($d->genero) }}</td>
                        <td>{{ $d->tipo_sanguineo }}</td>
                        <td>{{ $d->telefone }}</td>
                        <td>{{ $d->user->email ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Rodapé -->
        <div class="footer">
            <p>Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
