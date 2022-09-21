<?php
namespace Extranet;

require '../class/Autoloader.php';
Autoloader::register();

//$NL = new Newsletter;
//$nl = $NL->getNewsletter(19);
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <title>My Favorite Page #22</title>
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
        <h1 class="display-1">My Favorite Page <span class="text-danger">#22</span><img class="float-end me-2" width="180px" src="test/logo_mfp.gif"/></h1>
      </div>
      <div class="row m-2 p-2 bg-white" name="edito">
        <h3>Edito</h3>
        <div class="row">
        <div class="col">
        <p>Septembre est arrivé, c’est l’heure de la rentrée !</p>
        <p>En ce début d’année universitaire, le MFP accueille ses nouveaux et nouvelles arrivant(e)s.
          Dans le zoom, nous vous proposons pour exemple, de découvrir le parcours de l’une d’entre elles, Sonia Burrel, virologue qui rejoint l’équipe SpacVir.
</p>
          <p>Bonne lecture à tous !</p>
      </div>
      <div class="col-4">
      <img width="300px" src="img/nl22/Edito_NL22.jpg" alt="" />
    </div>
      <div class="float-end">
        <a class="link-dark" href="article.php?nl=22&cat=edito">Lire la suite...</a>
      </div>
    </div>
    </div>
      <div name="zoom" class="m-2 p-2 row bg-warning bg-opacity-50">
        <h3>ZOOM</h3>
        <div class="row">
          <div class="col-3">
          <img width="200px" src="img/nl22/Zoom_NL22.jpg" alt="" />
        </div>
        <div class="col">
        <p>Nous avons rencontré Sonia Burrel, virologue, qui vient de rejoindre notre unité au sein de l’équipe de Harald Wodrich et Marie-Edith Lafon.
Elle a accepté de répondre à nos questions.</p>
        </div>
        <div class="float-end">
        <a class="link-dark" href="article.php?nl=22&cat=zoom">Lire la suite...</a>
        </div>
        </div>
      </div>
      <div name="zoom" class="m-2 p-2 row bg-success bg-opacity-50 text-white">
        <h3>ZOOM</h3>
        <div class="row">
          <div class="col-3">
          <img width="200px" src="img/nl22/Zoom_NL22.jpg" alt="" />
        </div>
        <div class="col">
        <p>Nous avons rencontré Sonia Burrel, virologue, qui vient de rejoindre notre unité au sein de l’équipe de Harald Wodrich et Marie-Edith Lafon.
Elle a accepté de répondre à nos questions.</p>
        </div>
        <div class="float-end">
        <a class="link-light" href="article.php?nl=22&cat=zoom">Lire la suite...</a>
        </div>
        </div>
      </div>
      <div class="row m-2 p-2 bg-white" name="vie">
        <h3>Vie Scientifique</h3>
        <div class="row m-2">
        <div class="col mt-2">
        <p>Après l’obtention du niveau concepteur en 2006 et plusieurs années en expérimentation animale, Corinne Asencio (équipe iMET) a décidé d’intégrer le Comité d’Ethique en Expérimentation Animale (C2EA) de Bordeaux en 2011. Elle y exerce le rôle d’experte.</p>
        </div>
        <div class="col-4">
        <img width="300px" src="img/nl22/Vie_labo_NL22.jpg" alt="" />
      </div>
        <div class="float-end">
        <a class="link-dark" href="article.php?nl=22&cat=vieLabo">Lire la suite...</a>
        </div>
        </div>
      </div>
      <div class="row m-2 bg-danger">
      <div name="photo" class="col-6 bg-dark text-center">
        <h3 class="text-white text-center">Photo du jour</h3>
        <img class="p-2" width="100%" src="img/nl22/Photo_bis_NL22.png" alt=""/>
        <div class="float-start">
          <a class="link-light" href="article.php?nl=22&cat=photo">Lire la suite...</a>
        </div>
      </div>
      <div class="col-6 bg-danger align-self-center">
      <div name="chiffre" class="align-self-center">
        <h1 class="display-1 text-white text-center">12</h1>
      </div>
      <div class="float-start">
        <a class="link-dark" href="article.php?nl=22&cat=chiffre">Lire la suite...</a>
      </div>
    </div>
    </div>
      <div class="row m-2 p-2 bg-white" name="carteBlanche">
        <h3>Carte Blanche</h3>
        <div class="col m-2">
        <img width="200px" src="img/nl22/Denis_NL22.jpg" alt="" class="rounded float-start"/>
        <img width="200px" src="img/nl22/Harry_NL22.jpg" alt="" class="rounded float-end"/>
        </div>
        <div class="col">
          <p>Les publications ne sont pas les seuls moyens de valider un projet scientifique. En effet le brevet, et/ou la licence permettent de valoriser des résultats novateurs. Au sein de notre UMR, plusieurs projets ont déjà donné lieu à l’obtention de brevets et/ou licences. Suite à un projet collaboratif entre H. Wodrich (équipe SpacVir) et D. Dacheux (équipe ProParaCyto), une licence auprès de la société EMD Millipore a été obtenue. De la pipette à la validation du projet, ils nous en disent un peu plus sur les différentes étapes ayant mené à la validation de cette licence.</p>
        </div>
        <div class="float-end">
          <a class="link-dark" href="article.php?nl=22&cat=tribune">Lire la suite...</a>
        </div>
      </div>
      <div class="row m-2">
      <div name="breves" class="col p-3 bg-info bg-opacity-50">
        <span class="h3 align-bottom">Brèves de paillasse...  </span>
      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-chat-text" viewBox="0 0 16 16">
  <path d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z"/>
  <path d="M4 5.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8zm0 2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5z"/>
</svg>
        <div class="m-2 p-2">
        <ul>
          <li>Arrivées, départs, distinctions </li>
          <li>Recrutement</li>
          <li>Valorisation scientifique</li>
          <li>Nouvelle maquette de la NL</li>
          <li>Publications, DD, Recette...</li>
        </ul>
      </div>
      <div class="float-start">
        <a class="link-dark" href="article.php?nl=22&cat=breve">Lire la suite...</a>
      </div>
      </div>
      <div class="col p-3 bg-dark bg-opacity-50 text-white" name="agenda">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
          <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
          <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
        </svg>
        <span class="h3 align-bottom"> Agenda</span>
        <div class="m-2 p-2">
          <ul>
            <li>Conférences SBM</li>
            <li>CAP SCIENCE</li>
            <li>CONGRES</li>
            <li>Philosophy and Biology seminar in Bordeaux</li>
            <li>FORMATIONS</li>
            <li>ET A NE PAS OUBLIER...</li>
          </ul>
        </div>
        <div class="float-start">
          <a class="link-light" href="article.php?nl=22&cat=agenda">Lire la suite...</a>
        </div>
      </div>
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
