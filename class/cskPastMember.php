<?php
namespace Extranet;
use Extranet\Investigator;

class cskPastMember{

  private $table;
  private $table_current;

  public function __construct(){
    $this->table = App::getTablePastMembers();
    $this->table_current = App::getTableUsers();
  }

  private function getPastMember(){
    return Database::query("SELECT * FROM $this->table ORDER BY annee_fin DESC")->fetchAll();
  }

  private function tableHead(){
    return Display::TableauHead(['Position in the team', 'Name','Start', 'End', 'Current Position', 'Modify']);
  }

  private function tableContent(){
    $data = self::getPastMember();
    $content ="";
    foreach($data as $member){
      $content .=  "
        <tr>
          <td>$member->team_pos</td>
          <td>$member->firstname $member->name</td>
          <td>$member->annee_debut</td>
          <td>$member->annee_fin</td>
          <td>$member->current_pos</td>
          <td><a href='modif.php?id=$member->id' class='btn btn-secondary' role='button'>Update</a></td>
        </tr>
      ";
    }
    return $content;

  }
  private function tableClose(){
    return Display::TableauFoot();
  }

  public function affichePastMembers(){
    return self::tableHead().self::tableContent().self::tableClose();
  }

  public function newPastMember($user, $position,$debut,$fin,$current_pos){
    $current_user = Database::query("SELECT * FROM $this->table_current WHERE id =?",[$user])->fetch();

    $newPast = Database::query("INSERT INTO $this->table (id,name,firstname,team_pos, annee_debut, annee_fin, current_pos)
    VALUES (?,?,?,?,?,?,?)",
    [
      $current_user->id,
      $current_user->name,
      $current_user->firstname,
      $position,
      $debut,
      $fin,
      $current_pos
    ]);
    if($newPast){
      $removeCurrent = Database::query("DELETE FROM $this->table_current WHERE id=$user");
    }
    return true;
  }

  public function updatePastMember($id,$position,$firstname,$name,$debut,$fin,$current_pos){
    if(Database::query("UPDATE $this->table SET
      team_pos = ?,
      firstname = ?,
      name = ?,
      annee_debut = ?,
      annee_fin = ?,
      current_pos = ?
      WHERE id =?
    ",[$position,$firstname,$name,$debut,$fin,$current_pos,$id])){
      return true;
    }
  }
}
