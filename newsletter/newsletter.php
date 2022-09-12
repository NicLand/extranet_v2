<?php
namespace Extranet;
require '../class/Autoloader.php';
Autoloader::register();
$NL = $_GET['nl'];

$nl = new Newsletter;
$newsletter = $nl->getNewsletter($NL);


$lireLasuite = 'http://www.mfp.cnrs.fr/mfp/newsletter/article.php?nl='.$NL.'&cat=';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Newsletter du MFP #<?php echo $NL;?> </title>
</head>

<body style="font-family:Arial, Helvetica, sans-serif; background-color:#F4F4F4;">
<table height="100" width="600" cellpadding="0" cellspacing="0" border="0" align="center" style="background-color:#fff;">
<tr>
<td>
<!-- Banniere -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td height="25" colspan="2" style="background-color:#333333; color:#fff; font-size:11px; text-align:center; border-bottom:1px solid #fff;">Pour visualiser la newsletter sur votre navigateur, <a style="color:#cccccc" href="http://www.mfp.cnrs.fr/mfp/newsletter/newsletter.php?nl=<?php echo $NL;?>">cliquez ici</a>.</td>
    </tr>
	<tr>
		<td colspan="2"><img src="http://www.mfp.cnrs.fr/extranet_v2/newsletter/img/banniere<?php echo $NL;?>.jpg" /></td>
	</tr>
	<tr>
    	<td style="background-color:#fff;" width="10%"></td>
  		<td height="5" style="background-color:#fff;"></td>
  	</tr>
</table>
</td>
</tr>

<tr>
<td>
<!-- Bloc EDITO -->
<table width="100%" cellpadding="0" cellspacing="1" border="0" style="background-color:#fff;">
	<tr>
    	<td width="2%" rowspan="4"></td>
        <td>
          <h2><?php echo $newsletter->editoTitre;?></h2></td>
        <td width="2%" rowspan="4"></td>
    </tr>

	<tr>
	  <td><?php echo $newsletter->editoResume;?></td>
	  </tr>

	<tr>
	  <td align="right"><a href="<?php echo $lireLasuite;?>1" style="color:#333333; font-size:10px;">Lire la suite...</a></td>
	  </tr>
    <tr>
    	<td colspan="5" height="4"></td>
    </tr>

</table>
</td>
</tr>

<tr>
<td>
<!-- Bloc Zoom -->
<table width="100%" border="0" cellspacing="1" style="background-color:#cccccc">
  <tr>
    <td width="2%" rowspan="4"></td>
    <td><h3><?php echo $newsletter->zoomTitre;?></h3></td>
    <td width="2%" rowspan="4"></td>
  </tr>
  <?php if (!empty($newsletter->zoomSousTitre)){  ?>
  <tr>
    <td><h4><?php echo $newsletter->zoomSousTitre;?></h4></td>
  </tr><?php } ?>
  <tr>
    <td><?php echo $newsletter->zoomResume;?></td>
  </tr>
  <tr>
    <td align="right"><a href="<?php echo $lireLasuite;?>2" style="color:#000; font-size:10px;">Lire la suite...</a></td>
  </tr>
  <tr>
    <td></td>
    <td height="10"></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>
</td>
</tr>

<!-- Bloc à trois colonnes -->


