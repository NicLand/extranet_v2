<?php
namespace Extranet;
use Extranet\Project;
use Extranet\TeamProparacyto;

class TeamSpacvirForm extends Form
{

    private $table_investigator;
    private $table_past_investigator;
    private $table_categories;

    public function __construct()
    {
        $this->table_investigator = App::getTableUsers();
        $this->table_past_investigator = App::getTablePastMembers();
        $this->table_categories = App::getTableSpacvirProtocolCategories();
    }

    private function getInvestigator()
    {
        return Database::query("SELECT * FROM $this->table_investigator WHERE spacvir = 1 AND admin_validate = 1 ORDER BY name ASC");
    }

    private function getPastInvestigator()
    {
        return Database::query("SELECT * FROM $this->table_past_investigator ORDER BY name ASC");
    }

    public function investigator_select($name, $label, $default, $user_id, $past = true)
    {
        $affiche = "<div class=\"form-group col-md-4\">";
        if ($label === '') {
        } else {
            $affiche .= "<label for='$name'>$label</label>";
        }
        $affiche .= "<select name='$name' class='form-control'>";
        $affiche .= $this->investigator_option($default, $user_id, $past);
        $affiche .= "</select></div>";
        return $affiche;
    }

    public function investigator_option($default, $user_id, $past)
    {
        $affiche = "<option value='0'> " . $default . " </option>";
        $affiche .= "<optgroup label='Current investigator'>";
        $datas = self::getInvestigator();
        foreach ($datas as $data) {
            if ($data->id === $user_id) {
                $selected = " selected ";
            } else {
                $selected = "";
            }
            $affiche .= "<option value=$data->id $selected >";
            $affiche .= ucfirst($data->firstname) . " " . strtoupper($data->name);
            $affiche .= "</option>";
        }
        if ($past === true) {
            $affiche .= "<optgroup label='Past investigator'>";
            $past = self::getPastInvestigator();
            foreach ($past as $data) {
                if ($data->id === $user_id) {
                    $selected = " selected ";
                } else {
                    $selected = "";
                }
                $affiche .= "<option value=$data->id $selected >";
                $affiche .= ucfirst($data->firstname) . " " . strtoupper($data->name);
                $affiche .= "</option>";
            }
        }
        return $affiche;

    }

    public function category_select($name, $label, $default, $value)
    {
        return "
      <div class='form-group col-md-4'>
      <label for='$name'>$label</label>
      <select name='$name' class='form-control'>
      " . $this->category_option($default, $value) . "
      </select></div>
    ";
    }

    private function category_option($default, $value)
    {
        $affiche = "<option value='0'> " . $default . "</option>";
        $datas = Database::query("SELECT * FROM $this->table_categories")->fetchAll();
        foreach ($datas as $data) {
            if ($data->id === $value) {
                $selected = " selected ";
            } else {
                $selected = "";
            }
            $affiche .= "<option value=$data->id $selected >";
            $affiche .= ucfirst($data->category);
            $affiche .= "</option>";
        }
        return $affiche;
    }
}
