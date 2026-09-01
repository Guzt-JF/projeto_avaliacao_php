<div class="modal_background" id="modal_bg">
  <div id="modal" class="modal">
    <div class="modal_header">
      <strong>Cadastrar Novo Serviço</strong>
      <button id="close_modal_button">X</button>
    </div>
    <div class="modal_content">
      <div class="edit_container">
        <div class="input_container">
          <label>Descrição:</label>
          <input type="text" id="cad_descricao">
        </div>
        <div class="input_container">
          <label>Valor:</label>
          <input type="text" id="cad_total">
        </div>
      </div>
    </div>
    <div class="modal_footer">
      <button id="modal_cancel_button" class="modal_cancel_button modal_buttons">Cancelar</button>
      <button id="modal_confirm_button" class="modal_confirm_button modal_buttons">Confirmar</button>
    </div>
  </div>
  <script src="<?= BASE_URL ?>/Public/js/servicos/modal/cad.js"></script>
</div>