<?php
namespace Extranet;
use Extranet\TeamIMet;

class TeamIMetForm extends Form{

  private $table_souchier;
  private $table_souchier_antibio;
  private $table_souchier_souche;

  public function __construct(){
    $this->table_souchier = App::getTableIMetSouchier();
    $this->table_souchier_antibio = App::getTableIMetSouchierAntibio();
    $this->table_souchier_souche = App::getTableIMetSouchierSouche();
    $this->table_azote = App::getTableIMetAzote();
    $this->table_azote_forme = App::getTableIMetAzoteForme();
    $this->table_azote_souche = App::getTableIMetAzoteSouche();
  }

  public function selectSouche(){
    $datas = Database::query("SELECT * FROM $this->table_souchier_souche ORDER BY souche ASC")->fetchAll();

    $affiche = "<div class='form-group col-md-4'>
                <label for='souche'>Souche</label>
                <select name='souche' class='form-control'>";
    $affiche .= "<option value='0'> Choisir une souche </option>";
    foreach($datas as $data){
      $affiche .= "<option value='".$data->id_souche."'>".$data->souche."</option>";
    }
    $affiche .= "</select></div>";
    return $affiche;
  }

  public function selectAntibio(){
    $datas = Database::query("SELECT * FROM $this->table_souchier_antibio ORDER BY antibio ASC")->fetchAll();

    $affiche = "<div class='form-group col-md-4'>
                <label for='antibio'>Antibiotique</label>
                <select name='antibio' class='form-control'>";
    $affiche .= "<option value='0'> Choisir un antibiotique </option>";
    foreach($datas as $data){
      $affiche .= "<option value='".$data->id_antibio."'>".$data->antibio."</option>";
    }
    $affiche .= "</select></div>";
    return $affiche;
  }

  public function selectAzoteForme($name, $label, $default,$value){
    return "
      <div class='form-group col-md-4'>
      <label for='$name'>$label</label>
      <select name='$name' class='form-control'>
      ".$this->optionAzoteForme($default, $value)."
      </select></div>
    ";
  }

  private function optionAzoteForme($default, $value){
    $affiche = "<option value='0'> ".$default."</option>";
    $datas = Database::query("SELECT * FROM $this->table_azote_forme")->fetchAll();
    foreach($datas as $data){
      if($data->id_forme === $value){$selected = " selected ";}else{$selected = "";}
      $affiche .= "<option value=$data->id_forme $selected >";
      $affiche .= ucfirst($data->forme_text);
      $affiche .= "</option>";
    }
    return $affiche;
  }
  public function selectAzoteSouche($name, $label, $default,$value){
    return "
      <div class='form-group col-md-4'>
      <label for='$name'>$label</label>
      <select name='$name' class='form-control'>
      ".$this->optionAzoteSouche($default, $value)."
      </select></div>
    ";
  }

  private function optionAzoteSouche($default, $value){
    $affiche = "<option value='0'> ".$default."</option>";
    $datas = Database::query("SELECT * FROM $this->table_azote_souche")->fetchAll();
    foreach($datas as $data){
      if($data->id_souche === $value){$selected = " selected ";}else{$selected = "";}
      $affiche .= "<option value=$data->id_souche $selected >";
      $affiche .= $data->genre." ".ucfirst($data->souche_texte);
      $affiche .= "</option>";
    }
    return $affiche;
  }
  public function iMetDelete($text,$css){
    $affiche='<button type="button" class="btn btn-'.$css.' m-1" data-bs-toggle="modal" data-bs-target="#verifDelete">
                '.$text.'
              </button>';
    $affiche .= '<!-- Modal -->
                  <div class="modal fade" id="verifDelete" tabindex="-1" aria-labelledby="verifDeleteLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="verifDeleteLabel">Etes-vous certain ?</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                          </button>
                        </div>
                        <div class="modal-body">
                          Cette action est definitive !
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary" name="delete" value="Delete">Delete</button>
                        </div>
                      </div>
                    </div>
                  </div>';
    return $affiche;
  }
}
