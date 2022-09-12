<?php
namespace Extranet;
use Extranet\Database;
class Search{

  private $table_azote;
  private $azote_fields =['plasmide','souche','id_project','investigator','commentaire_azote'];

  private $table_project;
  private $project_fields =['project','investigator'];

  private $project_col = "id_project";

  private $table_primer;
  private $primer_fields =['name', 'sequence', 'purpose', 'investigator','id_project', 'comments'];

  private $table_plasmide;
  private $plasmide_fields = ['name', 'id_project','investigator'];

  private $table_freezer;
  private $freezer_fields = ['tube', 'id_project', 'investigator', 'comment'];

  private $table_vector;
  private $vector_fields = ['name','antibiotic', 'fragment_cloned','cloning_vector','investigator','comments'];

  private $table_prim_antibody;
  private $prim_antibody_fields =['name','made_in','ig_class','immunogen','conjugate','company','reference','comments'];

  private $table_sec_antibody;
  private $sec_antibody_fields = ['name','made_in','ig_class','ig_specificity','conjugate','company','reference','comments'];

  private $table_chemical;
  private $chemical_fields =['name','real_name','company','reference','cas'];

  private $table_protocol;
  private $protocol_fields =['name','category','protocolBody'];

  private $table_strain;
  private $strain_fields = ['name','groupe','paper', 'from', 'comment'];

  private $table_investigator;

  private $table_past_investigator;

  private $table_commande_fournisseur;
  private $commande_fournisseur_fields = ['fournisseur','website'];

  private $table_commande;
  private $commande_fields = ['fournisseur', 'user', 'reference', 'bon_commande', 'bon_livraison', 'designation','nomenclature'];

  private $table_spacvir_plasmide;
  private $spacvir_plasmide_fields = ['numero','name','origin','vector','insert'];

  private $table_imet_souchier;
  private $imet_souchier_fields = ['plasmide','fragment'];

  private $table_imet_azote;
  private $imet_azote_fields = ['modification','strain','forme','manipulateur','commentaire'];

  private $table_imet_azote_log;
  private $imet_azote_log_fields = ['textLog','dateLog','userLog'];

  private $table_reger_primer;
  private $reger_primer_fields = ['name','sequence','investigateur','comments'];

  private $table_reger_chemical;
  private $reger_chemical_fields = ['name','real_name','company','reference','cas'];

  private $table_reger_azote;
  private $reger_azote_fields = ['name','num_tube','numero','description'];
//========================================================================================
  private $searchTable;
  private $words;
  // s�parateur
  private $sep;
  // option de recherche
  private $option;
  // nombre de mots
  private $count_words;
  // clause where
  private $query_where = '';
  private $sql;
  private $data;


  public function __construct($search_text, $search_option){
    $this->table_azote = App::getTableAzote();
    $this->table_project = App::getTableProjects();
    $this->table_primer = App::getTablePrimers();
    $this->table_freezer = App::getTableFreezer();
    $this->table_investigator = App::getTableUsers();
    $this->table_past_investigator = App::getTablePastMembers();
    $this->table_vector = App::getTableVectors();
    $this->table_plasmide = App::getTablePlasmides();
    $this->table_prim_antibody = App::getTablePrimAntibody();
    $this->table_sec_antibody = App::getTableSecAntibody();
    $this->table_chemical = App::getTableChemicals();
    $this->table_protocol = App::getTableProtocols();
    $this->table_strain = App::getTableStrains();
    $this->table_commande_fournisseur = App::getTableFournisseur();
    $this->table_commande = App::getTableCommande();
    $this->table_spacvir_plasmide = App::getTableSpacvirPlasmides();

    $this->table_imet_souchier = App::getTableIMetSouchier();
    $this->table_imet_azote = App::getTableIMetAzote();
    $this->table_imet_azote_forme = App::getTableIMetAzoteForme();
    $this->table_imet_azote_souche = App::getTableIMetAzoteSouche();
    $this->table_imet_azote_log = App::getTableIMetAzoteLog();

    $this->table_reger_primer = App::getTableRegerPrimers();
    $this->table_reger_azote = App::getTableRegerAzote();
    $this->table_reger_chemical = App::getTableRegerChemicals();

    $this->query_prepared = 1;

    // option de recherche = all, one, sentence
    $this->option = $search_option;

    // recherche en ET
    if( $this->option  == 'all' )
    {
      $this->sep = ' AND ';
      $this->option = 1;
      $this->words = explode( ' ', addslashes( $search_text ) );
      $this->count_words = count( $this->words );
    }
    // recherche en OU
    else if( $this->option  == 'one' )
    {
      $this->sep = ' OR ';
      $this->option = 2;
      $this->words = explode( ' ', addslashes( $search_text ) );
      $this->count_words = count( $this->words );
    }
    // phrase exacte
    else
    {
      $this->option = 0;
      $this->words[0] = addslashes( $search_text );
    }
  }

  private function setTable($tab){
    $table = "table_".$tab;
    //var_dump($table);
    return $this->searchTable = $this->$table;
  }
  private function setFields($tab){
    $fields = $tab."_fields";
    //var_dump($fields);
    return $this->$fields;
  }

