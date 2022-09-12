<?php

  //===========================================================
  //Charge automatiquemebnt les Class utilisÃ©es dans les pages
  // Dans le bon __NAMESPACE__
  namespace Extranet;

  require '../class/Autoloader.php';
  Autoloader::register();

  //===========================================================
  $menuItem=[];
  $rapidAccess=[];
  $title = 'Extranet MFP';

  //===========================================================

  $db = App::getDatabase();

  if (App::getAuth()->confirm($db, $_GET['id'], $_GET['token'])){
    Session::getInstance()->setFlash('success', "Votre demande d'inscription est valide, vous receverez un email pour vous informer quend votre compte sera actif .");
    App::redirect('/index.php');
    exit();
    }
  else{
    Session::getInstance()->setFlash('danger', "Ce jeton de confirmation n'est plus valide");
    App::redirect('login.php');
    exit();
    }

    //var_dump($_SESSION);

    echo Header::getHeader("Extranet MFP",'index.php', $rapidAccess, $menuItem);

?>

<?= Footer::getFooter();
