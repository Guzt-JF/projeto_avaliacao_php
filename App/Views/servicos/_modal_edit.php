<?php
  if(!isset($modal_data) || empty($modal_data)){
    $modal_data = [
      'id'          => '',
      'description' => '',
      'price'       => '',
    ];
  }
?>

<div class="modal_background" id="modal_bg">
  <div id="modal" class="modal">
    <div class="modal_header">
      <input hidden class="d-none" id="serv_id" value="<?= $modal_data['id'] ?>" />
      <strong>Editar Serviço N° <?= $modal_data['id'] ?></strong>
      <button id="close_modal_button">X</button>
    </div>
    <div class="modal_content">
      <div class="edit_container">
        <div class="input_container">
          <label>Descrição:</label>
          <input type="text" id="edit_descricao" value="<?= $modal_data['description'] ?>">
        </div>
        <div class="input_container">
          <label>Valor:</label>
          <input type="text" id="edit_total" value="<?= $modal_data['price'] ?>">
        </div>
      </div>
    </div>
    <div class="modal_footer">
      <button id="modal_cancel_button" class="modal_cancel_button modal_buttons">Cancelar</button>
      <button id="modal_confirm_button" class="modal_confirm_button modal_buttons">Confirmar</button>
    </div>
  </div>
  <script src="<?= BASE_URL ?>/Public/js/servicos/modal/edit.js"></script>
</div>