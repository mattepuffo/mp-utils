<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Invio email tramite PHPMailer
 *
 * @author Matteo Ferrone
 * @since 2024-06-11
 * @version 2.6.3
 */
class Email {

  private $email;

  public function __construct() {
    $this->email = new PHPMailer(TRUE);
    $this->email->CharSet = 'UTF-8';
  }

  public function sendClassic($from, $fromName, $subject, $message, $arrayTo, $arrayBcc = NULL, $attach = NULL) {
    try {
      $this->email->From = $from;
      $this->email->FromName = $fromName;
      $this->email->Subject = $subject;
      $this->email->addReplyTo($from);
      $this->email->MsgHTML($message);

      foreach ($arrayTo as $t) {
        $this->email->AddAddress($t);
      }

      if ($arrayBcc != NULL) {
        foreach ($arrayBcc as $b) {
          $this->email->AddBCC($b);
        }
      }

      if ($attach != NULL) {
        $this->email->AddAttachment($attach);
      }

      $this->email->Send();

      return json_encode(
          array(
              "res" => "ok",
              "message" => "Email inviata"
          )
      );
    } catch (Exception $e) {
      return json_encode(
          array(
              "res" => "ko",
              "message" => "Si è verificato un errore: " . $e->getMessage()
          )
      );
    }
  }

  public function sendWithSmtp($from, $fromName, $subject, $message, $host, $arrayTo, $arrayBcc = NULL, $port = 25, $attach = NULL) {
    try {
      $this->email->IsSMTP();
      $this->email->Host = $host;
      $this->email->Port = $port;
      $this->email->From = $from;
      $this->email->FromName = $fromName;
      $this->email->Subject = $subject;
      $this->email->addReplyTo($from);
      $this->email->MsgHTML($message);

      foreach ($arrayTo as $t) {
        $this->email->AddAddress($t);
      }

      if ($arrayBcc != NULL) {
        foreach ($arrayBcc as $b) {
          $this->email->AddBCC($b);
        }
      }

      if ($attach != NULL) {
        $this->email->AddAttachment($attach);
      }

      $this->email->Send();

      return json_encode(
          array(
              "res" => "ko",
              "message" => "Emaill inviata"
          )
      );
    } catch (Exception $e) {
      return json_encode(
          array(
              "res" => "ok",
              "message" => "Si è verificato un errore " . $e->getMessage()
          )
      );
    }
  }

  public function sendWithSmtpCredential($from, $fromName, $subject, $message, $host, $port, $username, $password, $arrayTo, $arrayBcc = NULL, $attach = NULL) {
    try {
      $this->email->IsSMTP();
//            $this->email->SMTPDebug = 1;
      $this->email->SMTPAuth = true;
      $this->email->SMTPSecure = 'ssl';
      $this->email->Host = $host;
      $this->email->Port = $port;
      $this->email->Username = $username;
      $this->email->Password = $password;
      $this->email->SetFrom($from, $fromName);
      $this->email->Subject = $subject;
      $this->email->MsgHTML($message);
      foreach ($arrayTo as $t) {
        $this->email->AddAddress($t);
      }
      if ($arrayBcc != NULL) {
        foreach ($arrayBcc as $b) {
          $this->email->AddBCC($b);
        }
      }
      if ($attach != NULL) {
        $this->email->AddAttachment($attach);
      }
      $this->email->Send();
      return json_encode(
          array(
              "res" => "ok",
              "message" => "Email inviata"
          )
      );
    } catch (Exception $e) {
      return json_encode(
          array(
              "res" => "ko",
              "message" => "Si è verificato un errore " . $e->getMessage()
          )
      );
    }
  }

}
