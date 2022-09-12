<?php
//===========================================================
//Charge automatiquemebnt les Class utilisées dans les pages
// Dans le bon __NAMESPACE__
namespace Extranet;
use \DateTime;

require '../class/Autoloader.php';
Autoloader::register();

$auth = App::getAuth();
$user = $auth->user();
//===========================================================

$title = 'Extranet MFP';
$titleLink = 'index.php';

$rapidAccess = [];
$menuItem = [];

if (!empty($_POST)){

  $errors = [];

  $validator = new Validator($_POST);
  $validator->isCheck('frais', "Veuillez renseigner si votre ordre de mission est avec ou sans frais.");
  $validator->isCheck('destination', "Veuillez renseigner votre destination.");
  $validator->isCheck('objet', "Veuillez renseigner l'objet de votre mission.");
  $validator->isAlpha('objetPrecision', "Veuillez donner des détails sur l'objet de la mission.");
  $validator->isCheck('charge',"Qu'est pris en charge par le laboratoire ?");
  $validator->isDate('allerDate', "La date d'aller n'est pas valide");
  $validator->isDate('retourDate', "La date de retour n'est pas valide");
  $validator->isAllerRetour('allerDate', 'retourDate', "Les dates d'aller et de retour ne sont pas cohérentes.");

  if ($validator->isValid()){
    $keys = "";
    $val = "";
    foreach ($_POST as $key =>$value){
      $keys .= ','.$key;
      $val .= ',"'.$value.'"';
    }
    $keys = ltrim($keys,',');
    $val = ltrim($val,',');
    $date_demande = date("Y-m-d");
    $token = '"'.Str::random(60).'"';
    $newM = new Mission;
    if($newM->newMission(
      $_POST['frais'],
      $_POST['destination'],
      $_POST['objet'],
      $_POST['objetPrecision'],
      $_POST['charge'],
      $_POST['incriptionFees'],
      $_POST['allerDate'],
      $_POST['allerVilleDepart'],
      $_POST['allerHeureDepart'],
      $_POST['allerVilleArrivee'],
      $_POST['allerHeureArrivee'],
      $_POST['retourDate'],
      $_POST['retourVilleDepart'],
      $_POST['retourHeureDepart'],
      $_POST['retourVilleArrivee'],
      $_POST['retourHeureArrivee'],
      $_POST['sejourPrive'],
      $_POST['transport'],
      $_POST['passager'],
      $_POST['transportDetails'],
      $_POST['hotel'],
      $_POST['commentaire']
    )){
      Session::getInstance()->setFlash('success', 'Votre demande est envoyé');
      App::redirect('mission/mission.php/');
      exit();
    }
  }
  else{
      $errors = $validator->getErrors();
    }
}
//===========================================================

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
if(isset($validator)){echo $validator->afficheErrors($errors);}
?>

