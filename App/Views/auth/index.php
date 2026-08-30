<div class="panel_main_container">
  <div class="panel_div">
    <span class="panel_title">Sistema de Controle de Serviços</span>
    
    <span class="panel_error_span" id="panel_error_username"></span>

    <div class="panel_input_container" id="panel_email_container">
      <input class="panel_input" id="panel_email_input" type="text" placeholder="email@email.com"/>
    </div>

    <span class="panel_error_span" id="panel_error_email"></span>

    <div class="panel_input_container" id="panel_password_container">
      <input class="panel_input" id="panel_password_input" type="password" placeholder="••••••••••"/>
      <button class="panel_show_button" id="panel_pass_show">
        <img src="<?= BASE_URL  ?>/Public/lib/icons/eye-close.svg" alt="Mostrar senha" height="20" width="20" />
      </button>
      <button class="panel_show_button d-none" id="panel_pass_hide">
        <img src="<?= BASE_URL  ?>/Public/lib/icons/eye-open.svg" alt="Esconder senha" height="20" width="20" />
      </button>
    </div>
    <span class="panel_error_span" id="panel_error_password"></span>

    <div class="panel_buttons">
      <button class="panel_button" id="panel_sign_in">
        <div id="panel_loading_span" class="panel_loading loading d-none"></div>
        <span id="panel_sign_in_span" class="panel_span">Entrar</span>
      </button>
      <a class="panel_a" href="<?= BASE_URL ?>/auth/registro">Cadatrar Usuário</a>
    </div>
  </div>
</div>