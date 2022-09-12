<?php

namespace Extranet;

class Footer{

  public static function getFooter(){

    if (!empty($title)){$titre = $title;}else{$titre = 'MFP Extranet';}
     return '
     </div>
     </div>
     <div class="pt-5" style="height : 150px;">
     <div class="h-100 w-100 p-3 mb-2 bg-light text-dark text-center">-- <a href="'.App::getMFP().'"> MFP lab</a> --</div>
     </div>
     <!-- Optional JavaScript -->
     <!-- jQuery first, then Popper.js, then Bootstrap JS -->
     <!-- Option 1: Bootstrap Bundle with Popper -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script type="text/javascript" src="'.App::getRoot().'/inc/js/extranet.js"></script>
     <script type="text/javascript" src="'.App::getRoot().'/inc/js/commande.js"></script>
     <script type="text/javascript" src="'.App::getRoot().'/inc/ckeditor/ckeditor.js"></script>
   </body>
 </html>';
  }

}
