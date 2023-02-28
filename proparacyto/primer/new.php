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
$access = new Access("proparacyto", $user);
if(!$access->access()){
  Session::setFlash('danger', "Vous n'�tes pas autoris� � acc�der � cette page.");
  App::redirect('index.php');
  exit();
}

//===========================================================
// verification des datas du formulaire
if(!empty($_POST)){
$errors =[];
$db = App::getDatabase();
$validator = new Validator($_POST);
$validator->isNumeric('num',"The unique number is not correct.");
if($validator->isValid()){
  $validator->isUniq('num', $db, App::getTablePrimers(), "This unique number already exists.");
}
$validator->isText('name', "The name is not valid.");
if($validator->isValid()){
  $validator->isUniq('name', $db, App::getTablePrimers() , "This primer name already exists.");
}
$validator->isDNA('sequence',"This primer sequence is not DNA.");
if($validator->isValid()){
  $validator->isUniq('sequence', $db, App::getTablePrimers(), "This DNA sequence already exists");
}
$validator->isText('purpose',"The purpose is not valid.");
$validator->isSelect('project', "Please choose a project for this primer.");
$project = "";
foreach ($_POST["project"] as $proj){
  $project .= ",$proj";
}
$project = ltrim($project,",");
$validator->isSelect('investigator', "Please choose an investigator.");
$validator->isDate('date', "The date is not correct.");
//$validator->isText('comments', "Comments are not valid.");

if($validator->isValid()){
  $primer = new Primer('proparacyto');
  $primer->newPrimer(
    $_POST['num'],
    $_POST['name'],
    $_POST['sequence'],
    $project,
    $_POST['purpose'],
    $_POST['investigator'],
    $_POST['comments'],
    $_POST['date']
  );
  Session::getInstance()->setFlash('success', 'The new primer is recorded.');
  App::redirect('proparacyto/primer/index.php');
  exit();
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
if(isset($validator)){echo $validator->afficheErrors($errors);}

?>

<h1 class="mt-3">New Oligonucleotide</h1>
<form action="" method="post">
<?php
$today = date("d/m/Y");
$tab = App::getTablePrimers();
$lastnum = App::getDatabase()->query("SELECT num FROM $tab ORDER BY id DESC LIMIT 1")->fetch();
$form = new cskForm($_POST);
echo $form->input('num','number','Unique Number',$lastnum->num+1);
echo $form->input('name', 'text', 'Name', $lastnum->num+1);
echo $form->input('sequence','text','Sequence','');
echo $form->input('purpose', 'text', 'Purpose : ', '');
echo $form->project_select('project', 'Project', ' Choose a project ','');
echo $form->investigator_select('investigator','Investigator : ','Choose an investigator', $user->id);
echo $form->input('date','date', 'Date : ', date("Y-m-d"), "By default : today's date");
echo $form->textArea('comments','Comments : ','');
echo $form->submit('primary', 'add', 'Add this new primer');
?>
</form>
<?= Footer::getFooter();
