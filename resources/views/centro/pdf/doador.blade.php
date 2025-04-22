<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lista de Doadores - {{ $centro->nome ?? 'Centro de Coleta' }}</title>
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
            width: 90%; 
        }

        h1 {
            margin: 0;
            font-size: 28px;
            color: white;
        }

        h2 {
            text-align: center;
            color: #333;
            margin: 20px 0;
            font-size: 22px;
        }

        .header p {
            margin: 5px 0;
            font-size: 16px;
            color: #ffd4d4;
        }

        .table-container {
            margin: 20px 0;
        }

        table {
            width: 95%;
            border-collapse: collapse;
            font-size: 12px;
            
        }

        table thead {
            background-color: #cc0000;
        }

        table thead th {
            color: white;
            padding: 10px 12px;
            text-align: left;

        }
        th {
        font-weight: 600;
        text-transform: uppercase;
       
    }

        table tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }

        table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tbody tr:hover {
            background-color: #e9e9e9;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $centro->nome ?? 'Centro de Coleta' }}</h1>
        <p>{{ $centro->endereco ?? 'Endereço não informado' }}</p>
        <div class="info">
            <span>Telefone: {{ $centro->telefone ?? 'N/A' }}</span>
            <span>Email: {{ $centro->user->email ?? 'N/A' }}</span>
        </div>
    </div>

    <h2>Lista de Doadores</h2>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 30%;">Nome</th>
                    <th>Idade</th>
                    <th>Gênero</th>
                    <th>Tipo Sanguíneo</th>
                    <th>Telefone</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach($doadores as $d)
                <tr>
                    <td >{{ $d->nome }}</td>
                    <td>{{ \Carbon\Carbon::parse($d->data_nascimento)->age }}</td>
                    <td>{{ ucfirst($d->genero) }}</td>
                    <td>{{ $d->tipo_sanguineo }}</td>
                    <td>{{ $d->telefone }}</td>
                    <td>{{ $d->user->email ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>