<?php
namespace Extranet;
use Extranet\Project;
use Extranet\TeamProparacyto;

class cskForm extends Form{

  private $table_project;
  private $table_project_type;
  private $table_investigator;
  private $table_past_investigator;
  private $table_categories;

  public function __construct(){
    $this->table_project = App::getTableProjects();
    $this->table_project_type = App::getTableProjectTypes();
    $this->table_investigator = App::getTableUsers();
    $this->table_past_investigator = App::getTablePastMembers();
    $this->table_categories = App::getTableProtocolCategories();
  }

  private function getInvestigator(){
    return Database::query("SELECT * FROM $this->table_investigator WHERE proparacyto = 1 AND admin_validate = 1 ORDER BY name ASC");
  }

  private function getPastInvestigator(){
    return Database::query("SELECT * FROM $this->table_past_investigator ORDER BY name ASC");
  }

  public function investigator_select($name, $label, $default, $user_id, $past=true){
    $affiche = "<div class=\"form-group col-md-4\">";
    if($label ===''){}else{$affiche .= "<label for='$name'>$label</label>";}
    $affiche .= "<select name='$name' class='form-control'>";
    $affiche .= $this->investigator_option($default, $user_id, $past);
    $affiche .= "</select></div>";
    return $affiche;
  }

  public function investigator_option($default, $user_id, $past){
    $affiche = "<option value='0'> ".$default." </option>";
    $affiche .= "<optgroup label='Current investigator'>";
    $datas = self::getInvestigator();
      foreach($datas as $data){
        if($data->id === $user_id){$selected = " selected ";}else{$selected ="";}
        $affiche .= "<option value=$data->id $selected >";
        $affiche .= ucfirst($data->firstname). " " . strtoupper($data->name) ;
        $affiche .= "</option>";
      }
      if($past === true){
    $affiche .= "<optgroup label='Past investigator'>";
    $past = self::getPastInvestigator();
    foreach($past as $data){
      if($data->id === $user_id){$selected = " selected ";}else{$selected ="";}
      $affiche .= "<option value=$data->id $selected >";
      $affiche .= ucfirst($data->firstname). " " . strtoupper($data->name) ;
      $affiche .= "</option>";
    }
  }
    return $affiche;

  }

  public function selectY2HHorizontal($data, $name, $label, $default, $value){
    $affiche = "<div class='form-group col-md-4'><select name='$name' class='form-control'>";
    $affiche .= $this->optionY2H($data, $default, $value);
    $affiche .= "</select></div>";
    return $affiche;
  }
  public function optionY2H($datas, $default, $value){
    $affiche = "<option value='0'> ".$default." </option>";
      foreach($datas as $data){
        if($data->prot_id === $value){$selected = "selected";}else{$selected = "";}
        $affiche .= "<option $selected value='$data->prot_id'>";
        $affiche .= $data->prot_name;
        $affiche .= "</option>";
      }
    return $affiche;
  }
  public function category_select($name, $label, $default,$value){
    return "
      <div class='form-group col-md-4'>
      <label for='$name'>$label</label>
      <select name='$name' class='form-control'>
      ".$this->category_option($default, $value)."
      </select></div>
    ";
  }

  private function category_option($default, $value){
    $affiche = "<option value='0'> ".$default."</option>";
    $datas = Database::query("SELECT * FROM $this->table_categories")->fetchAll();
    foreach($datas as $data){
      if($data->id === $value){$selected = " selected ";}else{$selected = "";}
      $affiche .= "<option value=$data->id $selected >";
      $affiche .= ucfirst($data->category);
      $affiche .= "</option>";
    }
    return $affiche;
  }

  public function type_select($name, $label, $default, $value){
    $affiche = "<div class=\"form-group col-md-4\"><label for='$name'>$label</label>";
    $affiche .= "<select name='$name' class=' form-control'>";
    $affiche .= self::type_option($value, $default);
    $affiche .= "</select></div>";
    return $affiche;
  }

  public function type_option($value, $default){
    $proj = new Project();
    $affiche = "<option value='0'> ".$default." </option>";
    $datas = $proj->getProjectType();
      foreach($datas as $data){
        if($data->id === $value){$selected = " selected ";}else{$selected ="";}
        $affiche .= "<option value=".$data->id." $selected >";
        $affiche .= ucfirst($data->type);
        $affiche .= "</option>";
      }
    return $affiche;
  }
  public function project_select($name, $label,$default,$value){
    $affiche ='<div class="form-group"><a class="btn btn-info" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">';
    $affiche .= $default;
    $affiche .= '</a>';
    $affiche .= '<div class="collapse" id="collapseExample">';
    $affiche .= $this->project_list_check($default, $value);
    $affiche .= '</div>';
    $affiche .= '</div>';
    return $affiche;
  }
  public function project_list_check($default,$value){
    $projList = explode(",",$value);
    $proj = new Project();
    $affiche ="";
    $types = $proj->getProjectType();
    foreach($types as $type){
      $projects = $proj->getListProjectForm($type->id);
        if($projects){
        $affiche .= "<h4>$type->type</h4>";
        $affiche .= "<div class='row align-items-start'>";
        foreach($projects as $project){
          if (in_array($project->id, $projList)){$checked = 'checked';}else{$checked ='';}
        $affiche .= "<div class='col-md-auto form-check m-1'>
                    <input name='project[]' class='btn-check' type='checkbox' id=$project->id value=$project->id $checked autocomplete='off'>
                    <label class='btn btn-outline-$type->color text-dark' for=$project->id>$project->project</label></div>";
          }
        $affiche .= "</div>";
      }
  }
    return $affiche;
  }

  public function project_select2($name, $label, $default, $value){
    $affiche = "<div class=\"form-group col-md-4\"><label for='$name'>$label</label>";
    $affiche .= "<select name='$name' class=' form-control'>";
    $affiche .= $this->project_option($default, $value);
    $affiche .= "</select></div>";
    return $affiche;
  }

  public function project_option2($default, $value){
    $proj = new Project();
    $affiche = "<option value='0'> ".$default." </option>";
    $types = $proj->getProjectType();
    foreach($types as $type){
      $projects = $proj->getListProject($type->id);
      $affiche .= "<optgroup label='$type->type'>";
      foreach($projects as $project){
        if($project->id === $value){$selected = " selected ";}else{$selected ="";}
        $affiche .= "<option value='$project->id' $selected >";
        $affiche .= ucfirst($project->project);
        $affiche .= "</option>";
      }
    }
    return $affiche;
  }
}
