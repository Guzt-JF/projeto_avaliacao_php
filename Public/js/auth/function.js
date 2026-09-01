// Função para remover os visuais que indicam erro em um input
// Normalmente acionados após o usuário falhar na verificação de um input
// e então digitar algo, foi feita mais como um toque visual
function cleanInput(input_name){
  $(`#panel_error_${input_name}`).html('');
  $(`#panel_error_${input_name}`).addClass('d-none');
  $(`#panel_${input_name}_container`).removeClass('panel_error_container');
}

// Função para Verifcar os inputs do cadastro e do login
function verifyInputs(){
  let valid = true;
  // regex para verificar se o formato do email esta correto  
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  // Verifica se o nome de usuário está vazio
  if($('#panel_username_input').length && !$('#panel_username_input').val()){
    $('#panel_error_username').html('Nome de usuário Vazio');
    $('#panel_error_username').removeClass('d-none');
    $('#panel_username_container').addClass('panel_error_container')
    valid = false;
  }

  // Verifica se o Email está vazio
  if(!$('#panel_email_input').val()){
    $('#panel_error_email').html('Email Vazio');
    $('#panel_error_email').removeClass('d-none');
    $('#panel_email_container').addClass('panel_error_container')
    valid = false;
  }
  // Verifica se o Email é valido
  else if(!emailRegex.test($('#panel_email_input').val())) {
    $('#panel_error_email').html('Email Inválido');
    $('#panel_error_email').removeClass('d-none');
    $('#panel_email_container').addClass('panel_error_container')
    valid = false;
  }

  // Verifica se o senha está vazio
  if(!$('#panel_password_input').val()){
    $('#panel_error_password').html('Senha Vazia');
    $('#panel_error_password').removeClass('d-none');
    $('#panel_password_container').addClass('panel_error_container')
    valid = false;
  }

  return valid;
}

// Função para esconder a senha mudando o type do input
function hidePassword(){
  $("#panel_pass_show").removeClass('d-none')
  $("#panel_pass_hide").addClass('d-none')
  $('#panel_password_input').attr('type','password');
}

// Função para mostrar a senha mudando o type do input
function showPassword(){
  $("#panel_pass_show").addClass('d-none')
  $("#panel_pass_hide").removeClass('d-none')
  $('#panel_password_input').attr('type','text');
}

// Função para mostrar o carregamento de um input, além de o desabilitar
function startLoadingInput(id){
  $(`#${id}`).attr('disabled', true);
  $(`#${id} > .panel_loading`).removeClass('d-none');
  $(`#${id} > .panel_span`).addClass('d-none');
}

// Função para remover o carregamento de um input, além de o re-habilitar
function stopLoadingInput(id){
  $(`#${id}`).removeAttr('disabled');
  $(`#${id} > .panel_loading`).addClass('d-none');
  $(`#${id} > .panel_span`).removeClass('d-none');
}