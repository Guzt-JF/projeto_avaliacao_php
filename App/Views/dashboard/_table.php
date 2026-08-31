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
        $editable = ($status || $serv["user_id_user"] != $_SESSION['id_user']);
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
        <td id="row_id_<?= $id ?>">
          <?= str_pad($id , 7, '0', STR_PAD_LEFT) ?>
        </td>
        <td id="row_description_<?= $id ?>"><?= $serv["description"] ?></td>
        <td id="row_status_<?= $id ?>" data-real_value="<?= $status ? 2 : 1 ?>">
          <?= $status ? 'FINALIZADO' : 'PENDENTE' ?>
        </td>
        <td id="row_price_<?= $id ?>" data-real_value="<?= $serv["price"] ?>">
          R$ <?= number_format($serv["price"], 2, ',', '.') ?>
        </td>
        <td id="row_user_<?= $id ?>">
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