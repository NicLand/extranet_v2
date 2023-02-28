<?php
namespace Extranet;
use Extranet\Investigator;

class Protocol{

  private $table_protocols;
  private $table_protocoles;
  private $table_categories;
  private $protocolFolder = "sheet/";
  private $protocolPDFimg = "/img/iconePDF.png";

  public function __construct($extranet){
    if($extranet === "proparacyto"){
      $this->table_protocoles = App::getTableProtocols();
      $this->table_categories = App::getTableProtocolCategories();
    }
    elseif($extranet === "spacvir"){
      $this->table_protocoles = App::getTableSpacvirProtocols();
      $this->table_categories = App::getTableSpacvirProtocolCategories();
    }
    elseif($extranet === "reger"){
      $this->table_protocoles = App::getTableRegerProtocols();
      $this->table_categories = App::getTableRegerProtocolCategories();
    }
    else{
      $this->table_protocoles = App::getTableProtocols();
      $this->table_categories = App::getTableProtocolCategories();
    }
  }

  public function getCategoryList(){
    return Database::query("SELECT * FROM $this->table_categories ORDER BY category ASC")->fetchAll();
  }

  public function getProtocolByCatCKE($cat){
    return Database::query("SELECT * FROM $this->table_protocoles WHERE category = $cat")->fetchAll();
  }

  public function getProtocolCKE($id){
    return Database::query("SELECT * FROM $this->table_protocoles WHERE id = $id")->fetch();
  }

  public function getCategory($id){
    return Database::query("SELECT * FROM $this->table_categories WHERE id = $id")->fetch();
  }

  public function afficheProtocolAccordeon(){
    $link = 'protocol.php?id=';
    $categories = self::getCategoryList();

    $affiche = '<div class="accordion w-75" id="protocole_list">';

    foreach($categories as $cat){

      $slug = Str::fileNameUpload($cat->category);
      $protocoles = self::getProtocolByCatCKE($cat->id);
      if($protocoles){
        $affiche .= '<div class="accordion-item">
          <h2 class="accordion-header" id="'.$slug.'-heading">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#'.$slug.'-collapse" aria-expanded="false" aria-controls="'.$slug.'-collapse">
            <span class="h5 me-2">'.$cat->category.'</span> <span class="badge bg-info ml-2">'.count($protocoles).'</span>
            </button>
          </h2>
          <div id="'.$slug.'-collapse" class="accordion-collapse collapse" aria-labelledby="'.$slug.'-heading">
                <a href="modif.php?p=cat&id='.$cat->id.'" class="btn btn-secondary btn-sm m-1" role="button">Update Category</a>
              <div class="accordion-body">
              <ul class="list-group">';
        foreach($protocoles as $protocole){
          $affiche .= '<a class="list-group-item list-group-item-action" href="'.$link.$protocole->id.'"><strong>'.$protocole->name.'</strong></a>';
          $affiche .= '</ul></div></div></div>';
        }
      }
      $affiche .= '</div>';
    }
    return $affiche;
  }

  public function afficheSingleCKE($id){
    $data = self::getProtocolCKE($id);
      if($data){
        $user = new Investigator;
        $updater = $user->getInvestigator($data->updater);
        $affiche = "<a href='modif.php?p=pro&id=$id' class='btn btn-success m-2' role='button'>Modify this protocol</a>";
        $affiche .= "<h1>$data->name</h1>";
        if(isset($updater)){
        $affiche .= "<div class='alert alert-info' role='alert'>Last Update $data->dateModif by $updater</div>";
      }
        $affiche .= "<div class='card p-2'>$data->protocolBody</div>";
      }
    return $affiche;
  }

  public function getSearchList($res,$num){
    if($num>0){
      $affiche  = '<h5 class="m-3">Number of results : '.$num.'</h5>';
      $affiche .= self::headTableProtocols();
      foreach($res as $protocol){
        $affiche .= "<tr>
                      <th>$protocol->protocol</th>
                      <td><a href='".$this->protocolFolder.$protocol->link."' class='btn btn-info' role='button'>Download</a></td>
                      <td><a href='modif.php?p=pro&id=$protocol->id' class='btn btn-secondary' role='button'>Update</a></td>
                    </tr>";
      }
      $affiche .= $this->closeTableProtocols();
    }else{
      $affiche = "<h5 class=''>No result for your search</h5>";
    }
      return $affiche;
    }

    public function newCategory($cat){
      var_dump($cat);
      if(Database::query("INSERT INTO $this->table_categories (category) VALUES (?)",[$cat])){
        return true;
      }
    }
    public function upCategory($id, $cat){
      if(Database::query("UPDATE $this->table_categories SET category = ? WHERE id = ? ",[$cat,$id])){
        return true;
      }
    }
    public function delCategory($id){
      if(Database::query("DELETE FROM $this->table_categories WHERE id = $id")){
        return true;
      }
    }

    public function newProtocolCKE($name,$cat,$body,$user){
      $dateModif = date("Y-m-d H:i:s");
      if(Database::query("INSERT INTO $this->table_protocoles (name,category,protocolBody,updater,dateModif) VALUES (?,?,?,?,?)",[$name,$cat,$body,$user,$dateModif])){
        return true;
      }
    }

    public function upProtocolCKE($id,$name,$cat,$body,$user){
      if(Database::query("UPDATE $this->table_protocoles SET
      name = ?,
      category = ?,
      protocolBody = ?,
      updater =?,
      dateModif = NOW()
      WHERE id = ?
      ",[$name,$cat,$body,$user,$id])){
        return true;
      }
    }

    public function delProtocol($id){
      if(Database::query("DELETE FROM $this->table_protocoles WHERE id = $id")){
        return true;
      }
    }
}
