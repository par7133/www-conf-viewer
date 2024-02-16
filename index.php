<?php

/**
 * Copyright 2021, 2024 5 Mode
 *
 * This file is part of www-conf-viewer.
 *
 * www-conf-viewer is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * www-conf-viewer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.  
 * 
 * You should have received a copy of the GNU General Public License
 * along with www-conf-viewer. If not, see <https://www.gnu.org/licenses/>.
 *
 * home.php
 * 
 * www-conf-viewer home page.
 *
 * @author Daniele Bonini <my25mb@aol.com>
 * @copyrights (c) 2016, 2024, 5 Mode
 */

 // CONFIGURATION
 error_reporting(E_ALL | E_STRICT);  
 ini_set('display_startup_errors',1);  
 ini_set('display_errors',1);
 ini_set('log_errors',1); 

 require "config.inc";
 define('PHP_BR', "<br>");
 define('PHP_SPACE', " "); 
 define('PHP_STR', "");
 
 define("FUNCTIONS_PATH", APP_PATH . DIRECTORY_SEPARATOR . "CONFVW_func");

 require FUNCTIONS_PATH . DIRECTORY_SEPARATOR . "func.string.inc";
 require FUNCTIONS_PATH . DIRECTORY_SEPARATOR . "func.various.inc";

 $password = filter_input(INPUT_POST, "Password")??"";
 $password = strip_tags($password);
 if ($password !== PHP_STR) {	
   $hash = hash("sha256", $password . APP_SALT, false);

   if ($hash !== APP_HASH) {
     $password=PHP_STR;	
   }	 
 } 
 
 if ($password == PHP_STR) {
   $NGINX_CONF_PATH = APP_EXAMPLE_PATH . DIRECTORY_SEPARATOR . "nginx.conf";
   $NGINX_SERVER_CONF_PATH = APP_EXAMPLE_PATH . DIRECTORY_SEPARATOR . "site.com.conf";
   $PHPFPM_CONF_PATH = APP_EXAMPLE_PATH . DIRECTORY_SEPARATOR . "php-fpm.conf";
 } else {  
   $NGINX_CONF_PATH = APP_NGINX_CONF_PATH;
   $NGINX_SERVER_CONF_PATH = APP_NGINX_SERVER_CONF_PATH;
   $PHPFPM_CONF_PATH = APP_PHPFPM_CONF_PATH;
 }
 
 //echo("NGINX_CONF_PATH=$NGINX_CONF_PATH<br>");
 //echo("NGINX_SERVER_CONF_PATH=$NGINX_SERVER_CONF_PATH<br>");
 //echo("PHPFPM_CONF_PATH=$PHPFPM_CONF_PATH<br>");
 
 ?>

<!DOCTYPE html>
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>

  <meta name="viewport" content="width=device-width, initial-scale=1"/>
   
<!--<?PHP echo(APP_LICENSE);?>-->  
  
  <title><?PHP echo(APP_TITLE);?></title>

  <link rel="shortcut icon" href="/favicon.ico" />

  <meta name="description" content="Welcome to www-conf-viewer! Everyone its web config."/>
  <meta name="keywords" content="www-conf-viewer,web,configuration,viewer,on,premise,solution"/>
  <meta name="robots" content="index,follow"/>
  <meta name="author" content="5 Mode"/>
  
  <script src="/CONFVW_js/jquery-3.6.0.min.js" type="text/javascript"></script>
  <script src="/CONFVW_js/sha.js" type="text/javascript"></script>
  <script src="/CONFVW_js/common.js" type="text/javascript"></script>  
    
  <link href="/CONFVW_css/style.css?r=<?PHP echo(time());?>" type="text/css" rel="stylesheet">
  
