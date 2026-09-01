<?php 
  error_reporting(0);
  ini_set("display_errors", 0);

  define('BASE_PATH', __DIR__ . '/');
  define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));
  session_start();

  require_once BASE_PATH . '/Public/autoload.php';

  use App\Core\Router;

  $router = new Router();