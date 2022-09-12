<?php
namespace Extranet;

class Chemical{

  private $table;
  private $extranet;
  private $iconeFolder = "/img/icone_chem/";
  private $chemIcone = [1,2,3,4,5,6,7,8,9];
  private $msdsFolder;
  private $docFolder;
  private $docLinkImg = "/img/iconePDF.png";

  public function __construct($extranet){
    $this->extranet = $extranet;
    if($this->extranet == 'proparacyto'){
    $this->table = App::getTableChemicals();
    }
    elseif($this->extranet == 'reger'){
      $this->table = App::getTableRegerChemicals();
    }
    else{
    }
    $this->msdsFolder = "/$this->extranet/chemical/msds/";
    $this->docFolder = "/$this->extranet/chemical/doc/";
  }

  public function chemIcone(){
    $affiche =[];
    foreach($this->chemIcone as $icone){
      $affiche[$icone] = "<img style='width:60px;' class='float-left' src='".App::getRoot().$this->iconeFolder.$icone.".png'>";
    }
    return $affiche;
  }

  private function getList(){
    return Database::query("SELECT * FROM $this->table ORDER BY name ASC")->fetchAll();
  }

  public function getSingle($id){
    return Database::query("SELECT * FROM $this->table WHERE id = $id")->fetch();
  }

  public function getSearchList($res,$num){
    if($num>0){
      $affiche  = '<h5 class="m-3">Number of results : '.$num.'</h5>';
      $affiche .= self::headTable();
      foreach($res as $chemical){
        $affiche .= "<tr style='cursor: pointer;' onclick='document.location.href=\"../chemical/details.php?id=".$chemical->id."\"'>
                      <th class='align-middle'>".$chemical->name."</th>
                      <td class='text-center align-middle'>".Str::wrapWord($chemical->real_name,20, false)."</td>
                      <td class='text-center align-middle'>".$chemical->company."</td>
                      <td class='text-center align-middle'>".$chemical->reference."</td>
                      <td class='text-center align-middle'>".$chemical->cas."</td>
                      <td class='text-center align-middle'>".self::getIcone($chemical->icone)."</td>
                      <td class='text-center align-middle'>".$chemical->localisation."</td>
                    </tr>";
      }
      $affiche .= self::closeTable();
    }
    else{
      $affiche = "<h5 class=''>No result for your search</h5>";
    }
    return $affiche;
  }

  private function headTable(){
    return '<table class="table table-hover table-sm">
      <thead>
        <tr>
          <th scope="col">Usual Name</th>
          <th scope="col">Real Name</th>
          <th class ="text-center" scope="col">Company</th>
          <th class ="text-center" scope="col">Reference</th>
          <th class ="text-center" scope="col">CAS</th>
          <th scope="col">Icone</th>
          <th scope="col">Localisation</th>
          </tr>
      </thead>
      <tbody>';
  }

  private function closeTable(){
    return '</tbody></table>';
  }

  public function getIcone($list){
    $affiche ="";
    if(!empty($list)){
      $icones = explode(",", $list);
      foreach ($icones as $icone){
        $affiche .= "<img style='width : 40px;' class='float-left' src='".App::getRoot().$this->iconeFolder.$icone.".png'>";
      }
    }
    return $affiche;
  }

  public function getcheckedIcone($list){
    $affiche ="";
    if(!empty($list)){
      $icones = explode(",", $list);
      foreach ($icones as $icone){
        $affiche .= "<img style='width : 40px;' class='float-left' src='".App::getRoot().$this->iconeFolder.$icone.".png'>";
      }
    }
    return $affiche;
  }

  private function getLink($type, $file){
    if(!empty($file)){
      if($type == "msds"){
        $link = App::getRoot().$this->msdsFolder.$file;
      }
      elseif($type == "doc"){
        $link = App::getRoot().$this->docFolder.$file;
      }
      return "<a href='$link'><img style='width:40px' src='".App::getRoot().$this->docLinkImg."'></a>";
    }
  }

  public function afficheList(){
    $data = self::getList();
    $affiche = self::headTable();
    foreach($data as $chemical){
      $affiche .= "<tr style='cursor: pointer;' onclick='document.location.href=\"../chemical/details.php?id=".$chemical->id."\"'>
                    <th class='align-middle'>".$chemical->name."</th>
                    <td class='text-center align-middle'>".Str::wrapWord($chemical->real_name,20, false)."</td>
                    <td class='text-center align-middle'>".$chemical->company."</td>
                    <td class='text-center align-middle'>".$chemical->reference."</td>
                    <td class='text-center align-middle'>".$chemical->cas."</td>
                    <td class='text-center align-middle'>".self::getIcone($chemical->icone)."</td>
                    <td class='text-center align-middle'>".$chemical->localisation."</td>
                  </tr>";
      }
    $affiche .= self::closeTable();
    return $affiche;
  }

  public function afficheSingle($id){
    $data = self::getSingle($id);
    //var_dump($data);
    $affiche = "
    <div class='card mt-3'>
    <h5 class='card-header'>".$data->name."</h5>
      <div class='card-body'>
        <table class='table table-bordered'>
        <tr>
          <th>Real Name</th>
          <td>".$data->real_name."</td>
        </tr>
        <tr>
          <th>Formula</th>
          <td>".$data->formule."</td>
        </tr>
        <tr>
          <th>MW</th>
          <td>".$data->mw."</td>
        </tr>
        <tr>
          <th>Company</th>
          <td>".$data->company."</td>
        </tr>
        <tr>
          <th>Reference</th>
          <td>".$data->reference."</td>
        </tr>
        <tr>
          <th>Quantity</th>
          <td>".$data->quantity."</td>
        </tr>
        <tr>
          <th>CAS</th>
          <td>".$data->cas."</td>
        </tr>
        <tr>
          <th>Icone</th>
          <td>".self::getIcone($data->icone)."</td>
        </tr>
        <tr>
          <th>Localisation</th>
          <td>".$data->localisation."</td>
        </tr>
        <tr>
          <th>MSDS</th>
          <td>".self::getLink("msds",$data->msds)."</td>
        </tr>
        <tr>
          <th>Documentation</th>
          <td>".self::getLink("doc",$data->documentation)."</td>
        </tr>
    </table>
    <a href='modif.php?id=$data->id' class='btn btn-primary'>Modify the chemical's data</a>
    </div></div>";
    return $affiche;
  }

  public function newChemical($name,$real_name, $formule, $mw,$company,$reference,$quantity, $cas, $icone, $localisation, $msds, $doc){
    if(Database::query("INSERT INTO $this->table (name,real_name,formule,mw,company,reference,quantity, cas, icone, localisation, msds, documentation) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)",
    [
      $name,$real_name,$formule,$mw,$company,$reference,$quantity, $cas, $icone, $localisation, $msds, $doc
    ])){
      return true;
    }
  }

  public function upChemical($id,$name,$real_name, $formule, $mw,$company,$reference,$quantity, $cas, $icone, $localisation, $msds, $doc){
    if(Database::query("UPDATE $this->table SET
      name = ?,
      real_name = ?,
      formule = ?,
      mw = ?,
      company = ?,
      reference = ?,
      quantity = ?,
      cas = ?,
      icone = ?,
      localisation = ?,
      msds = ?,
      documentation = ?
      WHERE id = ?
    ",[$name,$real_name, $formule, $mw,$company,$reference,$quantity, $cas, $icone, $localisation, $msds, $doc,$id])){
      //var_dump($icone);
      return true;
    }
  }

  public function delchemical($id){
    if(Database::query("DELETE FROM $this->table WHERE id = $id")){
      return true;
    }
  }
}
