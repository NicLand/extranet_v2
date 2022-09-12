<?php
namespace Extranet;

class Upload{

  private $file;
  private $errors = [];
  private $destination;
  private $folder;
  private $team;
  private $type;
  private $taille_maxi = 10000000;
  private $extensions = array('.png', '.gif', '.jpg', '.jpeg','.JPG','.JPEG','.pdf','.PDF','.docx','.DOCX','.doc','.DOC','.txt','.TXT', '.XDNA', '.xdna' ,'.DNA', '.dna');

  public function __construct($file, $team, $type, $folder){
    $this->file = $file;
    $this->team = $team;
    $this->type = $type;
    $this->folder = $folder;
  }
  private function getTaille(){
    return filesize($_FILES[$this->file]['tmp_name']);
  }

  private function getExtension(){
    return strrchr($_FILES[$this->file]['name'], '.');
  }

  private function getName(){
    return basename($_FILES[$this->file]['name']);
  }

  public function setName(){
    return Str::fileNameUpload(self::getName());
  }

  public function setDestination(){
    return $this->destination =  App::getPHPRoot().'/'.$this->team.'/'.$this->type.'/'.$this->folder.'/';
  }

  public function upload(){
    if(!in_array(self::getExtension(), $this->extensions)){
      $erreur = 'Vous devez uploader un fichier de type PDF, DOC, TXT...';
    }
    if(self::getTaille()>$this->taille_maxi){
      $erreur = 'Le fichier est trop gros...';
    }
    if(!isset($erreur)){
      if(move_uploaded_file($_FILES[$this->file]['tmp_name'], self::setDestination() . self::setName())){
      return self::setName();
			}
    }
		else{
			Session::getInstance()->setFlash('danger', $erreur);
      return false;
		}
  }

  public function delete($file){
    $del = self::setDestination().$file;
    unlink($del);
  }

}
