<?php
namespace Extranet;

class TeamSpacvirPlasmide {

  private $table;
  private $perPage = 100;
  private $count;
  private $nbPage;
  private $cPage;
  private $biomolfileImg = "/img/SnapGene_SGIcon1.png";
  private $biomolFolder = "biomol_files/";

  public function __construct(){
    $this->table = App::getTableSpacvirPlasmides();
  }

  public function getPlasmides($id=false){
    if($id){
      return Database::query("SELECT * FROM $this->table WHERE id = $id")->fetch();
    }
    else{
      return Database::query("SELECT * FROM $this->table ORDER BY id ASC")->fetchAll();
    }
  }

  public function setList($cPage=false){
    $nbPage = $this->nbPage();
    $currentPage = ceil(($this->cPage($cPage)-1)* $this->perPage);
    return Database::query("SELECT * FROM $this->table ORDER BY id ASC LIMIT $currentPage , $this->perPage")->fetchAll();
  }

  public function afficheInvestigator($id){
    if(isset($id)){
      $invest = Database::query("SELECT name, firstname FROM mfp_extranet_users WHERE id = '$id'")->fetch();
      if($invest){
      $affiche = $invest->firstname." ".$invest->name;
    }
    else{
      $affiche ="";
    }
    }
    return $affiche;
  }

  private function getBiomolLink($link){
    if(!empty($link)){
      return "<a href='".$this->biomolFolder.$link."' download='".$link."'><img style='width:40px' src='".App::getRoot().$this->biomolfileImg."'></a>";
    }
  }

  private function getData($data=false){
    $affiche ="";
    if($data){
      foreach($data as $plasmide){
        $affiche .= "<tr style='cursor: pointer;' onclick='document.location.href=\"../plasmide/plasmide.php?id=".$plasmide->id."\"'>
                      <th class='align-middle'>".$plasmide->numero."</th>
                      <td class='text-center align-middle'>".$plasmide->name."</td>
                      <td class='text-center align-middle'>".$plasmide->resistance."</td>
                      <td class='text-center align-middle'>".$plasmide->origin."</td>
                      <td class='text-center align-middle'>".$plasmide->vector."</td>
                      <td class='text-center align-middle'>".$plasmide->insert."</td>
                      <td class='text-center align-middle'>".$plasmide->cloning_site."</td>
                      <td class='text-center align-middle'>".utf8_decode($plasmide->strain)."</td>
                      <td class='text-center align-middle'>".$plasmide->date."</td>
                      <td class='text-center align-middle'>".self::getBiomolLink($plasmide->map)."</td>
                    </tr>";
      }
    }
    return $affiche;
  }

  public function AfficheAllPlasmides($cPage=false){
    $data = self::setList($cPage);
    $affiche = self::openTable();
    foreach($data as $plasmide){
      $affiche .= "<tr style='cursor: pointer;' onclick='document.location.href=\"../plasmide/plasmide.php?id=".$plasmide->id."\"'>
                    <th class='align-middle'>".$plasmide->numero."</th>
                    <td class='text-center align-middle'>".$plasmide->name."</td>
                    <td class='text-center align-middle'>".$plasmide->resistance."</td>
                    <td class='text-center align-middle'>".$plasmide->origin."</td>
                    <td class='text-center align-middle'>".$plasmide->vector."</td>
                    <td class='text-center align-middle'>".$plasmide->insert."</td>
                    <td class='text-center align-middle'>".$plasmide->cloning_site."</td>
                    <td class='text-center align-middle'>".utf8_decode($plasmide->strain)."</td>
                    <td class='text-center align-middle'>".$plasmide->date."</td>
                    <td class='text-center align-middle'>".self::getBiomolLink($plasmide->map)."</td>
                  </tr>";
    }
    $affiche.= self::closeTable();
    return $affiche;
  }

  private function openTable(){
    $affiche ='<table class="table table-hover table-sm">
      <thead>
        <tr>
          <th class="text-center align-middle" scope="col">Number</th>
          <th class="text-center align-middle" scope="col">Name</th>
          <th class="text-center align-middle" scope="col">Resistance</th>
          <th class="text-center align-middle" scope="col">Origin</th>
          <th class="text-center align-middle" scope="col">Vector</th>
          <th class="text-center align-middle" scope="col">Insert</th>
          <th class="text-center align-middle" scope="col">Cloning site</th>
          <th class="text-center align-middle" scope="col">Bacterial strain</th>
          <th class="text-center align-middle" scope="col">Date</th>
          <th class="text-center align-middle" scope="col">SnapGene File</th>
        </tr>
      </thead>
      <tbody>';
    return $affiche;
  }

  private function closeTable(){
    return '</tbody></table>';
  }

  public function paginationPlasmide($cPage){
    $nbPage = $this->nbPage();
    $currentPage = ceil(($this->cPage($cPage)-1)* $this->perPage);
    $affiche = '<nav aria-label="primer_vav">
      <ul class="pagination pagination-sm">';
        if($this->cPage <= 1){
          $affiche .= '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
        }
        else{
          $affiche .= '<li class="page-item"><a class="page-link" href="index.php?p='.($this->cPage($cPage)-1).'">Previous</a></li>';
        }
      for ($i=1; $i<=$this->nbPage;$i++){
        if($i == $cPage){
          $affiche .= '<li class="page-item active" aria-current="page">
            <span class="page-link">'.$i.'</span>
          </li>';
        }
        else{
          $affiche .= '<li class="page-item"><a class="page-link" href="index.php?p='.$i.'">'.$i.'</a></li>';
        }
      }
      if($this->cPage < $nbPage){
        $affiche .='<li class="page-item"><a class="page-link" href="index.php?p='.($this->cPage($cPage)+1).'">Next</a></li>';
      }
      else{
        $affiche .= '<li class="page-item disabled"><span class="page-link">Next</span></li>';
      }
      $affiche .= '</ul></nav>';
    return $affiche;
  }

  private function countPlasmide(){
    $db = Database::query("SELECT COUNT(id) as nbPlasmide FROM $this->table");
    $this->count = $db->fetch();
    return $this->count;
  }

  public function getCount(){
    return $this->countPlasmide();
  }

  public function nbPage(){
    $count = $this->countPlasmide()->nbPlasmide;
    $nbPage = ceil($count/$this->perPage);
    $this->nbPage = $nbPage;
    return $this->nbPage;
  }

  public function cPage($page=false){
      if(isset($page) && $page>0 && $page<=$this->nbPage()){
        $this->cPage = $page;
      }
      else{
        $this->cPage = 1;
      }
      return $this->cPage;
  }

  public function getSearchList($res,$num){
    if($num>0){
    $affiche = '<h5 class="m-3">Number of results : '.$num.'</h5>';
    $affiche .= self::openTable();
    $affiche .= self::getData($res);
    $affiche .= self::closeTable();
      }
      return $affiche;
  }

  private function getPlasmideData($data){

  }
}
