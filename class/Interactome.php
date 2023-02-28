<?php
namespace Extranet;

class Interactome{

  private $table_interaction;
  private $table_prot;
  private $table_AD;
  private $table_BD;


  public function __construct(){
    $this->table_interaction = App::getTableY2hInteractions();
    $this->table_AD = App::getTableY2HAD();
    $this->table_BD = App::getTableY2HBD();
    $this->table_prot = App::getTableY2Hprot();
  }

  public function getProt(){
    return Database::query("SELECT * FROM $this->table_prot ORDER BY prot_name ASC")->fetchAll();
  }
  public function getAD($prot=false){
    if(isset($prot)){
      return Database::query("SELECT * FROM $this->table_AD WHERE id_prot = $prot")->fetchAll();
    }
    else{
      return Database::query("SELECT * FROM $this->table_AD")->fetchAll();
    }
  }
  public function getBD($prot=false){
    if(isset($prot)){
      return Database::query("SELECT * FROM $this->table_BD WHERE id_prot = $prot")->fetchAll();
    }
    else{
      return Database::query("SELECT * FROM $this->table_BD")->fetchAll();
    }
  }

  public function getADbyId($id){
    return Database::query("SELECT * FROM $this->table_AD WHERE ad_id  = $id")->fetch();
  }
  public function getBDbyId($id){
    return Database::query("SELECT * FROM $this->table_BD WHERE bd_id = $id")->fetch();
  }


