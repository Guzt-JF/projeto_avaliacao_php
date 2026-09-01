// Principal Script para a tela de auth

$(document).ready(()=>{
  // Função para o click do botão de Login
  $('#panel_sign_in').on('click',async function(){
    try{
      // executa função para ver se os inputs foram preenchidos coretamente
      const input_verify = verifyInputs();

      // Caso não seja ele encerra a função
      if(!input_verify){
        return;
      }

      // Função para mostrar o loading dentro do input
      startLoadingInput('panel_sign_in')

      // Carrega os dados em um FormData para serem enviados via requisição POST
      const formData = new FormData();

      formData.append('email', $('#panel_email_input').val());
      formData.append('password', $('#panel_password_input').val());

      // Realiza a requisição POST para realizar o login
      const response = await fetch(`${window.BASE_URL}/auth/sign_in`, {
        method: 'POST',
        body: formData
      });

      // Espera carregar os dados e os converte para json
      const data = await response.json();

    // Em caso de problema, lança um erro pro catch
      if(data.erro){
        throw new Error(data.msg);
      }

      window.location.href = `${window.BASE_URL}/`;
    }
    catch(err){
    // Em caso de erro mostra o porque da falha e remove o loading do input
      showToast(err.message, true);
      stopLoadingInput('panel_sign_in')
      return;
    }
  });

  // Função para esconder senha no click do botão de esconder senha
  $('#panel_pass_hide').on('click', function(){
    hidePassword()
  })

  // Função para mostrar senha no click do botão de mostrar senha
  $('#panel_pass_show').on('click', function(){
    showPassword()
  });

  // Função para limpar o visual do campo de email caso ele esteja com erro
  $("#panel_email_input").on('change, keydown',function(){
    cleanInput('email');
  })

  // Função para limpar o visual do campo de senhas caso ele esteja com erro
  $("#panel_password_input").on('change, keydown',function(){
    cleanInput('password');
  })
});