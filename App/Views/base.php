<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <title>Projeto Avaliação</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/Public/css/styles.css">
    <?= isset($stylesheet) ? $stylesheet : '' ?>
  </head>
  <body>
    <?= isset($page) ? $page : '' ?>
    <script src="<?= BASE_URL ?>/Public/lib/jquery-4.0.0.min.js"></script>
    <script src="<?= BASE_URL ?>/Public/js/function.js"></script>
    <script>
      window.BASE_URL = `<?= BASE_URL ?>`;
    </script>
    <?= isset($script) ? $script : '' ?>
  </body>
</html>
