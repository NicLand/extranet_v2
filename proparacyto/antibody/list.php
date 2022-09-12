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
if(isset($_GET['l']) && $_GET['l'] !=""){
  $l = $_GET['l'];
  if($l==1){$h1 = "Primary Antibodies list";$list="prim_antibody";}
  elseif($l==2){$h1 = "Secondary Antibodies list";$list="sec_antibody";}
}
else{
  App::redirect('proparacyto/antibody/');
  exit();
}
//===========================================================

if(!empty($_POST)){
  $search = new Search($_POST['search'],$_POST['search_option']);
  $search->getResult($list, ['name'], 'asc');
  $r = $search->getData();
  $n = $search->getNumResult();
  //var_dump($r);
  //var_dump($n);
  $post = $_POST['search'];
  $opt = $_POST['search_option'];
  if(empty($r)){
    Session::getInstance()->setFlash('danger', "Sorry there are no result for your search.");
  }
}
else{
  $post = "";
  $opt = "all";
}
//===========================================================
$rapidAccess = TeamProparacyto::getRapidAccess();
$menuItem = [];

$title = 'ProParaCyto';
$titleLink = 'proparacyto/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
?>

<h1 class="mt-3"><?= $h1;?></h1>

<?php
$ab = new Antibody;
echo '<a href="'.App::getRoot().'/proparacyto/antibody/new.php?ab='.$l.'" class="btn btn-primary mb-2" role="button">Add a new antibody</a>';

$form = new cskForm($_POST);

echo $form->inputSearch('#', $post, $opt);

if(isset($n) && $n>0){
  echo $ab->getSearchList($l,$r,$n);
}
else{
  echo $ab->afficheList($l);
}


?>
<?php echo Footer::getFooter();?>
