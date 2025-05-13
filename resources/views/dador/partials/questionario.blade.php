<div class="alert alert-info mb-2" >
    Por favor, responda <strong>todas</strong> as perguntas abaixo marcando a caixa se a resposta for “Sim”.  
    Deixe desmarcada para “Não”.
</div>

<div class="row">
  @foreach([
    'ja_doou_sangue'                => 'Doou sangue nos últimos 4 meses?',
    'problema_doacao_anterior'      => 'Teve problemas em doações anteriores?',
    'tem_doenca_cronica'            => 'Possui doença crônica?',
    'fez_tatuagem_ultimos_12_meses' => 'Fez tatuagem ou piercing nos últimos 12 meses?',
    'fez_cirurgia_recente'          => 'Realizou cirurgia nos últimos 6 meses?',
    'esta_gravida'                  => 'Está grávida ou amamentando?',
    'recebeu_transfusao_sanguinea'  => 'Recebeu transfusão sanguínea nos últimos 12 meses?',
    'tem_doenca_infecciosa'         => 'Possui doença infecciosa?',
    'usa_medicacao_continua'        => 'Faz uso contínuo de medicamentos?',
    'tem_comportamento_de_risco'    => 'Nos últimos 6 meses, você teve práticas de risco de infecções sexualmente transmissíveis?',
    'teve_febre_ultimos_30_dias'    => 'Teve febre nos últimos 30 dias?',
    'consumiu_alcool_ultimas_24_horas' => 'Consumiu álcool nas últimas 24 horas?',
    'teve_malaria_ultimos_3meses'   => 'Teve malária nos últimos 3 meses?',
    'nasceu_ou_viveu_angola'        => 'Nasceu e sempre viveu em Angola?',
    'esteve_internado'              => 'Esteve internado nos últimos 12 meses?',
  ] as $field => $label)
    <div class="col-md-6 mb-2">
      <div class="form-check">
        <input
          type="checkbox"
          name="{{ $field }}"
          id="{{ $field }}"
          class="form-check-input"
          value="1"
          {{ old($field, isset($questionario) && $questionario->$field) ? 'checked' : '' }}>
        <label class="form-check-label" for="{{ $field }}">{{ $label }}</label>
      </div>
    </div>
  @endforeach
</div>