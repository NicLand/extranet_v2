<?php
namespace Extranet;
use Extranet\Session;

class Mail{

  private $to;
  private $subject;
  private $message;
  private $boundary;
  private $passage_ligne;
  private $message_success = "Un courriel a été envoyé. <i>An email was succesfully sent.</i>";
  private $message_error = "Le courriel n'a pu être envoyé. <i>We are sorry for technical reasons, the email was not sent.</i>";
  private $from = "no-reply@mfp.cnrs.fr";

  public function __construct($to, $subject, $message, $from = false){

    $this->to = $to;
    $this->subject = $subject;
    $this->message = $message;
    $this->from = $from;

  }
  private function setBoundary(){
    if (!isset($this->boundary)){
    $this->boundary = "-----=".md5(rand());
  }
  return $this->boundary;

  }
  public function serverFilter(){

    if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $this->to)) // On filtre les serveurs qui rencontrent des bogues.
    {$passage_ligne = "\r\n";}
    else{$passage_ligne = "\n";}
    return $passage_ligne;
  }


  private function createHeader(){
    $boundary = $this->setBoundary();
    $passage_ligne = $this->serverFilter();

    $header = "From: no-reply@mfp.cnrs.fr".$passage_ligne;
    $header.= "Reply-to: ".$this->from." ".$passage_ligne;
    $header.= "MIME-Version: 1.0".$passage_ligne;
    $header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
    return $header;
  }

  private function createMessage(){
    $boundary = $this->setBoundary();
    $passage_ligne = $this->serverFilter();
    $content = $this->message;

    $message = $passage_ligne."--".$boundary.$passage_ligne;
    //=====Ajout du message au format HTML
    $message.= "Content-Type: text/html; charset=\"UTF-8\"".$passage_ligne;
    $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;

    $message.= $passage_ligne.$content.$passage_ligne;
    //==========
    $message.= $passage_ligne."--".$boundary."--".$passage_ligne;
    $message.= $passage_ligne."--".$boundary."--".$passage_ligne;
    //==========
    return $message;
  }

  public function sendMail(){
    $message = $this->createMessage();
    $header = $this->createHeader();

    if(mail($this->to,$this->subject,$message,$header))
         {
    	    return Session::getInstance()->setFlash('success', $this->message_success);
         }
         else
         {
           return Session::getInstance()->setFlash('danger', $this->message_error);
         }
  }
}