<div class="row justify-content-center">
  <div class="col-10 bg-light p-3 md-2">
    <h2>Demande d'ordre de mission pour </br><span style="text-transform: capitalize;"> <?= $user->firstname;?></span> <span style="text-transform: uppercase;"><?= $user->name ;?></span></h2>
    <form action="" method="POST">
      <h5 class="bg-secondary text-white p-2 mt-3">Orde de mission :</h5>
         <?php
         $form = new Form($_POST);
         echo $form->radio('frais','avec','Avec Frais');
         echo $form->radio('frais','sans','Sans Frais');
          ?>
      <h5 class="bg-secondary text-white p-2 mt-3">Destination de la mission</h5>
        <?php
        echo $form->radio('destination','france', 'France');
        echo $form->radio('destination','etranger', 'Étranger');?>
      <h5 class="bg-secondary text-white p-2 mt-3">Objet de la mission :</h5>
        <?php
        echo $form->radio('objet','Administration de la recherche.','Administration de la recherche.');
        echo $form->radio('objet','Recherche au sein d\'une équipe ou en collaboration.','Recherche au sein d\'une équipe ou en collaboration.');
        echo $form->radio('objet','Visite ou contact pour la mise en place d\'un projet.','Visite ou contact pour la mise en place d\'un projet.');
        echo $form->radio('objet','Colloque, congrès.','Colloque, congrès.');
        echo $form->radio('objet','Enseignement dispensé.','Enseignement dispensé.');
        echo $form->input('objetPrecision', 'text', 'Précision sur votre mission : ','', 'Donner toutes les précisions nécessaires (lieu, contact, site web...).');
        ?>
      <h5 class="bg-secondary text-white p-2 mt-3">Prise en charge par le laboratoire :</h5>
        <?php
        echo '<div class="col p-3">';
        echo $form->checkBoxInline('charge', 'Aucun', 'Aucun');
        echo $form->checkBoxInline('charge','Transport','Transport');
        echo $form->checkBoxInline('charge','Hôtel','Hôtel');
        echo $form->checkBoxInline('charge','Inscription','Inscription (*)');
        echo $form->input('incriptionFees','text','(*) Montant des frais d\'inscription : ','', 'Préciser si l\'hôtel est compris dans les droits d\'inscription');
        echo "</div>";
        ?>
      <h5 class="bg-secondary text-white p-2 mt-3">Voyage aller :</h5>
        <?php
        echo '<div class="form-row">';
        echo $form->inputCard('allerDate', 'date', 'Date de départ : ',"Format obligatoire : jj/mm/yyyy", false);
        echo '</div>';
        echo '<div class="form-row">';
        echo $form->inputCard('allerVilleDepart', 'text', 'Ville de départ : ', false);
        echo $form->inputCard('allerHeureDepart', 'time', 'Heure de départ : ', false);
        echo '</div>';
        echo '<div class="form-row">';
        echo $form->inputCard('allerVilleArrivee', 'text', 'Ville d\'arrivée : ', false);
        echo $form->inputCard('allerHeureArrivee', 'time', 'Heure d\'arrivée : ', false);
        echo '</div>';
        ?>
      <h5 class="bg-secondary text-white p-2 mt-3">Voyage retour :</h5>
        <?php
        echo '<div class="form-row">';
        echo $form->inputCard('retourDate', 'date', 'Date de départ : ',"Format obligatoire : jj/mm/yyyy", false);
        echo '</div>';
        echo '<div class="form-row">';
        echo $form->inputCard('retourVilleDepart', 'text', 'Ville de départ : ', false);
        echo $form->inputCard('retourHeureDepart', 'time', 'Heure de départ : ', false);
        echo '</div>';
        echo '<div class="form-row">';
        echo $form->inputCard('retourVilleArrivee', 'text', 'Ville d\'arrivée : ', false);
        echo $form->inputCard('retourHeureArrivee', 'time', 'Heure d\'arrivée : ', false);
        echo '</div>';
        echo $form->checkBox('sejourPrive','sejourPrive','oui','Séjour à titre privé inclus dans la mission (Précisez dans les commentaires en fin de formulaire.)', '');
        ?>
      <h5 class="bg-secondary text-white p-2 mt-3">Moyen de transport :</h5>
        <?php
        echo $form->checkBox('transport','avion','avion','Avion','');
        echo $form->checkBox('transport','train2','train2','Train 2nd classe','');
        echo $form->checkBox('transport','train1','train1','Train 1ère classe','');
        echo $form->checkBox('transport','commun','commun','Transport en commun','');
        echo $form->checkBox('transport','perso','perso','Véhicule personnel',''," (Fournir impérativement une copie de la carte grise et de l'assurance.)");
        echo $form->input('passager','text','Passager du véhicule personnel : ','',"Renseigner les nom(s) et prénom(s) du (des) passager(s) s'il y en a.");
        echo $form->input('transportDetails','text','Détails sur moyen de transport : ','',"Veuillez indiquer si vous avez une carte d'abonnement, si vous avez fait des préreservations...")
        ?>
      <h5 class="bg-secondary text-white p-2 mt-3">Hôtel :</h5>
        <?php
        echo $form->radioInline('hotel', 'oui', 'Oui');
        echo $form->radioInline('hotel', 'non', 'Non');
        ?>
      <h5 class="bg-secondary text-white p-2 mt-3">Commentaires :</h5>
        <?php
        echo $form->textArea('commentaire','Commentaires éventuels : ','');
        ?>
        <?php echo $form->button('submit', 'primary','Valider ma demande');
        echo $form->button('reset' , 'danger', 'Effacer le formulaire');
        ?>
    </form>
  </div>
</div>

<?= Footer::getFooter();
