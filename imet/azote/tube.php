<?php
//===========================================================
//Charge automatiquemebnt les Class utilis�es dans les pages
// Dans le bon __NAMESPACE__
namespace Extranet;

require '../../class/Autoloader.php';
Autoloader::register();

$auth = App::getAuth();
$user = $auth->user();

if(!$user){
  Session::setFlash('danger', "Veuillez vous identifier.");
  App::redirect('login.php');
  exit();
}
$access = new Access("imet", $user);
if(!$access->access()){
  Session::getInstance()->setFlash('danger', "Vous n'�tes pas autoris� � acc�der � cette page.");
  App::redirect('../index.php');
  exit();
}
//===========================================================
if(isset($_GET['id'])){
  $id = $_GET['id'];
}else{
  App::redirect('imet/azote/index.php');
  exit();
}

if(!empty($_POST)){
  //var_dump($_POST);
  if(!empty($_POST['new'])){
    $validator = new Validator($_POST);
    if($validator->isValid()){
      $new = new TeamIMet;
      if($new->newAzote($id,$_POST['souche'],$_POST['forme'],$_POST['modification'],$_POST['date'],$_POST['manipulateur'],$_POST['commentaire'])){
        Session::getInstance()->setFlash('success', "Tube congele !");
        App::redirect('imet/azote/');
        exit();
      }
    }
    else{
      $errors = $validator->getErrors();
    }
  }
  if(!empty($_POST['delete'])){
    $del = new TeamIMet;
    $del->newLog($id,$user->name);
    //if($del->delAzote($id)){
    //  Session::getInstance()->setFlash('success', "Tube decongele !");
    //  App::redirect('imet/azote/');
    //  exit();
  //  }
  }
}


//===========================================================
$rapidAccess = TeamIMet::getRapidAccess();
$menuItem = [];

$title = 'iMet';
$titleLink = 'imet/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);

$azote = new TeamIMet();
$vial = $azote->getAzoteTube($id);
//var_dump($vial);
$position = "Container : $vial->container | Rack : $vial->tige | Boite : $vial->boite | Place : $vial->place";
$form = new TeamIMetForm($_POST);
echo "<form method='post' action='#' enctype='multipart/form-data'>";
echo "<h3>Position : $position</h3>";
echo $form->selectAzoteSouche('souche','Souche :','',$vial->strain);
echo $form->selectAzoteForme('forme','Forme :','',$vial->forme);
echo $form->input('modification','text','Modification :',$vial->modification);
echo $form->input('date','text',"Date : ",$vial->date1);
echo $form->input('manipulateur','text','Manipulateur :',$vial->manipulateur);
echo $form->textArea('commentaire','Commentaires :',$vial->commentaire);
echo $form->submit('primary','new','Congeler');
echo $form->iMetDelete('Decongeler','danger');

echo "</form>";
?>
<?= Footer::getFooter();
