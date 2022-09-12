<?php
//===========================================================
//Charge automatiquemebnt les Class utilisées dans les pages
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
$access = new Access("proparacyto", $user);
if(!$access->access()){
  Session::setFlash('danger', "Vous n'êtes pas autorisé à accéder à cette page.");
  App::redirect('index.php');
  exit();
}
//===========================================================
// Verification des post
if(!empty($_POST)){
  $errors = [];
  $validator = new Validator($_POST);
  
  if($_POST['user'] === "0"){
    $validator->isText('name','The name is not valid.');
    $validator->isText('firstname','The firstname is not valid.');
  }
  else{
    $validator->isSelect('user','Please choose an user to add to the past member list');
  }
  $validator->isText('team_pos','The team position is not valid.');
  $validator->isYear('annee_debut','The starting year is not valid.');
  $validator->isYear('annee_fin','The ending year is not valid.');
  $validator->isYearChrono('annee_debut','annee_fin', "The start and end years are not chronological.");
  $validator->isText('current_pos', "The current position is not valid.");

  if($validator->isValid()){
    $new = new cskPastMember;
    $past = $new->newPastMember($_POST['user'], $_POST['team_pos'],$_POST['annee_debut'], $_POST['annee_fin'],$_POST['current_pos']);
    Session::getInstance()->setFlash('success', 'The past member has been recorded.');
  }
  else{
      $errors = $validator->getErrors();
    }
}

//===========================================================
$rapidAccess = TeamProparacyto::getRapidAccess();
$menuItem = [];

$title = 'ProParaCyto';
$titleLink = 'proparacyto/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors,"EN");}

echo "<h1 class='mt-3'>Past Members</h1>";

$form = new cskForm($_POST);
echo '<div class="alert alert-info" role="alert">Choose preferentially in the selector to keep the investigator in the different part of the Extranet</div>';

echo $form->openFormHorizontal();
echo $form->investigator_select('user','','Choose an user','', false);
echo "OR";
echo $form->inputHorizontal('name','text','3','Name');
echo $form->inputHorizontal('firstname','text','3','Firstname');
echo $form->inputHorizontal('team_pos','text','2',"Position in the team");
echo $form->inputHorizontal('annee_debut','number','1',"Start year");
echo $form->inputHorizontal('annee_fin','number','1',"End year");
echo $form->inputHorizontal('current_pos','text','2',"Current position");
echo $form->submitHorizontal('primary','add',"Add a past member");
echo $form->closeFormHorizontal();

?>
<?= Footer::getFooter();
