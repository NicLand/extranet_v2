<?php
namespace Extranet;

class TeamReger{

  private $table_azote;
  private $containers = ['1'];

  private $table_chemicals;
  private $table_primers;
  static $rapidAccess = [];


  public function __construct(){
    $this->table_azote = App::getTableRegerAzote();
    $this->table_azote_cell = App::getTableRegerAzoteCell();
    $this->table_chemicals = App::getTableRegerChemicals();
    $this->table_primers = App::getTableRegerPrimers();
  }

  static function getTable(){
    return App::getTableRegerHomeItems();
  }

  static function getRapidAccess(){
    $table = self::getTable();
    $items = Database::query("SELECT name , link from $table ORDER BY ordre ASC")->fetchall();
    foreach($items as $item){
      $link = App::getRoot().'/reger/'.$item->link;
      self::$rapidAccess[$item->name] = $link;
    }
    return self::$rapidAccess ;
  }

  //========================================================
  //=================== AZOTE ==============================
  //========================================================
    public function getAzote(){
      $racks = self::getUniqRack();
      $affiche ="";
      foreach($racks as $cuve => $rack){
        $affiche .= "<h2 class='text-capitalize'>Container $cuve</h2>";
        $affiche .= "<div class='row row-cols-1 row-cols-md-4'>";
        foreach($rack as $tige){
          $affiche .= "<div class='col mb-4'>";
          $affiche .= "<div class='card'>";
          $affiche .= "<div class='card-header text-center'><h5>Rack $tige->tige</h5></div>";
            $affiche .= '<ul class="list-group list-group-flush">';
              $boxes = self::getBox($cuve, $tige->tige);
              foreach($boxes as $box){
                $free = self::getFreePlaces($cuve,$tige->tige, $box->etage);
                $link = "boite.php?c=$cuve&t=$tige->tige&e=$box->etage";
                $affiche .= "<li class='list-group-item text-center list-group-item-action'><a class='stretched-link text-decoration-none' href='$link'>$box->etage </a>($free)</li>";
              }
            $affiche .= '</ul>';
          $affiche .= "</div></div>";
        }
        $affiche .= "</div>";
      }
      return $affiche;
    }

    public function getAzoteSearch($res,$num){
      if($num>0){
        $affiche = '<h5 class="m-3">Number of results : '.$num.'</h5>
          <table class="table table-hover table-sm">
            <thead>
              <tr>
                <th class="text-center" scope="col">Position</th>
                <th class="text-center" scope="col">Ligne</th>
                <th class="text-center" scope="col">Colonne</th>
                <th class="text-center" scope="col">Nom</th>
                <th class="text-center" scope="col">Date</th>
                <th class="text-center" scope="col">Description</th>
                </tr>
            </thead>
            <tbody>';
          foreach($res as $tube){
            $affiche .= "<tr style='cursor:pointer' onClick=\"document.location='tube.php?id=".$tube->id."'\">
                          <th class='text-center align-middle'>Cuve ".$tube->cuve." | Tige ".$tube->tige." | Etage ".$tube->etage."</th>
                          <td class='text-center align-middle'>".$tube->ligne."</td>
                          <td class='text-center align-middle'>".$tube->colonne."</td>
                          <td class='text-center align-middle'>".$tube->name."</td>
                          <td class='text-center align-middle'>".$tube->date."</td>
                          <td class='text-center align-middle'>".$tube->description."</td>
                        </tr>";
          }
        $affiche .= "</tbody></table>";
      }
      else{
            $affiche = "<h3 class=''>No result for your search</h5>";
      }
      return $affiche;

    }

    private function getUniqRack(){
      foreach($this->containers as $container){
        $uniq[$container] = Database::query("SELECT DISTINCT tige FROM  $this->table_azote WHERE cuve = '$container' ORDER BY tige ASC")->fetchAll();
      }
      return $uniq;
    }

    private function getBox($cuve, $tige){
      return Database::query("SELECT DISTINCT etage FROM $this->table_azote WHERE cuve = '$cuve' AND tige = $tige ORDER BY etage ASC")->fetchAll();
    }

