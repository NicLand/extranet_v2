<?php
namespace Extranet;

class Plasmide{

  private $table;
  private $biomolfileImg = "/img/SnapGene_SGIcon1.png";
  private $biomolFolder = "biomol_files/";

  public function __construct(){
    $this->table = App::getTablePlasmides();
  }

  public function afficheInvestigator($id){
    if(isset($id)){
      $invest = Database::query("SELECT name, firstname FROM mfp_extranet_users WHERE id = '$id'")->fetch();
      $old = Database::query("SELECT name, firstname FROM extranet_proparacyto_past_members WHERE id = '$id'")->fetch();
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

  private function getList($project,$type=false){
    if(isset($type) AND $type !=""){
      $where = " AND type = '$type'";
    }
    else {$where ="";}
    $sql = "SELECT * FROM $this->table WHERE id_project REGEXP '(^|,)$project(,|$)' $where";
    return Database::query($sql);
  }

  public function getSinglePlasmide($id){
    return Database::query("SELECT * FROM $this->table WHERE id = $id")->fetch();
  }

  private function getBiomolLink($link){
    if(!empty($link)){
      return "<a href='".$this->biomolFolder.$link."' download='".$link."'><img style='width:40px' src='".App::getRoot().$this->biomolfileImg."'></a>";
    }
  }
  private function displayType($type){
    if($type === 'plasmide'){$affiche = "Plasmide";}
    elseif($type === 'cell'){$affiche = 'Cell Line';}
    elseif($type == false OR $type === ""){$affiche = "Plasmide & Cell Line";}
    return $affiche;
  }

  public function getSearchList($search, $num){
    if($num>0){
      $affiche  = '<h5 class="m-3">Number of results : '.$num.'</h5>';
      $affiche .= self::openTable();
      foreach($search as $plasmide){
        $affiche .= "<tr style='cursor: pointer;' onclick='document.location.href=\"../plasmide/plasmide.php?id=".$plasmide->id."\"'>
                      <th class='align-middle'>".$plasmide->name."</th>
                      <td class='text-center align-middle'>".$plasmide->size."</td>
                      <td class='text-center align-middle'>".$plasmide->antibiotic."</td>
                      <td class='text-center align-middle'>".$plasmide->cloning_vector."</td>
                      <td class='text-center align-middle'>".$plasmide->dna_stock."</td>
                      <td class='text-center align-middle'>".$plasmide->glycerol_stock."</td>
                      <td class='text-center align-middle'>".$plasmide->date."</td>
                      <td class='text-center align-middle'>".self::getBiomolLink($plasmide->link_biomol)."</td>
                    </tr>";
      }
      $affiche .= self::closeTable();
    }
    else{
      $affiche = "<h5 class=''>No result for your search</h5>";
    }
    return $affiche;
  }
  public function afficheAllPlasmides(){
    $data = Database::query("SELECT * FROM $this->table WHERE type = 'plasmide'")->fetchAll();
    $affiche = self::openTable();
  foreach($data as $plasmide){
    $affiche .= "<tr style='cursor: pointer;' onclick='document.location.href=\"../plasmide/plasmide.php?id=".$plasmide->id."\"'>
                  <th class='align-middle'>".$plasmide->name."</th>
                  <td class='text-center align-middle'>".$plasmide->size."</td>
                  <td class='text-center align-middle'>".$plasmide->antibiotic."</td>
                  <td class='text-center align-middle'>".$plasmide->cloning_vector."</td>
                  <td class='text-center align-middle'>".$plasmide->dna_stock."</td>
                  <td class='text-center align-middle'>".$plasmide->glycerol_stock."</td>
                  <td class='text-center align-middle'>".$plasmide->date."</td>
                  <td class='text-center align-middle'>".self::getBiomolLink($plasmide->link_biomol)."</td>
                </tr>";
  }
  $affiche .= self::closeTable();
  return $affiche;
  }


  public function affichePlasmidesOrCells($project,$type){
    //var_dump($project);
    $affiche = "";
    $data = self::getList($project,$type)->fetchAll();
    //var_dump($data);
    if(empty($data)){$affiche .= "<h5>They are no recorded $type for this project</h5>";}
    else{
      $affiche .= self::openTable($type);
    foreach($data as $plasmide){

      $affiche .= "<tr style='cursor: pointer;' onclick='document.location.href=\"../plasmide/plasmide.php?id=".$plasmide->id."\"'>
                    <th class='align-middle'>".$plasmide->name."</th>
                    <td class='text-center align-middle'>".$plasmide->size."</td>
                    <td class='text-center align-middle'>".$plasmide->antibiotic."</td>
                    <td class='text-center align-middle'>".$plasmide->cloning_vector."</td>";
      if($plasmide->type === 'plasmide'){
        $affiche.= "<td class='text-center align-middle'>".$plasmide->dna_stock."</td>
        <td class='text-center align-middle'>".$plasmide->glycerol_stock."</td>";
      }
      else {$affiche .= "<td class='text-center align-middle'></td>
                        <td class='text-center align-middle'></td>";
                      }
      $affiche .= "<td class='text-center align-middle'>".$plasmide->date."</td>
                    <td class='text-center align-middle'>".self::getBiomolLink($plasmide->link_biomol)."</td>
                  </tr>";
    }
    $affiche .= self::closeTable();
  }
    return $affiche;
  }

  private function openTable($type=false){
    $affiche ='<table class="table table-hover table-sm">
      <thead>
        <tr>
          <th class="text-center align-middle" scope="col">'.self::displayType($type).'</th>
          <th class="text-center align-middle" scope="col">Size</th>
          <th class="text-center align-middle" scope="col">Antibiotic</th>
          <th class="text-center align-middle" scope="col">Origin vector</th>';
    if($type === 'plasmide' OR $type == false){
      $affiche .= '<th class="text-center align-middle" scope="col">DNA stock</th>
      <th class="text-center align-middle" scope="col">Glycerol Stock</th>';
    }
    else{$affiche .= '<th class="text-center align-middle" scope="col"></th>
                      <th class="text-center align-middle" scope="col"></th>';}
    $affiche .= '<th class="text-center align-middle" scope="col">Date</th>
                <th class="text-center align-middle" scope="col">File</th>
                </tr>
                </thead>
                <tbody>';
  return $affiche;
  }

  private function closeTable(){
    return '</tbody></table></div></div>';
  }
  public function getSinglePlasmideProject($projects){
    $newP = new Project;
    return $newP->getProjectFromProjList($projects);
  }

  public function afficheSinglePlasmide($id){
    $affiche = "";
    $plasmide = self::getSinglePlasmide($id);
    if($plasmide->id_project != NULL){
      $projList = self::getSinglePlasmideProject($plasmide->id_project);
    }
    if($projList){$proj = $projList;}else{$proj ='';}
    $affiche .="<div class='card mt-3'>
        <h5 class='card-header'>".$plasmide->name."</h5>
        <div class='card-body'>
          <table class='table table-bordered'>
            <tr>
              <th>Size</th>
              <td>".$plasmide->size."</td>
            </tr>
            <tr>
              <th>Antibiotic</th>
              <td>".$plasmide->antibiotic."</td>
            </tr>
            <tr>
              <th>Project</th>
              <td>".$proj."</td>
            </tr>
            <tr>
              <th>Purpose</th>
              <td>".$plasmide->fragment_cloned."</td>
            </tr>
            <tr>
              <th>Origin vector</th>
              <td>".htmlspecialchars_decode($plasmide->cloning_vector)."</td>
            </tr>
            <tr>
              <th>Date</th>
              <td>".$plasmide->date."</td>
            </tr>
            <tr>
              <th>Investigator</th>
              <td>".$this->afficheInvestigator($plasmide->investigator)."</td>
            </tr>
            <tr>
              <th>Comments</th>
              <td>".$plasmide->comments."</td>
            </tr>
            <tr>
              <th>Dna Stock</th>
              <td>".$plasmide->dna_stock."</td>
            </tr>
            <tr>
              <th>Glycerol Stock</th>
              <td>".$plasmide->glycerol_stock."</td>
            </tr>
            <tr>
              <th>Biomol File</th>
              <td>".self::getBiomolLink($plasmide->link_biomol)."</td>
            </tr>
          </table>
          <a href='modif.php?id=$plasmide->id' class='btn btn-primary'>Modify the $plasmide->type's data</a>
        </div>
      </div>";
    return $affiche;
  }

  public function newPlasmide($type, $project, $name, $size, $antibiotic, $fragment_cloned, $cloning_vector, $date, $investigator,$comments,$dna,$glycerol,$link_biomol){
    if(Database::query("INSERT INTO $this->table (type, id_project, name, size, antibiotic, fragment_cloned, cloning_vector, date, investigator, comments, dna_stock, glycerol_stock, link_biomol) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)",
    [
      $type,$project, $name, $size, $antibiotic, $fragment_cloned, $cloning_vector, $date, $investigator,$comments,$dna,$glycerol,$link_biomol
    ])){
      return true;
    }
  }

  public function modifPlasmide($id,$project, $name, $size, $antibiotic, $type, $fragment_cloned, $cloning_vector, $date, $investigator,$comments,$dna,$glycerol,$link_biomol){
      //$cloning_vector = ProtectForm::protectData($cloning_vector);

    if(Database::query("UPDATE $this->table SET
      id_project = ?,
      name = ?,
      size = ?,
      antibiotic = ?,
      type = ?,
      fragment_cloned = ?,
      cloning_vector = ?,
      date = ?,
      investigator = ?,
      comments = ?,
      dna_stock = ?,
      glycerol_stock = ?,
      link_biomol = ?
      WHERE id  = ?
    ",[$project, $name, $size, $antibiotic, $type, $fragment_cloned, $cloning_vector, $date, $investigator,$comments,$dna,$glycerol,$link_biomol,$id])){
      return true;
    }
  }

  public function deletePlasmide($id){
    if(Database::query("DELETE FROM $this->table WHERE id = $id")){
      return true;
    }
  }

}
