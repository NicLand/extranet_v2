<?php
namespace Extranet;
use \PDO;

class Azote{

  private $table;
  private $table_users;
  private $table_old_users;
  private $containers = ['big1','big2'];
  private $boxCapacity = 81;
  private $rackCapacity = 8;

  public function __construct(){
    $this->table = App::getTableAzote();
    $this->table_users = App::getTableUsers();
    $this->table_old_users = App::getTablePastMembers();
  }

  public function afficheInvestigator($id){
    if(isset($id)){
      $invest = Database::query("SELECT * FROM $this->table_users WHERE id = '$id' ORDER BY name ASC")->fetch();
      $old = Database::query("SELECT * FROM $this->table_old_users WHERE id = '$id' ORDER BY name ASC")->fetch();
      if($invest){
      $affiche = $invest->firstname;
      $affiche .= " ";
      $affiche .= $invest->name;
    }elseif($old){
      $affiche = $old->firstname;
      $affiche .= " ";
      $affiche .= $old->name;
    }
    else{
      $affiche ="";
    }
    }
    return $affiche;
  }

  public function newTube($id, $plasmide, $souche, $clonality, $investigator, $id_project, $date, $commentaire_azote){
    if(Database::query("UPDATE $this->table SET
      plasmide = ?,
      souche = ?,
      clonality = ?,
      investigator = ?,
      id_project = ?,
      date = ?,
      commentaire_azote = ?
      WHERE id = ?
    ", [$plasmide, $souche, $clonality, $investigator, $id_project, $date, $commentaire_azote, $id])){
      return true;
    }
  }

  public function deFreeze($id){
    if(Database::query("UPDATE $this->table SET
      plasmide = ?,
      souche = ?,
      clonality = ?,
      investigator = ?,
      id_project = ?,
      date = ?,
      commentaire_azote = ?
      WHERE id = ?
  ",[NULL,NULL,NULL,NULL,NULL,NULL,NULL,$id])){
    return true;
  }

  }

  public function afficheListResult($res, $num){

    if($num>0){
    $affiche = '<h5 class="m-3">Number of results : '.$num.'</h5>
    <table class="table table-hover table-sm">
      <thead>
        <tr>
          <th class="text-center" scope="col">Place</th>
          <th class="text-center" scope="col">Tube</th>
          <th class="text-center" scope="col">Strain</th>
          <th class="text-center" scope="col">Clonality</th>
          <th class="text-center" scope="col">Investigator</th>
          <th class="text-center" scope="col">Project</th>
          <th class="text-center" scope="col">Date</th>
          </tr>
      </thead>
      <tbody>';
    foreach($res as $tube){
      if(!is_null($tube->id_project) && $tube->id_project !=0){
        $newP = new Project;
        $project = $newP->getProjectFromProjList($tube->id_project);
        if($project){
        $proj = $project;
      }else{
      $proj="";
      }
      }
      else{
        $proj="";
      }
      $affiche .= "<tr style='cursor:pointer' onClick=\"document.location='tube.php?id=".$tube->id."'\">
                    <th class='text-center align-middle'>".$tube->container." | ".$tube->tige." | ".$tube->box." | ".$tube->place."</th>
                    <td class='text-center align-middle'>".$tube->plasmide."</td>
                    <td class='text-center align-middle'>".$tube->souche."</td>
                    <td class='text-center align-middle'>".$tube->clonality."</td>
                    <td class='text-center align-middle'>".$this->afficheInvestigator($tube->investigator)."</td>
                    <td class='text-center align-middle'>".$proj."</td>
                    <td class='text-center align-middle'>".$tube->date."</td>
                  </tr>";
    }
    $affiche .= "</tbody></table>";
  }
  else{
    $affiche = "<h3 class=''>No result for your search</h5>";
  }
    return $affiche;

  }

  private function getBox($cuve, $tige){
      $req = Database::query("SELECT DISTINCT box FROM $this->table WHERE container = '$cuve' AND tige = $tige ORDER BY `box` DESC");
      $boxes = $req->fetchAll();
      return $boxes;
  }

  public function getFreePlaces($cuve, $tige, $box){
    $req = Database::query("SELECT id FROM $this->table WHERE container = '$cuve' AND tige = $tige and box = $box AND (`plasmide` = '' OR `plasmide` IS NULL) AND (`souche` = '' OR `souche`IS NULL)");
    return $req->rowCount();
  }

  private function getUniqRack(){
    foreach($this->containers as $container){
      $uniq[$container] = Database::query("SELECT DISTINCT tige FROM  $this->table WHERE container = '$container'")->fetchAll();
    }
    return $uniq;
  }

  public function getAzote(){
    $racks = self::getUniqRack();
    $affiche ="";
    foreach($racks as $cuve => $rack){
      $affiche .= "<h2 class='text-capitalize'>\"$cuve\" Container</h2>";
      $affiche .= "<div class='row row-cols-1 row-cols-md-4'>";
      foreach($rack as $tige){
        $affiche .= "<div class='col mb-4'>";
        $affiche .= "<div class='card'>";
        $affiche .= "<div class='card-header text-center'><h5>Rack $tige->tige</h5></div>";
          $affiche .= '<ul class="list-group list-group-flush">';
            $boxes = self::getBox($cuve, $tige->tige);
            foreach($boxes as $box){
              $free = self::getFreePlaces($cuve,$tige->tige, $box->box);
              $link = "box.php?c=$cuve&r=$tige->tige&b=$box->box";
              $affiche .= "<li class='list-group-item text-center list-group-item-action'><a class='stretched-link text-decoration-none' href='$link'>$box->box </a>($free)</li>";
            }
          $affiche .= '</ul>';
        $affiche .= "</div></div>";
      }
      $affiche .= "</div>";
    }
    return $affiche;
  }

  public function getSingleBox($cuve,$tige,$box){
    $req = Database::query("SELECT * FROM $this->table WHERE container = '$cuve' AND tige = '$tige' AND `box` = $box");
    $boxes = $req->fetchAll();
    //var_dump($boxes);
    $affiche = "<h2 class='text-capitalize m-3'>$cuve container, Rack $tige, Box $box</h2>";
    $affiche .= '<table class="table table-hover table-sm">
      <thead>
        <tr>
          <th class="text-center" scope="col">Place</th>
          <th class="text-center" scope="col">Tube</th>
          <th class="text-center" scope="col">Strain</th>
          <th class="text-center" scope="col">Clonality</th>
          <th class="text-center" scope="col">Investigator</th>
          <th class="text-center" scope="col">Project</th>
          <th class="text-center" scope="col">Date</th>
          </tr>
      </thead>
      <tbody>';
    foreach($boxes as $tube){
      if(!is_null($tube->id_project) && $tube->id_project !=0){
        $newP = new Project;
        $project = $newP->getProjectFromProjList($tube->id_project);
        if($project){
        $proj = $project;
      }else{
      $proj="";
      }
      }
      else{
        $proj="";
      }
      $affiche .= "<tr style='cursor:pointer' onClick=\"document.location='tube.php?id=".$tube->id."'\">
                    <th class='text-center align-middle'>".$tube->place."</th>
                    <td class='text-center align-middle'>".$tube->plasmide."</td>
                    <td class='text-center align-middle'>".$tube->souche."</td>
                    <td class='text-center align-middle'>".$tube->clonality."</td>
                    <td class='text-center align-middle'>".$this->afficheInvestigator($tube->investigator)."</td>
                    <td class='text-center align-middle'>".$proj."</td>
                    <td class='text-center align-middle'>".$tube->date."</td>
                  </tr>";
    }
    $affiche .= "</tbody></table>";
    return $affiche;
  }

  public function getSingleTube($id){
    return Database::query("SELECT * FROM $this->table WHERE id = $id")->fetch();
  }

  public function afficheSingleTube($id){
    $tube = self::getSingletube($id);

    if($tube->id_project != NULL){
      $newP = new Project;
      $project = $newP->getProjectFromProjList($tube->id_project);
    }else{$project="";}
    if($project){$proj = $project;}else{$proj ='';}
    $affiche ="<div class='card mt-3'>
    <h5 class='card-header'>".$tube->plasmide."</h5>
        <div class='card-body'>
          <table class='table table-bordered'>
            <tr>
              <th>Position :</th>
              <td>Container : ".$tube->container." | Rack : ".$tube->tige." | Box : ".$tube->box." | Place : ".$tube->place."</td>
            </tr>
            <tr>
              <th>Tube</th>
              <td>".$tube->plasmide."</td>
            </tr>
            <tr>
              <th>Souche</th>
              <td>".$tube->souche."</td>
            </tr>
            <tr>
              <th>Clonality</th>
              <td>".$tube->clonality."</td>
            </tr>
            <tr>
              <th>Project</th>
              <td>".$proj."</td>
            </tr>
            <tr>
              <th>Investigator</th>
              <td>".$this->afficheInvestigator($tube->investigator)."</td>
            </tr>
            <tr>
              <th>Date</th>
              <td>".$tube->date."</td>
            </tr>
            <tr>
              <th>Comments</th>
              <td>".$tube->commentaire_azote."</td>
            </tr>
          </table>
          <a href='vial.php?id=$tube->id' class='btn btn-primary'>Update or defreeze the vial</a>
        </div>
      </div>";
    return $affiche;
  }
}
