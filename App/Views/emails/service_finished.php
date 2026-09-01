<?php
  if(!isset($data_email) || empty($data_email)){
    $data_email = [
      'id'    => '',
      'name'  => '',
      'user'  => '',
      'total' => 0
    ];
  }
?>

<div>
  <h3>Olá, <?= $data_email['user'] ?></h3>
  <span>Você concluiu o Serviço <strong><?= $data_email['name'] ?></strong> (N° <?= $data_email['id'] ?>) com sucesso, Parabéns!!<br></span>
  <span>Com isso você ganhou um total de R$ <?= $data_email['total'] ?> em comissões!</span>
</div>