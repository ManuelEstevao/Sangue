<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Solicitação de Sangue</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 14px;
            color: #444;
            padding: 30px;
            line-height: 1.6;
        }

        .header {
            background-color: #cc0000;
            padding: 25px 30px;
            margin: -30px -30px 30px -30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header h2 {
            margin: 0;
            color: #fff;
            font-size: 24px;
            letter-spacing: 1px;
        }

        .header p {
            font-size: 13px;
            color: #ffd4d4;
            margin: 5px 0 0;
        }

        h3 {
            color: #cc0000;
            margin-top: 35px;
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 2px solid #eee;
            padding-bottom: 8px;
        }

        .section {
            margin-bottom: 30px;
        }

        .info {
            background-color: #fff9f9;
            padding: 20px;
            border: 1px solid #ffe0e0;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(204,0,0,0.05);
        }

        .info p {
            margin: 8px 0;
            padding: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 10px rgba(0,0,0,0.05);
        }

        th {
            background-color: #cc0000;
            color: #fff;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
        }

        td {
            border: 1px solid #ffecec;
            padding: 12px 15px;
            background-color: #fff;
        }

        .assinatura {
            margin-top: 80px;
            padding: 20px 0;
        }

        .assinatura p {
            margin: 15px 0;
            color: #666;
        }

        .linha-assinatura {
            border-top: 2px dashed #cc0000;
            width: 400px;
            margin: 25px auto 10px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #eee;
            background-color: #f9f9f9;
        }

        .destaque {
            color: #cc0000;
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            background-color: #cc0000;
            color: white;
            border-radius: 4px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>SOLICITAÇÃO DE SANGUE</h2>
        <p>Relatório emitido em: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="section">
        <h3>Dados da Solicitação</h3>
        <div class="info">
            <p><span class="badge">Código</span> #{{ $resposta->solicitacao->id_sol }}</p>
            <p><strong>Tipo Sanguíneo:</strong> <span class="destaque">{{ $resposta->solicitacao->tipo_sanguineo }}</span></p>
            <p><strong>Quantidade Solicitada:</strong> <span class="destaque">{{ $resposta->solicitacao->quantidade }} bolsas</span></p>
            <p><strong>Centro Solicitante:</strong> {{ $resposta->solicitacao->centroSolicitante->nome }}</p>
        </div>
    </div>

    <div class="section">
        <h3>Detalhes da Transferência</h3>
        <table>
            <thead>
                <tr>
                    <th>Centro Doador</th>
                    <th>Quantidade Transferida</th>
                    <th>Data da Confirmação</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $resposta->centroDoador->nome }}</td>
                    <td class="destaque">{{ $resposta->quantidade_aceita }} bolsas</td>
                    <td>{{ ($resposta->created_at)->format('d/m/Y') ?? 'Data não disponível' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="assinatura">
        <div class="linha-assinatura"></div>
        <p>Assinatura do Responsável pelo Recebimento</p>
        <p>Nome: _________________________________________</p>
        
    </div>

    <div class="footer">
        Relatório gerado automaticamente pelo Sistema ConectaDador - Todos os direitos reservados
    </div>
</body>
</html>