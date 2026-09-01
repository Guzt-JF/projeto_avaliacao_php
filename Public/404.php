<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <title>Projeto Avaliação</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/Public/css/styles.css">
  </head>
  <body>
    <div class="error_container">
      <img src="<?= BASE_URL  ?>/Public/lib/icons/error.svg" alt="Error" height="200" width="200" />
      Erro ao abrir Página - <?= isset($erro) ? $erro : 'Erro Desconhecido' ?>  
    </div>
  </body>
</html>
