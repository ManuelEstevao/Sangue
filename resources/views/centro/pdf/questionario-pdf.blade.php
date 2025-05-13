<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Questionário Pré-Doação</title>
    <style>
        @page {
            margin: 1.5cm;
            size: A4;
        }
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 10pt;
            line-height: 1.4;
            margin: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 15pt;
            border-bottom: 3px solid #c64242;
        }
        .info-container {
            display: grid;
            margin-bottom: 20px;
            background: #f8f8f8;
            padding: 10px;
            border-radius: 8px;
            grid-template-columns: repeat(2, 1fr);
            gap: 12pt;
            margin-bottom: 11pt;
        }
        .info-item {
            color: #666;
            display: block;
            margin-bottom: 3px;
            padding: 8pt;
            border-radius: 4pt;
        }
        .columns {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20pt;
            margin-bottom: 20pt;
        }
        .question-group {
            break-inside: avoid;
        }
        .question-row {
            display: grid;
            grid-template-columns: 3fr 1fr;
            margin-bottom: 8pt;
            padding: 4pt 0;
            border-bottom: 1pt solid #eee;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding-top: 10pt;
            border-top: 1pt solid #ccc;
            font-size: 8pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="color: #c64242;margin: 5pt 0; font-size: 14pt;">Questionário Pré-Doação</h2>
    </div>

    <div class="info-container">
        <div class="info-item">
            <strong>Doador:</strong> {{ $doador->nome }}<br>
            <strong>Tipo Sanguíneo:</strong> {{ $doador->tipo_sanguineo }}
        </div>
        <div class="info-item">
            <strong>Data:</strong> {{ \Carbon\Carbon::parse($questionario->data_resposta)->format('d/m/Y H:i') }}<br>
            <strong>Centro:</strong> {{ $centro->nome }}
        </div>
    </div>

    <div class="columns">
        <!-- Coluna 1 -->
        <div class="question-group">
            <h3 style="margin-bottom: 8pt;">Triagem Clínica</h3>
            @foreach([
                'Doação nos últimos 4 meses?' => $questionario->ja_doou_sangue,
                'Problemas em doações anteriores?' => $questionario->problema_doacao_anterior,
                'Tatuagem/Piercing últimos 12 meses?' => $questionario->fez_tatuagem_ultimos_12_meses,
                'Cirurgia últimos 6 meses?' => $questionario->fez_cirurgia_recente,
                'Transfusão últimos 12 meses?' => $questionario->recebeu_transfusao_sanguinea,
                'Doença infecciosa?' => $questionario->tem_doenca_infecciosa,
                'Febre últimos 30 dias?' => $questionario->teve_febre_ultimos_30_dias
            ] as $pergunta => $resposta)
                <div class="question-row">
                    <span>{{ $pergunta }}</span>
                    <span style="text-align: right;">{{ $resposta ? 'SIM' : 'NÃO' }}</span>
                </div>
            @endforeach
        </div>

        <!-- Coluna 2 -->
        <div class="question-group">
            <h3 style="margin-bottom: 8pt;">Histórico de Saúde</h3>
            @foreach([
                'Doença crônica?' => $questionario->tem_doenca_cronica,
                'Grávida/Amamentando?' => $questionario->esta_gravida,
                'Medicação contínua?' => $questionario->usa_medicacao_continua,
                'Comportamento de risco?' => $questionario->tem_comportamento_de_risco,
                'Álcool últimas 24h?' => $questionario->consumiu_alcool_ultimas_24_horas,
                'Malária últimos 3 meses?' => $questionario->teve_malaria_ultimos_3meses,
                'Residente em Angola?' => $questionario->nasceu_ou_viveu_angola,
                'Internação últimos 12 meses?' => $questionario->esteve_internado
            ] as $pergunta => $resposta)
                <div class="question-row">
                    <span>{{ $pergunta }}</span>
                    <span style="text-align: right;">{{ $resposta ? 'SIM' : 'NÃO' }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="footer">
        <div style="text-align: center;">
            {{ $centro->endereco }} | Tel: {{ $centro->telefone }}<br>
            Documento válido por 30 dias | Emitido em: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>