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


$rapidAccess = TeamSpacvir::getRapidAccess();
$menuItem = [];

$title = 'SpacVir';
$titleLink = 'spacvir/index.php';

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
?>

<h1 class="mt-3">SpacVir Protocols</h1>
<?= '<a href="'.App::getRoot().'/spacvir/protocol/new.php?p=cat" class="btn btn-primary m-2" role="button">Add a new Category</a>';?>
<?= '<a href="'.App::getRoot().'/spacvir/protocol/new.php?p=pro" class="btn btn-success m-2" role="button">Add a new Protocol</a>';?>
<?php


  echo '<div class="row row-cols-1 row-cols-md-3 m-3">';
$link = "protocol.php?id=";

$data = new Protocol("spacvir");

//echo $data->afficheProtocolAccordeon();

$categories = $data->getCategoryList();
echo '<div class="accordion w-75" id="protocole_list">';
foreach($categories as $cat){
  $slug = Str::fileNameUpload($cat->category);
  $protocoles = $data->getProtocolByCatCKE($cat->id);
  if($protocoles){

  echo '<div class="accordion-item">
    <h2 class="accordion-header" id="'.$slug.'-heading">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#'.$slug.'-collapse" aria-expanded="false" aria-controls="'.$slug.'-collapse">
      <span class="h5 me-2">'.$cat->category.'</span> <span class="badge bg-info ml-2">'.count($protocoles).'</span>
      </button>
    </h2>
    <div id="'.$slug.'-collapse" class="accordion-collapse collapse" aria-labelledby="'.$slug.'-heading">
    <a href="modif.php?p=cat&id='.$cat->id.'" class="btn btn-secondary btn-sm m-1" role="button">Update Category</a>
        <div class="accordion-body">
        <ul class="list-group">';
        foreach($protocoles as $protocole){
          echo '<a class="list-group-item list-group-item-action" href="'.$link.$protocole->id.'"><strong>'.$protocole->name.'</strong></a>';
        }

  echo '</ul>
        </div>
      </div>
      </div>';
}
}
echo '</div>';
?>
<?= Footer::getFooter();
