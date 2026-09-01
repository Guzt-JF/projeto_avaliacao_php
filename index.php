<?php 
  // Ocultando erros que são escritos na tela
  error_reporting(0);
  ini_set("display_errors", 0);

  // Definindo as url base para serem usadas em importação de script
  define('BASE_PATH', __DIR__ . '/');
  define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));

  // Iniciando a Sessão
  session_start();

  // Importando o autoload
  require_once BASE_PATH . '/Public/autoload.php';

  // Importando e executando o router
  use App\Core\Router;

  $router = new Router();