$(document).ready(()=>{
  $('#modal_confirm_button').on('click', async function(){
    try{
      const new_description = $('#cad_descricao').val();
      const new_price = $('#cad_total').val();

      const has_errors = verifyInputs(new_description, new_price);
      if(has_errors){
        return;
      }

      $(`#modal_bg`).remove();

      showGeneralLoading();

      const formData = new FormData();

      formData.append('description', new_description);
      formData.append('price', new_price);

      const response = await fetch(`${window.BASE_URL}/servicos/insert`, {
        method: 'POST',
        body: formData
      });

      const data = await response.json();

      showToast(data.msg, data.erro);
      hideGeneralLoading();
      await filterServices();
    }
    catch(err){
      showToast('Erro Desconhecido', true);
      hideGeneralLoading();
    }
  });
  
  $('#modal_cancel_button, #close_modal_button').on('click', async function(){
    $(`#modal_bg`).remove();
  });
})