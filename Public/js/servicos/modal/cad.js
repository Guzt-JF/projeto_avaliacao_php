// Script para o modal de edição de serviços

$(document).ready(()=>{
  // Função em caso de confirmação
  $('#modal_confirm_button').on('click', async function(){
    try{
      // Carrega na memória os campos que foram cadastrados na modal para se tornarem serviços
      const new_description = $('#cad_descricao').val();
      const new_price = $('#cad_total').val();

      // Verifica se há algum erro nos campos cadastrados, como um número invalido ou campo vazio
      const has_errors = verifyInputs(new_description, new_price);
      // Caso tenha ele vai só encerrar a função visto que a função já notifica o usuário de qual erro ocorreu
      if(has_errors){
        return;
      }

      // Mostra a tela de loading
      showGeneralLoading();

      // Carrega os dados em um FormData para serem enviados via requisição POST
      const formData = new FormData();

      formData.append('description', new_description);
      formData.append('price', new_price);

      // Realiza a requisição para atualizar os dados
      const response = await fetch(`${window.BASE_URL}/servicos/insert`, {
        method: 'POST',
        body: formData
      });

      // Espera carregar os dados e os converte para json
      const data = await response.json();

      // Notifica o usuário da conclusão
      showToast(data.msg, data.erro);

      // Remove a modal
      $(`#modal_bg`).remove();

      // Recarrega os dados exibidos na dashboard
      await filterServices();
    }
    catch(err){
      // Avisa ao usuário que houve um erro inesperado na execução
      console.error(err);
      showToast('Erro Desconhecido', true);
    }
    finally{
      // Remove o loading
      hideGeneralLoading();
    }
  });
  
  // Remove a modal
  $('#modal_cancel_button, #close_modal_button').on('click', async function(){
    $(`#modal_bg`).remove();
  });
})