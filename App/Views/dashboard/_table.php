<?php 
  if(!isset($data)){
    $data = [
      'header'  => '',
      'user'    => '',
      'id_user' => ''
    ];
  }
?>
<div class="loading_container d-none" id="loading_table">
  <div class="panel_loading loading"></div>
</div>
 <table class="dashboard_table">
  <thead>
    <tr>
      <td style="width: 70px;"></td>
      <td>ID</td>
      <td>DESCRIÇÃO</td>
      <td>STATUS</td>
      <td>VALOR</td>
      <td>NOME USUÁRIO</td>
    </tr>
  </thead>
  <tbody>
    <?php
    if(isset($servicos_data) && !empty($servicos_data)){
      foreach($servicos_data as $serv){
        $id = $serv["id_service"];
        $status = isset($serv["finished_at"]) && !empty($serv["finished_at"]);
        $editable = ($status || $serv["user_id_user"] != $data['id_user']);
    ?>
      <tr>
        <td>
          <div class="row_buttons">
            <button class="row_edit_button" <?=  $editable ? 'disabled' : '' ?> data-id="<?= $id ?>">
              <img src="<?= BASE_URL ?>/Public/lib/icons/edit.svg" alt="Editar" height="18" width="18" />
            </button>
            <button class="row_delete_button" <?= $editable ? 'disabled' : '' ?> data-id="<?= $id ?>">
              <img src="<?= BASE_URL ?>/Public/lib/icons/trash.svg" alt="Deletar" height="18" width="18" />
            </button>
            <button class="row_finish_button" <?=$editable ? 'disabled' : '' ?> data-id="<?= $id ?>">
              <img src="<?= BASE_URL ?>/Public/lib/icons/check.svg" alt="Finalizar" height="18" width="18" />
            </button>
          </div>
        </td>
        <td>
          <?= str_pad($id , 7, '0', STR_PAD_LEFT) ?>
        </td>
        <td>
          <?= $serv["description"] ?>
        </td>
        <td>
          <?= $status ? 'FINALIZADO' : 'PENDENTE' ?>
        </td>
        <td>
          R$ <?= number_format($serv["price"], 2, ',', '.') ?>
        </td>
        <td>
          <?= $serv["user_name"] ?>
        </td>
      </tr>
    <?php
      }
    }
    else{
      echo '<tr><td colspan="6" style="text-align: center;">Nenhum registro Encontrado</td></tr>';
    }
    ?>
  </tbody>
</table>