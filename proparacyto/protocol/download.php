<?php

if(isset($_GET['id']) AND $_GET['id']!=""){
  $id = $_GET['id'];
  var_dump($id);
}
//const DB_NAME = 'lab0123sql1db';
//const DB_USER = 'lab0123sql1';
//const DB_PASS = 'pF44hnVT90Ve';
//const DB_HOST = 'mysql2.lamp.ods';
$host ="mysql2.lamp.ods";
$dbname = "lab0123sql1db";
$dbuser = "lab0123sql1";
$dbpass = "pF44hnVT90Ve";
try
{
	$bdd = new PDO('mysql:host='.$host.';dbname='.$dbname.';', $dbuser, $dbpass);
  $bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $bdd -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}

$req = $bdd->query("SELECT * FROM extranet_proparacyto_protocols WHERE id = $id")->fetch();

require('../../inc/fpdf182/fpdf.php');

class PDF extends FPDF
{
function Header()
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

$pdf = new PDF();
$title = "test";
$pdf->SetTitle($title,true);
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

$pdf->SubTitle('General Information :');
$pdf->Rubrique('Made in (Ig Class):');
$pdf->Resultats();



$pdf->SubTitle('Comments :');
$pdf->LongResultat();



$pdf->Output();
