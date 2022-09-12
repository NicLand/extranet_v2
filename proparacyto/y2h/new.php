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
if(!empty($_POST)){
  $errors =[];
  $validator = new Validator($_POST);
  $db = new Database;
  $y2h = new Interactome;
  if(isset($_POST['new_prot'])){
    //var_dump($_POST);
    $validator->isText('prot_name',"The name is not valid.");
    //$validator->isUniq('prot_name',$db, App::getTableY2Hprot(),"This protein is already in the interactome");
    if($validator->isValid()){
      if($y2h->newProt($_POST['prot_name'])){
        echo "oki";
        Session::getInstance()->setFlash('success',"Interactome protein recorded !");
        App::redirect('proparacyto/y2h');
        exit();
      }
    }
    else{
      $errors = $validator->getErrors();
    }
  }
  elseif(isset($_POST['new_ad'])){
    echo "new ad";
    $validator->isText("ad_vector","The pGADT7 vector is not valid.");
    //$validator->isUniq('ad_vector',$db, App::getTableY2HAD(),"This pGADT7 vector already exists");
    $validator->isSelect('prot_name',"Please choose a protein associated to your vector");
    if($validator->isvalid()){
      if($y2h->newAD($_POST['ad_vector'], $_POST['prot_name'])){
        Session::getInstance()->setFlash('success',"pGADT7 vector recorded !");
        App::redirect('proparacyto/y2h');
        exit();
      }
    }
    else{
      $errors = $validator->getErrors();
    }
  }
  elseif(isset($_POST['new_bd'])){
    var_dump($_POST);
    die();
    $validator->isText("bd_vector","The pGBKT7 vector is not valid.");
    //$validator->isUniq('bd_vector',$db, App::getTableY2HBD(),"This pGBKT7 vector already exists");
    $validator->isSelect('prot_name',"Please choose a protein associated to your vector");
    if($validator->isvalid()){
      if($_POST['autoactive']==1){$auto = 1;}else{$auto = 0;}
      if($y2h->newBD($_POST['bd_vector'], $_POST['prot_name'],$auto)){
        Session::getInstance()->setFlash('success',"pGBKT7 vector recorded !");
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

<h1 class="mt-3">New protein | construction</h1>
<?php
$form = new cskForm($_POST);

$interactome = new Interactome;
$data = $interactome->getProt();

echo $form->openFormHorizontal();
echo $form->inputHorizontal('prot_name','text','3',"New protein for interactome",'');
echo $form->submitHorizontal('primary','new_prot',"Add");
echo $form->closeFormHorizontal();

echo $form->openFormHorizontal();
echo $form->inputHorizontal('ad_vector','text','3',"New pGADT7 vector",'');
echo $form->selectY2HHorizontal($data,"prot_name", "",'Choose a protein','');
echo $form->submitHorizontal('primary','new_ad',"Add");
echo $form->closeFormHorizontal();

echo $form->openFormHorizontal();
echo $form->inputHorizontal('bd_vector','text','3',"New pGBKT7 vector",'');
echo $form->selectY2HHorizontal($data,"prot_name", "",'Choose a protein','');
echo $form->checkBoxInline('autoactive',"1"," Autoactive");
echo $form->submitHorizontal('primary','new_bd',"Add");
echo $form->closeFormHorizontal();



?>

<?php echo Footer::getFooter();?>
