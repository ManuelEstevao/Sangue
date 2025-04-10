<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Doações - {{ $centro->nome }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 20px; }
        h2, h4 { text-align: center; margin: 0; }
        .header { text-align: center; margin-bottom: 20px; }
        .header p { font-size: 14px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
        table, th, td { border: 1px solid #ddd; }
        th { background-color: #f2f2f2; padding: 10px; }
        td { padding: 8px; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { text-align: center; font-size: 12px; color: #777; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Relatório de Doações</h2>
        <h4>{{ $centro->nome }}</h4>
        <p>{{ $centro->endereco }}</p>
        <p>Telefone: {{ $centro->telefone }} | Email: {{ $centro->user->email ?? 'N/A' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Doador</th>
                <th>Pressão</th>
                <th>Hemoglobina</th>
                <th>Data da Doação</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($doacoes as $doacao)
                <tr>
                    <td>{{ $doacao->agendamento->doador->nome }}</td>
                    <td>{{ $doacao->pressao_arterial }}</td>
                    <td>{{ $doacao->hemoglobina }} g/dL</td>
                    <td>{{ \Carbon\Carbon::parse($doacao->data_doacao)->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($doacao->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Nenhuma doação encontrada com os filtros aplicados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Relatório gerado em: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
