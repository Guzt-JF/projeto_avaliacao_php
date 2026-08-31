$(document).ready(()=>{
  $('#panel_sign_up').on('click' ,async function(){
    try{
      const input_verify = verifyInputs();

      if(input_verify){
        return;
      }

      startLoadingInput('panel_sign_up')

      const formData = new FormData();

      formData.append('username', $('#panel_username_input').val());
      formData.append('email', $('#panel_email_input').val());
      formData.append('password', $('#panel_password_input').val());

      const response = await fetch(`${window.BASE_URL}/auth/sign_up`, {
        method: 'POST',
        body: formData
      });

      const data = await response.json();

      if(data.erro){
        throw new Error(data.msg);
      }

      window.location.href = `${window.BASE_URL}/`;
    }
    catch(err){
      showToast(err.message, true);
      stopLoadingInput('panel_sign_up')
      return;
    }
  });

  $('#panel_pass_hide').on('click', function(){
    hidePassword()
  })

  $('#panel_pass_show').on('click', function(){
    showPassword()
  });

  $("#panel_username_input").on('change, keydown',function(){
    cleanInput('username');
  })

  $("#panel_email_input").on('change, keydown',function(){
    cleanInput('email');
  })

  $("#panel_password_input").on('change, keydown',function(){
    cleanInput('password');
  })
});