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
$access = new Access("spacvir", $user);
if(!$access->access()){
  Session::setFlash('danger', "Vous n'êtes pas autorisé à accéder à cette page.");
  App::redirect('index.php');
  exit();
}
//===========================================================
// verification des datas du formulaire
if(!empty($_POST)){
  $errors =[];
  $db = App::getDatabase();
  $validator = new Validator($_POST);
  $validator->isText('name', "The name is not valid.");
  $validator->isSelect('investigator', "You must select an investigator.");
  if(!empty($_FILES['gel']['name'])){
    $validator->isFileUniq('gel','spacvir','virus','virus_prep',$db, App::getTableSpacvirVirus(), "The Gel image already exists with this name.");
  }
  if(!empty($_FILES['gradient']['name'])){
    $validator->isFileUniq('gradient','spacvir','virus','virus_prep',$db, App::getTableSpacvirVirus(), "The Gradient image already exists with this name.");
  }
    if($validator->isValid()){
      if(!empty($_FILES['gel']['name'])){
        $up = new Upload('gel','spacvir','virus','virus_prep');
        $gelFileName = $up->upload();
      }
    else{
      $gelFileName = NULL;
    }
    if(!empty($_FILES['gradient']['name'])){
        $up = new Upload('gradient','spacvir','virus','virus_prep');
        $gradientFileName = $up->upload();
    }
    else{
        $gradientFileName = NULL;
    }

    $new = new TeamSpacvirVirus();
    if($plamide = $new->newSingleVirus(
      $_POST['name'],
      $_POST['investigator'],
      $_POST['purif_date'],
      $_POST['cells'],
      $_POST['plate'],
      $_POST['OD_260'],
      $_POST['pfu'],
      $_POST['storage'],
      $_POST['comment'],
      $gelFileName,
      $gradientFileName
    )){
      Session::getInstance()->setFlash('success', "Virus recorded !");
      App::redirect('spacvir/virus/index.php');
      exit();
    }
  }
  else{
      $errors = $validator->getErrors();
    }
}

//===========================================================
$rapidAccess = TeamSpacvir::getRapidAccess();
$menuItem = [];

$title = 'SpacVir';
$titleLink = 'spacvir/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors,"EN");}
?>

<h1 class="mt-3">New Virus Preparation</h1>
<?php
$virus = new TeamSpacvirVirus;


$form = new TeamSpacvirForm($_POST);
echo "<form method='post' action='' enctype='multipart/form-data'>";
echo $form->input('name', 'text', 'Name : ', '', "Should be precise and easy to read/understand.");
echo $form->input('purif_date','date', 'Date : ', date("Y-m-d"), "Default : today's date");
echo $form->investigator_select('investigator','Investigator : ','Choose an investigator', $user->id, false);
echo $form->input('cells', 'text', 'Cells : ', '');
echo $form->input('plate', 'text', 'Plate Number  : ', '');
echo $form->input('OD_260', 'number', 'OD 260 nm : ', '',"This will calculate automaticly the particles concentration - OD260 should be done on a 1/10 dilution");
echo $form->input('pfu', 'number', '[PFU] : ', '');
echo $form->input('storage', 'text', 'Storage : ', '');
echo $form->textArea('comment','Comments : ','');
echo $form->input('gradient','file', "Gradient image : ",'',"Supported format : .jpeg, .png");
echo $form->input('gel','file', "SDS-PAGE image : ",'',"Supported format : .jpeg, .png");
echo $form->submit('primary', 'new', 'Add this new virus prep');
echo "</form>";
?>

<?= Footer::getFooter();