    public function getFreePlaces($cuve, $tige, $box){
      return Database::query("SELECT id FROM $this->table_azote WHERE cuve = '$cuve' AND tige = $tige AND etage = $box AND (`name` = '' OR `name` IS NULL)")->rowCount();
    }

    public function getTubeByPosition($c,$t,$e,$ligne,$colonne){
      return Database::query("SELECT * FROM $this->table_azote WHERE cuve = $c AND tige = $t AND etage = $e AND ligne = $ligne AND colonne = $colonne")->fetch();
    }
    public function getTubeByID($id){
      return Database::query("SELECT * FROM $this->table_azote WHERE id=$id")->fetch();
    }

    public function afficheBoite($c,$t,$e){
      $ligne = self::getLigne($c,$t,$e);
      $colonne = self::getColonne($c,$t,$e);
      $affiche = "<div class='container w-100 border'>";
      for($i=1;$i<=$ligne;$i++){
        $affiche .= "<div class='row'>";
        for($j=1;$j<=$colonne;$j++){
          $tube = self::getTubeByPosition($c,$t,$e,$i,$j);
          $affiche .= "<div class='col border text-center'><a href=\"tube.php?id=$tube->id\" type='button' class='btn'>";
          if(!empty($tube->name)){
            $affiche .= "<span class='bg-light text-info'>".$tube->name."</span>";
            $affiche .= "</br>Numero :".$tube->num_tube;
            $affiche .= "</br>Date : ".$tube->date;
          }else{
            $affiche.= "</br>Vide</br></br>";
          }
          $affiche .= "</a></div>";
        }
        $affiche .= "</div>";
      }
      $affiche .= "</div>";
      return $affiche;
    }

    public function afficheTube($id){
      $tube = self::getTubeByID($id);
      $affiche = "<div class='card w-50'>";
      $affiche .= "<div class='card-header'><h4>Position : Cuve $tube->cuve, Tige $tube->tige, Etage $tube->etage.</h4></div>";
      $pos = self::schematicPosition($tube->ligne,$tube->colonne);
      $affiche .= "<div class='card-body'>$pos</div>";
      $form = new cskForm;
      $affiche .= $form->openform();
      $affiche .= $form->inputCard('name','text','Name :',$tube->name);
      $affiche .= $form->inputCard('date','text','Date :',$tube->date);
      $affiche .= $form->inputCard('num_tube','number','Numero du tube :',$tube->num_tube);
      $affiche .= $form->textAreaCard('description','Description :',$tube->description);
      $affiche .= $form->submit('primary','update','Update');
      $affiche .= $form->delete();
      $affiche .= $form->closeForm();
      $affiche .= "</div>";
      return $affiche;
    }
    public function schematicPosition($x,$y){
      $affiche = "<div class='container w-50 border'>";
      $pos = $x.$y;
      for($i=1;$i<=5;$i++){
        $affiche .= "<div class='row'>";
        for($j=1;$j<=5;$j++){
          $affiche .= "<div class='col text-center'>";
          if($pos == $i.$j){
            $affiche .= "<p class='bg-warning'>-</p>";
          }
          else{
            $affiche .= "<p>-</p>";
          }
          $affiche .= "</div>";
        }
        $affiche .= "</div>";
      }
      $affiche .= "</div>";
      return $affiche;
    }

    private function getLigne($c,$t,$e){
      return Database::query("SELECT DISTINCT ligne FROM $this->table_azote WHERE cuve = $c AND tige = $t  AND etage = $e")->rowCount();
    }

    private function getColonne($c,$t,$e){
      return Database::query("SELECT DISTINCT colonne FROM $this->table_azote WHERE cuve = $c AND tige = $t  AND etage = $e")->rowCount();
    }
    public function upAzote($id,$name,$date,$num_tube,$description){
      if(Database::query("UPDATE $this->table_azote SET
      name = ?,
      date = ?,
      num_tube =?,
      description = ?
      WHERE id = ?",[$name,$date,$num_tube,$description,$id])){
        return true;
      }
    }
}
