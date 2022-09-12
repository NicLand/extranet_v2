<?php

namespace Extranet;
use \SplFileInfo;

class App{

  const PHP_ROOT = __DIR__;
/*
  const HTTP_ROOT = "https://www.mfp.cnrs.fr/extranet_v2";
  const HTTP_MFP = "https://www.mfp.cnrs.fr/wp/";
  const DB_NAME = 'lab0123sql1db';
  const DB_USER = 'lab0123sql1';
  const DB_PASS = 'pF44hnVT90Ve';
  const DB_HOST = 'mysql2.lamp.ods';
  const DB_NAME_WP = 'lab0123sql0db';
  const DB_USER_WP = 'lab0123sql0';
  const DB_PASS_WP = 'EYoXfmSf9122';
*/
  const HTTP_ROOT = "http://localhost:8888/extranet_v2";
  const HTTP_MFP = "http://localhost:8888/";
  const DB_NAME = 'mfp_db';
  const DB_USER = 'root';
  const DB_PASS = 'root';
  const DB_HOST = 'localhost';

  //=========== TABLE GENERALE ====================================
  const TABLE_USERS = "mfp_extranet_users";
  const TABLE_TEAMS = "mfp_extranet_teams";
  const TABLE_MISSIONS = "mfp_extranet_missions";
  const TABLE_BUTTON_INDEX = "extranet_home_items";
  const TABLE_PUBLICATIONS = "wp_publication";

  //===============================================================
  const TABLE_COMMANDE = "commande_list";
  const TABLE_FOURNISSEUR = "commande_fournisseur";
  const TABLE_NOMENCLATURE = "commande_nomenclature";
  const TABLE_COMMUN_SPACVIR = "commande_spacvir";
  const TABLE_COMMUN_REGER = "commande_reger";
  const TABLE_COMMUN_PARAMYC = "commande_paramyc";
  //===============================================================
  const TABLE_NEWSLETTER = "mfp_newsletters";

  //===============================================================
  //=========== TABLE PRoparacyto =================================
  const TABLE_AZOTE =             "extranet_proparacyto_azote";
  const TABLE_PAST_MEMBERS =      "extranet_proparacyto_past_members";
  const TABLE_FREEZER =           "extranet_proparacyto_freezer";
  const TABLE_PLASMIDES =         "extranet_proparacyto_plasmides";
  const TABLE_PROJECTS =          "extranet_proparacyto_projects";
  const TABLE_PROJECT_TYPES =     "extranet_proparacyto_project_types";
  const TABLE_PRIMERS =           "extranet_proparacyto_primers";
  const TABLE_CLONING_VECTORS =   "extranet_proparacyto_cloning_vectors";
  const TABLE_PROPARACYTO_ITEMS = "extranet_proparacyto_home_items";
  const TABLE_PRIM_ANTIBODIES =   "extranet_proparacyto_prim_antibodies";
  const TABLE_SEC_ANTIBODIES =    "extranet_proparacyto_sec_antibodies";
  const TABLE_CHEMICALS =         "extranet_proparacyto_chemicals";
  const TABLE_PROTOCOLS =         "extranet_proparacyto_protocoles";
  const TABLE_PROTOCOL_CATEGORIES = "extranet_proparacyto_protocol_categories";
  const TABLE_STRAINS =           "extranet_proparacyto_strains";
  const TABLE_Y2H_PROT =          "extranet_proparacyto_y2h_prot";
  const TABLE_Y2H_AD =            "extranet_proparacyto_y2h_ad";
  const TABLE_Y2H_BD =            "extranet_proparacyto_y2h_bd";
  const TABLE_Y2H_INTERACTION =   "extranet_proparacyto_y2h_inter";


//=========== TABLE SpacVir =================================
  const TABLE_SPACVIR_ITEMS =                 "extranet_spacvir_home_items";
  const TABLE_SPACVIR_PROTOCOLS =             "extranet_spacvir_protocoles";
  const TABLE_SPACVIR_PROTOCOL_CATEGORIES =   "extranet_spacvir_protocol_categories";
  const TABLE_SPACVIR_VIRUS =                 "extranet_spacvir_virus";
  const TABLE_SPACVIR_FREEZER =               "extranet_spacvir_freezer";
  const TABLE_SPACVIR_PLASMIDES =             "extranet_spacvir_plasmides";

//=========== TABLE iMet =================================
  const TABLE_IMET_ITEMS =                    "extranet_imet_home_items";
  const TABLE_IMET_SOUCHIER =                 "extranet_imet_souchier";
  const TABLE_IMET_SOUCHIER_ANTIBIO =         "extranet_imet_souchier_antibio";
  const TABLE_IMET_SOUCHIER_SOUCHE =          "extranet_imet_souchier_souche";
  const TABLE_IMET_AZOTE =                    "extranet_imet_azote";
  const TABLE_IMET_AZOTE_FORME =              "extranet_imet_azote_forme";
  const TABLE_IMET_AZOTE_SOUCHE =             "extranet_imet_azote_souche";
  const TABLE_IMET_AZOTE_LOG =                "extranet_imet_azote_log";

