<?php
namespace Extranet;
use Extranet\App;

class Project{

  private $table_project;
  private $table_project_type;
  private $type;
  private $link = "list.php?l=";
  private $data;
  private $tritrypImg = "/img/tritrypdb.png";
  private $tritryp = "http://tritrypdb.org/tritrypdb/app/record/gene/";
  private $tryptagImg = "/img/tryptag.png";
  private $tryptag = "http://tryptag.org/?id=";
  private $uniprotImg = "/img/uniprot_logo.gif";
  private $uniprot = "http://www.uniprot.org/uniprot/?query=";
  private $toxodb = "https://toxodb.org/toxo/app/record/gene/";
  private $toxoImg = "/img/toxodb.png";

  public function __construct(){
    $this->table_project = App::getTableProjects();
    $this->table_project_type = App::getTableProjectTypes();
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

  public function getSearchList($search,$num){
    if($num>0){
      $affiche  = '<h5 class="m-3">Number of results : '.$num.'</h5>';
      $affiche .= self::tableProjectsTryp();
      foreach($search as $project){
        $affiche .= "<tr>
                      <th><a class='text-decoration-none text-dark' href='details.php?id=".$project->id."'>".$project->project."</a></th>
                      <td>".$project->accession."</td>
                      <td>".$this->afficheInvestigator($project->investigator)."</td>
                      <td>".$project->associate.""
                      .self::getLink($project->type, $project->accession)."
                      <td class ='align-middle'><a href='modif.php?id=$project->id' class='btn btn-secondary' role='button'>Modify</a></td>
                    </tr>";
      }
      $affiche .= self::closeProjects();
    }
    else{
      $affiche = "<h5 class=''>No result for your search</h5>";
    }
    return $affiche;
  }

  public  function getProject($id){
    return Database::query("SELECT * FROM $this->table_project WHERE id = $id");
  }

  public function getListProjectForm($list){
    if($list === "1"){
      return false;
    }
    else{
      return Database::query('SELECT * FROM '.$this->table_project.' WHERE type = "'.$list.'" ORDER BY project ASC');
    }
  }

  public function getListProject($list){
    if($list === "1"){
      $where = "";
    }
      else{
      $where = " WHERE type = '".$list."'";
    }
    return Database::query('SELECT * FROM '.$this->table_project.' '.$where.' ORDER BY project ASC');
  }
  public function getProjectType(){
    return Database::query("SELECT * FROM $this->table_project_type");
  }
  public function getProjectTypeByList($list){
    return Database::query("SELECT * from $this->table_project_type WHERE id = '$list'")->fetch();
  }

  public function getLink($project, $accession){
    if($project == "2"){
    return "<td class ='align-middle'><a href='".$this->tritryp.$accession."' target=_blank><img src='".App::getRoot().$this->tritrypImg."' style='width:50px;'></a></td>
            <td class ='align-middle'><a href='".$this->uniprot.$accession."' target=_blank><img src='".App::getRoot().$this->uniprotImg."' style='width:50px;'></a></td>
            <td class ='align-middle'><a href='".$this->tryptag.$accession."' target=_blank><img src='".App::getRoot().$this->tryptagImg."' style='width:50px;'></a></td>";
    }
    elseif($project == "3"){
      return "<td class ='align-middle'><a href='".$this->toxodb.$accession."' target=_blank><img src='".App::getRoot().$this->toxoImg."' style='width:50px;'></a></td>
              <td class ='align-middle'><a href='".$this->uniprot.$accession."' target=_blank><img src='".App::getRoot().$this->uniprotImg."' style='width:50px;'></a></td>
              <td class ='align-middle'></td>";
    }
    elseif($project == "4"){
      return "<td class ='align-middle'><a href='".$this->tritryp.$accession."' target=_blank><img src='".App::getRoot().$this->tritrypImg."' style='width:50px;'></a></td>
              <td class ='align-middle'><a href='".$this->uniprot.$accession."' target=_blank><img src='".App::getRoot().$this->uniprotImg."' style='width:50px;'></a></td>
              <td class ='align-middle'></td>";
    }
    else{
      return "<td class ='align-middle'></td>
              <td class ='align-middle'><a href='".$this->uniprot.$accession."' target=_blank><img src='".App::getRoot().$this->uniprotImg."' style='width:50px;'></a></td>
              <td class ='align-middle'></td>";
    }
  }

  public function tableProjectsTryp(){
    return Display::TableauHead(['Project','Accession number','Investigator','Associate','EuPath Link','UniProt Link','TrypTag Link','Modify']);
  }

  public function prepareProjects($list){
    $affiche ="";
    $data = self::getListProject($list);
    foreach ($data as $project){
    $affiche .= "<tr style='cursor:pointer' onClick='document.location=\"details.php?id=$project->id\"'>
                  <th><a class='text-decoration-none text-dark' href='details.php?id=".$project->id."'>".$project->project."</a></th>
                  <td>".$project->accession."</td>
                  <td>".$this->afficheInvestigator($project->investigator)."</td>
                  <td>".$project->associate."</td>"
                  .self::getLink($project->type, $project->accession)."
                  <td class ='align-middle'><a href='modif.php?id=$project->id' class='btn btn-secondary' role='button'>Modify</a></td>
                </tr>";
    }
    return $affiche;
  }

  public function closeProjects(){
    return display::TableauFoot();
  }

  public function afficheProjects($list){
    $affiche = self::tableProjectsTryp();
    $affiche .= self::prepareProjects($list);
    $affiche .= self::closeProjects();
    return $affiche;
  }

  public function afficheSingleProject($id){
    $affiche = "";
    $data = self::getProject($id);
    $project = $data->fetch();
      $affiche .= "<h1 class='mt-3'>".$project->project." Project</h1>";
      $affiche .= '<div class="alert alert-info" role="alert">'.$project->comment.'</div>';
    return $affiche;
  }

  public function newProject($project, $accession, $type, $investigator,$associate, $comment){
    if(Database::query("INSERT INTO $this->table_project (project, accession, type, investigator, associate, comment)
    VALUES ('$project', '$accession', '$type', '$investigator','$associate', '$comment')")){
      return true;
    }
  }

  public function updateProject($id, $project, $accession, $type, $investigator, $associate, $comment){
    if(Database::query("UPDATE $this->table_project SET
      project = ?,
      accession = ?,
      type = ?,
      investigator = ?,
      associate = ?,
      comment = ?
      WHERE id = ?
    ",[$project, $accession, $type, $investigator, $associate, $comment, $id])){
      return true;
    }
  }

  public function deleteProject($id){
    if(Database::query("DELETE FROM $this->table_project WHERE id = $id")){
      return true;
    }
  }

  public function afficheProjectButton($link){
    $buttons = Database::query("SELECT * FROM $this->table_project_type")->fetchAll();

    $affiche ="";
    $index = new ButtonIndex();
    foreach($buttons as $button){
      $affiche .= $index->manualButton($button->type,$link.$button->id, $button->description, $button->color, $button->textColor);
    }
    return $affiche;
  }

  public function getProjectFromProjList($projList){
    //var_dump($projList);
    $projArr = explode(",", $projList);
    $affiche ="";
    foreach ($projArr as $project){
      if($project == NULL && $project ==""){$affiche.="";}
      else{
      $proj = self::getProject($project)->fetch();
      if($proj){
      $affiche .= "<span class='m-1 badge bg-info'>$proj->project</span></br>";
    }
    else{$affiche .= "";}
    }
    }
    return $affiche;
  }
  public function newProjectType($type, $color, $textColor, $description){
    if(Database::query("INSERT INTO $this->table_project_type (type, color, textColor, description)
    VALUES ('$type', '$color','$textColor', '$description')")){
      return true;
    }
  }

  public function upProjectType($id,$type, $color, $textColor, $description){
    if(Database::query("UPDATE $this->table_project_type SET
      type = ?,
      color = ?,
      textColor = ?,
      description = ?
      WHERE id = ?
      ",[$type, $color, $textColor, $description, $id])){
      return true;
    }
  }

  public function delProjectType($id){
    if(Database::query("DELETE FROM $this->table_project_type WHERE id = $id")){
      return true;
  }
}
}
