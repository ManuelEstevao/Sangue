<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Doação de Sangue</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 14px;
            color: #444;
            padding: 25px;
            line-height: 1.5;
        }

        .header {
            background-color: #cc0000;
            padding: 20px 25px;
            margin: -25px -25px 20px -25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .header h2 {
            margin: 0 0 5px 0;
            color: #fff;
            font-size: 22px;
        }

        .header p {
            font-size: 13px;
            color: #ffd4d4;
        }

        h3 {
            color: #cc0000;
            margin: 25px 0 12px;
            font-size: 16px;
            padding-bottom: 6px;
            border-bottom: 2px solid #f0f0f0;
        }

        .info {
            background-color: #fff9f9;
            padding: 15px;
            border: 1px solid #ffe0e0;
            border-radius: 6px;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            background: #fff;
        }

        th {
            background-color: #cc0000;
            color: #fff;
            padding: 10px 12px;
            font-size: 13px;
            text-align: center;
            vertical-align: middle;
        }

        td {
            padding: 10px 12px;
            border: 1px solid #ffecec;
            text-align: center;
            vertical-align: middle;
        }

        .assinatura {
            margin: 40px auto 0;
            padding: 15px 0;
            text-align: center;
            max-width: 600px;
        }

       

        .footer {
            padding: 12px;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #eee;
            margin-top: 30px;
        }

        .destaque { color: #cc0000; }
        .badge {
            padding: 3px 6px;
            background: #cc0000;
            color: white;
            border-radius: 3px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>RELATÓRIO DE DOAÇÃO</h2>
        <p>Emitido em: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info">
        <h3>Centro Doador</h3>
        <p><strong>Nome:</strong>  {{ $resposta->centroDoador->nome }}</p>
        <p><strong>Endereço:</strong> {{ $resposta->centroDoador->endereco }}</p>
        <p><strong>Contato:</strong> {{ $resposta->centroDoador->telefone }}</p>
    </div>

    <div class="info">
        <h3>Solicitação Atendida</h3>
        <p><strong>Código:</strong> <span class="destaque">#{{ $resposta->solicitacao->id_sol }}</span></p>
        <p><strong>Tipo Sanguíneo:</strong> {{ $resposta->solicitacao->tipo_sanguineo }}</p>
        <p><strong>Quantidade:</strong> {{ $resposta->solicitacao->quantidade }} bolsas</p>
        <p><strong>Solicitante:</strong> {{ $resposta->solicitacao->centroSolicitante->nome }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Doação Realizada</th>
                <th>Data</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="destaque">{{ $resposta->quantidade_aceita }} bolsas</td>
                <td>{{ ($resposta->created_at)->format('d/m/Y') }}</td>
                <td>{{ $resposta->status }}</td>
            </tr>
        </tbody>
    </table>

    <div class="assinatura">
        
        <p>Responsável pela Doação</p>
        <p>_________________________________________</p>
    </div>

   
</body>
</html>