<?php
namespace Extranet;
use Extranet\Project;

class Primer{

  private $data;
  private $perPage = 100;
  private $count;
  private $nbPage;
  private $cPage;
  private $table;
  private $extranet;
  private $proparacyto_fields = ['num', 'name', 'sequence', 'id_project', 'purpose', 'investigator', 'comments', 'date'];
  private $reger_fields = ['numero', 'name', '5modif', 'sequence', '3modif', 'length', 'Tm', 'MW', '%GC', '[ng]', '[uM]', 'date', 'investigateur', 'commentaire'];

  public function __construct($extranet){
    $this->extranet = $extranet;
    if($this->extranet == "proparacyto"){
      $this->table = App::getTablePrimers();
    }
    elseif($this->extranet == "reger"){
      $this->table = App::getTableRegerPrimers();
    }
    else{
    }
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
    $affiche = '<h5 class="m-3">Number of results : '.$num.'</h5>';
    $affiche .= self::getTableHeader();
    if($this->extranet == 'reger'){
      $affiche .= self::getPrimerDataReger($search);
    }
    else{
      $affiche .= self::getPrimerData($search);
    }
      }
      return $affiche;
    }

  private function setList($cPage=false){
    $nbPage = $this->nbPage();
    $currentPage = ceil(($this->cPage($cPage)-1)* $this->perPage);
    $db = Database::query("SELECT * FROM $this->table ORDER BY num ASC LIMIT $currentPage , $this->perPage");
    $this->data = $db->fetchall();
    return $this->data;
  }

  public function paginationPrimer($cPage){
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
  private function getTableHeader(){
    if ($this->extranet == 'proparacyto'){
      $affiche = "
      <table class='table table-hover table-sm'>
          <thead>
            <tr>
              <th class='align-middle' scope='col'>Name</th>
              <th class='text-center align-middle' scope='col'>Sequence</th>
              <th class='text-center align-middle' scope='col'>Length</th>
              <th class='text-center align-middle' scope='col'>GC%</th>
              <th class='text-center align-middle' scope='col'>Project</th>
              <th class='text-center align-middle' scope='col'>Purpose</th>
              <th class='text-center align-middle' scope='col'>Investigator</th>
              <th class='text-center align-middle' scope='col'>Date</th>
            </tr>
          </thead>
          <tbody>
            ";
    }
    elseif($this->extranet == 'reger'){
      $affiche = "
        <table class='table table-hover table-sm'>
          <thead>
            <tr>";
            foreach($this->reger_fields as $col){
              $affiche.= "<th class='text-center align-middle' scope='col'>$col</th>";
            }
      $affiche .= "
            </tr>
          </thead>
          <tbody>";
    }
    return $affiche;
  }
  private function getPrimerData($datas){
    $affiche ="";
    foreach ($datas as $primer){
      if(!is_null($primer->id_project) && $primer->id_project !=0){
        $proj = self::getSinglePrimerProject($primer->id_project);
      }
      else{$proj="";}
      $affiche .= "<tr style='cursor: pointer;' onclick='document.location.href=\"../primer/modif.php?id=".$primer->id."\"'>
                    <td>$primer->name</td>
                    <td>".Str::wrapWord($primer->sequence, 20, "<br/>")."</td>
                    <td class='text-center'>".self::primerLength($primer->sequence)."</td>
                    <td class='text-center'>".self::setGC($primer->sequence)."</td>
                    <td>".$proj."</td>
                    <td>$primer->purpose</td>
                    <td>".$this->afficheInvestigator($primer->investigator)."</td>
                    <td>$primer->date</td>
                  </tr>";
    }
    $affiche .= "</tbody></table>";
    return $affiche;
  }
  private function getPrimerDataReger($datas){
    $affiche ="";
    foreach ($datas as $primer){
      $affiche .= "<tr style='cursor: pointer;' onclick='document.location.href=\"../primer/modif.php?id=$primer->id\"'>
                    <td>$primer->num</td>
                    <td>$primer->name</td>
                    <td>$primer->five_modif</td>
                    <td>".Str::wrapWord($primer->sequence, 20, "<br/>")."</td>
                    <td>$primer->three_modif</td>
                    <td>".self::primerLength($primer->sequence)."</td>
                    <td>$primer->Tm</td>
                    <td>$primer->mol_w</td>
                    <td>".self::setGC($primer->sequence)."</td>
                    <td>$primer->concentration_ng</td>
                    <td>$primer->concentration_uM</td>
                    <td>$primer->date</td>
                    <td>$primer->investigateur</td>
                    <td>$primer->comments</td>
                  </tr>";
    }
    $affiche .= "</tbody></table>";
    return $affiche;
  }
  public function getList($cPage){
    $datas = $this->setList($cPage);
    $affiche = self::getTableHeader();
    if($this->extranet == 'reger'){
      $affiche .= self::getPrimerDataReger($datas);
    }
    else{
      $affiche .= self::getPrimerData($datas);
    }
    return $affiche;
  }

  private function countPrimer(){
    $db = Database::query("SELECT COUNT(id) as nbPrimer FROM $this->table");
    $this->count = $db->fetch();
    return $this->count;
  }

  public function getCount(){
    return $this->countPrimer();
  }

  public function nbPage(){
    $count = $this->countPrimer()->nbPrimer;
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

  private function primerLength($seq){
    if(isset($seq)){
    $seq = Str::strLength(str_replace(' ', '',$seq));
    }
    return $seq;
  }

  public function setGC($seq){
    if(isset($seq)){
      $g = mb_substr_count($seq, 'g');
      $c = mb_substr_count($seq, 'c');
      $G = mb_substr_count($seq, 'G');
      $C = mb_substr_count($seq, 'C');
      $gc = ($g+$c+$G+$C);
      $gcPercent = round(($gc/$this->primerLength($seq))*100, 1);
    }
    return $gcPercent;
  }

  public function getSinglePrimerProject($projects){
    $newP = new Project;
    return $newP->getProjectFromProjList($projects);
  }

  public function newPrimer($num, $name, $sequence, $project, $purpose, $inverstigator, $comments, $date){
    if(Database::query("INSERT INTO $this->table (num, name, sequence, id_project, purpose, investigator, comments, date) VALUES (?,?,?,?,?,?,?,?)",
    [$num, $name, $sequence, $project, $purpose, $inverstigator, $comments, $date])){
      return true;
    }
  }
  public function newPrimerReger($num,$name,$five_modif,$sequence,$three_modif,$Tm,$mol_w,$concentration_ng,$concentration_uM,$date,$investigateur,$comments){
    if(Database::query("INSERT INTO $this->table (num,name,five_modif,sequence,three_modif,Tm,mol_w,concentration_ng,concentration_uM,date,investigateur,comments) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)",
    [$num,$name,$five_modif,$sequence,$three_modif,$Tm,$mol_w,$concentration_ng,$concentration_uM,$date,$investigateur,$comments])){
      return true;
    }
  }

  public function upPrimer($id,$num, $name, $sequence, $project, $purpose, $investigator, $comments, $date){
    if(Database::query("UPDATE $this->table SET
      num = ?,
      name = ?,
      sequence = ?,
      id_project = ?,
      purpose = ?,
      investigator = ?,
      date = ?,
      comments = ?
      WHERE id  = ?
    ",[$num, $name, $sequence, $project, $purpose, $investigator, $date, $comments, $id])){
      return true;
    }
  }
  public function upPrimerReger($id,$num,$name,$five_modif,$sequence,$three_modif,$Tm,$mol_w,$concentration_ng,$concentration_uM,$date,$investigateur,$comments){
    if(Database::query("UPDATE $this->table SET
      num = ?,
      name = ?,
      five_modif = ?,
      sequence = ?,
      three_modif = ?,
      Tm = ?,
      mol_w = ?,
      concentration_ng = ?,
      concentration_uM = ?,
      date = ?,
      investigateur = ?,
      comments = ?
      WHERE id = ?
    ",
    [$num,$name,$five_modif,$sequence,$three_modif,$Tm,$mol_w,$concentration_ng,$concentration_uM,$date,$investigateur,$comments,$id])){
      return true;
    }
  }

  public function deletePrimer($id){
    if(Database::query("DELETE FROM $this->table WHERE id = $id")){
      return true;
    }
  }

  public function getSinglePrimer($id){
     return Database::query("SELECT * FROM $this->table WHERE id = $id")->fetch();
  }

  public function afficheSinglePrimer($id){
    $primer = self::getSinglePrimer($id);
    //var_dump($primer->id_project);
    if($primer->id_project != NULL){
      $projList = self::getSinglePrimerProject($primer->id_project);
    }
    if($projList){$proj = $projList;}else{$proj ='';}
    $affiche ="<div class='card mt-3'>
        <h5 class='card-header'>".$primer->name."</h5>
        <div class='card-body'>
          <table class='table table-bordered'>
            <tr>
              <th>Sequence</th>
              <td class='text-break'>".$primer->sequence."</td>
            </tr>
            <tr>
              <th>Length</th>
              <td>".self::primerLength($primer->sequence)."</td>
            </tr>
            <tr>
              <th>GC %</th>
              <td>".self::setGC($primer->sequence)."</td>
            </tr>
            <tr>
              <th>Project</th>
              <td>".$proj."</td>
            </tr>
            <tr>
              <th>Purpose</th>
              <td>".$primer->purpose."</td>
            </tr>
            <tr>
              <th>Investigator</th>
              <td>".$this->afficheInvestigator($primer->investigator)."</td>
            </tr>
            <tr>
              <th>Date</th>
              <td>".$primer->date."</td>
            </tr>
            <tr>
              <th>Comments</th>
              <td>".$primer->comments."</td>
            </tr>
          </table>
          <a href='modif.php?id=$primer->id' class='btn btn-primary'>Modify the primer</a>
        </div>
      </div>";
    return $affiche;
  }

public function exportPrimer($type){
  $primers = Database::query("SELECT * FROM $this->table ORDER BY id ASC")->fetchAll();
  $affiche ='';
  if($type === 'snap'){
    foreach ($primers as $primer){
      $seq = str_replace( array('<br>', '<br />', "\n", "\r", "\r\n", ";"," "), array( '', '', '', '', '', '', ''), $primer->sequence);
      $comm = str_replace( array('<br>', '<br />', "\n", "\r", "\r\n", ";"), array( '', '', '', '', '', ''), $primer->comments);
      if (!empty($primer->comments)){$tab = $comm.';';}else{$tab='';}
      $affiche .= $primer->name.';'.$seq.';'.$tab;
      $affiche .= "\n";
    }
  }
  elseif($type === 'amplifx'){
    $affiche .='// AmplifX Primer list created with AmplifX <version> 2.0.7</version><parameters>40/60/50/68</parameters>
    <OwnerIndex></OwnerIndex>
    <Familly></Familly>
';
    $id =0;
    foreach ($primers as $primer){
      $id +=1;
    $seq = str_replace( array('<br>', '<br />', "\n", "\r", "\r\n", ";"," "), array( '', '', '', '', '', '', ''), $primer->sequence);
    $affiche .= "<primer><UID></UID><cat></cat><ID>".$id."</ID><SEQ>".$seq."</SEQ><NAME>".$primer->name."</NAME><OWNER>".self::afficheInvestigator($primer->investigator)."</OWNER><L>".self::primerLength($primer->sequence)."</L><Q></Q><TM></TM><details></details></primer>";
    //$affiche .= $seq.';'.$id.';'.$primer->name."\n";
  }
}
  return $affiche;
}
}
