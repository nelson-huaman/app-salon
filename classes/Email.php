<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

   public $email;
   public $nombre;
   public $apellido;
   public $token;

   public function __construct( $nombre, $apellido, $email, $token) {

      $this->nombre = $nombre;
      $this->apellido = $apellido;
      $this->email = $email;
      $this->token = $token;
      
   }

   public function enviarConfirmacion() {

      $mail = new PHPMailer();
      $mail->isSMTP();
      $mail->Host = $_ENV['EMAIL_HOST'];
      $mail->SMTPAuth = true;
      $mail->Port = $_ENV['EMAIL_PORT'];
      $mail->Username = $_ENV['EMAIL_USER'];
      $mail->Password = $_ENV['EMAIL_PASS'];

      $mail->setFrom('cuentas@appsalon.com');
      $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
      $mail->Subject = 'Confirmar tu Cuenta';

      $mail->isHTML(true);
      $mail->CharSet = 'UTF-8';

      $contenido = "<html>";
      $contenido .= "<p><b>" . $this->nombre . " " . $this->apellido . "</b> Has creado tu Cuenta en App Salón, solo debes de confirmar precsionando en el siguiente enlace.</p>";
      $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['APP_URL'] ."/confirmar-cuenta?token=" . $this->token . "'> Confirmar Cuenta</a></p>";
      $contenido .= "<p>Si tu no solicitastes este cambo, puedes ignorar el Mensaje</p>";
      $contenido .= "</html>";
      $mail->Body = $contenido;

      $mail->send();

   }

   public function enviarInstrucciones() {

      $mail = new PHPMailer();
      $mail->isSMTP();
      $mail->Host = $_ENV['EMAIL_HOST'];
      $mail->SMTPAuth = true;
      $mail->Port = $_ENV['EMAIL_PORT'];
      $mail->Username = $_ENV['EMAIL_USER'];
      $mail->Password = $_ENV['EMAIL_PASS'];

      $mail->setFrom('cuentas@appsalon.com');
      $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
      $mail->Subject = 'Reestablece tu Password';

      $mail->isHTML(true);
      $mail->CharSet = 'UTF-8';

      $contenido = "<html>";
      $contenido .= "<p><b>" . $this->nombre . " " . $this->apellido . "</b> Has solicitado reestablecer tu password, sigue el siguiente elnace para hacerlo.</p>";
      $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['APP_URL'] ."/recuperar?token=" . $this->token . "'>Reestablecer Password</a></p>";
      $contenido .= "<p>Si tu no solicitastes este cambo, puedes ignorar el Mensaje</p>";
      $contenido .= "</html>";
      $mail->Body = $contenido;

      $mail->send();

   }

}