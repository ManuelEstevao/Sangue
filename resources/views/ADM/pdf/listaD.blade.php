<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Doadores</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px 30px;
        }

        .header {
            border-bottom: 3px solid #cc0000;
            padding-bottom: 10px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            width: 20px;
            height: auto;
        }

        h1 {
            color: #cc0000;
            font-size: 22px;
            margin: 5px 0;
            text-transform: uppercase;
        }

        .donor-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .donor-table th {
            background-color: #cc0000;
            color: white;
            padding: 10px;
            text-align: left;
            text-transform: uppercase;
            font-size: 11px;
        }

        .donor-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
        }

        .donor-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #cc0000;
            color: white;
            padding: 8px 20px;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="header">
    
    <div class="header-content">
        <h1><img src="{{ public_path('assets/img/flavicon.png') }}" class="logo" alt="Logo ConectaDad">Relatório de Doadores</h1>
        <div class="header-details">
            <div class="detail-item">
                <span class="detail-label">Data de Emissão:</span>
                <span class="detail-value">{{ now()->format('d/m/Y H:i') }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Total de Registros:</span>
                <span class="detail-value">{{ $doadores->count() }}</span>
            </div>
        </div>
    </div>
</div>

    <table class="donor-table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo Sanguíneo</th>
                <th>Telefone</th>
                <th>Cadastrado em</th>
            </tr>
        </thead>
        <tbody>
            @foreach($doadores as $doador)
            <tr>
                <td>{{ $doador->nome }}</td>
                <td>{{ $doador->email }}</td>
                <td>{{ $doador->tipo_sanguineo }}</td>
                <td>{{ $doador->telefone }}</td>
                <td>{{ \Carbon\Carbon::parse($doador->created_at)->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Plataforma de Auxílio ao Processo de Doação de Sangue | ConectaDador
    </div>
</body>
</html>