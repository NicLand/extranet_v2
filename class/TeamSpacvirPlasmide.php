<?php
namespace Extranet;

class TeamSpacvirPlasmide {

  private string $table;
  private int $perPage = 100;
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

    private function getBiomolLink($link){
    if(!empty($link)){
      return "<a href='".$this->biomolFolder.$link."' download='".$link."'><img style='width:40px' src='".App::getRoot().$this->biomolfileImg."'></a>";
    }
  }
  private function validSeq($value){
      if($value == 1){
          $affiche = '<span style="color:green"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
</svg></span>';
      }
      else{
          $affiche = '<span style="color:red"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
</svg></span>';
      }
      return $affiche;
  }
  private function getData($super,$data=false){
    $affiche ="";
    if($data){
      foreach($data as $plasmide){
          if($super == true){
              $position ="<td class='text-center align-middle'>".$plasmide->glycerol_stock."</td>
                      <td class='text-center align-middle'>".$plasmide->dna_stock."</td>";
              $num = "<th class='align-middle'>".$plasmide->number."</th>";
          }
          else{
              $position = "";
              $num = "";
          }
        $affiche .= "<tr style='cursor: pointer;' onclick='document.location.href=\"../plasmides/plasmide.php?id=".$plasmide->id."\"'>
                      ".$num."
                      <td class='text-center align-middle'>".$plasmide->name."</td>
                      <td class='text-center align-middle'>".$plasmide->resistance."</td>
                      <td class='text-center align-middle'>".$plasmide->origin_vector."</td>
                      <td class='text-center align-middle'>".$plasmide->cloning_method."</td>
                      <td class='text-center align-middle'>".$plasmide->inserted_dna."</td>
                      <td class='text-center align-middle'>".$plasmide->investigateur."</td>
                      <td class='text-center align-middle'>".utf8_decode($plasmide->bacterie)."</td>
                      <td class='text-center align-middle'>".self::validSeq($plasmide->insert_seq)."</td>
                      <td class='text-center align-middle'>".self::validSeq($plasmide->vector_seq)."</td>
                      ".$position."
                      <td class='text-center align-middle'>".$plasmide->date."</td>
                      <td class='text-center align-middle'>".self::getBiomolLink($plasmide->seq_file)."</td>
                    </tr>";
      }
    }
    return $affiche;
  }
    private function openTable($super){
      if($super == true){
          $entetes = ["Number","Name","Résistance","Vecteur d'origine","Méthode de clonage","Insert","Investigateur", "Bactérie","Insert seq","Vecteur seq","Stock glycérolé","ADN stock","Date","SnapGene"];
      }
      else{
          $entetes = ["Name","Résistance","Vecteur d'origine", "Méthode de clonage","Insert","Investigateur", "Bactérie","Insert seq","Vecteur seq","Date","SnapGene"];

      }
        $affiche ='<table class="table table-hover table-sm"><thead><tr>';
            foreach($entetes as $entete) {
                $affiche .= "<th class='text-center align-middle' scope='col'>$entete</th>";
            }
            $affiche .= '</tr></thead><tbody>';
    return $affiche;
  }

  private function closeTable(){
    return '</tbody></table>';
  }

