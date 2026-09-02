<?php

// define o namespace como App\Core
namespace App\Core;

// importa classes do phpmailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// importa funções do phpmailer
require_once BASE_PATH . 'Public/lib/PHPMailer/src/SMTP.php';
require_once BASE_PATH . 'Public/lib/PHPMailer/src/PHPMailer.php';
require_once BASE_PATH . 'Public/lib/PHPMailer/src/Exception.php';

// classe para enviar email
class Mail
{
  // função para enviar email
  public function send(string $to, string $subject, string $message) {
    $mail = new PHPMailer(true);
    // importa as configurações aqui
    $config = require BASE_PATH . '/App/Config/mail.php';

    try {
      // define que o envio vai ser feito em um serivdor smtp
      $mail->isSMTP();

      // define o host do servidor smtp
      $mail->Host       = $config['host'];
      // define a porta do servidor smtp
      $mail->Port       = $config['port'];
      // define a codificação dos caracteres do email
      $mail->CharSet    = 'UTF-8';
      // liga a autenticação smtp
      $mail->SMTPAuth   = true;
      // define o email para a autenticação
      $mail->Username   = $config['email'];
      // define a senha para a autenticação
      $mail->Password   = $config['password'];
      // define a encriptação utilizada para o envio do email
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      // define o assunto do email
      $mail->Subject    = $subject;
      // define a mensagem do email
      $mail->Body       = $message;
      
      // define o email e o nome do remetente
      $mail->setFrom(
        $config['email'],
        $config['sender_name']
      );

      // define o endereço de email a ser enviado
      $mail->addAddress($to);
      // define que a mensagem a ser enviada é escrita em html, e não é só um texto
      $mail->isHTML(true);

      // aqui o PHPMailer tenta fazer o envio do email 
      $mail->send();

      // caso ele consiga vai retornar uma mensagem para o usuário
      return ['erro' => 0, 'msg'  => 'E-mail enviado com sucesso', 'data' => []];
    } catch (Exception $e) {
      // caso contrario vai retornar uma mensagem de erro
      return ['erro' => 1, 'msg'  => 'Erro ao enviar e-mail - '.$mail->ErrorInfo,'data' => []];
    }
  }
}