<tr>
<td>
<table width="100%" border="0" cellspacing="0">
  <tr>

  <!-- Colonne 1 -->

    <td width="33%">
    <table width="100%" border="0" cellspacing="0">
  <tr valign="top">

  <!-- Cellule 1 haut -->

    <td valign="top">

    <table width="100%" border="0" cellspacing="0" style="background-color:#333333;">
  <tr>
    <td width="5" rowspan="4"></td>
    <td height="10"></td>
    <td width="5" rowspan="4"></td>
  </tr>
  <tr>
    <td style="color:#fff; text-align:center;"><h3>PHOTO DU JOUR</h3></td>
    </tr>
  <tr>
    <td style="color:#fff; text-align:center;"><?php echo $newsletter->photoResume;?></td>
  </tr>

  <tr>
    <td style="color:#fff; text-align:center;"><a href="<?php echo $lireLasuite;?>3" style="color:#fff; font-size:10px;">Lire la suite...</a></td>
  </tr>
  <tr>
    <td></td>
    <td height="10"></td>
    <td></td>
  </tr>
    </table>

    </td>
  </tr>
  <tr>
    <!-- Cellule 2 bas -->

    <td><table width="100%" border="0" cellspacing="0">
      <tr>
        <td width="5" rowspan="5"></td>
        <td height="10"></td>
        <td width="5" rowspan="5"></td>
      </tr>

      <tr>
        <td height="10"></td>
      </tr>
      <tr>
        <td align="center"><h2><?php $newsletter->vieTitre;?></h2></td>
      </tr>
      <tr>
        <td align="center"><h4><em><?php echo $newsletter->vieSousTitre;?></em></h4></td>
      </tr>
      <tr>
        <td align="center"><?php echo $newsletter->vieResume;?></td>
      </tr>
      <tr>
        <td></td>
        <td align="right"><a href="<?php echo $lireLasuite;?>6" style="color:#333333; font-size:10px;">Lire la suite...</a></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td height="10"></td>
        <td></td>
      </tr>
    </table></td>
  </tr>
</table>

    </td>

  <!-- colonne 2 -->
    <!-- Cellule 3 haut -->

    <td width="34%">
    <table width="100%" border="0" cellspacing="0">
  <tr>
    <td valign="top" style="border:1px solid black">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="5" rowspan="5"></td>
    <td height="10"></td>
    <td width="5" rowspan="5"></td>
  </tr>
  <tr>
    <td align="center" style="font-size:20px; font-weight:bold;"><?php echo $newsletter->vieLaboTitre?></td>
  </tr>
  <tr>
    <td height="10"><?php echo $newsletter->vieLaboResume?></td>
  </tr>
  <tr>
    <td align="center"></td>
  </tr>
  <tr>
    <td align="right"><a href="<?php echo $lireLasuite;?>8" style="color:#333333; font-size:10px;">Lire la suite...</a></td>
    </tr>
    <tr>
    <td height="10"></td>
  </tr>
    </table>
    </td>
  </tr>
    <!-- Cellule 4 bas -->

  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
      <tr>
        <td width="5" rowspan="7"></td>
        <td height="10"></td>
        <td width="5" rowspan="7"></td>
      </tr>
      <tr>
        <td><h2>Brèves</h2><h3>de paillasse...</h3></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td height="10"></td>
      </tr>
      <tr>
        <td>



        </td>
      </tr>
      <tr>
        <td style="font-weight:bold"><?php echo $newsletter->breveResume;?></td>
      </tr>
      <tr>
 <td align="right"><a href="<?php echo $lireLasuite;?>7" style="color:#000; font-size:10px;">Lire la suite...</a></td>
 </tr>
      <tr>
        <td></td>
        <td height="10"></td>
        <td></td>
      </tr>
    </table></td>
  </tr>
</table>
    </td>

  <!-- colonne 3 -->
    <td width="33%">
    <table width="100%" border="0" cellspacing="0">
  <tr>
  <!-- cellule 5 haut -->
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FF0000">
  <tr>
    <td width="5" rowspan="5"></td>
    <td height="35"></td>
    <td width="5" rowspan="5"></td>
  </tr>
  <tr>
    <td align="center"><a href="<?php echo $lireLasuite;?>4" style="text-decoration:none;font-size:40px; color:#fff; font-weight:bold;"><?php echo $newsletter->chiffreTitre; ?></a></td>
  </tr>
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
<td align="center"><a href="<?php echo $lireLasuite;?>4" style="color:#fff; font-size:10px;">Lire la suite...</a></td>
</tr>
  <tr>
    <td height="35"></td>
  </tr>
    </table>

    </td>
  </tr>
  <tr>
  <!-- cellule 6 bas -->
    <td style="border:1px solid black;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="5" rowspan="9"></td>
    <td height="10"></td>
    <td width="5" rowspan="9"></td>
  </tr>
  <tr>
    <td height="40" align="center" style="background-color:#000; color:#fff; font-size:18px; font-weight:bold">CARTE BLANCHE</td>
  </tr>
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td align="center" style="font-weight:bold;"><?php echo $newsletter->tribuneTitre;?></td>
  </tr>
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td align="center" style="font-size:14px;"><?php echo $newsletter->tribuneResume;?></td>
  </tr>
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
<td align="right"><a href="<?php echo $lireLasuite;?>5" style="color:#333333; font-size:10px;">Lire la suite...</a></td>
  </tr>
  <tr>
    <td height="10"></td>
  </tr>
    </table>
    </td>
  </tr>
