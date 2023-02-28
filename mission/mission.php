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

if(!$user){
  App::redirect('login.php');
  exit();
}
//===========================================================


$title = 'Extranet MFP';
$titleLink = 'index.php';

$rapidAccess = [];
$menuItem = [];

if (!empty($_POST)){

  $errors = array();

  $validator = new Validator($_POST);

  $validator->isCheck('frais', "Veuillez renseigner si votre ordre de mission est avec ou sans frais.");
  $validator->isCheck('destination', "Veuillez renseigner votre destination.");
  $validator->isAlpha('name', "Votre nom n'est pas valide.");
  $validator->isAlpha('firstname', "Votre prénom n'est pas valide.");
  $validator->isEmail('email', "Votre Email n'est pas valide.");

  $validator->isCheck('objet', "Veuillez renseigner l'objet de votre mission.");
  $validator->isAlpha('objetPrecision', "Veuillez donner des détails sur l'objet de la mission.");

  $validator->isCheck('charge',"Qu'est pris en charge par le laboratoire ?");

  $validator->isDate('allerDate', "La date d'aller n'est pas valide");

  $validator->isDate('retourDate', "La date de retour n'est pas valide");

  $validator->isAllerRetour('allerDate', 'retourDate', "Les dates d'aller et de retour ne sont pas cohérentes.");

  if ($validator->isValid()){
    $keys = "";
    $values = "";
    foreach ($_POST as $key =>$value){
      $keys .= ','.$key;
      $values .= ',"'.$value.'"';
    }
    $keys = ltrim($keys,',');
    $values = ltrim($values,',');
    $token = '"'.Str::random(60).'"';
    $db = App::getDatabase();
    $db->query("INSERT INTO demo_mfp_extranet_missions (dateDemande, user_id, $keys, token) VALUES (NOW(), $user->id, $values, $token)");
      Session::getInstance()->setFlash('success', 'Votre demande est envoyé');
  }
  else{
      $errors = $validator->getErrors();
    }


}
echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);

if(isset($validator)){echo $validator->afficheErrors($errors);}


?>

<div class="row justify-content-center">
  <div class="col-10 bg-light p-3 md-2">
    <h2>Demande d'ordre de mission</h2>
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
      <h5 class="bg-secondary text-white p-2 mt-3">Missionnaire :</h5>
      <div class="form-row p-3">
        <?php
        echo $form->inputInline('name', 'text', 'Votre Nom :', $user->name);
        echo $form->inputInline('firstname', 'text', 'Votre Prénom : ', $user->firstname);
        echo $form->inputInline('email', 'email', 'Votre Email : ', $user->email);
        ?>
      </div>
        <h5 class="bg-secondary text-white p-2 mt-3">Objet de la mission :</h5>
        <?php
        echo $form->radio('objet','Administration de la recherche.','Administration de la recherche.');
        echo $form->radio('objet','Recherche au sein d\'une équipe ou en collaboration.','Recherche au sein d\'une équipe ou en collaboration.');
        echo $form->radio('objet','Visite ou contact pour la mise en place d\'un projet.','Visite ou contact pour la mise en place d\'un projet.');
        echo $form->radio('objet','Colloque, congrès.','Colloque, congrès.');
        echo $form->radio('objet','Enseignement dispensé.','Enseignement dispensé.');
        echo $form->input('objetPrecision', 'text', 'Précision sur votre mission : ', 'Donner toutes les précisions nécessaires (lieu, contact, site web...).');
        ?>
        <h5 class="bg-secondary text-white p-2 mt-3">Prise en charge par le laboratoire :</h5>
        <?php
        echo '<div class="col p-3">';
        echo $form->checkBoxInline('charge', 'Aucun', 'Aucun');
        echo $form->checkBoxInline('charge','Transport','Transport');
        echo $form->checkBoxInline('charge','Hôtel','Hôtel');
        echo $form->checkBoxInline('charge','Inscription','Inscription (*)');
        echo $form->input('incriptionFees','text','(*) Montant des frais d\'inscription : ', 'Préciser si l\'hôtel est compris dans les droits d\'inscription');

        echo "</div>";
        ?>
        <h5 class="bg-secondary text-white p-2 mt-3">Voyage aller :</h5>
        <?php
        echo '<div class="form-row">';
        echo $form->inputInline('allerDate', 'date', 'Date de départ : ',"Format obligatoire : jj/mm/yyyy", false);
        echo '</div>';
        echo '<div class="form-row">';
        echo $form->inputInline('allerVilleDepart', 'text', 'Ville de départ : ', false);
        echo $form->inputInline('allerHeureDepart', 'time', 'Heure de départ : ', false);
        echo '</div>';
        echo '<div class="form-row">';
        echo $form->inputInline('allerVilleArrivee', 'text', 'Ville d\'arrivée : ', false);
        echo $form->inputInline('allerHeureArrivee', 'time', 'Heure d\'arrivée : ', false);
        echo '</div>';
        ?>
        <h5 class="bg-secondary text-white p-2 mt-3">Voyage retour :</h5>
        <?php
        echo '<div class="form-row">';
        echo $form->inputInline('retourDate', 'date', 'Date de départ : ',"Format obligatoire : jj/mm/yyyy", false);
        echo '</div>';
        echo '<div class="form-row">';
        echo $form->inputInline('retourVilleDepart', 'text', 'Ville de départ : ', false);
        echo $form->inputInline('retourHeureDepart', 'time', 'Heure de départ : ', false);
        echo '</div>';
        echo '<div class="form-row">';
        echo $form->inputInline('retourVilleArrivee', 'text', 'Ville d\'arrivée : ', false);
        echo $form->inputInline('retourHeureArrivee', 'time', 'Heure d\'arrivée : ', false);
        echo '</div>';
        echo $form->checkBox('sejourPrive','sejourPrive','oui','Séjour à titre privé inclus dans la mission (Précisez dans les commentaires en fin de formulaire.)', '');
        ?>
        <h5 class="bg-secondary text-white p-2 mt-3">Moyen de transport :</h5>
        <?php
        echo $form->checkBox('transport','avion','avion','Avion','');
        echo $form->checkBox('transport','train2','train2','Train 2nd classe','');
        echo $form->checkBox('transport','train1','train1','Train 1ère classe','');
        echo $form->checkBox('transport','commun','commun','Transport en commun','');
        echo $form->checkBox('transport','perso','perso','Véhicule personnel','',"Fournir impérativement une copie de la carte grise et de l'assurance.");
        echo $form->input('passager','text','Passager du véhicule personnel : ',"Renseigner les nom et prénom du (des) passagers s'il y en a.");
        echo $form->input('transportDetails','text','Détails sur moyen de transport : ',"Veuillez indiquer si vous avez une carte d'abonnement, si vous avez fait des préreservations...")
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
        echo $form->button('reset' , 'secondary', 'Effacer le formulaire');
        ?>
    </form>
  </div>
</div>





<?= Footer::getFooter();
