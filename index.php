<?php 
  define('BASE_PATH', __DIR__ . '/');
  define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <title>Projeto Avaliação</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/Public/css/styles.css">
  </head>
  <body>
    <?php
      require_once __DIR__ . '/Public/autoload.php';

      use App\Core\Router;

      $router = new Router();
    ?>
    <script src="<?= BASE_URL ?>/Public/lib/jquery-4.0.0.min.js"></script>
  </body>
</html>
