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
if(isset($_GET['id'])){
  $id = $_GET['id'];
  $mod = new Primer('proparacyto');
  $primer = $mod->getSinglePrimer($id);
}

//===========================================================
// verification des datas du formulaire
if(!empty($_POST)){
  //var_dump($_POST);
  if(isset($_POST['update'])){
$errors =[];
$db = App::getDatabase();
$validator = new Validator($_POST);
$validator->isNumeric('num',"The unique number is not correct.");
$validator->isText('name', "The name is not valid.");
$validator->isDNA('sequence',"This primer sequence is not DNA.");
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

  $upPrimer = new Primer('proparacyto');
  $upPrimer->upPrimer(
    $id,
    $_POST['num'],
    htmlspecialchars($_POST['name'], ENT_QUOTES),
    $_POST['sequence'],
    $project,
    htmlspecialchars($_POST['purpose'], ENT_QUOTES),
    $_POST['investigator'],
    htmlspecialchars($_POST['comments'], ENT_QUOTES),
    $_POST['date']
  );
    Session::getInstance()->setFlash('success', 'The primer has been updated');
    App::redirect('proparacyto/primer/index.php');
    exit();

    }
else{
    $errors = $validator->getErrors();
  }
}
if(isset($_POST['delete'])){
  $delete = new Primer('proparacyto');
  if($delete->deletePrimer($id)){
    Session::getInstance()->setFlash('success', "The primer has been deleted !");
    App::redirect('proparacyto/primer/index.php');
    exit();
  }
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

<h1 class="mt-3">Update Oligonucleotide</h1>
<form action="" method="post">
<?php
$form = new cskForm($_POST);
echo $form->input('num','number','Unique Number',$primer->num);
echo $form->input('name', 'text', 'Name', $primer->name);
echo $form->input('sequence','text','Sequence',$primer->sequence);
echo $form->input('purpose', 'text', 'Purpose : ', $primer->purpose);
echo $form->project_select('project', 'Project', ' Choose a project ',$primer->id_project);
echo $form->investigator_select('investigator','Investigator : ','Choose an investigator', $primer->investigator);
echo $form->input('date','date', 'Date : ', $primer->date);
echo $form->textArea('comments','Comments : ', $primer->comments);
echo $form->submit('primary', 'update', 'Update this primer');
echo $form->delete();
?>
</form>
<?= Footer::getFooter();