  public function getResult($table, $order, $way){
    $tab = self::setTable($table);
    $fields = self::setFields($table);
    //var_dump($tab);
    //var_dump($fields);
    $this->query_where = '';

    if( !is_array( $fields ) )
      $fields = array( $fields );

    $fields_count = count( $fields );
    //var_dump($fields_count);

    // si recherche en ET ou OU
    if( $this->option )
    {
      for( $i = 0; $i < $this->count_words; $i++ ) // boucle sur les mots
      {
        // si pas premi�re it�ration
        if( $i )
          $this->query_where .= $this->sep;

        $this->query_where .= '( ';

        for( $j = 0; $j < $fields_count; $j++ ) // boucle sur les champs
        {
          if( $j )
            $this->query_where .= ' OR ';

          $this->query_where .= ' ' . $tab.'.'.$fields[ $j ] . ' LIKE \'%' . $this->words[ $i ] . '%\'';


        }
        if(in_array('id_project', $fields)){
        //$this->query_where .= ' OR ' .$this->table_project.'.project LIKE \'%' . $this->words[ $i ] . '%\'';
        //pour trouver les projets avec le format 12,34,56
        $projArr = Database::query("SELECT * FROM $this->table_project WHERE project LIKE '%".$this->words[ $i ]."%'")->fetchAll();
        //var_dump($projArr);
        foreach($projArr as $proj){
          $this->query_where .= ' OR ' .$tab.'.id_project REGEXP  \'(^|,)'.$proj->id.'(,|$)\'';
          //$test = ' OR ' .$this->table_project.'.id REGEXP  \'(^|,)'.$proj->id.'(,|$)\'';
          //var_dump($test);
        }

      }

        if(in_array('investigator', $fields)){
          $this->query_where .= ' OR ' .$this->table_investigator.'.name LIKE \'%' . $this->words[ $i ] . '%\'';
          $this->query_where .= ' OR ' .$this->table_investigator.'.firstname LIKE \'%' . $this->words[ $i ] . '%\'';
          $this->query_where .= ' OR ' .$this->table_past_investigator.'.name LIKE \'%' . $this->words[ $i ] . '%\'';
          $this->query_where .= ' OR ' .$this->table_past_investigator.'.firstname LIKE \'%' . $this->words[ $i ] . '%\'';
        }

        if(in_array('strain', $fields)){
          $this->query_where .= ' OR ' .$this->table_imet_azote_souche.'.genre LIKE \'%' . $this->words[ $i ] . '%\'';
          $this->query_where .= ' OR ' .$this->table_imet_azote_souche.'.souche_texte LIKE \'%' . $this->words[ $i ] . '%\'';
        }
        if(in_array('forme', $fields)){
          $this->query_where .= ' OR ' .$this->table_imet_azote_forme.'.forme_text LIKE \'%' . $this->words[ $i ] . '%\'';
        }
// for( $j = 0; $j < $this->count_words; $j++ ) // boucle sur les champs
        $this->query_where .= ' )';
      } // for( $i = 0; $i < $count_champs; $i++ ) // boucle sur les mots
    }
    else // recherche phrase exacte
    {
      for( $i = 0; $i < $fields_count; $i++ ) // boucle sur les champs
      {
        if( $i )
          $this->query_where .= ' OR ';

        $this->query_where .=' ' . $tab.'.'.$fields[$i] . ' LIKE \'%' . $this->words[0] .
'%\' ';
      } // for( $i = 0; $j < $count_champs; $i++ ) // boucle sur les champs
    } // else // recherche phrase exacte

    // construction de la requ�te finale

    $sql  = 'SELECT ' . $tab . '.id FROM ' . $tab;

    if(in_array('id_project', $fields)){
      $sql .= ' LEFT JOIN '.$this->table_project.' ON '.$tab.'.'.$this->project_col.' = '.$this->table_project.'.id';
    }
    if(in_array('investigator', $fields)){
      $sql .= ' LEFT JOIN ' . $this->table_investigator . ' ON ' . $tab . '.investigator = ' .$this->table_investigator.'.id';
      $sql .= ' LEFT JOIN ' . $this->table_past_investigator . ' ON ' . $tab . '.investigator = ' .$this->table_past_investigator.'.id';
    }
    if(in_array('strain', $fields)){
      $sql .= ' LEFT JOIN ' . $this->table_imet_azote_souche . ' ON ' . $tab . '.strain = ' .$this->table_imet_azote_souche.'.id_souche';
    }
    if(in_array('forme', $fields)){
      $sql .= ' LEFT JOIN ' . $this->table_imet_azote_forme . ' ON ' . $tab . '.forme = ' .$this->table_imet_azote_forme.'.id_forme';
    }

    $sql .= ' WHERE ' . $this->query_where;
    if( !empty( $order ) )
      $end_sql = "";
      foreach($order as $col){
        $end_sql .= ', ' . $tab . '.' . $col . ' ' . $way;
      }
      $end_sql = ltrim($end_sql,",");
      $end_sql = ' ORDER BY '.$end_sql;

    $this->sql = $sql.$end_sql;
    // r�cuperation des r�sultats avec la requete g�n�r�e
    //var_dump($this->sql);
    return $this->data = Database::query($this->sql);

  }
  public function getData(){
    $res = $this->data->fetchAll();
    if($res){
    $tubes = "";
    foreach($res as $id){
      $tubes .= ','.$id->id;
    }
    $tubes = ltrim($tubes,',');

    return Database::query("SELECT * FROM $this->searchTable WHERE id IN($tubes)")->fetchAll();

  }
    return false;
  }

  public function getNumResult(){
    return $this->data->rowCount();
  }

}
