<?php
require('../include/fpdf/fpdf.php');

use \FPDF;

class Pdf extends FPDF{

  public $text = "Hello Guys";

  public function sayHello(){
    return $text;
  }
  public function Header()
  {
      global $title;

      // Arial bold 15
      $this->SetFont('Arial','B',15);
      // Calculate width of title and position
      $w = $this->GetStringWidth($title)+6;
      $this->SetX((210-$w)/2);
      // Colors of frame, background and text
      //$this->SetDrawColor(0,80,180);
      //$this->SetFillColor(230,230,0);
      //$this->SetTextColor(220,50,50);
      // Thickness of frame (1 mm)
      $this->SetLineWidth(1);
      // Title
      $this->Cell($w,9,$title,'C',true);
      // Line break
      $this->Ln(10);
  }
  function Footer()
  {
  	// Position at 1.5 cm from bottom
      $this->SetY(-15);
      // Arial italic 8
      $this->SetFont('Arial','I',8);
      // Text color in gray
      $this->SetTextColor(128);
      // Page number
      $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
  }
  function SubTitle($subtitle)
  {
  	$this->SetFont('Arial','BU',12);
  	$this->Cell(60,10,$subtitle);
  	$this->SetX(100);
  	$this->Ln(10);
  }
  function Rubrique($rubrique)
  {
  	$this->SetFont('Arial','B',10);
  	$this->Cell(60,10,$rubrique);
  }
  function Resultats($resultat)
  {
  	$w = $this->GetStringWidth($resultat)+1;
  	$this->SetFont('Arial','',10);
  	$this->Cell($w,10,$resultat);
  }
  function LongResultat($resultat)
  {
  	$this->SetFont('Arial','',10);
  	// Output justified text
      $this->MultiCell(0,5,$resultat);
      // Line break
      $this->Ln();
  }
}