</head>

  <body style="background:#dadada no-repeat; background-size: cover; background-attachment: fixed; background-position: center;">

  <div class="header" style="margin-top:18px;margin-bottom:18px;">
        <a href="http://www-conf-viewer.5mode-foss.eu" target="_self" style="color:#000000; text-decoration: none;">&nbsp;<img src="/CONFVW_res/logo.png" align="middle" style="position:relative;top:-5px;width:22px;">&nbsp;www-conf-viewer</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="https://github.com/par7133/www-conf-viewer" style="color:#000000;"><span style="color:#119fe2">on</span> github</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="mailto:posta@elettronica.lol" style="color:#000000;"><span style="color:#119fe2">for</span> feedback</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="tel:+39-378-0812391" style="font-size:13px;background-color:lightcyan;border:2px solid lightcyan;color:#000000;height:27px;text-decoration:none;">&nbsp;&nbsp;get support&nbsp;&nbsp;</a>
   </div>
  
   <form id="frmUpload" role="form" method="post" action="/" target="_self" enctype="multipart/form-data">  
   
  <?PHP if (!MAX_SECURITY || (MAX_SECURITY && ($password != PHP_STR))) :?>
     
   <table style="width:100%">
      <tr>
         <td colspan="3" style="width:100%;padding:10px;padding-bottom:7px;">
               <div style="float:left;font-weight:900;font-size:12px;">NGINX (Core configuration)</div>
               <div style="float:right"><img src="/CONFVW_res/nginx.png" style="width:48px;" slt="Nginx logo"></div>
         </td>
      </tr>  
     
        <?PHP
     $aNginxConf = file($NGINX_CONF_PATH, FILE_SKIP_EMPTY_LINES);                
     if (!empty($aNginxConf)) {
       
       $nested = false;
       foreach($aNginxConf as $line) {
         $line = trim($line, " \n\r\t\v\x00"); 
         $line = trim($line, "\x00..\x1F");
         
         if (((mb_strlen($line) < 4) && ($line!="}")) || (left($line,1)=="#")) { 
           continue;           
         }
         
         $directive = explode(PHP_SPACE, $line)[0]??null;
         
         if (isset($directive) && strtolower(left($directive,4)=="http")) {
           break;
         }
         
         if (right($line,1)=="{") { ?>
     
                             <tr>
                                 <td class="td-caption" colspan="2">
                                      <?PHP echo($directive); ?><br>
                                      <span id="directive-desc2">
                                          <?PHP echo($NGINX_DIRECTIVES[$directive]??PHP_STR);?>
                                      </span>
                                 </td>
                                 <td class="td-full-opts">
                                       &nbsp;
                                  </td> 
                              </tr>   
                                 
     <?PHP    $nested = true;
           continue;
         }

         if ($directive=="}") {
           $nested = false;
           continue;
         }
         
         if (isset($directive) && (left($directive,1)!="#") && ($directive!="}")) {
           
           $fullopts = substr($line, mb_strlen($directive)+1); 
           $fullopts = rtrim($fullopts,";") ?? PHP_STR;
           
           ?>
     
                              <tr>
                                  <?PHP if ($nested): ?>
                                  <!--<td class="td-tab">
                   &nbsp;
                 </td> -->
                                 <td class="td-caption2" colspan="2">
                                  <?PHP else: ?> 
                                   <td class="td-caption" colspan="2">
                                  <?PHP endif; ?>
                                     <span id="directive"><?PHP echo($directive); ?></span><br>
                                     <span id="directive-desc">
                                    <?PHP echo($NGINX_DIRECTIVES[$directive]??PHP_STR);?>
                                     </span>
                                  </td> 
                                 <td class="td-full-opts">
                                         <?PHP echo($fullopts); ?>
                                  </td> 
                              </tr>   
       <?PHP 
         }         
       } 
     }  
   ?>
     
      <tr>
         <td colspan="3" style="width:100%;padding:8px;padding-top:12px;border-top: 1px dotted gray;">
               <div style="float:left;font-weight:900;font-size:12px;">This is www-conf-viewer, a 5 Mode software.<br>Copyright 2021, 2024 5 Mode - GPL License<br>Authors: Daniele Bonini</div>
               <div style="float:right"><a href="https://5mode.com"><img src="/CONFVW_res/5mode.png" style="width:48px;" alt="5 Mode"></a>&nbsp;&nbsp;&nbsp;</div>
         </td>
      </tr>  
     
  </table>   
 
 <br><br>    
      
 <table style="width:100%">
      <tr>
         <td colspan="3" style="width:100%;padding:10px;padding-bottom:7px;">
               <div style="float:left;font-weight:900;font-size:12px;">NGINX (HTTP configuration)</div>
               <div style="float:right"><img src="/CONFVW_res/nginx.png" style="width:48px;" slt="Nginx logo"></div>
         </td>
      </tr>  
     
        <?PHP
     //$aNginxConf = file($NGINX_CONF_PATH, FILE_SKIP_EMPTY_LINES);                
     if (!empty($aNginxConf)) {
       
       $found_http = false;
       $line_buff = "";
       $nested = false;
       foreach($aNginxConf as $line) {
         $line = trim($line, " \n\r\t\v\x00"); 
         $line = trim($line, "\x00..\x1F");
         
         if (((mb_strlen($line) < 4) && ($line!="}")) || (left($line,1)=="#")) { 
           continue;           
         }
         
         $directive = explode(PHP_SPACE, $line)[0]??null;
         
         // searching the initial token 'http'..
         if (isset($directive) && (strtolower($directive)!="http")) {
           if (!$found_http) {
             continue;
           }  
         } else {
           $found_http = true;
           continue;
         }

         // exiting on the server token.. 
         if (isset($directive) && (strtolower($directive)=="map")) {
           break;
         }        
         
         // exiting on the server token.. 
         if (isset($directive) && (strtolower($directive)=="server")) {
           break;
         }

         //echo("<tr><td>$directive (debug)</td><td>$line</td></tr>");
         
         if (right($line,1)!=";") {
           $line_buff .= $line . "|||||";
           continue;
         } else {
           if ($line_buff!=PHP_STR) {
             $line_buff .= $line . "|||||";
             $directive = explode(PHP_SPACE, $line_buff)[0]??null;
           }
         }
         
         if (right($line,1)=="{" || ($line_buff!=PHP_STR && !$nested)) { ?>
     
                             <tr>
                                 <td class="td-caption" colspan="2">
                                      <?PHP echo($directive); ?><br>
                                      <span id="directive-desc2">
                                          <?PHP echo($NGINX_DIRECTIVES[$directive]??PHP_STR);?>
                                      </span>
                                 </td>
                                 <td class="td-full-opts">
                                       &nbsp;
                                  </td> 
                              </tr>   
                                 
     <?PHP    $nested = true;
     
           if ($line_buff==PHP_STR) {
             continue;
           }  
         }
         
         if ($line_buff!=PHP_STR && $nested) {
           
           $ipos = stripos($line_buff, PHP_SPACE);
           $s = substr($line_buff,$ipos+1);
           
           $buffLines = explode("|||||", $s);
           
           foreach($buffLines as $buffLine) { 
             
             if ($buffLine==PHP_STR) {
               continue;
             }
?>
                                         <tr>
                                         <td class="td-caption2" colspan="2">
                                             <span id="directive">&nbsp;</span>
                                          </td> 
                                         <td class="td-full-opts">
                                                 <?PHP echo(trim($buffLine,";")); ?>
                                          </td> 
                                      </tr>   
<?PHP
          }
          
          $nested = false;
          $line_buff = PHP_STR;           
          
         } else {

            if ($directive=="}") {
              $nested = false;
              continue;
            }

            if (isset($directive) && (left($directive,1)!="#") && ($directive!="}")) {

              $fullopts = substr($line, mb_strlen($directive)+1); 
              $fullopts = rtrim($fullopts,";") ?? PHP_STR;

              ?>

                                      <tr>
                                          <?PHP if ($nested): ?>
                                         <td class="td-caption2" colspan="2">
                                          <?PHP else: ?> 
                                           <td class="td-caption" colspan="2">
                                          <?PHP endif; ?>
                                             <span id="directive"><?PHP echo($directive); ?></span><br>
                                             <span id="directive-desc">
                                            <?PHP echo($NGINX_DIRECTIVES[$directive]??PHP_STR);?>
                                             </span>
                                          </td> 
                                         <td class="td-full-opts">
                                                 <?PHP echo($fullopts); ?>
                                          </td> 
                                      </tr>   
            <?PHP               
            }
         } 
       } 
     }  
   ?>
     
      <tr>
         <td colspan="3" style="width:100%;padding:8px;padding-top:12px;border-top: 1px dotted gray;">
               <div style="float:left;font-weight:900;font-size:12px;">This is www-conf-viewer, a 5 Mode software.<br>Copyright 2021, 2024 5 Mode - GPL License<br>Authors: Daniele Bonini</div>
               <div style="float:right"><a href="https://5mode.com"><img src="/CONFVW_res/5mode.png" style="width:48px;" alt="5 Mode"></a>&nbsp;&nbsp;&nbsp;</div>
         </td>
      </tr>  
     
  </table>       
 
  <br><br>   
     
   <table style="width:100%">
      <tr>
         <td colspan="3" style="width:100%;padding:10px;padding-bottom:7px;">
               <div style="float:left;font-weight:900;font-size:12px;">NGINX (SERVER configuration)</div>
               <div style="float:right"><img src="/CONFVW_res/nginx.png" style="width:48px;" slt="Nginx logo"></div>
         </td>
      </tr>     
     
     <?PHP
    $serverConf = file($NGINX_SERVER_CONF_PATH);
  ?>   
     
    <tr>
        <td class="td-server-spec" style="white-space:pre;">
