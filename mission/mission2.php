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

echo Header::getHeader($title, $titleLink, $rapidAccess, $menuItem);
?>
<form id="form1" name="mission" method="post" action="process_mission.php" target="_blank">
     <h3>Orde de mission :</h3>
       <p>
         Avec frais<input type="radio" name="frais" id="frais" value="avec" />
         Sans frais<input type="radio" name="frais" id="frais" value="sans" />
       </p>
       <h3>Destination de la mission :</h3>
       <p>
         France<input type="radio" name="destination" id="destination" value="france" />
         Etranger<input type="radio" name="destination" id="destination" value="etranger" />
       </p>
       <h3>Missionnaire :</h3>
       <p>
         Nom :<input type="text" name="nom" id="nom" />
         Prénom :<input type="text" name="prenom" id="prenom" />
         E-mail :  <input type="email" name="email" id="email" />
       </p>
       <h3>Objet de la mission :</h3>
       <p>
         <input type="radio" name="objet" id="objet" value="Administration de la recherche" />
         Administration de la recherche.

         <input type="radio" name="objet" id="objet" value="Recherche au sein d'une équipe ou en collaboration"/>
         Recherche au sein d'une équipe ou en collaboration.

         <input type="radio" name="objet" id="objet" value="Visite ou contact pour la mise en place d'un projet"/>
         Visite ou contact pour la mise en place d'un projet.

         <input type="radio" name="objet" id="objet" value=" Colloque, congrès"/>
         Colloque, congrès.

         <input type="radio" name="objet" id="objet" value=" Enseignement dispens&eacute;"/>
         Enseignement dispensé.
       </p>
       <h3>Prise en charge par le laboratoire:</h3>
       <p>
         <input type="checkbox" name="charge_transport" id="charge1" value="Transport" />
       Transport
         <input type="checkbox" name="charge_hotel" id="charge2" value="Hotel" />
