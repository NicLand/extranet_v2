<?php
namespace Extranet;

require '../class/Autoloader.php';
Autoloader::register();

if(isset($_GET['cat']) && isset($_GET['nl'])){
  $CAT = $_GET['cat'];
  $NL = $_GET['nl'];
}
else{
  App::redirect('newsletter/newsletter.php');
  exit();
}

  $NLtitle = $CAT.'Titre';
	$NLsubTitle = $CAT.'SousTitre';
	$NLtext = $CAT;
	$NLresume = $CAT.'Resume';

$news = new Newsletter;
$data = $news->getNewsletter($NL);
//var_dump($data);
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <title>My Favorite Page #<?=$NL;?></title>
    <style>
       /* Add custom classes and styles that you want inlined here */
    </style>
  </head>
  <body class="bg-dark bg-opacity-25">
    <div class="container w-75">
      <div id="carouselExampleDark" class="carousel carousel-dark slide m-2" data-bs-ride="carousel">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="3" aria-label="Slide 4"></button>
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="4" aria-label="Slide 5"></button>
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="5" aria-label="Slide 6"></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active" data-bs-interval="10000">
            <img src="img/nl22/ban_adeno.jpg" class="d-block w-100" alt="...">
          </div>
        <div class="carousel-item" data-bs-interval="2000">
          <img src="img/nl22/ban_imet.png.png" class="d-block w-100" alt="...">

        </div>
        <div class="carousel-item">
          <img src="img/nl22/ban_armyne.png" class="d-block w-100" alt="...">

        </div>
        <div class="carousel-item">
          <img src="img/nl22/ban_mobilvir.png" class="d-block w-100" alt="...">

        </div>
        <div class="carousel-item">
          <img src="img/nl22/ban_proparacyto.png" class="d-block w-100" alt="...">

        </div>
        <div class="carousel-item">
          <img src="img/nl22/ban_andevir.png" class="d-block w-100" alt="...">

        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
      </div>
      <div class="m-2 p-2 bg-white">
        <a href="newsletter.php?id=<?=$NL;?>"><< Retour à la Newsletter</a>
      </div>
      <div class="m-2 p-2 bg-white">
        <h1 class="display-1"><span class="text-danger">M</span>y <span class="text-danger">F</span>avorite <span class="text-danger">P</span>age <span class="text-danger">#<?=$NL;?></span><img class="float-end me-2" width="180px" src="test/logo_mfp.gif"/></h1>
      </div>
      <div class="row m-2 p-2 bg-white" name="edito">
        <?php
if($data->$NLtitle){
  echo "<h1>".$data->{$NLtitle}."</h1>";
}
if($data->{$NLtext}){
  echo "<p align='justify'>".$data->{$NLtext}."</p>";
}
?>
      </div>
    <div name="footer" class="m-2 p-2 bg-white">
      <div class="row text-center">
        <p>Cette lettre est publiée par le comité de rédaction de la Newsletter de l'UMR5234</p>
        <p>Pour toute question concernant cette lettre, écrivez à Christina Calmels.</p>
        <p>Responsable de la publication : Frédéric Bringaud</p>
        <p>Responsables de la rédaction : Christina Calmels et Patricia Pinson</p>
        <p>Comité de rédaction : Corinne Asencio, Marie-Lise Blondot, Floriane Lagadec, Paul Lesbats.</p>
        <p>Intégration / Design : Nicolas Landrein.</p>
      </div>
    </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

  </body>
</html>
