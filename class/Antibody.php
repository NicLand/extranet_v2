<?php
namespace Extranet;
use Extranet\Display;

class Antibody{
  private $table_primary;
  private $table_secondary;
  private $table;
  private $primFolder = "prim/";
  private $secFolder = "sec/";
  private $primItems = [
    'Name'=> 'name',
    'Made In' => 'made_in',
    'Ig Class' => 'ig_class',
    'Immunogen' => 'immunogen',
    'Conjugate' => 'conjugate',
    'Company' => 'company',
    'Reference' => 'reference',
    'Batch' => 'batch',
    'Dilution Usage' => 'dilution_used',
    'Storage' => 'storage',
    'Date' => 'date',
    'Comments' => 'comments'];
  private $secItems = [
    'Name'=> 'name',
    'Made In' => 'made_in',
    'Ig Class' => 'ig_class',
    'Ig Specificity' => 'ig_specificity',
    'Conjugate' => 'conjugate',
    'Company' => 'company',
    'Reference' => 'reference',
    'Batch' => 'batch',
    'Dilution Usage' => 'dilution_used',
    'Storage' => 'storage',
    'Date' => 'date',
    'Comments' => 'comments'
  ];
  private $docLinkImg = "/img/iconePDF.png";

  public function __construct(){
    $this->table_primary = App::getTablePrimAntibody();
    $this->table_secondary = App::getTableSecAntibody();
  }
  private function headTable($list){
    if($list==1){$fields=['Name','Made In','Ig Class', 'Company', 'Ref.', 'Dilution', 'Storage'];}
    elseif($list==2){$fields = ['Name','Made In','Ig Class','Ig Specificity','Conjugate', 'Company', 'Ref.', 'Dilution', 'Storage'];}
    return Display::TableauHead($fields);
  }
  public function afficheList($list){
    $data = self::getList($list);
    $affiche = self::headTable($list);
    foreach($data as $antibody){
      if($list==1){$diffField = "";}elseif($list==2){$diffField ="<td class='text-center align-middle'>".$antibody->ig_specificity."</td>
        <td class='text-center align-middle'>".$antibody->conjugate."</td>";}
      $affiche .= "<tr style='cursor: pointer;' onclick='document.location.href=\"../antibody/details.php?ab=".$list."&id=".$antibody->id."\"'>
                    <th class='align-middle'>".Str::wrapWord($antibody->name, 20, "<br/>")."</th>
                    <td class='text-center align-middle'>".$antibody->made_in."</td>
                    <td class='text-center align-middle'>".$antibody->ig_class."</td>
                    ".$diffField."
                    <td class='text-center align-middle'>".$antibody->company."</td>
                    <td class='text-center align-middle'>".Str::wrapWord($antibody->reference, 20, "<br/>")."</td>
                    <td class='text-center align-middle'>".$antibody->dilution_used."</td>
                    <td class='text-center align-middle'>".$antibody->storage."</td>
                  </tr>";
    }
    $affiche .= Display::TableauFoot();
    return $affiche;
  }

  private function setTableByNum($ab){
    if($ab == 1){return $this->table = $this->table_primary;}
    elseif($ab == 2){return $this->table = $this->table_secondary;}
    else{return false;}
  }
  private function getList($list){
    $table = self::setTableByNum($list);
    return Database::query("SELECT * FROM $table ORDER BY name ASC")->fetchAll();
  }

  public function getSingle($ab,$id){
    $tab = self::setTableByNum($ab);
    return Database::query("SELECT * FROM $tab WHERE id = $id")->fetch();
  }

  private function getItems($ab){
    if($ab == 1){return $this->primItems;}
    elseif($ab == 2){return $this->secItems;}
  }
  private function getFolder($ab){
    if($ab == 1){return $this->primFolder;}
    elseif($ab == 2){return $this->secFolder;}
  }
  public function afficheSingle($ab,$id){
    $items = self::getItems($ab);
    $folder = self::getFolder($ab);
    $data = self::getSingle($ab,$id);
    $affiche = "
      <div class='card mt-3'>
      <h5 class='card-header'>".$data->name."</h5>
        <div class='card-body'>
          <table class='table table-bordered'>
    ";
    foreach($items as $item => $field){
      $affiche .= "
        <tr>
          <th>".$item." :</th>
          <td>".$data->$field."</td>
        </tr>
      ";
    }
    if(!empty($data->document)){
      $affiche .= "
        <tr>
          <th>Documentation : </th>
          <td><a href='".$folder.$data->document."'><img style='width:40px' src='".App::getRoot().$this->docLinkImg."'></a></td>
        </tr>
      ";
    }
    $affiche .= "</table>
    <a href='modif.php?ab=$ab&id=$data->id' class='btn btn-primary'>Modify the antibody's data</a>
    </div></div>";
    return $affiche;
  }

  public function newAntibody($ab,$datas){
      $table = self::setTableByNum($ab);
      $items = Str::arrayToStr(self::getItems($ab),",").",document"; //on ajoute un pour les document qui ne sont pas dans l'array.
      $values = "";
      for($i=0;$i<count($datas);$i++){
        $values .= ",?";
      }
      $values = ltrim($values,",");
      if(Database::query("INSERT INTO $table ($items) VALUES ($values)",$datas)){
        return true;
      }
  }

  public function updateAntibody($ab,$id,$datas){
    $table = self::setTableByNum($ab);
    $items = self::getItems($ab);
    $itemList = array_values($items);
    array_push($itemList,"document");
    $arrayCombine = array_combine($itemList,$datas);
    $toUpdate ="";
    foreach($arrayCombine as $key => $value){
      $toUpdate .= ", ".$key."= ?";
    }
    $toUpdate = ltrim($toUpdate, ",");

    if(Database::query("UPDATE $table SET $toUpdate WHERE id = $id",$datas)){
      return true;
    };
  }

  public function deleteAntibody($ab,$id){
    $table = self::setTableByNum($ab);
    if(Database::query("DELETE FROM $table WHERE id = $id")){
      return true;
    }
  }

  public function getSearchList($ab,$res,$num){
    if($num>0){
      $affiche = '<h5 class="m-3">Number of results : '.$num.'</h5>';
      $affiche.= self::headTable($ab);
      foreach ($res as $antibody) {
        if($ab==1){$diffField = "";}elseif($ab==2){$diffField ="<td class='text-center align-middle'>".$antibody->ig_specificity."</td>
          <td class='text-center align-middle'>".$antibody->conjugate."</td>";}
        $affiche .= "<tr style='cursor: pointer;' onclick='document.location.href=\"../antibody/details.php?ab=".$ab."&id=".$antibody->id."\"'>
                      <th class='align-middle'>".Str::wrapWord($antibody->name, 20, "<br/>")."</th>
                      <td class='text-center align-middle'>".$antibody->made_in."</td>
                      <td class='text-center align-middle'>".$antibody->ig_class."</td>
                      ".$diffField."
                      <td class='text-center align-middle'>".$antibody->company."</td>
                      <td class='text-center align-middle'>".Str::wrapWord($antibody->reference, 20, "<br/>")."</td>
                      <td class='text-center align-middle'>".$antibody->dilution_used."</td>
                      <td class='text-center align-middle'>".$antibody->storage."</td>
                    </tr>";
      }
      $affiche .= Display::TableauFoot();
      return $affiche;
    }
  }
}