  public function newProt($name){
    return Database::query("INSERT INTO $this->table_prot SET prot_name = ?" ,[$name]);
  }
  public function newAD($ad,$prot){
    return Database::query("INSERT INTO $this->table_AD SET ad_vector=? ,id_prot=? ", [$ad,$prot]);
  }
  public function newBD($bd,$prot,$auto){
    return Database::query("INSERT INTO $this->table_BD SET bd_vector=?, id_prot=?, autoactiv=?", [$bd,$prot,$auto]);
  }
  public function upAD($id,$ad,$prot){
    return Database::query("UPDATE $this->table_AD SET
      ad_vector = ?,
      id_prot = ?
      WHERE ad_id = ?
    ",[$ad,$prot,$id]);
  }
  public function upBD($id,$bd,$prot,$auto){
    return Database::query("UPDATE $this->table_BD SET
      bd_vector = ?,
      id_prot = ?,
      autoactiv = ?
      WHERE bd_id = ?
    ",[$bd,$prot,$auto,$id]);
  }
  public function getChoice(){
    $prots = self::getProt();
    //var_dump($prots);
    $affiche = "<table class='table table-striped table-sm'>";
    $affiche .= "<thead>
                  <tr>
                    <th>Protein</th>
                    <th>pGADT7 vector</th>
                    <th>pGBKT7 vector</th>
                  </tr>
                </thead>";
    foreach($prots as $prot){
      $ad = self::getAD($prot->prot_id);
      $bd = self::getBD($prot->prot_id);
      if(!empty($ad) && !empty($bd)){
        $affiche .= "<tr>
                      <th>$prot->prot_name</th>
                      <td>";
        $affiche .= "<ul class='list-unstyled'><li>
        <input type='checkbox' name='all_AD' id='all_AD-$prot->prot_id' value=''><label for='all_AD-$prot->prot_id'>All Plasmides</label>
        </ul></li>";
        $affiche .= self::checkAll('AD',$prot->prot_id);
        foreach($ad as $vector_ad){
          $affiche .= self::getInterCheckBoxAD($prot->prot_id,$vector_ad->ad_id,$vector_ad->ad_vector);
        }
        $affiche .= "</td><td>";
        $affiche .= "<ul class='list-unstyled'><li>
        <input type='checkbox' id='all_BD-$prot->prot_id' onClick='checkAll($prot->prot_id,BD);' ><label for='all_BD-$prot->prot_id'>All Plasmides</label>
        </ul></li>";
        $affiche .= self::checkAll('BD',$prot->prot_id);

        foreach($bd as $vector_bd){
          $affiche .= self::getInterCheckBoxBD($prot->prot_id,$vector_bd->bd_id,$vector_bd->bd_vector);
        }
        $affiche .= "</td></tr>";
      }
    }
    $affiche .= "</table>";
    return $affiche;
  }

  private function getInterCheckBoxAD($prot,$id,$vector){
    return "
    <ul class='list-unstyled'><li>
    <input type='checkbox' class='caseAD_$prot' name='AD[]' id='$vector' value='$id'><label for='$vector'>$vector</label>
    </ul></li>";
  }
  private function getInterCheckBoxBD($prot,$id,$vector){
    return "
    <ul class='list-unstyled'><li>
    <input type='checkbox' name='BD[]' class='caseBD_$prot' id='$vector' value='$id'><label for='$vector'>$vector</label>
    </ul></li>";
  }

  private function checkAll($var,$prot){
    return '
      <script>
      $(function(){
        $("#all_'.$var.'-'.$prot.'").click(function () {
      if($(this).prop("checked"))
          $(".case'.$var.'_'.$prot.'").prop("checked",true);
      else
         $(".case'.$var.'_'.$prot.'").prop("checked",false);
       });
      });
     </script>
    ';
  }
  private function getInteraction($ad,$bd){
    return Database::query("SELECT * FROM $this->table_interaction WHERE id_ad_vector = $ad AND id_bd_vector = $bd")->fetch();
  }

  public function getInteractome($adList,$bdList){

    $affiche = "<table class='table table-bordered table-sm'><thead><tr><th></th>";
    foreach($adList as $ad){
      $ad_vector = self::getADbyId($ad);
      $affiche .= "<th><a class='link text-decoration-none small' href='modif.php?ad-id=$ad_vector->ad_id'>$ad_vector->ad_vector</a></th>";
    }
    $affiche .= "</th></tr></thead>";
    foreach($bdList as $bd){
      $bd_vector = self::getBDbyId($bd);
      if($bd_vector->autoactiv == 1){$class = "class='bg-warning'";}else{$class ="";}
      $affiche .= "<tbody><tr $class ><th><a class='link text-decoration-none small' href='modif.php?bd-id=$bd_vector->bd_id'>$bd_vector->bd_vector</a></th>";
      foreach($adList as $ad){
        $inter = self::getInteraction($ad,$bd);
        if($inter && !empty($inter->interaction)){
          echo self::modalInteraction($ad,$bd,$adList,$bdList,$inter);
          $style = self::positiveInteraction($inter->interaction);
          $affiche .= "<td $style ><button class='btn small' data-bs-toggle='modal' data-bs-target='#exampleModal$ad-$bd'>$inter->interaction</button></td>";
        }
        else{
          echo self::modalInteraction($ad,$bd);
          $affiche .= "<td><button class='btn text-black-50 small' data-bs-toggle='modal' data-bs-target='#exampleModal$ad-$bd'>Not tested</button></td>";
        }
      }
      $affiche .= "</tr></tbody>";
    }
    $affiche .= "</table>";
    return $affiche;
  }

  private function positiveInteraction($inter){
    $needle  = ['yes', 'Yes', 'YES', 'weak', 'Weak', 'WEAK', 'positive', 'positif', 'Positive', 'Positif'];
    $subject = explode(' ',$inter);
    foreach($needle as $n){
      if(in_array($n, $subject)){
        return "class='bg-info'";
      }
    }
  }
  private function modalInteraction($ad,$bd,$adList,$bdList,$inter=false){
    $listAD = Str::arrayToStr($adList,',');
    $listBD = Str::arrayToStr($bdList,',');

    $ad_vector = self::getADbyId($ad);
    $bd_vector = self::getBDbyId($bd);
    return '
    <div class="modal fade" id="exampleModal'.$ad.'-'.$bd.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Interaction </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            Between <strong>'.$ad_vector->ad_vector.'</strong> and <strong>'.$bd_vector->bd_vector.'</strong>
            <form method="post" action="#">
              <div class="form-group">
                <label for="interaction" class="col-form-label">Interaction :</label>
                <input type="text" name="interaction" class="form-control" id="interaction" value="'.$inter->interaction.'">
              </div>
          </div>
          <div class="modal-footer">
            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="update" class="btn btn-primary">Save interaction</button>
            <input type="hidden" name="inter" value="'.$inter->y2h_id.'">
            <input type="hidden" name="ad" value="'.$ad.'">
            <input type="hidden" name="bd" value="'.$bd.'">
            <input type="hidden" name="AD" value="'.$listAD.'">
            <input type="hidden" name="BD" value="'.$listBD.'">

          </div>
            </form>
        </div>
      </div>
    </div>
    ';
  }

  public function newInteraction($ad,$bd,$inter){
    if(Database::query("INSERT INTO $this->table_interaction SET id_ad_vector = ? , id_bd_vector = ? , interaction = ?",[$ad,$bd,$inter])){
      return true;
    }
    else return false;
  }

  public function updateInteraction($id,$inter){
    if(Database::query("UPDATE $this->table_interaction SET interaction = ? WHERE y2h_id = ?",[$inter,$id])){
      return true;
    }
    else return false;
    }

}
