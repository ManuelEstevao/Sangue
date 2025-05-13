<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Comprovativo de Agendamento</title>
    <style>
        @page { margin: 2cm; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            position: relative;
        }
        .header {
            border-bottom: 3px solid #c00;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .logo {
            display: inline-block;
            margin-bottom: 10px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 80px;
            color: #c00;
            opacity: 0.1;
            z-index: -1000;
        }
        .section {
            margin-bottom: 20px;
            clear: both;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        td {
            padding: 4px 0;
        }
        .codigo {
            text-align: right;
            font-size: 0.9rem;
            color: #555;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>

    {{-- Marca-d'água rodando atrás do conteúdo --}}
    <div class="watermark">CONFIRMADO</div>

    {{-- Cabeçalho com logo --}}
    <div class="header">
        <img src="{{ public_path('assets/img/logo.png') }}" class="logo" width="120" alt="Logo">
        <h2 style="color: #c00; margin: 0;">Comprovativo de Agendamento</h2>
    </div>

    {{-- Código de confirmação --}}
    <div class="codigo">
        <strong>Código:</strong> {{ $codigo_confirmacao }}
    </div>

    {{-- Dados do Doador --}}
    <div class="section">
        <h3 style="color: #c00; margin-bottom: 5px;">Dados do Doador</h3>
        <table>
            <tr><td><strong>Nome:</strong></td><td>{{ $agendamento->doador->nome }}</td></tr>
            <tr><td><strong>BI:</strong></td><td>{{ $agendamento->doador->numero_bilhete }}</td></tr>
            <tr><td><strong>Tipo Sanguíneo:</strong></td><td>{{ $agendamento->doador->tipo_sanguineo }}</td></tr>
        </table>
    </div>

    {{-- Detalhes do Agendamento --}}
    <div class="section">
        <h3 style="color: #c00; margin-bottom: 5px;">Detalhes do Agendamento</h3>
        <table>
            <tr><td><strong>Data:</strong></td>
                <td>{{ \Carbon\Carbon::parse($agendamento->data_agendada)->format('d/m/Y') }}</td></tr>
            <tr><td><strong>Horário:</strong></td><td>{{ $agendamento->horario }}</td></tr>
        </table>
    </div>

    {{-- Local da Doação --}}
    <div class="section">
        <h3 style="color: #c00; margin-bottom: 5px;">Local da Doação</h3>
        <p style="margin: 0;">
            {{ $agendamento->centro->nome }}<br>
            {{ $agendamento->centro->endereco }}<br>
            Tel: {{ $agendamento->centro->telefone }}
        </p>
    </div>

    <div class="section">
    <h3 style="color: #c00; margin-bottom: 5px;">Orientações Antes da Doação</h3>
    <ul style="margin: 0; padding-left: 1.2em; line-height: 1.4;">
        <li>Alimente-se bem nas últimas 3 horas e evite jejum prolongado.</li>
        <li>Hidrate-se: beba pelo menos 500 ml de água nas 2 horas que antecedem a doação.</li>
        <li>Evite bebidas alcoólicas nas últimas 24 horas.</li>
        <li>Descanse bem na noite anterior (mínimo 6–8 h de sono).</li>
        <li>Leve um documento de identificação com foto.</li>
    </ul>
</div>

    {{-- Rodapé com data/hora de emissão --}}
    <div class="footer">
        Emitido em: {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>