  //=========== TABLE REGER =================================
  const TABLE_REGER_ITEMS =                   "extranet_reger_home_items";
  const TABLE_REGER_CHEMICALS =               "extranet_reger_chemicals";
  const TABLE_REGER_PRIMERS =                 "extranet_reger_primers";
  const TABLE_REGER_AZOTE =                   "extranet_reger_azote";
  const TABLE_REGER_AZOTE_CELL =              "extranet_reger_azote_cell";
  const TABLE_REGER_PROTOCOL =                "extranet_reger_protocoles";
  const TABLE_REGER_PROTOCOL_CATEGORY =       "extranet_reger_protocol_categories";

  static $db = null;

  static function getAuth(){
    return new Auth(Session::getInstance(), ['restriction_msg' => "Vous n'�tes pas autoris� � aller sur cette page."]);
  }
  static function getDatabase(){
      if(!self::$db){
        self::$db =  new Database();
      }
        return self::$db;
    }
  static function redirect($link){
      $link = App::getRoot()."/".$link;
      //var_dump($link);
      header ("Location:$link");
    }

  static function getRoot(){
    return self::HTTP_ROOT;
    }

  static function getMFP(){
    return self::HTTP_MFP;
  }

  static function getPHPRoot(){
    $root = new SplFileInfo(self::PHP_ROOT);
    return $root->getPath();
  }
  static function debug($var){
    $vardump = var_dump($var, true);
    $affiche = '<pre>'.$vardump.'</pre>';
    return $affiche;
  }
  static function getDbName(){
    return self::DB_NAME;
  }
  static function getDbNameWP(){
    return self::DB_NAME_WP;
  }
  static function getDbHost(){
    return self::DB_HOST;
  }
  static function getDbUser(){
    return self::DB_USER;
  }
  static function getDbUserWP(){
    return self::DB_USER_WP;
  }
  static function getDbPass(){
    return self::DB_PASS;
  }
  static function getDbPassWP(){
    return self::DB_PASS_WP;
  }
  static function getTableAzote(){
    return self::TABLE_AZOTE;
  }
  static function getTablePastMembers(){
    return self::TABLE_PAST_MEMBERS;
  }
  static function getTableFreezer(){
    return self::TABLE_FREEZER;
  }
  static function getTablePlasmides(){
    return self::TABLE_PLASMIDES;
  }
  static function getTableProjects(){
    return self::TABLE_PROJECTS;
  }
  static function getTableProjectTypes(){
    return self::TABLE_PROJECT_TYPES;
  }
  static function getTablePrimers(){
    return self::TABLE_PRIMERS;
  }
  static function getTableVectors(){
    return self::TABLE_CLONING_VECTORS;
  }
  static function getTableUsers(){
    return self::TABLE_USERS;
  }
  static function getTableTeams(){
    return self::TABLE_TEAMS;
  }
  static function getTableMissions(){
    return self::TABLE_MISSIONS;
  }
  static function getTableProparacytoItems(){
    return self::TABLE_PROPARACYTO_ITEMS;
  }
  static function getTablePrimAntibody(){
    return self::TABLE_PRIM_ANTIBODIES;
  }
  static function getTableSecAntibody(){
    return self::TABLE_SEC_ANTIBODIES;
  }
  static function getTableChemicals(){
    return self::TABLE_CHEMICALS;
  }
  static function getTableProtocols(){
    return self::TABLE_PROTOCOLS;
  }
  static function getTableProtocolCategories(){
    return self::TABLE_PROTOCOL_CATEGORIES;
  }
  static function getTablesForSearch(){
    $table =[
      'Azote'=>self::TABLE_AZOTE,
      'Freezer'=>self::TABLE_FREEZER,
      'Primer'=>self::TABLE_PRIMERS,
      'Cloning vector'=>self::TABLE_CLONING_VECTORS,
      'Plasmides'=>self::TABLE_PLASMIDES,
      'Project'=>self::TABLE_PROJECTS
    ];
    return $table;
  }
  static function getTableStrains(){
    return self::TABLE_STRAINS;
  }
  static function getTableY2HAD(){
    return self::TABLE_Y2H_AD;
  }
  static function getTableY2HBD(){
    return self::TABLE_Y2H_BD;
  }
  static function getTableY2Hprot(){
    return self::TABLE_Y2H_PROT;
  }
  static function getTableY2hInteractions(){
    return self::TABLE_Y2H_INTERACTION;
  }
  static function getTableCommande(){
    return self::TABLE_COMMANDE;
  }
  static function getTableFournisseur(){
    return self::TABLE_FOURNISSEUR;
  }
  static function getTableNomenclature(){
    return self::TABLE_NOMENCLATURE;
  }
  static function getTableCommunSpacvir(){
    return self::TABLE_COMMUN_SPACVIR;
  }
  static function getTableCommunReger(){
    return self::TABLE_COMMUN_REGER;
  }
  static function getTableCommunParamyc(){
    return self::TABLE_COMMUN_PARAMYC;
  }
  static function getTableButtonIndex(){
    return self::TABLE_BUTTON_INDEX;
  }
  static function getTableSpacvirItems(){
    return self::TABLE_SPACVIR_ITEMS;
  }
  static function getTableSpacvirProtocols(){
    return self::TABLE_SPACVIR_PROTOCOLS;
  }
  static function getTableSpacvirProtocolCategories(){
    return self::TABLE_SPACVIR_PROTOCOL_CATEGORIES;
  }
  static function getTableSpacvirVirus(){
    return self::TABLE_SPACVIR_VIRUS;
  }
  static function getTableSpacvirFreezer(){
    return self::TABLE_SPACVIR_FREEZER;
  }
  static function getTableNewsletter(){
    return self::TABLE_NEWSLETTER;
  }
  static function getTableSpacvirPlasmides(){
    return self::TABLE_SPACVIR_PLASMIDES;
  }
  static function getTableIMetItems(){
    return self::TABLE_IMET_ITEMS;
  }
  static function getTableIMetSouchier(){
    return self::TABLE_IMET_SOUCHIER;
  }
  static function getTableIMetSouchierAntibio(){
    return self::TABLE_IMET_SOUCHIER_ANTIBIO;
  }
  static function getTableIMetSouchierSouche(){
    return self::TABLE_IMET_SOUCHIER_SOUCHE;
  }
  static function getTableIMetAzote(){
    return self::TABLE_IMET_AZOTE;
  }
  static function getTableIMetAzoteForme(){
    return self::TABLE_IMET_AZOTE_FORME;
  }
  static function getTableIMetAzoteSouche(){
    return self::TABLE_IMET_AZOTE_SOUCHE;
  }
  static function getTableIMetAzoteLog(){
    return self::TABLE_IMET_AZOTE_LOG;
  }
  static function getTableRegerHomeItems(){
    return self::TABLE_REGER_ITEMS;
  }
  static function getTableRegerAzote(){
    return self::TABLE_REGER_AZOTE;
  }
  static function getTableRegerAzoteCell(){
    return self::TABLE_REGER_AZOTE_CELL;
  }
  static function getTableRegerChemicals(){
    return self::TABLE_REGER_CHEMICALS;
  }
  static function getTableRegerPrimers(){
    return self::TABLE_REGER_PRIMERS;
  }
  static function getTableRegerProtocols(){
    return self::TABLE_REGER_PROTOCOL;
  }
  static function getTableRegerProtocolCategories(){
    return self::TABLE_REGER_PROTOCOL_CATEGORY;
  }
  static function getTablePublications(){
    return self::TABLE_PUBLICATIONS;
  }

}
