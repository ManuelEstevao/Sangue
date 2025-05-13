<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* Estilos PDF */
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; }
        .header { border-bottom: 2px solid #666; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 150px; float: left; }
        .titulo { float: right; text-align: right; }
        .clear { clear: both; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background-color: #f8f9fa; text-align: left; padding: 8px; border: 1px solid #dee2e6; }
        td { padding: 8px; border: 1px solid #dee2e6; }
        .bg-primary { background-color: #007bff; color: white; }
        .text-center { text-align: center; }
        .assinatura { margin-top: 50px; border-top: 1px solid #000; width: 300px; padding-top: 10px; }
        .page-break { page-break-after: always; }
        .small { font-size: 0.8em; }
    </style>
</head>
<body>
    <!-- Cabeçalho -->
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" class="logo">
        <div class="titulo">
            <h2>Relatório Completo do Doador</h2>
            <p>Emitido em: {{ $data_emissao }}</p>
        </div>
        <div class="clear"></div>
    </div>

    <!-- Seção de Dados Pessoais -->
    <h3 class="bg-primary text-center" style="padding: 5px; margin-bottom: 15px;">
        Dados Pessoais
    </h3>
    
    <table>
        <tr>
            <th width="25%">Nome Completo:</th>
            <td>{{ $doador->nome }}</td>
            <th width="20%">Tipo Sanguíneo:</th>
            <td style="color: #dc3545; font-weight: bold;">{{ $doador->tipo_sanguineo }}</td>
        </tr>
        <tr>
            <th>Data de Nascimento:</th>
            <td>{{ $doador->data_nascimento->format('d/m/Y') }}</td>
            <th>Idade:</th>
            <td>{{ $doador->data_nascimento->age }} anos</td>
        </tr>
        <tr>
            <th>CPF:</th>
            <td>{{ $doador->numero_bilhete }}</td>
            <th>Peso Atual:</th>
            <td>{{ $doador->peso }} kg</td>
        </tr>
    </table>

    <!-- Histórico de Doações -->
    <h3 class="bg-primary text-center" style="padding: 5px; margin: 25px 0 15px;">
        Histórico de Doações (Total: {{ $total_doacoes }} / Volume Total: {{ $volume_total }}ml)
    </h3>

    @foreach($doador->agendamentos as $agendamento)
    <div style="margin-bottom: 20px;">
        <table>
            <tr>
                <th colspan="4" style="background-color: #e9ecef;">
                    Doação #{{ $loop->iteration }} - {{ $agendamento->data_agendada->format('d/m/Y H:i') }}
                </th>
            </tr>
            <tr>
                <th width="25%">Status</th>
                <td width="25%"><strong>{{ $agendamento->status }}</strong></td>
                <th width="25%">Local</th>
                <td>{{ $agendamento->centro->nome ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Volume Coletado</th>
                <td>{{ $agendamento->doacao->volume_coletado ?? 0 }}ml</td>
                <th>Hemoglobina</th>
                <td>{{ $agendamento->doacao->hemoglobina ?? 'N/A' }}g/dL</td>
            </tr>
            @if($agendamento->questionario)
            <tr>
                <td colspan="4" style="padding: 0;">
                    <table style="margin: 0; background-color: #f8f9fa;">
                        <tr>
                            <th colspan="4" class="small">Questionário Pré-Doação</th>
                        </tr>
                        <tr>
                            <td width="25%" class="small">Problemas anteriores:</td>
                            <td width="25%" class="small">{{ $agendamento->questionario->problema_doacao_anterior ? 'Sim' : 'Não' }}</td>
                            <td width="25%" class="small">Cirurgia recente:</td>
                            <td width="25%" class="small">{{ $agendamento->questionario->fez_cirurgia_recente ? 'Sim' : 'Não' }}</td>
                        </tr>
                        <!-- Adicionar mais questões conforme necessário -->
                    </table>
                </td>
            </tr>
            @endif
        </table>
    </div>
    @endforeach

    <!-- Rodapé -->
    <div style="position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 0.8em;">
        <p>Sistema de Gestão de Doações de Sangue | Página @{{ $page }} de @{{ $pages }}</p>
    </div>
</body>
</html>