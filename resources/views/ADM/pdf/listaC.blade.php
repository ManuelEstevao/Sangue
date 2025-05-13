<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Relatório de Doadores</title>
  <style>
    /* Estilos Gerais */
    body { 
      font-family: 'Segoe UI', Arial, sans-serif;
      font-size: 14px;
      line-height: 1.6;
      color: #333;
      margin: 0;
      padding: 20px 30px;
      position: relative;
      min-height: 100vh;
    }

    /* Cabeçalho Principal */
    .header {
      border-bottom: 3px solid #cc0000;
      padding-bottom: 10px;
      margin-bottom: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      width: 160px;
      height: auto;
    }

   

    h1 {
      color: #cc0000;
      font-size: 26px;
      margin: 10px 0;
      text-transform: uppercase;
      letter-spacing: 1.2px;
    }

    /* Informações do Centro */
    .center-info {
      background: #fff8f8;
      border-radius: 8px;
      padding: 20px;
      margin: 25px 0;
      border-left: 5px solid #cc0000;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .center-details {
      flex-grow: 1;
    }

    .center-name {
      color: #cc0000;
      font-size: 22px;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .center-address {
      color: #666;
      font-size: 14px;
      line-height: 1.4;
    }

    /* Contador de Doadores */
    .total-doadores {
      text-align: center;
      padding: 15px 25px;
      background: #cc0000;
      border-radius: 8px;
      color: white;
      margin-left: 30px;
      min-width: 140px;
    }

    .total-number {
      font-size: 32px;
      font-weight: 800;
      line-height: 1;
      margin-bottom: 5px;
    }

    .total-label {
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 0.8px;
    }
    .logo {
            width: 20px;
            height: auto;
        }
    /* Tabela */
    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }

    th {
      background-color: #cc0000;
      color: white;
      font-weight: 600;
      padding: 14px 18px;
      text-align: left;
      text-transform: uppercase;
      font-size: 13px;
      letter-spacing: 0.5px;
    }

    td {
      padding: 12px 18px;
      border-bottom: 1px solid #f0f0f0;
      vertical-align: top;
    }

    tr:nth-child(even) {
      background-color: #fcfcfc;
    }

    tr:hover {
      background-color: #fff5f5;
    }

    /* Rodapé */
    .footer {
      position: fixed;
      bottom: -20px;
      left: 0;
      right: 0;
      background-color: #cc0000;
      color: white;
      padding: 12px 25px;
      font-size: 12px;
      text-align: center;
      box-shadow: 0 -2px 4px rgba(0,0,0,0.06);
    }

    /* Metadados */
    .metadata {
      margin-bottom: 15px;
      color: #666;
      font-size: 13px;
      
    }

    /* Utilitários */
    .text-right { text-align: right; }
    .bold { font-weight: 600; }
    .nowrap { white-space: nowrap; }
  </style>
</head>
<body>
  <!-- Cabeçalho -->
  <div class="header">
    
    <div class="header-info">
      <h1><img src="{{ public_path('assets/img/flavicon.png') }}" class="logo" alt="Logo ConectaDad">Listagem dos centros</h1>
      <div class="metadata">
        Emitido em: {{ now()->format('d/m/Y H:i') }}<br>
       
      </div>
    </div>
  </div>

 <!-- Tabela de Centros de Coleta -->
<table>
    <thead>
        <tr>
            <th>Centro</th>
            <th>Endereço</th>
            <th>Telefone</th>
            <!--<th>Estoque</th>-->
            <th>Cadastrado em</th>
        </tr>
    </thead>
    <tbody>
        @foreach($centros as $c)
        <tr>
            <td class="bold">{{ $c->nome }}</td>
            <td>{{ $c->endereco }}</td>
            <td>{{ $c->telefone }}</td>
           <!-- <td>
                @foreach($c->estoque as $estoque)
                    <span class="blood-type">
                        {{ $estoque->tipo_sanguineo }} 
                        <span class="badge">{{ $estoque->quantidade }}</span>
                    </span>
                @endforeach
            </td>-->
            <td>{{ \Carbon\Carbon::parse($c->data_cadastro)->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
  <!-- Rodapé -->
  <div class="footer">
    Plataforma de Auxílio ao Processo de Doação de Sangue | ConectaDador
  </div>
</body>
</html>