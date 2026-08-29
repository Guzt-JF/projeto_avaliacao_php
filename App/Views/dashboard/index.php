<div class="main_container">
  <div class="drawer_container">
     <span>Logado como:</span> 
     <span>José Silva</span> 
     <div class="drawer_options">
      <a href="<?= BASE_URL ?>/servicos/cadastro">Cadastrar Serviço</a> 
     </div>
  </div>
  <div class="content">
    <h1>DASHBOARD</h1>
    <div class="latest_container">
      <div class="latest_div">
        <h2>Ultimos Serviços</h2>
        <span>0000001 - Troca de tela de Notebook</span>
        <span>0000002 - Conserto de carregador</span>
        <span>0000003 - Troca de pasta térmica</span>
      </div>
      <div class="latest_div">
        <h2>Serviços Pendentes</h2>
        <span>0000004 - Instalação do Office 2016</span>
        <span>0000005 - Reparo de Sistema Operacional</span>
        <span>0000006 - Troca de Memória</span>
      </div>
    </div>
    <div class="filter_inputs">
      <input type="text" placeholder="Nome"/>
      <input type="date" placeholder="Data inicial"/>
      <input type="date" placeholder="Data final"/>
      <button>Filtrar</button>
    </div>
    <div class="table_container">
      <table class="dashboard_table">
        <thead>
          <tr>
            <td>ID</td>
            <td>DESCRIÇÃO</td>
            <td>VALOR</td>
            <td>STATUS</td>
          </tr>
        </thead>
        <tbody>
         <tr>
            <td>4585874</td>
            <td>Troca de Tela LED</td>
            <td>R$ 425,00</td>
            <td>PENDENTE</td>
          </tr>
          <tr>
            <td>9945258</td>
            <td>Limpeza de Computador</td>
            <td>R$ 100,00</td>
            <td>FINALIZADO</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>