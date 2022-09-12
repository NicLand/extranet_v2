<?php

namespace Extranet;
use Extranet\Database;
use Extranet\Chemical;
class Form{

  protected $data;

  public function __construct($data = array()){
    $this->data = $data;
  }

  protected function getValue($index){
    return isset($this->data[$index]) ? $this->data[$index] : null;
  }

  public function divForm($formPart){
    return "<div class='col'><div class='form-group row'>".$formPart."</div></div>";
  }
  public function divFormCard($x){
    return "<div class='form-group row m-1'>$x</div>";
  }

  public function option($datas, $default){
    $affiche = "<option value='0'> ".$default." </option>";
      foreach($datas as $data){
        $affiche .= "<option value=".$data->id.">";
        $affiche .= $data->team_name;
        $affiche .= "</option>";
      }
    return $affiche;
  }
  public function openForm(){
    return "<form method='post' action='#' enctype='multipart/form-data'>";
  }
  public function closeForm(){
    return "</form>";
  }

  public function select($data, $name, $label, $default){
    $affiche = "<label for='$name'>$label</label>";
    $affiche .= "<select name='$name' class=' form-control'>";
    $affiche .= $this->option($data, $default);
    $affiche .= "</select>";
    return $this->divForm($affiche);
  }

  public function input($name, $type, $label, $value, $smallText=false){
    $affiche = '<div class="form-group col"><label class="col-form-label">'. ucfirst($label).'</label>';
    $affiche.= '<input type="'.$type.'" step="any" name = "' . $name . '" value="'.$value.'" class="form-control"/>';
    if(!empty($smallText)){
      $affiche .= '<small class="form-text text-muted">'.$smallText.'</small>';
    }
    $affiche .='</div>';
    return $affiche;
  }
  public function inputCard($name, $type, $label, $value, $smallText=false){
    $affiche = "<label for='$name' class='col-sm-3 col-form-label'>$label</label>
    <div class='col-sm-9'>
      <input name ='$name' type='$type' step='any'class='form-control' id='$name' value='$value'>";
      if(!empty($smallText)){
        $affiche .= '<small class="form-text text-muted">'.$smallText.'</small>';
      }
    $affiche .= "</div>";
    return $this->divFormCard($affiche);
  }
  public function textAreaCard($name, $label, $value){
    $affiche = '<label class="col-sm-3 col-form-label" for="'.$name.'">'.$label.'</label>
    <div class="col-sm-9">
    <textarea class="form-control" name ="'.$name.'" id="'.$label.'" rows="3">'.$value.'</textarea>
    </div>';
    return $this->divFormCard($affiche);
  }
  public function selectCard($data, $name, $label, $default){
    $affiche = "<label class='col-sm-3 col-form-label' for='$name'>$label</label>";
    $affiche .= "<div class='col-sm-9'><select name='$name' class='form-control'>";
    $affiche .= $this->option($data, $default);
    $affiche .= "</select></div>";
    return $this->divFormCard($affiche);
  }
  public function selectSimpleCard($data, $name, $label, $value){
    $affiche = "<label class='col-sm-3 col-form-label' for='$name'>$label</label>";
    $affiche .= "<div class='col-sm-9'><select name='$name' class='form-control'>";
    $affiche .= $this->optionSimpleCard($data, $value);
    $affiche .= "</select></div>";
    return $this->divFormCard($affiche);
  }
  public function optionSimpleCard($datas, $value){
    $affiche = "<option value='0'> --- </option>";
      foreach($datas as $key=>$val){
        if($key == $value){$selected = " selected ";}else{$selected="";}
        $affiche .= "<option $selected value=".$key.">".$val."</option>";
      }
    return $affiche;

  }

  public function inputInline($name, $type, $label, $value, $smallText=false){
    $affiche = '<div class="form-group col-md-4">
      <label for="'.$name.'">'.$label.'</label>
      <input type="'.$type.'" class="form-control" name="'.$name.'" value="'.$value.'">';
      if(!empty($smallText)){
        $affiche .= '<small class="form-text text-muted">'.$smallText.'</small>';
      }
      $affiche .= '</div>';
      return $affiche;
  }

  public function openFormHorizontal(){
    return "<form action='".$_SERVER["PHP_SELF"]."' method='post'><div class='row gy-2 gx-3 align-items-center'>";
  }

  public function closeFormHorizontal(){
    return "</div></form>";
  }

  public function inputHorizontal($name, $type, $size, $label, $value=false){
    return "<div class='col-auto col-sm-$size'>
                    <input type='$type' name='$name' class='form-control' placeholder='$label' value='$value'>
                  </div>";
  }