    private function countPlasmide(){
    $db = Database::query("SELECT COUNT(id) as nbPlasmide FROM $this->table");
    $this->count = $db->fetch();
    return $this->count;
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

  public function getSearchList($res,$num,$super){
    if($num>0){
    $affiche = '<h5 class="m-3">Number of results : '.$num.'</h5>';
    $affiche .= self::openTable($super);
    $affiche .= self::getData($super,$res);
    $affiche .= self::closeTable();
      }
      return $affiche;
  }

  public function getPlasmideData($super){
      $data = self::getPlasmides();
    $affiche = self::openTable($super);
    $affiche .= self::getData($super,$data);
    $affiche .= self::closeTable();
    return $affiche;
  }
  public function afficheSinglePlasmide($id,$super){
      $plasmide = self::getPlasmides($id);
      if($super == true){
          $num = "<tr class=''>
              <th>Number</th>
              <td>".$plasmide->number."</td>
            </tr>
            <tr>";
          $position = "<tr>
              <th>Glycérol Stock</th>
              <td>".$plasmide->glycerol_stock."</td>
            </tr>
            <tr>
              <th>DNA Stock</th>
              <td>".$plasmide->dna_stock."</td>
            </tr>
            <tr>";
          $modif = "<a href='modif.php?id=".$plasmide->id."' class='btn btn-primary'>Modifier</a>";
      }
      else{
          $num = "";
          $position ="";
          $modif = "";
      }
      $affiche ="<div class='card mt-3'>
        <h4 class='card-header'>".$plasmide->name."</h4>
        <div class='card-body'>
          <table class='table table-bordered table-hover'>
            ".$num."
            <tr>
              <th>Réssistance</th>
              <td>".$plasmide->resistance."</td>
            </tr>
            <tr>
              <th>Insert</th>
              <td>".$plasmide->inserted_dna."</td>
            </tr>
            <tr>
              <th>Dna séquence</th>
              <td>".$plasmide->dna_sequence."</td>
            </tr>            <tr>
              <th>Vecteur d'origine</th>
              <td>".$plasmide->origin_vector."</td>
            </tr>
            <tr>
              <th>Bactérie</th>
              <td>".$plasmide->bacterie."</td>
            </tr>
            <tr>
              <th>Date</th>
              <td>".$plasmide->date."</td>
            </tr>
            <tr>
              <th>Investigateur</th>
              <td>".$plasmide->investigateur."</td>
            </tr>
            <tr>
              <th>Comments</th>
              <td>".$plasmide->comments."</td>
            </tr>
            <tr>
              <th>Séquence insert vérifiée</th>
              <td>".self::validSeq($plasmide->insert_seq)."</td>
            </tr>
            <tr>
              <th>Séquence vecteur vérifiée</th>
              <td>".self::validSeq($plasmide->vector_seq)."</td>
            </tr>
            ".$position."
              <th>Biomol File</th>
              <td>".self::getBiomolLink($plasmide->seq_file)."</td>
            </tr>
          </table>
          ".$modif."
        </div>
      </div>";
      return $affiche;
  }

    public function newPlasmide($name, $dna_sequence, $number, $resistance, $investigateur, $origin_vector, $inserted_dna, $cloning_method, $bacterie, $vector_seq, $insert_seq, $glycerol_stock, $dna_stock, $date, $comments, $seqFileName)
    {
        if(Database::query("INSERT INTO $this->table (name, dna_sequence, number, resistance, investigateur, origin_vector, inserted_dna, cloning_method, bacterie, vector_seq, insert_seq, glycerol_stock, dna_stock, date, comments, seq_file) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
            [
                $name, $dna_sequence, $number, $resistance, $investigateur, $origin_vector, $inserted_dna, $cloning_method, $bacterie, $vector_seq, $insert_seq, $glycerol_stock, $dna_stock, $date, $comments, $seqFileName
            ])){
            return true;
        }
    }

    public function upPlasmide($name, $dna_sequence, $number, $resistance, $investigateur, $origin_vector, $inserted_dna, $cloning_method, $bacterie, $vector_seq, $insert_seq, $glycerol_stock, $dna_stock, $date, $comments, $seq_file, $id)
    {
        if(Database::query("UPDATE $this->table SET
            name = ?,
            dna_sequence = ?,
            number = ?,
            resistance = ?,
            investigateur = ?,
            origin_vector = ?,
            inserted_dna = ?,
            cloning_method = ?,
            bacterie = ?,
            vector_seq = ?,
            insert_seq = ?,
            glycerol_stock = ?,
            dna_stock = ?,
            date = ?,
            comments = ?,
            seq_file = ?
            WHERE id = ?"
            ,[$name, $dna_sequence, $number, $resistance, $investigateur, $origin_vector, $inserted_dna, $cloning_method, $bacterie, $vector_seq, $insert_seq, $glycerol_stock, $dna_stock, $date, $comments, $seq_file, $id])){
            return true;
        }
    }

    public function deletePlasmide($id){
        if(Database::query("DELETE FROM $this->table WHERE id = $id")){
            return true;
        }
    }
}
