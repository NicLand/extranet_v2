<?php
//===========================================================
//Charge automatiquemebnt les Class utilisées dans les pages
// Dans le bon __NAMESPACE__
namespace Extranet;

require '../class/Autoloader.php';
Autoloader::register();

$auth = App::getAuth();
$user = $auth->user();

if(!$user){
  App::redirect('login.php');
  exit();
}
//===========================================================

$title = 'Extranet MFP';
$titleLink = 'index.php';

$rapidAccess = [];
$menuItem = [];

$errors = array();

if(!empty($_POST)){
//var_dump($_POST);
$validator = new Validator($_POST);
$validator->isNumeric('pmid', "Veuillez entrer un PMID valide.");
$validator->isYearValid('annee', "Veuillez entrer une année valide.");
$validator->isSelect('equipe', "Veullez renseigner une équipe");

if($validator->isValid()){
  $validator->isUniqPmid('pmid', $_POST['equipe'], App::getTablePublications(), "Ce PMID existe déjà pour cette équipe.");
}

if($validator->isValid()){
  $tab = App::getTablePublications();
  if(DatabaseWP::query("INSERT INTO $tab (equipe, pmid, annee) VALUES (?,?,?)",
[
  $_POST['equipe'],
  $_POST['pmid'],
  $_POST['annee']
])){

  Session::getInstance()->setFlash('success', 'La publication est enregistrée');
}
}
else{
    $errors = $validator->getErrors();
  }
}
echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors,"EN");}

?>
  <div class="row justify-content-center">
    <div class="col-10">
      <h2 class="p-10">Gestion des publications sur le site Wordpress du MFP</h2>

      <h4>Fonctionnement du système d'implémentation de publication pour le site du MFP</h4>
      <p>En entrant les PMID de vos publications dans ce formulaire vous pourrez avoir un affichage organisé sur le site de l'UMR en entrant dans vos pages publication le code suivant : [listpubli team="X"]</p>
      <p>Remplacer le <strong>X</strong> par le numéro de votre équipe (cf tableau ci-après) sur votre page du site www.mfp.cnrs.fr</p>
      <p>Exemple :</p>
      <p>[listpubli team="2"] permettra d'afficher toutes les publications enregistrées ici pour l'équipe SpacVir</p>
      <h4>Correspondance numéro et nom d'équipe.</h4>

      <p><?php
      $tabTeam = App::getTableTeams();
        $teamList = Database::query("SELECT * FROM $tabTeam WHERE publi = 1 ORDER BY id ASC");
        $teams = $teamList->fetchAll();
          foreach ($teamList as $team){
            $teams[$team->id] = $team->team_name;
          }
          foreach ($teams as $team){
            echo $team->id." => ".$team->team_name;
            echo "<br/>";
          }
          ?>
      </p>
    </div>


  <div class="col-8">
    <div class="card text-center">
      <div class="card-header">Nouvelle publication</div>
      <div class="card-body">
        <div class="card-text">
          <form action="#" method="post">
          <?php $form = new Form($_POST);
            echo $form->inputCard('pmid', 'number', 'PMID : ','');
            echo $form->inputCard('annee', 'number', 'Année : ','');
            echo $form->selectCard($teams,'equipe', "Equipe : ", "Choississez une équipe");
            echo $form->submit('primary','ajouter',  'Ajouter la publication');
            ?>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Fin : Contenu de la page -->

<?= Footer::getFooter();