  public function buttonHorizontal($type, $css, $label = false){
    return "<div class='col-auto'>
              <button class='btn btn-$css text-white mr-1' type='$type'>$label</button>
            </div>";
  }

  public function submitHorizontal($css,$name,$value){
    return "<div class='col-auto'>
            <input class='btn btn-$css text-white mr-1' type='submit' name='$name' value='$value'>
          </div>";
  }

  public function checkBox($name, $id, $value, $label, $checked, $smallText=false){
    $affiche = '<div class="form-check">';
    $affiche .= '<input type="checkbox" name="'.$name.'" value="'.$value.'" class="form-check-input" id="'.$id.'" '.$checked.'>';
    $affiche .= '<label class="form-check-label" for="'.$id.'">'.$label.'</label>';
    if(!empty($smallText)){
      $affiche .= '<small class="form-text text-muted">'.$smallText.'</small>';
    }
    $affiche .= '</div>';
    return $affiche;
  }

  public function checkBoxInline($name, $value, $label){
    if($value == 1){$check = "checked";}else{$check ="";}
    $affiche = '<div class="col-auto d-flex align-items-center"><div class="form-check form-check-inline">';
    $affiche .= '<input type="checkbox" name='.$name.' value='.$value.' class="form-check-input" id="'.$value.'" '.$check.'>';
    $affiche .= '<label class="form-check-label" for="'.$value.'">'.$label.'</label></div></div>';
    return $affiche;
  }

  public function checkBoxChemIcone($name, $label, $value){
    $value = explode(",",$value);
    $chem = new Chemical('');
    $datas = $chem->chemIcone();
    $affiche = '<div class="form-group col form-check-inline">';
    foreach($datas as $key=>$icone){
      if(in_array($key,$value)){$checked = "checked='checked'";}else{$checked = "nop";}
      $affiche .= '<label class="form-check-label" for="'.$key.'">'.$icone.'</label>';
      $affiche .= '<input '.$checked.' type="checkbox" name="'.$name.$key.'" value='.$key.' class="form-check-input" id="'.$key.'">';
    }

    $affiche .= "</div>";
    return $affiche;
  }
  public function radioInlineCheck($name, $value, $label, $checked=false){
    if($checked){$check ="checked";}else{$check ="";}
  $affiche = '
  <input class="btn-check btn-lg m-2" type="radio" name="'.$name.'" id="'.$value.'" value="'.$value.'" autocomplete="off" '.$check.'>
  <label class="btn btn-outline-success" for="'.$value.'">'.$label.'</label>
  ';
  return $affiche;
  }

  public function radioInline($name, $value, $label){
    $affiche = '<div class="form-check form-check-inline m-2">
  <input class="form-check-input" type="radio" name="'.$name.'" id="'.$value.'" value="'.$value.'">
  <label class="form-check-label" for="'.$value.'">'.$label.'</label>
</div>';
  return $affiche;
  }

  public function radio($name, $value, $label, $checked=""){
    $affiche = '<div class="form-check">
  <input class="form-check-input" type="radio" name="'.$name.'" id="'.$value.'" value="'.$value.'" '.$checked.'>
  <label class="form-check-label" for="'.$value.'">'.$label.'</label>
  </div>';
  return $affiche;
  }

  public function textArea($name, $label, $value){
    return '<div class="form-group col">
    <label for="'.$name.'">'.$label.'</label>
    <textarea class="form-control" name ="'.$name.'" id="'.$label.'" rows="3">'.$value.'</textarea>
    </div>';
  }

  public function ckeditorText($name, $label, $value){
    $affiche = '<div class="form-group col">
    <label for="'.$name.'">'.$label.'</label>
    <textarea class="form-control ckeditor" name ="'.$name.'" id="'.$name.'" rows="30">'.$value.'</textarea>
    </div>
    <script>';
    $affiche .= "
    CKEDITOR.replace( '.$name.',
{
	filebrowserBrowseUrl : '/extranet_v2/include/ckfinder/ckfinder.html',
	filebrowserImageBrowseUrl : '/extranet_v2/include/ckfinder/ckfinder.html?type=Images',
	filebrowserFlashBrowseUrl : '/extranet_v2/include/ckfinder/ckfinder.html?type=Flash',
	filebrowserUploadUrl : '/extranet_v2/include/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	filebrowserImageUploadUrl : '/extranet_v2/include/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	filebrowserFlashUploadUrl : '/extranet_v2/include/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
});
    </script>";
    return $affiche;
  }

  public function button($type, $css, $label = false){
    return "<button class='btn btn-$css text-white m-1' type='$type' value='$label'>$label</button>";
  }

