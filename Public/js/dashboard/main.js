$(document).ready(async function(){
  await filterServices();

  $('#filter_button').on('click',async function(){
    await filterServices();
  })

  $('#cad_services').on('click', async function(){
    showGeneralLoading();

    const response = await fetch(`${window.BASE_URL}/servicos/modal_cad`, {
      method: 'GET',
    });

    const modal_cad = await response.json();

    if(modal_cad.erro){
      showToast(modal_cad.msg, true);
      return;
    }
    
    $('body').append(modal_cad.data);

    hideGeneralLoading();
  });

  $('body').on('click', '.row_edit_button', async function(){
    const serv_id = $(this).data('id');
      showGeneralLoading();

    const response = await fetch(`${window.BASE_URL}/servicos/modal_edit?id=${serv_id}`, {
      method: 'GET',
    });

    const modal_edit = await response.json();

    if(modal_edit.erro){
      showToast(modal_edit.msg, true);
      return;
    }
    
    $('body').append(modal_edit.data);

    hideGeneralLoading();
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
    const response = await fetch(`${window.BASE_URL}/dashboard/get_header_data`, {
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

function verifyInputs(description, price){
  if(description.trim() == ''){
    showToast("A descrição do produto está vazia", true);
    return true;
  }
  else if(price.trim() == ''){
    showToast("O valor do produto está vazio", true);
    return true;
  }
  else if (!Number.isFinite(Number(price.replace(/\./g, '').replace(',', '.')))) {
    showToast("A valor do produto é invalido", true);
    return true;
  }
  return false
}

