function cleanInput(input_name){
  $(`#panel_error_${input_name}`).html('');
  $(`#panel_error_${input_name}`).addClass('d-none');
  $(`#panel_${input_name}_container`).removeClass('panel_error_container');
}

function verifyInputs(){
  let error_input = false;
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if($('#panel_username_input').length && !$('#panel_username_input').val()){
    $('#panel_error_username').html('Nome de usuário Vazio');
    $('#panel_error_username').removeClass('d-none');
    $('#panel_username_container').addClass('panel_error_container')
    error_input = true;
  }

  if(!$('#panel_email_input').val()){
    $('#panel_error_email').html('Email Vazio');
    $('#panel_error_email').removeClass('d-none');
    $('#panel_email_container').addClass('panel_error_container')
    error_input = true;
  }
  else if(!emailRegex.test($('#panel_email_input').val())) {
    $('#panel_error_email').html('Email Inválido');
    $('#panel_error_email').removeClass('d-none');
    $('#panel_email_container').addClass('panel_error_container')
    error_input = true;
  }

  if(!$('#panel_password_input').val()){
    $('#panel_error_password').html('Senha Vazia');
    $('#panel_error_password').removeClass('d-none');
    $('#panel_password_container').addClass('panel_error_container')
    error_input = true;
  }

  return error_input;
}

function hidePassword(){
  $("#panel_pass_show").removeClass('d-none')
  $("#panel_pass_hide").addClass('d-none')
  $('#panel_password_input').attr('type','password');
}

function showPassword(){
  $("#panel_pass_show").addClass('d-none')
  $("#panel_pass_hide").removeClass('d-none')
  $('#panel_password_input').attr('type','text');
}

function startLoadingInput(id){
  $(`#${id}`).attr('disabled', true);
  $(`#${id} > .panel_loading`).removeClass('d-none');
  $(`#${id} > .panel_span`).addClass('d-none');
}

function stopLoadingInput(id){
  $(`#${id}`).removeAttr('disabled');
  $(`#${id} > .panel_loading`).addClass('d-none');
  $(`#${id} > .panel_span`).removeClass('d-none');
}