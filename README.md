# Avaliacao-PHP-MYSQL

## Resumo

-   [Sobre](##Sobre)
-   [Stack](##Stack)
-   [Instalação](##Instalação)
	- [Clonar o projeto](###1.-Clonar-o-projeto)
	- [Configuração do servidor](###2.-Configuração-do-servidor)
	- [Configuração do banco de dados](###3.-Configuração-do-banco-de-dados)
	- [Configuração do envio de e-mails](###4.-Configuração-do-envio-de-e-mails)
	- [Executando o projeto](###5.-Executando-o-projeto)
-   [Descrição Original](#Descrição-Original)


## Sobre

Este é um projeto avaliativo, trata-se de sistema de cadastro e gerenciamento de serviços, com controle de usuários.
No geral eu busquei fazer tudo que foi pedido e mais, aplicando a minha visão como dev de priorizar um bom UX, por isso fiz algumas mudanças pontuais no design, visando um dinamismo maior, mas mantendo a visão proposta do aplicativo

## Stack

Foram utilizadas as seguintes tecnologias para a realização desde projeto

* **PHP 8.2.12** (XAMPP)
* **JavaScript / jQuery**
* **MySQL** (PDO)
* **PHPMailer** (O PHPMailer foi configurado manualmente, sem utilização do Composer)

---

## Instalação

### 1. Clonar o projeto

Para obter o projeto, clone o repositório utilizando:

```bash
git clone https://github.com/Guzt-JF/projeto_avaliacao_php.git
```

Também é possível realizar o download diretamente pelo GitHub e extrair os arquivos na pasta utilizada pelo seu servidor PHP.

Durante o desenvolvimento, foi utilizado o **XAMPP** por uma questão de praticidade. Porém, o projeto também pode ser executado utilizando uma instalação convencional do **Apache + PHP + MySQL**.

Caso utilize o Apache diretamente, certifique-se de que o módulo **`mod_rewrite`** esteja habilitado, pois o projeto utiliza regras de reescrita definidas no arquivo `.htaccess`.

---

### 2. Configuração do servidor

Após clonar ou extrair o projeto, coloque a pasta do projeto dentro do diretório utilizado pelo Apache.

No XAMPP, por exemplo:

```text
C:\xampp\htdocs\
```

O projeto deverá ficar assim:

```text
C:\xampp\htdocs\projeto_avaliacao_php\
```

Em seguida inicie os serviços **Apache** e **MySQL** pelo painel de controle do XAMPP.

Caso utilize **Apache** e **MySQL** standalone apenas copie o projeto para pasta utilizada pelo seu servidor PHP
e certifique-se que os serviços do **Apache** e **MySQL** estão operantes.

---

### 3. Configuração do banco de dados

Após garantir que o serviço **MySQL** está operando, abra o script em [Database/db.sql](Database/db.sql) e rode o script no seu servidor **MySQL**

Após isso vá até [App\Config\database.php](App\Config\database.php) e insira suas credenciais de ambiente no arquivo.

Entre as credenciais necessárias estão:

* **Host**
* **Porta**
* **Nome do banco** (que a menos que seja alterado manualmente é "projeto_avaliacao")
* **Usuário**
* **Senha**

---

### 4. Configuração do envio de e-mails

O projeto utiliza o **PHPMailer** para realizar o envio de e-mails através de SMTP e para usar é necessário ir em [App\Config\mail.php](App\Config\mail.php) e substituir os valores pelas suas credenciais, recomendo usar o SMTP do gmail, para isso é necessário criar uma senha de APP [aqui](https://myaccount.google.com/u/1/apppasswords?rapt=AEjHL4MwKdyN0CwmbyBoUH41KZa4lfodScuKq4we9kx6a50iT1OVf88wColdPMX1aQILg_syo8xxv-8lMv0d0wTJXwZapOX8jS8J3xu0zFBb1W0E9ZvcS7w) após criar a senha do app, você deve usar ela e o email utilizado nas credencias do app, para assim poder enviar os emails, caso contrario ao finalizar um serviço, um aviso vai aparecer 

Entre as credenciais necessárias estão:

* **SMTP Host**
* **SMTP Username** (o email utilizado para criar a senha)
* **SMTP Password**
* **SMTP Port**
* **Nome do remetente** (o nome que vai aparecer no envio do email)

---

### 5. Executando o projeto

Com o **Apache** e o **MySQL** em execução e as configurações realizadas, basta acessar o projeto através do navegador.

Caso esteja utilizando o XAMPP e tenha mantido o nome da pasta original:

```text
http://localhost/projeto_avaliacao_php/
```

---

<br>

# Descrição Original

## Observação a ser considerada pelo candidato, não é permitido o uso de Inteligência Artificial para o desenvolvimento do teste, pois o teste visa analisar o conhecimento do candidato. Nós realizamos a análise para verificar se foi utilizado algum padrão/ferramenta de IA. Caso seja constatado o uso de IA, o candidato estará automaticamente desclassificado.

## O projeto consiste em analisar o conhecimento nas seguintes tecnologias:

* PHP Orientado a Objetos
* Arquiteura MVC
* PDO com MySql
* Javascript ou JQuery

*Obs.: Favor enviar junto com o projeto o script da criação das tabelas.*

## Pontos a se considerar:
Código legível, comentado e manutenível.
Separe cada responsabilidade no seu arquivo correto.
Não poderá ser utilizado nenhuma forma de framework (backend e frontend)

# NÃO UTILIZAR COMPOSER PARA GERENCIAMENTO DE DEPENDÊNCIAS, O CANDIDATO QUE UTILIZAR SERÁ AUTOMATICAMENTE DESCLASSIFICADO.

O gestor da empresa JM Informática decide criar um sistema de ordem de serviços para controlar os serviços prestados pelos seus funcionários. O sistema deve permitir autenticar-se para acesso a tela inicial (dashboard). Na tela inicial deverá mostrar os dados do usuário logado, a data atual e os serviços prestados.

## Tela de Login com email ou senha inválidos
Dado que o usuário acesse tela de login
Quando quando não informar email e senha corretos
Então deve mostrar mensagem ‘Ops, Email ou Senha inválido’

## Tela de Login com email e senha válidos
Dado que o usuário acesse tela de login
Quando informar email e senha correto
Então deve ser redirecionado a tela inicial do sistema (Dashboard)

## Tela de Dashboard
Dado que o usuário acesse a tela de dashboard com usuário correto
Então devo ver uma tabela com os serviços prestados pelos funcionários apresentando as seguintes informações (id, descrição, status, valor, nome usuário) com botões de excluir, alterar o registro e um botão para finalizar serviço


## Tela de Dashboard (Valor Total dos Serviços Prestados pelo Usuário)
Dado que o usuário acesse a tela de dashboard com usuário correto
Então deve mostrar de forma destacada o valor total dos serviços prestados por este usuário.

## Tela de Dashboard (Serviços com status Pendentes Prestados pelo Usuário)
Dado que o usuário acesse a tela de dashboard com usuário correto
Então deve mostrar de forma destacada uma pequena lista com os últimos serviços prestados com status “Pendentes”.

## Tela de Dashboard (Marcar status como finalizado)
Dado que o usuário acesse a tela de dashboard com usuário correto
Então devo clicar no botão do registro a ser finalizado, gravar a data de finalização do serviço e enviar um email para o usuário do serviço, e calcular o valor da comissão. Os serviços que possuem data de finalização serão considerados como finalizados e os que não possuem serão considerados como pendentes.
	Para valores abaixo ou igual a R$ 1.000,00 será dado 5% de comissão
	Para valores acima de R$ 1.000,00 será dado 10% de comissão
	Para valores acima de R$ 10.000,00 será dado 20% de comissão.



## Tela de Dashboard (Filtro por período)
Dado que o usuário acesse a tela de dashboard com usuário correto
Quando informar filtro por período inicial e final
Então deve mostrar na tabela os serviços prestados dentro do período

## Tela de Dashboard (Filtro por nome do serviço)
Dado que o usuário acesse a tela de dashboard com usuário correto
Quando informar o nome do serviço
Então deve mostrar os serviços prestados com este nome

## Tela de Dashboard (Filtro por status do serviço)
Dado que o usuário acesse a tela de dashboard com usuário correto
Quando informar o status do serviço
Então deve mostrar os serviços prestados com este status.

## Tela de Dashboard (Filtro por usuário do serviço)
Dado que o usuário acesse a tela de dashboard com usuário correto
Quando informar o nome do usuário do serviço
Então deve mostrar os serviços prestados por este usuário.

## Tela de Dashboard (Adicionar Novo Serviço)
Dado que o usuário acesse a tela de dashboard com usuário correto
Quando clicar no botão de adicionar novo serviço
Então deve mostrar nova tela com formulário para cadastrar novo serviço.


## Tela de Cadastro de Serviço (Adicionar novo serviço com sucesso)
Dado que o usuário acesse a tela de cadastrar novo serviço
Quando informar as informações obrigatórias(descrição do serviço, valor)
Então deve cadastrar o novo serviço com status de “Pendente” para o usuário logado, mostrando mensagem de sucesso redirecionando para tela inicial.

## Tela de Cadastro de Serviço (Falha ao adicionar novo serviço)
Dado que o usuário acesse a tela de cadastrar novo serviço
Quando não informar as informações obrigatórias (descrição e valor) ou ocorrer algum erro
Então não deve cadastrar o novo serviço mostrando mensagem de falha redirecionando para tela inicial.

- [Wireframe](TesteTitanWireFrame.pdf)

- [Modelagem do Banco](model_teste_titan.pdf)


Boa sorte!!



