<?php
namespace Extranet;

class Vector{
  private $table;
  private $biomolFileImg = "/img/SnapGene_SGIcon1.png";
  private $biomolFolder = "biomol/";
  private $pdfFileImg = "/img/iconePDF.png";
  private $pdfFolder = "pdf/";

  public function __construct(){
    $this->table = App::getTableVectors();
  }

  private function getList(){
    return Database::query("SELECT * FROM $this->table")->fetchAll();
  }

  public function getSingle($id){
    return Database::query("SELECT * FROM $this->table WHERE id = $id")->fetch();
  }

  public function getSearchList($res,$num){
    if($num>0){
      $affiche  = '<h5 class="m-3">Number of results : '.$num.'</h5>';
      $affiche .= self::tableHead();
      foreach($res as $vector){
        $affiche .= "<tr style='cursor:pointer' onClick=\"document.location='vector.php?id=".$vector->id."'\">
                      <th class='text-center align-middle'>$vector->name</th>
                      <th class='text-center align-middle'>$vector->size</th>
                      <td class='text-center align-middle'>$vector->antibiotic</td>
                      <td class='text-center align-middle'>$vector->fragment_cloned</td>
                      <td class='text-center align-middle'>$vector->cloning_vector</td>
                      <td class='text-center align-middle'>$vector->investigator</td>
                      <td class='text-center align-middle'>".self::getBiomolLink($vector->link_biomol)."</td>
                      <td class='text-center align-middle'>".self::getPdfLink($vector->link_pdf)."</td>
                    </tr>";
      }
      $affiche .= self::tableEnd();
      }
      else{
        $affiche = "<h5 class=''>No result for your search</h5>";
      }
    return $affiche;
  }

  public function afficheList(){
    $data = self::getList();
    $affiche = self::tableHead();
    foreach($data as $vector){
      $affiche .= "<tr style='cursor:pointer' onClick=\"document.location='vector.php?id=".$vector->id."'\">
                    <th class='text-center align-middle'>$vector->name</th>
                    <th class='text-center align-middle'>$vector->size</th>
                    <td class='text-center align-middle'>$vector->antibiotic</td>
                    <td class='text-center align-middle'>$vector->fragment_cloned</td>
                    <td class='text-center align-middle'>$vector->cloning_vector</td>
                    <td class='text-center align-middle'>$vector->investigator</td>
                    <td class='text-center align-middle'>".self::getBiomolLink($vector->link_biomol)."</td>
                    <td class='text-center align-middle'>".self::getPdfLink($vector->link_pdf)."</td>
                  </tr>";
    }
    $affiche .= self::tableEnd();
    return $affiche;
  }

  private function tableHead(){
    return '<table class="table table-hover table-sm">
              <thead>
                <tr>
                  <th class="text-center" scope="col">Name</th>
                  <th class="text-center" scope="col">Size</th>
                  <th class="text-center" scope="col">Antibiotic(s)</th>
                  <th class="text-center" scope="col">Cloned Fragment</th>
                  <th class="text-center" scope="col">Origin vector</th>
                  <th class="text-center" scope="col">Investigator</th>
                  <th class="text-center" scope="col">Biomol File</th>
                  <th class="text-center" scope="col">PDF File</th>
                  </tr>
              </thead>
              <tbody>';
  }
  private function tableEnd(){
    return '</tbody></table>';
  }
  private function getBiomolLink($link){
    if(!empty($link)){
      return "<a href='".$this->biomolFolder.$link."' download='".$link."'><img style='width:40px' src='".App::getRoot().$this->biomolFileImg."'></a>";
    }
  }
  private function getPdfLink($link){
      if(!empty($link)){
        return "<a href='".$this->pdfFolder.$link."'><img style='width:40px' src='".App::getRoot().$this->pdfFileImg."'></a>";
      }
  }
  public function afficheSingle($id){
    $data = self::getSingle($id);
    $affiche ="<div class='card mt-3'>
        <h5 class='card-header'>".$data->name."</h5>
        <div class='card-body'>
          <table class='table table-bordered'>
            <tr>
              <th>Size</th>
              <td>".$data->size."</td>
            </tr>
            <tr>
              <th>Antibiotic</th>
              <td>".$data->antibiotic."</td>
            </tr>
            <tr>
              <th>Fragment cloned</th>
              <td>".$data->fragment_cloned."</td>
            </tr>
            <tr>
              <th>Origin vector</th>
              <td>".$data->cloning_vector."</td>
            </tr>
            <tr>
              <th>Investigator</th>
              <td>".$data->investigator."</td>
            </tr>
            <tr>
              <th>Comments</th>
              <td>".$data->comments."</td>
            </tr>
            <tr>
              <th>Biomol File</th>
              <td>".self::getBiomolLink($data->link_biomol)."</td>
            </tr><tr>
              <th>PDF File</th>
              <td>".self::getPdfLink($data->link_pdf)."</td>
            </tr>
          </table>
          <a href='modif.php?id=$data->id' class='btn btn-primary'>Modify the vector's data</a>
        </div>
      </div>";
    return $affiche;
  }

  public function newVector($name,$size,$antibiotic,$fragment,$origin,$investigator,$comments,$pdf,$biomol){
    if(Database::query("INSERT INTO $this->table (name,size,antibiotic,fragment_cloned,cloning_vector,investigator,comments,link_pdf,link_biomol) VALUES (?,?,?,?,?,?,?,?,?)",
  [
    $name,$size,$antibiotic,$fragment,$origin,$investigator,$comments,$pdf,$biomol
  ])){
    return true;
    }
  }

  public function modifVector($id,$name,$size,$antibiotic,$fragment,$origin,$investigator,$comments,$pdf,$biomol){
    if(Database::query("UPDATE $this->table SET
      name = ?,
      size = ?,
      antibiotic = ?,
      fragment_cloned = ?,
      cloning_vector = ?,
      investigator = ?,
      comments = ?
      link_pdf = ?,
      link_biomol = ?
      WHERE id  = ?
    ",[$name,$size,$antibiotic,$fragment,$origin,$investigator,$comments,$pdf,$biomol,$id])){
      return true;
    }else{return false;}
  }
  public function deleteVector($id){
    if(Database::query("DELETE FROM $this->table WHERE id = $id")){
    return true;
    }
    else{return false;}
  }
}
