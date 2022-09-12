<?php
namespace Extranet;

class Freezer{

  private $table;

  public function __construct(){
    $this->table = App::getTableFreezer();

  }

  public function afficheInvestigator($id){
    if(isset($id)){
      $invest = Database::query("SELECT name, firstname FROM mfp_extranet_users WHERE id = '$id'")->fetch();
      $affiche = $invest->firstname;
      $affiche .= " ";
      $affiche .= $invest->name;
    }
    return $affiche;
  }

  public function afficheListResult($res,$num){
    if($num>0){
    $affiche = '<h5 class="m-3">Number of results : '.$num.'</h5>
    <table class="table table-hover table-sm">
      <thead>
        <tr>
          <th class="text-center" scope="col">Rack - Box - Place</th>
          <th class="text-center" scope="col">Tube</th>
          <th class="text-center" scope="col">Project</th>
          <th class="text-center" scope="col">Investigator</th>
          <th class="text-center" scope="col">Date</th>
          </tr>
      </thead>
      <tbody>';
      foreach($res as $tube){
        if(!is_null($tube->id_project) && $tube->id_project !=0){
          $proj = self::getSingleTubeProject($tube->id_project);
        }
        else{$proj="";}
        $affiche .= "<tr style='cursor:pointer' onClick=\"document.location='tube.php?id=".$tube->id."'\">
                      <th class='text-center align-middle'> $tube->rack - $tube->box - $tube->place </th>
                      <td class='text-center align-middle'>$tube->tube</td>
                      <td class='text-center align-middle'>$proj</td>
                      <td class='text-center align-middle'>".$this->afficheInvestigator($tube->investigator)."</td>
                      <td class='text-center align-middle'>$tube->date</td>
                    </tr>";
        }
        $affiche .= "</tbody></table>";
    }
    else{
      $affiche = "<h3 class=''>No result for your search</h5>";
    }
      return $affiche;
  }

  public function getSingleTube($id){
    return Database::query("SELECT * FROM $this->table WHERE id = $id")->fetch();
  }
  public function getSingleTubeProject($projects){
    $newP = new Project;
    return $newP->getProjectFromProjList($projects);
  }

  public function afficheSingleTube($id){
    $tube = self::getSingleTube($id);
    if(!is_null($tube->id_project) && $tube->id_project !=0){
      $proj = self::getSingleTubeProject($tube->id_project);
    }
    else{$proj="";}

    $affiche ="<div class='card mt-3'>
    <h5 class='card-header'>Details</h5>
        <div class='card-body'>
          <table class='table table-bordered'>
            <tr>
              <th>Position :</th>
              <td>Freezer : ".$tube->freezer." | Rack : ".$tube->rack." | Box : ".$tube->box." | Place : ".$tube->place."</td>
            </tr>
            <tr>
              <th>Tube</th>
              <td>".$tube->tube."</td>
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
              <td>".$tube->comment."</td>
            </tr>
          </table>
          <a href='modif.php?id=$tube->id' class='btn btn-primary'>Modify the vial</a>
        </div>
      </div>";
    return $affiche;
  }

  public function getSingleBox($freezer, $rack, $box){
    $tubes = Database::query("SELECT * FROM $this->table WHERE `freezer` = '$freezer' AND `rack` = '$rack' AND `box` = '$box' ORDER BY id ASC")->fetchAll();

    $affiche = "<h2 class='text-capitalize m-3'>Freezer $freezer, Rack $rack, Box $box</h2>";
    $affiche .= '<table class="table table-hover table-sm">
      <thead>
        <tr>
          <th class="text-center" scope="col">Place</th>
          <th class="text-center" scope="col">Tube</th>
          <th class="text-center" scope="col">Project</th>
          <th class="text-center" scope="col">Investigator</th>
          <th class="text-center" scope="col">Date</th>
          </tr>
      </thead>
      <tbody>';
    foreach($tubes as $tube){
      if(!is_null($tube->id_project) && $tube->id_project !=0){
        $proj = self::getSingleTubeProject($tube->id_project);
      }
      else{$proj="";}
      //if($projList){$proj = $projList;}else{$proj ='';}

      $affiche .= "<tr style='cursor:pointer' onClick=\"document.location='tube.php?id=".$tube->id."'\">
                    <th class='text-center align-middle'>$tube->place</th>
                    <td class='text-center align-middle'>$tube->tube</td>
                    <td class='text-center align-middle'>$proj</td>
                    <td class='text-center align-middle'>".$this->afficheInvestigator($tube->investigator)."</td>
                    <td class='text-center align-middle'>$tube->date</td>
                  </tr>";
    }
    $affiche .= "</tbody></table>";
    return $affiche;
  }

  public function getFreezer(){
    $freezers = self::getUniqFreezer();
    $affiche = "";
    foreach($freezers as $freezer){
      $affiche .= "<h2 class='text-capitalize'>-80°C number $freezer->freezer</h2>";
      $racks = self::getUniqRack();
      $affiche .= "<div class='row row-cols-1 row-cols-md-4'>";
      foreach($racks as $rack){
        $affiche .= "<div class='col mb-4'>";
        $affiche .= "<div class='card'>";
        $affiche .= "<div class='card-header text-center'><h5>Rack $rack->rack</h5></div>";
        $affiche .= '<ul class="list-group list-group-flush">';
        $boxes = self::getBoxes($rack->rack);
        foreach($boxes as $box){
          $free = self::getFreePlaces($freezer->freezer,$rack->rack, $box->box);
          $link = "box.php?f=$freezer->freezer&r=$rack->rack&b=$box->box";
          $affiche .= "<li class='list-group-item text-center list-group-item-action'><a class='stretched-link text-decoration-none' href='$link'>$box->box </a>($free)</li>";
        }
        $affiche .= '</ul>';
        $affiche .= "</div></div>";
      }
        $affiche .= "</div>";
      }
    return $affiche;
  }

  private function getFreePlaces($freezer, $rack, $box){
    return Database::query("SELECT id FROM $this->table WHERE freezer = $freezer AND rack = $rack and `box` = $box AND (`tube` = '' OR `tube` IS NULL)")->rowCount();
  }

  private function getUniqFreezer(){
    return Database::query("SELECT DISTINCT freezer FROM $this->table")->fetchAll();
  }

  private function getUniqRack(){
    return Database::query("SELECT DISTINCT rack FROM $this->table ORDER BY rack ASC")->fetchAll();
  }

  private function getBoxes($rack){
    return Database::query("SELECT DISTINCT `box` FROM $this->table WHERE rack = $rack ORDER BY box ASC")->fetchAll();
  }

  public function newTube($id, $tube, $project, $investigator, $date, $comment){
    if(Database::query("UPDATE $this->table SET
    tube = ?,
    id_project = ?,
    investigator = ?,
    date = ?,
    comment = ?
    WHERE id = ?",[$tube, $project, $investigator, $date, $comment,$id])
  ){
    return true;
  }
  }

  public function deFreeze($id){
    if(Database::query("UPDATE $this->table SET
    tube = NULL,
    id_project = NULL,
    investigator = NULL,
    date = NULL,
    comment = NULL
    WHERE id = $id")
  ){
    return true;
  }
  }
}
