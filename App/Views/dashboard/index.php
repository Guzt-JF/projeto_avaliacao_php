<?php 
  if(!isset($data)){
    $data = ['header'=>''];
  }
?>
<div class="main_container">
  <div class="drawer_container">
     <span>Logado como:</span> 
     <span style="font-weight:600"><?= $_SESSION["username"] ?></span> 
     <div class="drawer_options">
      <button id="cad_services">Cadastrar Serviço</button> 
     </div>
  </div>
  <div class="content">
    <h1>DASHBOARD</h1>
    <span><?= date('d/m/Y') ?></span>
    <div id="dashboard_header">
      <?= $data['header'] ?>
    </div>
    <div class="filter_inputs">
      <div class="input_container">
        <label>Descrição:</label>
        <input type="text" id="filter_descricao"/>
      </div>
      <div class="input_container">
        <label>Nome Usuário:</label>
        <input type="text" id="filter_nome_usuario" value="<?= $_SESSION["username"] ?>"/>
      </div>
      <div class="input_container">
        <label>Status:</label>
        <select id="filter_status">
          <option value="0">Todos</option>
          <option value="1">Pendente</option>
          <option value="2">Finalizado</option>
        </select>
      </div>
      <div class="input_container">
        <label>Data inicial:</label>
        <input type="date" id="filter_data_inicial"/>
      </div>
      <div class="input_container">
        <label>Data Final:</label>
        <input type="date" id="filter_data_final"/>
      </div>
      <button id="filter_button">Filtrar</button>
    </div>

    <div class="table_container" id="services_table">
      <?php require_once BASE_PATH . 'App/Views/dashboard/_table.php';?>
    </div>
  </div>
</div>