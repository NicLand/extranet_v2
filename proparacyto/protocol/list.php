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
if(!empty($_POST)){
  $search = new Search($_POST['search'],$_POST['search_option']);
  $search->getResult('protocol', ['protocol'], 'asc');
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

if(isset($_GET['cat']) && $_GET['cat'] != ""){$list = $_GET['cat'];}else{$list = "0";}
  $new = new Protocol("proparacyto");
  $cat = $new->getCategory($list);
  $h1 = $cat->category;
  if($h1){
  $h1 = $h1." Protocol";
}
else {
  $h1 = "Protocol";
}


echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
?>
<div class='m-2'><a class="btn btn-primary mb-3" href="index.php" role="button">Back to Protocols</a></div>

<h1 class="mt-3"><?= $h1;?><a href="modif.php?p=cat&id=<?= $list;?>" class="btn btn-secondary m-2" role="button">Update Category</a></h1>
<?= '<a href="'.App::getRoot().'/proparacyto/protocol/new.php?p=cat" class="btn btn-primary m-2" role="button">Add a new Category</a>';?>
<?= '<a href="'.App::getRoot().'/proparacyto/protocol/new.php?p=pro" class="btn btn-primary m-2" role="button">Add a new Protocol</a>';?>

<?php
$form = new cskForm($_POST);

echo $form->inputSearch('#', $post, $opt);

$data = new Protocol();
echo $data->afficheProtocols($list);


echo Footer::getFooter();