</table>
    </td>
  </tr>
</table>
</td>
</tr>

<!-- Bloc Agenda -->

<tr>
<td>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#333333" style="color:#fff;">
  <tr>
    <td width="10" rowspan="7"></td>
    <td height="10" colspan="2"></td>
    <td width="10" rowspan="7"></td>
  </tr>
  <tr>
    <td colspan="2" style="font-size:20px; font-weight:bold;">SAVE THE DATE</td>
  </tr>
  <tr>
    <td height="10" colspan="2"></td>
  </tr>
  <tr>
    <td valign="top"><?php echo $newsletter->agendaResume;?></td>
  </tr>
  <tr>
    <td colspan="2" height="10"></td>
  </tr>
  <tr>
    <td colspan="2" align="right"><a href="<?php echo $lireLasuite;?>9" style="color:#fff; font-size:10px;">Lire la suite...</a></td>
  </tr>
  <tr>
    <td colspan="2" height="10"></td>
  </tr>
</table></td>
</tr>


<!-- Bloc footer -->

<tr>
<td>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="10" rowspan="11"></td>
    <td height="10" colspan="3"></td>
    <td width="10" rowspan="11">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" style="font-size:11px; border-top:1px dashed #333;">Cette lettre est publiée par le comité de rédaction de la Newsletter de l'UMR5234</td>
  </tr>
  <tr>
    <td colspan="3" align="center" style="font-size:10px;">Pour toute question concernant cette lettre, écrivez à <a style="color:#666" href="mailto:christina.calmels@u-bordeaux.fr">Christina Calmels</a>.</td>
  </tr>
  <tr>
    <td colspan="3" align="center" style="font-size:10px;">Responsable de la publication : Frédéric Bringaud
</td>
  </tr>
  <tr>
    <td colspan="3" align="center" style="font-size:10px;">Responsables de la rédaction : Christina Calmels et Patricia Pinson
</td>
  </tr>
  <tr>
    <td colspan="3" align="center" style="font-size:10px;">Comité de rédaction : Corinne Asencio, Marie-Lise Blondot, Anne Cayrel, Floriane Lagadec, Paul Lesbats.</td>
  </tr>
	<tr>
    <td colspan="3" align="center" style="font-size:10px;">Intégration / Design : Nicolas Landrein.</td>
  </tr>
  <tr>
    <td height="10" colspan="3"></td>
  </tr>
  <tr>
    <td align="center"><a href="http://www.cnrs.fr" target="_blank"><img src="http://www.mfp.cnrs.fr/mfp/newsletter/img/logo_cnrs.jpg" alt="CNRS" height="65"></a></td>
    <td align="center"><a href="https://www.mfp.cnrs.fr" target="_blank"><img src="http://www.mfp.cnrs.fr/mfp/newsletter/img/logo_mfp.gif" alt="MFP" height="65"></a></td>
    <td align="center"><a href="http://www.u-bordeaux.fr" target="_blank"><img src="http://www.mfp.cnrs.fr/mfp/newsletter/img/logo_u-bordeaux.jpg" alt="Univ Bordeaux" height="65"></a></td>
  </tr>
  <tr>
    <td colspan="3" height="10"></td>
  </tr>
  <tr>
    <td colspan="3" align="center" style="font-size:10px;"><span align="center">Vous recevez ce courrier électronique car vous êtes inscrit comme membre du personnel de l'UMR5234.</span></td>
  </tr>
  <tr>
    <td colspan="3" align="center" style="font-size:10px"><span align="center">Vous pouvez vous désabonner de cette liste en <a style="color:#666;" href="mailto:christina.calmels@u-bordeaux.fr?subject=Desabonnement_de_la_newsletter&body=Veuillez_me_desabonner SVP.">cliquant ici</a></span></td>
  </tr>
  <tr>
    <td height="10"></td>
    <td colspan="3" height="10"></td>
    <td height="10"></td>
  </tr>
</table>
</td>
</tr>

</table>


</body>
</html>
