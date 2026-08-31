function showToast(message, error = false) {
  if(!error){
    $('.toast_error').removeClass('show');
    setTimeout(() => {
      $('.toast_error').remove();
    }, 100);
  }
  const random_id = Math.floor(Math.random() * 999999);

  const toast = $(
    `<div id="toast_${random_id}" class="toast ${error ? 'toast_error' : ''}">
      ${message}
    </div>`
  );

  $('body').append(toast);

  void toast[0].offsetWidth;

  toast.addClass('show');

  setTimeout(() => {
    toast.removeClass('show');
  }, 3000);

  setTimeout(() => {
    toast.remove();
  }, 3200);
  
  $('.toast_error.show').each(function () {
    $(this).removeClass('shake');
    void this.offsetWidth;
    $(this).addClass('shake');
  });
}

function confirmModal(title, message, response = ()=>{}, reject = ()=>{}){
  const random_id = Math.floor(Math.random() * 999999);

  $('body').append(
    `<div class="modal_background" id="modal_bg_${random_id}">
      <div id="modal_${random_id}" class="modal">
        <div class="modal_header">
          <strong>${title}</strong>
          <button id="close_modal_button_${random_id}">X</button>
        </div>
        <div class="modal_content">
          ${message}
        </div>
        <div class="modal_footer">
          <button id="modal_cancel_button_${random_id}" class="modal_cancel_button modal_buttons">Cancelar</button>
          <button id="modal_confirm_button_${random_id}" class="modal_confirm_button modal_buttons">Confirmar</button>
        </div>
      </div>
    </div>
  `);

  $(`#close_modal_button_${random_id}`).on('click',function(){
    $(`#modal_bg_${random_id}`).remove();
    reject();
  })

  $(`#modal_confirm_button_${random_id}`).on('click',function(){
    $(`#modal_bg_${random_id}`).remove(); 
    response(random_id);
  })

  $(`#modal_cancel_button_${random_id}`).on('click',function(){
    $(`#modal_bg_${random_id}`).remove();
    reject();
  })
}

function showGeneralLoading(){
  $('body').append(
    `<div class="general_loading">
      <div class="loading"></div>
    </div>
  `);
}

function hideGeneralLoading(){
  $('.general_loading').remove();
}