<?PHP
  $blank_lines = 0;
  foreach($serverConf as $line) {
    $sample = left($line,10);
    $sample = ltrim($sample);
    
    if ((mb_strlen(trim($line))<3) && (trim($line)!="}")) {
      $blank_lines++;
      continue;
    }
    
    if (left($sample, 1) != "#") {
      if ($blank_lines>0) {
        echo("\n");
      }
      echo(HTMLencode($line, true));
      $blank_lines = 0;
    }  
  }
?>
        </td>
     </tr>   

      <tr>
         <td colspan="3" style="width:100%;padding:8px;padding-top:12px;border-top: 1px dotted gray;">
               <div style="float:left;font-weight:900;font-size:12px;">This is www-conf-viewer, a 5 Mode software.<br>Copyright 2021, 2024 5 Mode - GPL License<br>Authors: Daniele Bonini</div>
               <div style="float:right"><a href="https://5mode.com"><img src="/CONFVW_res/5mode.png" style="width:48px;" alt="5 Mode"></a>&nbsp;&nbsp;&nbsp;</div>
         </td>
      </tr>  
     
  </table> 
     
  <br><br>      

   <table style="width:100%">
      <tr>
         <td colspan="3" style="width:100%;padding:10px;padding-bottom:7px;">
               <div style="float:left;font-weight:900;font-size:12px;">PHPFPM</div>
               <div style="float:right"><img src="/CONFVW_res/php.png" style="width:48px;" slt="Nginx logo"></div>
         </td>
      </tr>     
     
     <?PHP
    if ($PHPFPM_CONF_PATH != PHP_STR) {  
      $serverConf = file($PHPFPM_CONF_PATH);
    } else {
      $serverConf = [PHP_STR];
    }  
  ?>   
     
    <tr>
        <td class="td-server-spec-php" style="white-space:pre;">
