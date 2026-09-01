// Principal Script para a tela de dashboard

$(document).ready(async function(){
  // Carrega os dados do dashboard
  await filterServices();

  // Carrega os dados do dashboard qunado clica no botão de filtro
  $('#filter_button').on('click',async function(){
    await filterServices();
  })

  // Abre a modal de cadastro quando clica no botão de "Cadastrar Serviço"
  $('#cad_services').on('click', async function(){
      // Mostra a tela de loading
    showGeneralLoading();

    // Realiza a requisição GET para pegar a modal construida
    const response = await fetch(`${window.BASE_URL}/servicos/modal_cad`, {
      method: 'GET',
    });

    // Espera carregar os dados e os converte para json
    const modal_cad = await response.json();

    // Em caso de erro informa o usuário do ocorrido e encerra a função
    if(modal_cad.erro){
      showToast(modal_cad.msg, true);
      return;
    }
    
    // Em caso de sucesso mostra a modal
    $('body').append(modal_cad.data);

    // Remove o loading
    hideGeneralLoading();
  });

  // Abre a modal de edição quando clica no botão com ícone de lapis da tabela de serviços
  $('body').on('click', '.row_edit_button', async function(){
    // carrega na memória o id do serviço a ser editado
    const serv_id = $(this).data('id');
    // Mostra a tela de loading
    showGeneralLoading();

    // Realiza a requisição GET para carregar a modal de edição
    const response = await fetch(`${window.BASE_URL}/servicos/modal_edit?id=${serv_id}`, {
      method: 'GET',
    });

    // Espera carregar os dados e os converte para json
    const modal_edit = await response.json();

    // Em caso de erro informa o usuário do ocorrido e encerra a função
    if(modal_edit.erro){
      showToast(modal_edit.msg, true);
      return;
    }
    
    // Em caso de sucesso mostra a modal
    $('body').append(modal_edit.data);

      // Remove o loading
    hideGeneralLoading();
  })

  // Abre uma modal de confirmação para deletar um serviço
  $('body').on('click', '.row_delete_button', async function(){
    // carrega na memória o id do serviço a ser editado
    const serv_id = $(this).data('id');

    // inicia uma promise para o usuário tomar sua decisão
    new Promise((res, rej)=>{
      confirmModal('Atenção!','Deseja mesmo deletar este serviço?', res, rej)
    })
    .then(async ()=>{
      // Mostra a tela de loading
      showGeneralLoading();

      // Carrega os dados em um FormData para serem enviados via requisição POST
      const formData = new FormData();

      formData.append('id', serv_id);

      // Realiza a requisição para deletar um serviço
      const response = await fetch(`${window.BASE_URL}/servicos/delete`, {
        method: 'POST',
        body: formData
      });

      // Espera carregar os dados e os converte para json
      const data = await response.json();

      // Notifica o usuário da conclusão
      showToast(data.msg, data.erro);
      
      // Remove o loading
      hideGeneralLoading();
      
      // Carrega os dados do dashboard
      await filterServices();
    })
    .catch(()=>{})
  })

  // Abre uma modal de confirmação para finalizar um serviço
  $('body').on('click', '.row_finish_button', async function(){
    // carrega na memória o id do serviço a ser editado
    const serv_id = $(this).data('id');

    // inicia uma promise para o usuário tomar sua decisão
    new Promise((res, rej)=>{
      confirmModal('Atenção!','Deseja mesmo finalizar este serviço?', res, rej)
    })
    .then(async ()=>{
      // Mostra a tela de loading
      showGeneralLoading();

      // Carrega os dados em um FormData para serem enviados via requisição POST
      const formData = new FormData();

      formData.append('id', serv_id);

      // Realiza a requisição para finalizar um serviço
      const response = await fetch(`${window.BASE_URL}/servicos/finish`, {
        method: 'POST',
        body: formData
      });

      // Espera carregar os dados e os converte para json
      const data = await response.json();

      // Notifica o usuário da conclusão
      showToast(data.msg, data.erro);

      // Remove o loading
      hideGeneralLoading();
      // Carrega os dados do dashboard
      await filterServices();
    })
    .catch(()=>{})
  })
})

// função para Carregar os da table de serviços, também já executa a função
// para carregar os outros dados, como os totais e as pendencias
async function filterServices(){
  try{
    // mostra o loading da table de serviços
    $('#loading_table').removeClass('d-none');

    // agrupa os parâmetros a serem utilizados
    const params = { 
      username: $('#filter_nome_usuario').val(), 
      description: $('#filter_descricao').val(),
      status: $('#filter_status').val(),
      start_date: $('#filter_data_inicial').val(),
      end_date: $('#filter_data_final').val()
    };

    // converte o json em uma url para ser utilizada na request GET
    const query = new URLSearchParams(params).toString();

      // Realiza a requisição para carregar os dados
    const response = await fetch(`${window.BASE_URL}/servicos/filter?${query}`, {
      method: 'GET'
    });

    // Espera carregar os dados e os converte para json
    const data = await response.json();

    // Em caso de problema, lança um erro pro catch
    if(data.erro){
      throw new Error(data.msg);
    }

    // Executa a função para carregar as informações no topo da pagina de dashboard
    await updateHeaderData();
    // mostra os dados na table de serviços e remove o loading da mesma
    $('#services_table').html(data.data)
    $('#loading_table').addClass('d-none');
  }
  catch(err){
    // Em caso de erro mostra o porque da falha e remove o loading da table de serviços
    showToast(err.message, true);
    $('#loading_table').addClass('d-none');
    return;
  }
}


// Função para carregar as informações no topo da pagina de dashboard
// como os totais e as pendencias e os ultimos serviços prestados
async function updateHeaderData(){
  try{
    // Realiza a requisição GET para carregar os dados do topo da dashboard
    const response = await fetch(`${window.BASE_URL}/dashboard/get_header_data`, {
      method: 'GET'
    });

    // Espera carregar os dados e os converte para json
    const data = await response.json();

    // Em caso de problema, lança um erro pro catch
    if(data.erro){
      throw new Error(data.msg);
    }

    // Em caso de sucesso, mostra os dados na tela
    $('#dashboard_header').html(data.data)
  }
  catch(err){
    // Em caso de erro mostra o porque da falha e encerra a função
    showToast(err.message, true);
    return;
  }
}

// Função para verificar os inputs usados no cadastro e edição de modais
function verifyInputs(description, price){
  // verifica se a descrição do produto está vazia
  if(description.trim() == ''){
    showToast("A descrição do produto está vazia", true);
    return true;
  }
  // verifica se o valor do produto está vazio
  if(price.trim() == ''){
    showToast("O valor do produto está vazio", true);
    return true;
  }
  // verifica se o valor do produto é invalido
  if (!Number.isFinite(Number(price.replace(/\./g, '').replace(',', '.')))) {
    showToast("A valor do produto é invalido", true);
    return true;
  }

  // caso não entre em nenhum desses casos ele encerra a função
  return false
}

