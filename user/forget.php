<?php

  //===========================================================
  //Charge automatiquemebnt les Class utilis�es dans les pages
  // Dans le bon __NAMESPACE__
  namespace Extranet;

  require '../class/Autoloader.php';
  Autoloader::register();

  //===========================================================
  $menuItem=[];
  $rapidAccess=[];
  $title = 'Extranet MFP';

  //===========================================================
if(!empty($_POST) && !empty($_POST['email'])){

  $db = App::getDatabase();
  $auth = App::getAuth();
  $session = Session::getInstance();

  if($auth->resetPassword($db, $_POST['email'])){
    Session::getInstance()->setFlash('success' , "Les instructions vous ont �t� envoy�es par mail.");
    App::redirect('login.php');
    exit();
  }
  else{
    Session::getInstance()->setFlash('danger' , "Aucun compte ne correspond � cet adresse !");
  }
}

echo Header::getHeader("Extranet MFP",'index.php', $rapidAccess, $menuItem);
?>

<h1>Mot de passe oubli�</h1>
<form method="post" action="">
<?php
$form = new Form($_POST);
  echo $form->input('email','email','Votre Email : ');
  echo $form->button('submit','primary','R�initialiser mon mot de passe');
?>
</form>

<?= Footer::getFooter();