<?PHP
  $blank_lines = 0;
  foreach($serverConf as $line) {
    $sample = left($line,10);
    $sample = ltrim($sample);
    
    if ((mb_strlen(trim($line))<3) && (trim($line)!="}")) {
      $blank_lines++;
      continue;
    }
    
    if (left($sample, 1) != "#") {
      if ($blank_lines>0) {
        echo("\n");
      }
      echo(HTMLencode($line, true));
      $blank_lines = 0;
    }  
  }
?>
        </td>
     </tr>   

      <tr>
         <td colspan="3" style="width:100%;padding:8px;padding-top:12px;border-top: 1px dotted gray;">
               <div style="float:left;font-weight:900;font-size:12px;">This is www-conf-viewer, a 5 Mode software.<br>Copyright 2021, 2024 5 Mode - GPL License<br>Authors: Daniele Bonini</div>
               <div style="float:right"><a href="https://5mode.com"><img src="/CONFVW_res/5mode.png" style="width:48px;" alt="5 Mode"></a>&nbsp;&nbsp;&nbsp;</div>
         </td>
      </tr>  
     
  </table> 
 
 <?PHP else: 
      
    echo("<div style='width:250px;padding-top:250px;font-weight:400;margin:auto;text-align:center;'>Login required.</div>"); 
      
  endif; ?>     
      
  <br><br>       
      
  <div id="passworddisplay">
       <br>  
        &nbsp;&nbsp;<input type="password" id="Password" name="Password" placeholder="password" value="<?php echo($password);?>" autocomplete="off">&nbsp;<input type="submit" value="Go" style="text-align:left;width:25%;color:#000000;"><br>
        &nbsp;&nbsp;<input type="text" id="Salt" placeholder="salt" autocomplete="off"><br>
        <div style="text-align:center;">
           <a id="hashMe" href="#" onclick="showEncodedPassword();">Hash Me!</a>
        </div>
 </div> 

 </form>       
     
  <div id="footerCont">&nbsp;</div>
  <div id="footer"><span style="background:#FFFFFF; opacity:0.7;">&nbsp;&nbsp;A <a href="http://5mode.com" class="aaa">5 Mode</a> project and <a href="http://demo.5mode.com" class="aaa">WYSIWYG</a> system. Some rights reserved.</span></div>
  
 <script src="/CONFVW_js/home.js" type="text/javascript"></script>

<!-- METRICS CODE -->
<?php if (file_exists(APP_PATH . DIRECTORY_SEPARATOR . "metrics.html")): ?>
<?php include(APP_PATH . DIRECTORY_SEPARATOR . "metrics.html"); ?> 
<?php endif; ?>
    
  </body>
  </html>
