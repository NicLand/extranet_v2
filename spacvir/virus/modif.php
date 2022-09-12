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

if(isset($_GET['id'])){
  $virus = new TeamSpacvirVirus;
  $id = $_GET['id'];
  $data = $virus->getVirus($id);
  $name = $data->name;
  $purif_date = $data->purif_date;
  $investigator = $data->investigator;
  $cells = $data->cells;
  $plate = $data->plate;
  $OD_260 = $data->OD_260;
  $phu = $data->pfu;
  $storage = $data->storage;
  $comment = $data->comment;
  $gradient = $data->gradient;
  $gel = $data->gel;
}
//===========================================================
// verification des datas du formulaire
if(!empty($_POST)){
  if(isset($_POST['update'])){
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
        if(!empty($gel)){
          $up->delete($gel);
        }
        $gelFileName = $up->upload();
      }
      else{
        $gelFileName = $gel;
      }

    if(!empty($_FILES['gradient']['name'])){
        $up = new Upload('gradient','spacvir','virus','virus_prep');
        if(!empty($gradient)){
          $up->delete($gradient);
        }
        $gradientFileName = $up->upload();
    }
    else{
        $gradientFileName = $gradient;
    }

    $new = new TeamSpacvirVirus();

    if($plamide = $new->upSingleVirus(
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
      $gradientFileName,
      $id
    )){
      Session::getInstance()->setFlash('success', "Virus prep updated !");
      App::redirect('spacvir/virus/index.php');
      exit();
    }
  }
  else{
      $errors = $validator->getErrors();
    }
  }
}
  if(isset($_POST['delete'])){
    $delete = new TeamSpacvirVirus;
    if($delete->delSingleVirus($id)){
      $del = new Upload('gel','spacvir','virus', 'virus_prep');
      $del->delete($gel);
      $del2 = new Upload('gradient','spacvir','virus', 'virus_prep');
      $del2->delete($gradient);
      Session::getInstance()->setFlash('success', "Virus prep deleted !");
      App::redirect('spacvir/virus/index.php');
      exit();
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

<h1 class="mt-3">Update Virus Preparation data</h1>
<?php

$form = new TeamSpacvirForm($_POST);
echo "<form method='post' action='' enctype='multipart/form-data'>";
echo $form->input('name', 'text', 'Name : ', $name, "Should be precise and easy to read/understand.");
echo $form->input('purif_date','date', 'Date : ', $purif_date, "Default : today's date");
echo $form->investigator_select('investigator','Investigator : ','Choose an investigator', $investigator, false);
echo $form->input('cells', 'text', 'Cells : ', $cells);
echo $form->input('plate', 'text', 'Plate Number  : ', $plate);
echo $form->input('OD_260', 'number', 'OD 260 nm : ', $OD_260, "This will calculate automaticaly the particles concentration - OD260 should be done on a 1/10 dilution");
echo $form->input('pfu', 'number', '[PFU] : ', $pfu);
echo $form->input('storage', 'text', 'Storage : ', $storage);
echo $form->textArea('comment','Comments : ', $comment);
echo $form->input('gradient','file', "Gradient image : ",'',"Supported format : .jpeg, .png");
if(!empty($gradient)){echo "<div class='alert alert-info'>This virus prep has already a gradient image : $gradient</div>";}
echo $form->input('gel','file', "SDS-PAGE image : ",'',"Supported format : .jpeg, .png");
if(!empty($gel)){echo "<div class='alert alert-info'>This virus prep has already a gel image : $gel</div>";}
echo $form->submit('primary', 'update', 'Update the virus prep data');
echo $form->delete();
echo "</form>";
?>

<?= Footer::getFooter();
