<?php
  if(!isset($data_header) || empty($data_header)){
    $data_header = [
      'total_user'        => 0,
      'latest_finished'   => [],
      'latest_unfinished' => [],
    ];
  }
?>
  <span id="span_total"><h3>Valor Total dos serviços prestados:</h3><span>R$ <?=  number_format($data_header['total_user'], 2, ',', '.') ?></span></span>
  <div class="latest_container">
    <div class="latest_div">
      <h2>Ultimos Serviços</h2>
      <?php
        if(!$data_header['latest_finished']){
      ?>
      <span>Nenhum Serviço concluído</span>
      <?php
        }
        foreach($data_header['latest_finished'] as $lf){
        $lf_id = str_pad($lf['id_service'], 7, '0', STR_PAD_LEFT);
      ?>
        <span><?= $lf_id ?> - <?= $lf['description'] ?></span>
      <?php
        } 
      ?>
    </div>
    <div class="latest_div">
      <h2>Serviços Pendentes</h2>
      <?php
        if(!$data_header['latest_unfinished']){
      ?>
      <span>Nenhum Serviço pendente</span>
      <?php
        }
        foreach($data_header['latest_unfinished'] as $lu){
        $lu_id = str_pad($lu["id_service"], 7, '0', STR_PAD_LEFT);
      ?>
        <span><?= $lu_id ?> - <?= $lu['description'] ?></span>
      <?php
        } 
      ?>
    </div>
  </div>