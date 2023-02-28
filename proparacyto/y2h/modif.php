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
  Session::getInstance()->setFlash('danger', "Vous n'�tes pas autoris� � acc�der � cette page.");
  App::redirect('index.php');
  exit();
}
//===========================================================
if(isset($_GET['ad-id'])){
  $ad = new Interactome;
  $data = $ad->getADbyId($_GET['ad-id']);
}
if(isset($_GET['bd-id'])){
  $bd = new Interactome;
  $data = $bd->getBDbyId($_GET['bd-id']);
}
if(!empty($_POST)){
  $errors =[];
  $validator = new Validator($_POST);
  $db = new Database;
  $y2h = new Interactome;

  if(isset($_POST['up_ad'])){
    $validator->isText("ad_vector","The pGADT7 vector is not valid.");
    $validator->isSelect('prot_name',"Please choose a protein associated to your vector");
    if($validator->isvalid()){
      if($y2h->upAD($_GET['ad-id'],$_POST['ad_vector'], $_POST['prot_name'])){
        Session::getInstance()->setFlash('success',"pGADT7 vector updated !");
        App::redirect('proparacyto/y2h');
        exit();
      }
    }
    else{
      $errors = $validator->getErrors();
    }
  }
  elseif(isset($_POST['up_bd'])){
    $validator->isText("bd_vector","The pGBKT7 vector is not valid.");
    $validator->isSelect('prot_name',"Please choose a protein associated to your vector");
    if($validator->isvalid()){
      if($_POST['autoactive']==1){$auto = 1;}else{$auto = 0;}
      if($y2h->upBD($_GET['bd-id'],$_POST['bd_vector'], $_POST['prot_name'],$auto)){
        Session::getInstance()->setFlash('success',"pGBKT7 vector updated !");
        App::redirect('proparacyto/y2h');
        exit();
      }
    }
    else{
      $errors = $validator->getErrors();
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

<h1 class="mt-3">Update Vector</h1>
<?php
$form = new cskForm($_POST);
$inter = new Interactome;
$prot = $inter->getProt();

if(isset($_GET['ad-id'])){
echo $form->openFormHorizontal();
echo $form->inputHorizontal('ad_vector','text','4',"New pGADT7 vector",$data->ad_vector);
echo $form->selectY2HHorizontal($prot,"prot_name", "",'Choose a protein',$data->id_prot);
echo $form->submitHorizontal('primary','up_ad',"Update");
echo $form->closeFormHorizontal();
}
elseif(isset($_GET['bd-id'])){
echo $form->openFormHorizontal();
echo $form->inputHorizontal('bd_vector','text','4',"New pGBKT7 vector",$data->bd_vector);
echo $form->selectY2HHorizontal($prot,"prot_name", "",'Choose a protein', $data->id_prot);
echo $form->checkBoxInline('autoactive',$data->autoactiv," Autoactive");
echo $form->submitHorizontal('primary','up_bd',"Update");
echo $form->closeFormHorizontal();
}


?>

<?php echo Footer::getFooter();?>
