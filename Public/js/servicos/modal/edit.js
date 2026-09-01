$(document).ready(()=>{
  $('#modal_confirm_button').on('click', async function(){
    const new_description = $('#edit_descricao').val();
    const new_price = $('#edit_total').val();
    const serv_id = $('#serv_id').val();

    const has_errors = verifyInputs(new_description, new_price);
    if(has_errors){
      return;
    }

    $(`#modal_bg`).remove();

    showGeneralLoading();

    const formData = new FormData();

    formData.append('description', new_description);
    formData.append('price', new_price);
    formData.append('id',serv_id);

    const response = await fetch(`${window.BASE_URL}/servicos/update`, {
      method: 'POST',
      body: formData
    });

    const data = await response.json();

    showToast(data.msg, data.erro);
    hideGeneralLoading();
    await filterServices();
  });
  
  $('#modal_cancel_button, #close_modal_button').on('click', async function(){
    $(`#modal_bg`).remove();
  });
})