S&eacute;jour
<input type="checkbox" name="charge_inscription" id="charge3" onclick="check_montant();" value="Inscription"/>
Droit d'inscription
<div id="montant" style="display:none">Montant :
         <input type="text" name="montant_di" id="montant_di" /> Hotel inclus<input type="checkbox" name="hotel_inclus" id="hotel_inclus" value="Hotel inclus"/></div>
       <p>&nbsp;</p>
       <h3>Voyage aller :</h3>
       <p>Date de d&eacute;part :
         <input type="text" style="cursor: pointer" onclick="new calendar(this);" id="dateDebut" name="dateDebut"/>
       </p>
       <p>Ville de d&eacute;part :
         <input type="text" name="ville_depart" id="ville_depart" />
         Heure de d&eacute;part :
         <input type="text" name="heure_depart" id="heure_depart" />
       </p>
       <p>Ville d'arriv&eacute;e :
         <input type="text" name="ville_arrive" id="ville_arrive" />
       Heure d'arriv&eacute;e :
       <input type="text" name="heure_arrive" id="heure_arrive" />
       </p>
       <p>&nbsp;</p>
       <h3>Voyage retour :       </h3>
       <p>Date de d&eacute;part :
         <input type="text" style="cursor: pointer" onClick="new calendar(this);" id="dateFin" name="dateFin" />
       </p>
       <p>Ville de d&eacute;part :
         <input type="text" name="ville_retour" id="ville_retour" />
         Heure de d&eacute;part :
         <input type="text" name="heure_retour" id="heure_retour" />
       </p>
       <p>Ville d'arriv&eacute;e :
         <input type="text" name="ville_arrive2" id="ville_arrive2" />
       Heure d'arriv&eacute;e :
       <input type="text" name="heure_arrive2" id="heure_arrive2" />
       </p>
       <p>
         <input type="checkbox" name="prive" id="prive" onclick="check_prive();" value="oui"/>
       S&eacute;jour &agrave; titre priv&eacute; inclus dans la mission</p>
       <div id="detail_prive" style="display:none">
         <h3>Commentaire :</h3>
       <p><textarea name="prive_detail" id="prive_detail" cols="50" rows="5"></textarea></p></div>
       <p>&nbsp;</p>
       <h3>Moyen de transport :</h3>
       <p><input type="checkbox" name="avion" id="transport1" value="avion" onclick="check_transport();" />Avion <div id="div-avion" style="display:none">Carte d'abonnement :
         <input name="abonnement_avion" type="checkbox" value="oui" />
         Pr&eacute;-r&eacute;servation effectu&eacute;e aupr&egrave;s du march&eacute; de transport :
           <input name="reservation_avion" type="checkbox" value="oui" />
       </div></p>
       <p>
         <input type="checkbox" name="train2" id="transport2" value="train2" onclick="check_transport();"/>
       Train 2nd classe <div id="div-train2" style="display:none">Carte d'abonnement :
         <input name="abonnement_train2" type="checkbox" value="oui" />
         Pr&eacute;-r&eacute;servation effectu&eacute;e aupr&egrave;s du march&eacute; de transport :
           <input name="reservation_train2" type="checkbox" value="oui" />
       </div></p>
       <p>
  <input type="checkbox" name="train1" id="transport3" value="train1" onclick="check_transport();" />
       Train 1&egrave;re classe <div id="div-train1" style="display:none">Carte d'abonnement :
         <input name="abonnement_train1" type="checkbox" value="oui" />
         Pr&eacute;-r&eacute;servation effectu&eacute;e aupr&egrave;s du march&eacute; de transport :
           <input name="reservation_train1" type="checkbox" value="oui" />
       </div></p>
       <p>
  <input type="checkbox" name="commun" id="transport4" value="transport en commun" onclick="check_transport();"/>Transport en commun <div id="div-commun" style="display:none">Carte d'abonnement :
         <input name="abonnement_commun" type="checkbox" value="oui" />
       </div></p>
       <p>
  <input type="checkbox" name="vperso" id="transport5" value="vehicule personnel" onclick="check_transport();" />
         V&eacute;hicule personnel
       </p>

       <div id="vehicule_perso" style="display:none">
         <h3>Pi&egrave;ces &agrave; fournir :</h3>
         <ul> <li>Carte grise du v&eacute;hicule</li>
          <li>Assurance du v&eacute;hicule</li></ul>
          <p>Passager<input type="checkbox" name="vehicule" id="vehicule" value="passager" />
          Nom et pr&eacute;nom :
          <input type="text" name="passager_id" id="passager_id" /></p>
       <p>&nbsp;</p>
       </div>
       <p>&nbsp;</p>
       <h3>H&eacute;bergement :</h3>
       <p>H&ocirc;tel :<input name="hotel" id="hotel" type="radio" value="oui" onclick="check_hotel();" />Oui
       <input name="hotel" id="hotel" type="radio" value="non"  onclick="check_hotel();" />Non</p>
       <p>
         <h3>Commentaire :</h3><br/>
         <textarea name="commentaire" id="commentaire" cols="60" rows="6"></textarea>
       </p>
       <div id="div-hotel" style="display:none"><p>Pr&eacute;-r&eacute;servation effectu&eacute;e aupr&egrave;s du march&eacute; h&eacute;bergement :  <input name="reservation_hotel" type="checkbox" value="oui" /></p></div>
     <p>&nbsp;</p>

     <input type="submit" /> <input type="reset" />
     </form>

     <script type="text/javascript">
function check_montant() {
if(document.mission.charge3.checked == true)
{ $("#montant").slideDown('slow'); }
if(document.mission.charge3.checked == false)
{ $("#montant").slideUp('slow'); }
}

function check_prive() {
if(document.mission.prive.checked == true)
{ $("#detail_prive").slideDown('slow'); }
if(document.mission.prive.checked == false)
{ $("#detail_prive").slideUp('slow'); }
}
function check_transport(){
	if ((document.mission.transport1.checked == true)){
	 $("#div-avion").slideDown('slow');
	}
	else {
		$("#div-avion").slideUp('slow');
	}
	if ((document.mission.transport2.checked == true)){
	 $("#div-train2").slideDown('slow');
	}
	else {
		$("#div-train2").slideUp('slow');
	}
	if ((document.mission.transport3.checked == true)){
	 $("#div-train1").slideDown('slow');
	}
	else {
		$("#div-train1").slideUp('slow');
	}
	if ((document.mission.transport4.checked == true)){
	 $("#div-commun").slideDown('slow');
	}
	else {
		$("#div-commun").slideUp('slow');
	}
	if (document.mission.transport5.checked == true){
		$("#vehicule_perso").slideDown('slow'); }
	else {
		$("#vehicule_perso").slideUp('slow');
	}
}
function check_hotel(){
if 	(document.mission.hotel1.checked == true)
	{ $("#div-hotel").slideDown('slow');}
if 	(document.mission.hotel2.checked == true)
	{ $("#div-hotel").slideUp('slow');}
}
</script>
<?php echo Footer::getFooter();
