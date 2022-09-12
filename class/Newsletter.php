<?php
namespace Extranet;
use \PDO;

class Newsletter{

  private $table;
  private $table_users;
  private $link_ban = "";

  public function __construct(){
    $this->table = App::getTableNewsletter();
    $this->table_users = App::getTableUsers();
  }

  public function getNewsletters(){
    return Database::query("SELECT * FROM $this->table")->fetchAll();
  }
  public function getNewsletter($id){
    return Database::query("SELECT * FROM $this->table WHERE id=$id")->fetch();
  }

  public function afficheListNewsletter(){
    $newsletters = self::getNewsletters();
    foreach($newsletters as $newsletter){
      echo "<a href='newsletter.php?nl=$newsletter->id'>Newsletter numero $newsletter->id";
      if($newsletter->send == 1){
        echo " publiee le $newsletter->date";
      }
      else{
        echo " (en cours de redaction)";
      }
      echo "</a><br/>";
    }
  }

  public function getNL($id){
    $affiche = self::getNlHeader();
    $affiche .= self::getNlContent($id);
    $affiche .= self::getNlFooter();
    return $affiche;
  }

  public function getNlHeader(){
    return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <title>Newsletter du MFP</title>
    </head>
    <body style="font-family:Arial, Helvetica, sans-serif; background-color:#F4F4F4;">';
  }

  public function getNlFooter(){
    return '
    <div class="row text-center align-items-end">
      <div class="col">
      Cette lettre est publiée par le comité de rédaction de la Newsletter de l\'UMR5234.<br/>
      Pour toute question concernant cette lettre, écrivez à <a style="color:#666" href="mailto:christina.calmels@u-bordeaux.fr">Christina Calmels</a>.<br/>
      Responsable de la publication : Frédéric Bringaud.<br/>
      Responsables de la rédaction : Christina Calmels et Patricia Pinson.<br/>
      Comité de rédaction : Corinne Asencio, Marie-Lise Blondot, Anne Cayrel, Floriane Lagadec, Paul Lesbats.<br/>
      Intégration / Design : Nicolas Landrein.
      </div>
    </div>
    </body>
    </html>';
  }

  public function getNlContent($id){
    $affiche  = '<div class="container-md">';
    $affiche .= self::getNlBanniere($id);
      $newsletter = self::getNewsletter($id);
    $affiche .= '<h2>'.$newsletter->editoTitre.'</h2>';
    return $affiche;
  }

  private function getNLTopBlock(){

  }
  public function getNlBanniere($id){
    $affiche = '<img src="img/banniere'.$id.'.jpg" class="mx-auto d-block" alt="banniere">';
    return $affiche;
  }

}
