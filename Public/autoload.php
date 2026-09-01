<?php
  // Função para automaticamente carregar classes, caso o arquivo e o diretorio exista
  // roda toda vez que um require é feito
  spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    
    $file = __DIR__ . '/../' . $class . '.php';

    if (file_exists($file)) {
      require_once $file;
    }
  });