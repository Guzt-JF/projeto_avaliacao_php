$(document).ready(async function(){
  await filterServices();

  $('#filter_button').on('click',async function(){
    await filterServices();
  })

  $('body').on('click', '.row_edit_button', async function(){
    const serv_id = $(this).data('id');

    const price = Number($(`#row_price_${serv_id}`).data('real_value')).toLocaleString('pt-BR', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
    const description = $(`#row_description_${serv_id}`).html();

    const modalId = openModal(
      `Editar Serviço N° ${serv_id}`,`
        <div class="modal_content">
          <div class="edit_container">
            <div class="input_container">
              <label>Descrição:</label>
              <input type="text" id="edit_descricao" value="${description}">
            </div>
            <div class="input_container">
              <label>Valor:</label>
              <input type="text" id="edit_total" value="${price}">
            </div>
          </div>
        </div>
        <div class="modal_footer">
          <button id="modal_cancel_button" class="modal_cancel_button modal_buttons">Cancelar</button>
          <button id="modal_confirm_button" class="modal_confirm_button modal_buttons">Confirmar</button>
        </div>
      `);

    $('#modal_confirm_button').on('click', async function(){
      const new_description = $('#edit_descricao').val();
      const new_price = $('#edit_total').val();
      if(new_description.trim() == ''){
        showToast("A descrição do produto está vazia", true);
        return;
      }
      else if(new_price.trim() == ''){
        showToast("O valor do produto está vazio", true);
        return;
      }
      else if (!Number.isFinite(Number(new_price.replace(/\./g, '').replace(',', '.')))) {
        showToast("A valor do produto é invalido", true);
        return;
      }

      $(`#modal_bg_${modalId}`).remove();
      showGeneralLoading();

      const formData = new FormData();

      formData.append('description', new_description);
      formData.append('price', new_price);
      formData.append('id', serv_id);

      const response = await fetch(`${window.BASE_URL}/servicos/update`, {
        method: 'POST',
        body: formData
      });

      const data = await response.json();

      showToast(data.msg, data.erro);
      hideGeneralLoading();
      await filterServices();
    });
    
    $('#modal_cancel_button').on('click', async function(){
      $(`#modal_bg_${modalId}`).remove();
    });
  })

  $('body').on('click', '.row_delete_button', async function(){
    const serv_id = $(this).data('id');

    new Promise((res, rej)=>{
      confirmModal('Atenção!','Deseja mesmo deletar este serviço?', res, rej)
    })
    .then(async ()=>{
      showGeneralLoading();

      const formData = new FormData();

      formData.append('id', serv_id);

      const response = await fetch(`${window.BASE_URL}/servicos/delete`, {
        method: 'POST',
        body: formData
      });

      const data = await response.json();

      showToast(data.msg, data.erro);
      
      hideGeneralLoading();
      await filterServices();
    })
    .catch(()=>{})
  })

  $('body').on('click', '.row_finish_button', async function(){
    const serv_id = $(this).data('id');

    new Promise((res, rej)=>{
      confirmModal('Atenção!','Deseja mesmo finalizar este serviço?', res, rej)
    })
    .then(async ()=>{
      showGeneralLoading();

      const formData = new FormData();

      formData.append('id', serv_id);

      const response = await fetch(`${window.BASE_URL}/servicos/finish`, {
        method: 'POST',
        body: formData
      });

      const data = await response.json();

      showToast(data.msg, data.erro);

      hideGeneralLoading();
      await filterServices();
    })
    .catch(()=>{})
  })
})

async function filterServices(){
  try{
    $('#loading_table').removeClass('d-none');

    const params = { 
      username: $('#filter_nome_usuario').val(), 
      description: $('#filter_descricao').val(),
      status: $('#filter_status').val(),
      start_date: $('#filter_data_inicial').val(),
      end_date: $('#filter_data_final').val()
    };

    const query = new URLSearchParams(params).toString();

    const response = await fetch(`${window.BASE_URL}/servicos/filter?${query}`, {
      method: 'GET'
    });

    const data = await response.json();

    if(data.erro){
      throw new Error(data.msg);
    }

    await updateHeaderData();
    $('#services_table').html(data.data)
    $('#loading_table').addClass('d-none');
  }
  catch(err){
    showToast(err.message, true);
    $('#loading_table').addClass('d-none');
    return;
  }
}

async function updateHeaderData(){
  try{
    const response = await fetch(`${window.BASE_URL}/dashboard/getHeaderData`, {
      method: 'GET'
    });

    const data = await response.json();

    if(data.erro){
      throw new Error(data.msg);
    }

    $('#dashboard_header').html(data.data)
  }
  catch(err){
    showToast(err.message, true);
    return;
  }
}

