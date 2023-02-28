<?php
//===========================================================
//Charge automatiquemebnt les Class utilisÃ©es dans les pages
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
  Session::setFlash('danger', "Vous n'Ãªtes pas autorisÃ© Ã  accÃ©der Ã  cette page.");
  App::redirect('index.php');
  exit();
}
//===========================================================
// On recupert l'id
if(isset($_GET['id'])){
  $id = $_GET['id'];
  $req = Database::query("SELECT * FROM extranet_proparacyto_past_members WHERE id = $id");
  $member = $req->fetch();
}
//===========================================================
// Verification des post
if(!empty($_POST)){
  if(isset($_POST['update'])){
  $errors = [];
  $validator = new Validator($_POST);
  $validator->isText('team_pos','The team position is not valid.');
  $validator->isText('name', 'The name is not valid.');
  $validator->isYear('annee_debut','The starting year is not valid.');
  $validator->isYear('annee_fin','The ending year is not valid.');
  $validator->isYearChrono('annee_debut','annee_fin', "The start and end years are not chronological.");
  $validator->isText('current_pos', "The current position is not valid.");
  if($validator->isValid()){
    $edit = new cskPastMember;
    $update = $edit->updatePastMember($id,$_POST['team_pos'],$_POST['firstname'],$_POST['name'],$_POST['annee_debut'],$_POST['annee_fin'],$_POST['current_pos']);

    Session::getInstance()->setFlash('success', 'The past member has been updated.');
    App::redirect('/proparacyto/past_members/index.php');
    exit();
  }
  else{
      $errors = $validator->getErrors();
    }
  }
  if(isset($_POST['delete'])){
    Database::query("DELETE FROM extranet_proparacyto_past_members WHERE id = $id");
    Session::getInstance()->setFlash('success', 'The past members has been deleted.');
    App::redirect('/proparacyto/past_members/index.php');
    exit();
  }
}

//===========================================================
$rapidAccess = TeamProparacyto::getRapidAccess();
$menuItem = [];

$title = 'ProParaCyto';
$titleLink = 'proparacyto/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors,"EN");}

echo "<h1 class='mt-3'>Update Past Member</h1>";

$form = new cskForm($_POST);

echo $form->openFormHorizontal();
echo $form->inputHorizontal('team_pos','text','2',"Position in the team",$member->team_pos);
echo $form->inputHorizontal('firstname','text','2',"Firstname",$member->firstname);
echo $form->inputHorizontal('name','text','2',"Name",$member->name);
echo $form->inputHorizontal('annee_debut','number','1',"Start year",$member->annee_debut);
echo $form->inputHorizontal('annee_fin','number','1',"End year",$member->annee_fin);
echo $form->inputHorizontal('current_pos','text','2',"Current position",$member->current_pos);
echo $form->submitHorizontal('primary','update',"Update");
echo $form->delete();
echo $form->closeFormHorizontal();

$past = new cskPastMember();
echo $past->affichePastMembers();
?>
<?= Footer::getFooter();
