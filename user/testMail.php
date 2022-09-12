<?php

//===========================================================
//Charge automatiquemebnt les Class utilisées dans les pages
// Dans le bon __NAMESPACE__
namespace Extranet;

require '../class/Autoloader.php';
Autoloader::register();

  $subject = 'email de confirmation';
  $message = 'voici votre email de confirmation';
  $to  = 'test-72a242ty2@srv1.mail-tester.com';

  $title = 'Extranet MFP';

  $rapidAccess = [];
  $menuItem = [];

  echo Header::getHeader("Extranet MFP",'index.php', $rapidAccess, $menuItem);




$mail = new Mail($to, $subject, $message);

var_dump($mail);

$mail->sendMail();

echo Footer::getFooter();