  public function submit($css, $name, $value){
    return "<input class='btn btn-$css text-white m-1' type='submit' name='$name' value='$value'>";
  }

  public function delete($lang=false){
    if(isset($lang) && $lang == "FR"){
      $title = "Êtes-vous certain ?";
      $text = "La suppression est définitive !";
      $close = "Fermer";
      $delete = "Supprimer";
    }
    else{
      $title = "Are you sure ?";
      $text = "The deletion is final !";
      $close = "Close";
      $delete = "Delete";
    }
    $affiche='<button type="button" class="btn btn-danger m-1" data-bs-toggle="modal" data-bs-target="#verifDelete">
                '.$delete.'
              </button>';
    $affiche .= '<!-- Modal -->
                  <div class="modal fade" id="verifDelete" tabindex="-1" aria-labelledby="verifDeleteLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="verifDeleteLabel">'.$title.'</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                          </button>
                        </div>
                        <div class="modal-body">
                          '.$text.'
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">'.$close.'</button>
                          <button type="submit" class="btn btn-primary" name="delete" value="Delete">'.$delete.'</button>
                        </div>
                      </div>
                    </div>
                  </div>';
    return $affiche;
  }
  public function inputSearch($page, $value, $opt='all'){
    $affiche = "<form action='$page' method='post' class='row g-3 align-items-center m-3'>
            <div class='col-7'>
            <div class='input-group'>
              <input name='search' class='form-control' type='text' value='".$value."' placeholder='Search'>
              </div></div>
              ";
    $options = [
      'all'=>'All the words',
    'one'=>'One of the words',
  ];
    foreach($options as $option=>$text){
      if($opt == $option){$checked = " checked ";}else{$checked = "";}
      $affiche .= "
      <div class='col'>
      <div class='form-check'>
      <input name='search_option' class='form-check-input' type='radio' value='$option' id='$option' ".$checked.">
      <label class='form-check-label' for='$option'>$text</label>
      </div></div>";
    }
    $affiche .= "<div class='col'><button class='btn btn-primary' type='submit'>Go !</button>
            </div></form>";
    return $affiche;
  }

  public function inputBigSearch($page, $value, $opt="all"){
    $affiche = "<form action='$page' method='post'>
            <div class='form-inline center'>
              <input name='search' class='form-control form-control-lg m-3 col-4' type='text' value='".$value."' placeholder='Search'>";
    $options = [
      'all'=>'All the words',
    'one'=>'One of the words'
  ];
    foreach($options as $option=>$text){
      if($opt == $option){$checked = " checked ";}else{$checked = "";}
      $affiche .= "<div class='form-check form-check-inline'>
      <input name='search_option' class='form-check-input' type='radio' value='$option' id='$option' ".$checked.">
      <label class='form-check-label' for='$option'>$text</label>
      </div>";
    }
    $tables = App::getTablesForSearch();
    $affiche .= "<select class='form-control form-control-lg'>";
    $affiche .= "<option> --- </option>";
    //var_dump($tables);
    foreach($tables as $key=>$table){
      $affiche .= "<option value='$table'>$key</option>";
    }
    $affiche .= "</select>";
    $affiche .= "<button class='btn btn-primary m-3 col-1' type='submit'>Go !</button>
            </div></form>";
    return $affiche;
  }
  public function selectColor($name,$label,$default,$value){
    $colors = ['primary','secondary','success','danger','info'];
    $affiche = '<div class="form-group col-md-4">
    <select name="'.$name.'" class="form-select form-select-md col-md-4 mb-3" aria-label="selectColor">
  <option value=0 >'.$default.'</option>';
  foreach($colors as $color){
    if($color === $value){$selected = " selected ";}else{$selected = "";}
    $affiche .= '<option class="text-white bg-'.$color.'" value="'.$color.'" '.$selected.'>'.$color.'</option>';
  }
  $affiche .= '</select></div>';
  return $affiche;
  }

  public function protocolCategorySelect($tab, $name, $label, $default, $value){
    return "
      <div class='form-group col-md-4'>
      <label for='$name'>$label</label>
      <select name='$name' class='form-control'>
      ".$this->protocolCategoryOption($tab,$default, $value)."
      </select></div>
    ";
  }

  private function protocolCategoryOption($tab,$default, $value){
    $affiche = "<option value='0'> ".$default."</option>";
    $datas = Database::query("SELECT * FROM $tab")->fetchAll();
    foreach($datas as $data){
      if($data->id === $value){$selected = " selected ";}else{$selected = "";}
      $affiche .= "<option value=$data->id $selected >";
      $affiche .= ucfirst($data->category);
      $affiche .= "</option>";
    }
    return $affiche;
  }
}
