<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Doações - {{ $centro->nome }}</title>
    <style>
        
        @page {
        size: A4;
        margin: 10mm;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body { 
        font-family: 'Segoe UI', Arial, sans-serif;
        width: 100%;
        max-width: 210mm;  
        min-height: 297mm; 
        margin: 0 auto;
        padding: 20px 25px;
        color: #444;
        line-height: 1.6;
    }

    .header { 
        text-align: center;
        margin-bottom: 25px;
        padding: 20px;
        background-color: #cc0000;
        border-radius: 6px;
        color: white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        width:90% ; 
    }

    h2 {
        font-size: 24px;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    h4 {
        font-size: 16px;
        font-weight: 400;
        margin: 8px 0;
    }

    table {
        width: 95%;
        table-layout: fixed;
        border-collapse: collapse;
        font-size: 14px;
        word-wrap: break-word;
    }
        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #ffd4d4;
        }
        
        
        
        
        th, td {
        padding: 10px 12px;
        border: 1px solid #ffecec;
        text-align: left;
    }

    th {
        background-color: #cc0000;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
        
        tr:nth-child(even) { 
            background-color: #fff9f9; 
        }
        
        .status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .footer {
        position: absolute;
        bottom: 25px;
        left: 25px;
        right: 25px;
        text-align: center;
        font-size: 11px;
        color: #777;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }
        
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>RELATÓRIO DE DOAÇÕES</h2>
        <h4>{{ $centro->nome }}</h4>
        <p>{{ $centro->endereco }}</p>
        <p>{{ $centro->telefone }} | {{ $centro->user->email ?? 'N/A' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Doador</th>
                <th style="text-align: center;">Pressão</th>
                <th style="text-align: center; width: 20%">Hemoglobina</th>
                <th style="text-align: center; width: 20%">Data</th>
                <th style="text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($doacoes as $doacao)
                <tr>
                    <td style="width: 30%;">{{ $doacao->agendamento->doador->nome }}</td>
                    <td style="text-align: center;">{{ $doacao->pressao_arterial }}</td>
                    <td style="text-align: center; width: 20%">{{ $doacao->hemoglobina }} g/dL</td>
                    <td style="text-align: center; width: 20%">{{ \Carbon\Carbon::parse($doacao->data_doacao)->format('d/m/Y') }}</td>
                    <td style="text-align: center;">
                    <span class="status {{ strtolower($doacao->status) }}">
                        {{ ucfirst($doacao->status) }}
                    </span>
                </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-state">
                        Nenhuma doação registrada no período selecionado
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Relatório gerado em: {{ now()->format('d/m/Y H:i') }}</p>
        
    </div>
</body>
</html>