<?php
namespace Extranet;

require '../class/Autoloader.php';
Autoloader::register();

$NL = $_GET['nl'];
$news = new Newsletter;
$donnees = $news->getNewsletter($NL);

$lireLasuite = 'http://www.mfp.cnrs.fr/extranet_v2/newsletter/article.php?nl='.$NL.'&cat=';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <!-- Compiled with Bootstrap Email version: 1.3.1 --><meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style type="text/css">
      body,table,td{font-family:Helvetica,Arial,sans-serif !important}.ExternalClass{width:100%}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div{line-height:150%}a{text-decoration:none}*{color:inherit}a[x-apple-data-detectors],u+#body a,#MessageViewBody a{color:inherit;text-decoration:none;font-size:inherit;font-family:inherit;font-weight:inherit;line-height:inherit}img{-ms-interpolation-mode:bicubic}table:not([class^=s-]){font-family:Helvetica,Arial,sans-serif;mso-table-lspace:0pt;mso-table-rspace:0pt;border-spacing:0px;border-collapse:collapse}table:not([class^=s-]) td{border-spacing:0px;border-collapse:collapse}@media screen and (max-width: 600px){.w-full,.w-full>tbody>tr>td{width:100% !important}.p-1:not(table),.p-1:not(.btn)>tbody>tr>td,.p-1.btn td a{padding:4px !important}.p-2:not(table),.p-2:not(.btn)>tbody>tr>td,.p-2.btn td a{padding:8px !important}*[class*=s-lg-]>tbody>tr>td{font-size:0 !important;line-height:0 !important;height:0 !important}.s-1>tbody>tr>td{font-size:4px !important;line-height:4px !important;height:4px !important}}
    </style>
  </head>
  <body class="bg-gray-200" style="outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; font-family: Helvetica, Arial, sans-serif; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; margin: 0; padding: 0; border-width: 0;" bgcolor="#edf2f7">
    <table class="bg-gray-200 body" valign="top" role="presentation" border="0" cellpadding="0" cellspacing="0" style="outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; font-family: Helvetica, Arial, sans-serif; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; margin: 0; padding: 0; border-width: 0;" bgcolor="#edf2f7">
      <tbody>
        <tr>
          <td valign="top" style="line-height: 24px; font-size: 16px; margin: 0;" align="left" bgcolor="#edf2f7">
            <table class="container" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
              <tbody>
                <tr>
                  <td align="center" style="line-height: 24px; font-size: 16px; margin: 0; padding: 0 16px;">
                    <!--[if (gte mso 9)|(IE)]>
                      <table align="center" role="presentation">
                        <tbody>
                          <tr>
                            <td width="600">
                    <![endif]-->
                    <table align="center" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 600px; margin: 0 auto;">
                      <tbody>
                        <tr>
                          <td style="line-height: 24px; font-size: 16px; margin: 0;" align="left">
                            <table class="s-1 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                              <tbody>
                                <tr>
                                  <td style="line-height: 4px; font-size: 4px; width: 100%; height: 4px; margin: 0;" align="left" width="100%" height="4">
                                    &#160;
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="p-1" role="presentation" border="0" cellpadding="0" cellspacing="0">
                              <tbody>
                                <tr>
                                  <td style="line-height: 24px; font-size: 16px; margin: 0; padding: 4px;" align="left">
                                    <div class="row" style="margin-right: -24px;">
                                      <table class="" role="presentation" border="0" cellpadding="0" cellspacing="0" style="table-layout: fixed; width: 100%;" width="100%">
                                        <tbody>
                                          <tr>
                                            <td class="col bg-white" style="line-height: 24px; font-size: 16px; min-height: 1px; font-weight: normal; padding-right: 24px; margin: 0;" align="left" bgcolor="#ffffff" valign="top">
                                              <h1 class="text-6xl" style="padding-top: 0; padding-bottom: 0; font-weight: 500; vertical-align: baseline; font-size: 54px; line-height: 76.8px; margin: 0;" align="left">
                                                <span class="text-danger" style="color: #dc3545;">M</span>y <span class="text-danger" style="color: #dc3545;">F</span>avorite <span class="text-danger" style="color: #dc3545;">P</span>age <span class="text-danger" style="color: #dc3545;">#<?php echo $NL;?></span>
                                              </h1>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="s-1 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                              <tbody>
                                <tr>
                                  <td style="line-height: 4px; font-size: 4px; width: 100%; height: 4px; margin: 0;" align="left" width="100%" height="4">
                                    &#160;
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="s-1 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                              <tbody>
                                <tr>
                                  <td style="line-height: 4px; font-size: 4px; width: 100%; height: 4px; margin: 0;" align="left" width="100%" height="4">
                                    &#160;
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="p-1" role="presentation" border="0" cellpadding="0" cellspacing="0">
                              <tbody>
                                <tr>
                                  <td style="line-height: 24px; font-size: 16px; margin: 0; padding: 4px;" align="left">
                                    <div class="row" style="margin-right: -24px;">
                                      <table class="" role="presentation" border="0" cellpadding="0" cellspacing="0" style="table-layout: fixed; width: 100%;" width="100%">
                                        <tbody>
                                          <tr>
                                            <td class="col p-2 bg-white" style="line-height: 24px; font-size: 16px; min-height: 1px; font-weight: normal; margin: 0; padding: 8px 24px 8px 8px;" align="left" bgcolor="#ffffff" valign="top">
                                              <h3 style="padding-top: 0; padding-bottom: 0; font-weight: 500; vertical-align: baseline; font-size: 28px; line-height: 33.6px; margin: 0;" align="left"><?= $donnees->editoTitre;?></h3>
                                              <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left"><?= $donnees->editoResume;?></p>
                                              <table class="ax-right" role="presentation" align="right" border="0" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                  <tr>
                                                    <td style="line-height: 24px; font-size: 16px; margin: 0;" align="left">
                                                      <div class="">
                                                        <a class="text-dark" href="<?= $lireLasuite;?>edito" style="color: #1a202c;">Lire la suite...</a>
                                                      </div>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="s-1 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                              <tbody>
                                <tr>
                                  <td style="line-height: 4px; font-size: 4px; width: 100%; height: 4px; margin: 0;" align="left" width="100%" height="4">
                                    &#160;
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="s-1 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                              <tbody>
                                <tr>
                                  <td style="line-height: 4px; font-size: 4px; width: 100%; height: 4px; margin: 0;" align="left" width="100%" height="4">
                                    &#160;
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="p-1" role="presentation" border="0" cellpadding="0" cellspacing="0">
                              <tbody>
                                <tr>
                                  <td style="line-height: 24px; font-size: 16px; margin: 0; padding: 4px;" align="left">
                                    <div class="row" style="margin-right: -24px;">
                                      <table class="" role="presentation" border="0" cellpadding="0" cellspacing="0" style="table-layout: fixed; width: 100%;" width="100%">
                                        <tbody>
                                          <tr>
                                            <td class="col p-2 bg-yellow-200" style="line-height: 24px; font-size: 16px; min-height: 1px; font-weight: normal; margin: 0; padding: 8px 24px 8px 8px;" align="left" bgcolor="#ffe69c" valign="top">
                                              <h3 style="padding-top: 0; padding-bottom: 0; font-weight: 500; vertical-align: baseline; font-size: 28px; line-height: 33.6px; margin: 0;" align="left"><?= $donnees->zoomTitre;?></h3>
                                              <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left"><?= $donnees->zoomResume;?></p>
                                              <table class="ax-right" role="presentation" align="right" border="0" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                  <tr>
                                                    <td style="line-height: 24px; font-size: 16px; margin: 0;" align="left">
                                                      <div class="">
                                                        <a class="text-dark" href="<?= $lireLasuite;?>zoom" style="color: #1a202c;">Lire la suite...</a>
                                                      </div>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="s-1 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                              <tbody>
                                <tr>
                                  <td style="line-height: 4px; font-size: 4px; width: 100%; height: 4px; margin: 0;" align="left" width="100%" height="4">
                                    &#160;
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="s-1 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                              <tbody>
                                <tr>
                                  <td style="line-height: 4px; font-size: 4px; width: 100%; height: 4px; margin: 0;" align="left" width="100%" height="4">
                                    &#160;
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="p-1" role="presentation" border="0" cellpadding="0" cellspacing="0">
                              <tbody>
                                <tr>
                                  <td style="line-height: 24px; font-size: 16px; margin: 0; padding: 4px;" align="left">
                                    <div class="row" style="margin-right: -24px;">
                                      <table class="" role="presentation" border="0" cellpadding="0" cellspacing="0" style="table-layout: fixed; width: 100%;" width="100%">
                                        <tbody>
                                          <tr>
                                            <td class="col p-2 bg-white" style="line-height: 24px; font-size: 16px; min-height: 1px; font-weight: normal; margin: 0; padding: 8px 24px 8px 8px;" align="left" bgcolor="#ffffff" valign="top">
                                              <h3 style="padding-top: 0; padding-bottom: 0; font-weight: 500; vertical-align: baseline; font-size: 28px; line-height: 33.6px; margin: 0;" align="left"><?= $donnees->vieLaboTitre;?></h3>
                                              <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left"><?= $donnees->vieLaboResume;?></p>
                                              <table class="ax-right" role="presentation" align="right" border="0" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                  <tr>
                                                    <td style="line-height: 24px; font-size: 16px; margin: 0;" align="left">
                                                      <div class="">
                                                        <a class="text-dark" href="<?= $lireLasuite;?>vieLabo" style="color: #1a202c;">Lire la suite...</a>
                                                      </div>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="s-1 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                              <tbody>
                                <tr>
                                  <td style="line-height: 4px; font-size: 4px; width: 100%; height: 4px; margin: 0;" align="left" width="100%" height="4">
                                    &#160;
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="s-1 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                              <tbody>
                                <tr>
                                  <td style="line-height: 4px; font-size: 4px; width: 100%; height: 4px; margin: 0;" align="left" width="100%" height="4">
                                    &#160;
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="p-1" role="presentation" border="0" cellpadding="0" cellspacing="0">
                              <tbody>
                                <tr>
                                  <td style="line-height: 24px; font-size: 16px; margin: 0; padding: 4px;" align="left">
                                    <div class="row" style="margin-right: -24px;">
                                      <table class="" role="presentation" border="0" cellpadding="0" cellspacing="0" style="table-layout: fixed; width: 100%;" width="100%">
                                        <tbody>
                                          <tr>
                                            <td class="col-6 p-2 bg-dark text-white text-center" style="line-height: 24px; font-size: 16px; min-height: 1px; font-weight: normal; width: 50%; color: #ffffff; margin: 0; padding: 8px 24px 8px 8px;" align="center" bgcolor="#1a202c" valign="top">
                                              <h3 class="text-center" style="padding-top: 0; padding-bottom: 0; font-weight: 500; vertical-align: baseline; font-size: 28px; line-height: 33.6px; margin: 0;" align="center"><?= $donnees->photoTitre;?></h3>
                                              <table class="ax-center" role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                                                <tbody>
                                                  <tr>
                                                    <td style="line-height: 24px; font-size: 16px; margin: 0;" align="left">
                                                      <?= $donnees->photoResume;?>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                              <table class="ax-left" role="presentation" align="left" border="0" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                  <tr>
                                                    <td style="line-height: 24px; font-size: 16px; margin: 0;" align="left">
                                                      <div class="">
                                                        <a class="text-light" href="<?= $lireLasuite;?>photo" style="color: #f7fafc;">Lire la suite...</a>
                                                      </div>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                            </td>
                                            <td class="col-6 p-2 bg-danger text-white" style="line-height: 24px; font-size: 16px; min-height: 1px; font-weight: normal; width: 50%; color: #ffffff; margin: 0; padding: 8px 24px 8px 8px;" align="left" bgcolor="#dc3545" valign="top">
                                              <table class="ax-center" role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                                                <tbody>
                                                  <tr>
                                                    <td style="line-height: 24px; font-size: 16px; margin: 0;" align="left">
                                                      <div class="ay-middle" style="vertical-align: middle !important;">
                                                        <h1 class="text-6xl text-center" style="padding-top: 0; padding-bottom: 0; font-weight: 500; vertical-align: baseline; font-size: 64px; line-height: 76.8px; margin: 0;" align="center"><?=$donnees->chiffreTitre;?></h1>
                                                      </div>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                              <table class="ax-left" role="presentation" align="left" border="0" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                  <tr>
                                                    <td style="line-height: 24px; font-size: 16px; margin: 0;" align="left">
                                                      <div class="">
                                                        <a class="text-light" href="<?= $lireLasuite;?>chiffre" style="color: #f7fafc;">Lire la suite...</a>
                                                      </div>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="s-1 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                              <tbody>
                                <tr>
                                  <td style="line-height: 4px; font-size: 4px; width: 100%; height: 4px; margin: 0;" align="left" width="100%" height="4">
                                    &#160;
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="s-1 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                              <tbody>
                                <tr>
                                  <td style="line-height: 4px; font-size: 4px; width: 100%; height: 4px; margin: 0;" align="left" width="100%" height="4">
                                    &#160;
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="p-1" role="presentation" border="0" cellpadding="0" cellspacing="0">
                              <tbody>
                                <tr>
                                  <td style="line-height: 24px; font-size: 16px; margin: 0; padding: 4px;" align="left">
                                    <div class="row" style="margin-right: -24px;">
                                      <table class="" role="presentation" border="0" cellpadding="0" cellspacing="0" style="table-layout: fixed; width: 100%;" width="100%">
                                        <tbody>
                                          <tr>
                                            <td class="col p-2 bg-white" style="line-height: 24px; font-size: 16px; min-height: 1px; font-weight: normal; margin: 0; padding: 8px 24px 8px 8px;" align="left" bgcolor="#ffffff" valign="top">
                                              <h3 style="padding-top: 0; padding-bottom: 0; font-weight: 500; vertical-align: baseline; font-size: 28px; line-height: 33.6px; margin: 0;" align="left"><?= $donnees->tribuneTitre;?></h3>
                                              <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left"><?=$donnees->tribuneResume;?></p>
                                              <div class="ax-rigth">
                                                <a class="text-dark" href="<?= $lireLasuite;?>tribune" style="color: #1a202c;">Lire la suite...</a>
                                              </div>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="s-1 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                              <tbody>
                                <tr>
                                  <td style="line-height: 4px; font-size: 4px; width: 100%; height: 4px; margin: 0;" align="left" width="100%" height="4">
                                    &#160;
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="s-1 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                              <tbody>
                                <tr>
                                  <td style="line-height: 4px; font-size: 4px; width: 100%; height: 4px; margin: 0;" align="left" width="100%" height="4">
                                    &#160;
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="p-1" role="presentation" border="0" cellpadding="0" cellspacing="0">
                              <tbody>
                                <tr>
                                  <td style="line-height: 24px; font-size: 16px; margin: 0; padding: 4px;" align="left">
                                    <div class="row" style="margin-right: -24px;">
                                      <table class="" role="presentation" border="0" cellpadding="0" cellspacing="0" style="table-layout: fixed; width: 100%;" width="100%">
                                        <tbody>
                                          <tr>
                                            <td class="col-6 p-2 bg-info text-white" style="line-height: 24px; font-size: 16px; min-height: 1px; font-weight: normal; width: 50%; color: #ffffff; margin: 0; padding: 8px 24px 8px 8px;" align="left" bgcolor="#0dcaf0" valign="top">
                                              <h3 style="padding-top: 0; padding-bottom: 0; font-weight: 500; vertical-align: baseline; font-size: 28px; line-height: 33.6px; margin: 0;" align="left">Br&#232;ves de paillasse</h3>
                                              <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left"><?= $donnees->breveResume;?></p>
                                              <table class="ax-left" role="presentation" align="left" border="0" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                  <tr>
                                                    <td style="line-height: 24px; font-size: 16px; margin: 0;" align="left">
                                                      <div class="">
                                                        <a class="text-light" href="<?= $lireLasuite;?>breve" style="color: #f7fafc;">Lire la suite...</a>
                                                      </div>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                            </td>
                                            <td class="col-6 p-2 bg-secondary text-white" style="line-height: 24px; font-size: 16px; min-height: 1px; font-weight: normal; width: 50%; color: #ffffff; margin: 0; padding: 8px 24px 8px 8px;" align="left" bgcolor="#718096" valign="top">
                                              <h3 style="padding-top: 0; padding-bottom: 0; font-weight: 500; vertical-align: baseline; font-size: 28px; line-height: 33.6px; margin: 0;" align="left">Agenda</h3>
                                              <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left"><?= $donnees->agendaResume;?></p>
                                              <table class="ax-left" role="presentation" align="left" border="0" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                  <tr>
                                                    <td style="line-height: 24px; font-size: 16px; margin: 0;" align="left">
                                                      <div class="">
                                                        <a class="text-light" href="<?=$lireLasuite;?>agenda" style="color: #f7fafc;">Lire la suite...</a>
                                                      </div>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="s-1 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                              <tbody>
                                <tr>
                                  <td style="line-height: 4px; font-size: 4px; width: 100%; height: 4px; margin: 0;" align="left" width="100%" height="4">
                                    &#160;
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="s-1 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                              <tbody>
                                <tr>
                                  <td style="line-height: 4px; font-size: 4px; width: 100%; height: 4px; margin: 0;" align="left" width="100%" height="4">
                                    &#160;
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="p-1" role="presentation" border="0" cellpadding="0" cellspacing="0">
                              <tbody>
                                <tr>
                                  <td style="line-height: 24px; font-size: 16px; margin: 0; padding: 4px;" align="left">
                                    <div class="row" style="margin-right: -24px;">
                                      <table class="" role="presentation" border="0" cellpadding="0" cellspacing="0" style="table-layout: fixed; width: 100%;" width="100%">
                                        <tbody>
                                          <tr>
                                            <td class="col p-2 bg-white" style="line-height: 24px; font-size: 16px; min-height: 1px; font-weight: normal; margin: 0; padding: 8px 24px 8px 8px;" align="left" bgcolor="#ffffff" valign="top">
                                              <div class="text-center" style="" align="center">
                                                Cette lettre est publi&#233;e par le comit&#233; de r&#233;daction de la Newsletter de l'UMR5234<br>
                                                Pour toute question concernant cette lettre, &#233;crivez &#224; Christina Calmels.<br>
                                                Responsable de la publication : Fr&#233;d&#233;ric Bringaud.<br>
                                                Responsables de la r&#233;daction : Christina Calmels et Patricia Pinson.<br>
                                                Comit&#233; de r&#233;daction : Corinne Asencio, Marie-Lise Blondot, Floriane Lagadec, Paul Lesbats.<br>
                                                Int&#233;gration / Design : Nicolas Landrein.
                                              </div>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="s-1 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                              <tbody>
                                <tr>
                                  <td style="line-height: 4px; font-size: 4px; width: 100%; height: 4px; margin: 0;" align="left" width="100%" height="4">
                                    &#160;
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <!--[if (gte mso 9)|(IE)]>
                    </td>
                  </tr>
                </tbody>
              </table>
                    <![endif]-->
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>
