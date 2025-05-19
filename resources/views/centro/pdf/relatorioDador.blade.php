<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; margin: 1cm; }
        .header { border-bottom: 2px solid #666; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { float: left; width: 120px; }
        .titulo { float: right; text-align: right; }
        .clear { clear: both; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f2f2f2; }
        .section-title { background: #007bff; color: #fff; padding: 4px; margin: 20px 0 10px; text-align: center; }
        .small { font-size: 0.85em; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 0.8em; }
    </style>
</head>
<body>
    {{-- Cabeçalho --}}
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" class="logo">
        <div class="titulo">
            <h2>Relatório do Doador</h2>
            <p>Emitido: {{ $data_emissao }}</p>
        </div>
        <div class="clear"></div>
    </div>

    {{-- Dados Pessoais --}}
    <div class="section-title">Dados Pessoais</div>
    <table>
        <tr>
            <th>Nome Completo</th>
            <td>{{ $doador->nome }}</td>
            <th>Tipo Sanguíneo</th>
            <td>{{ $doador->tipo_sanguineo }}</td>
        </tr>
        <tr>
            <th>Data de Nascimento</th>
            <td>{{ $doador->data_nascimento->format('d/m/Y') }}</td>
            <th>Idade</th>
            <td>{{ $doador->data_nascimento->age }} anos</td>
        </tr>
        <tr>
            <th>BI/CPF</th>
            <td>{{ $doador->numero_bilhete }}</td>
            <th>Peso Atual</th>
            <td>{{ $doador->peso }} kg</td>
        </tr>
    </table>

    {{-- Histórico de Doações --}}
    <div class="section-title">
        Histórico de Doações ({{ $total_doacoes }} feitas • {{ $volume_total }} ml total)
    </div>

    @foreach($doador->agendamentos as $idx => $ag)
        <table>
            <tr>
                <th colspan="4">Doação #{{ $idx + 1 }} — {{ $ag->data_agendada->format('d/m/Y H:i') }}</th>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $ag->status }}</td>
                <th>Local</th>
                <td>{{ optional($ag->centro)->nome ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Volume Coletado</th>
                <td>{{ $ag->doacao->volume_coletado ?? '—' }} ml</td>
                <th>Hemoglobina</th>
                <td>{{ $ag->doacao->hemoglobina ?? '—' }} g/dL</td>
            </tr>

            @if($ag->questionario)
            <tr>
                <th colspan="4" class="small" style="background: #f9f9f9;">Questionário Pré‑Doação</th>
            </tr>
            <tr class="small">
                <td>Problemas Anteriores</td>
                <td>{{ $ag->questionario->problema_doacao_anterior ? 'Sim' : 'Não' }}</td>
                <td>Cirurgia Recente</td>
                <td>{{ $ag->questionario->fez_cirurgia_recente ? 'Sim' : 'Não' }}</td>
            </tr>
            {{-- adicionar outras perguntas aqui --}}
            @endif
        </table>
    @endforeach

    {{-- Rodapé com numeração de páginas --}}
    <div class="footer">
        Página <span class="small">{{ '{PAGE_NUM}' }}</span> de <span class="small">{{ '{PAGE_COUNT}' }}</span>
    </div>
</body>
</html>
