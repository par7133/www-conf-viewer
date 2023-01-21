 
 var myToolsOnIntID;

function settingsOn() {
  $(".settingson").hide();
  $(".magicjar1").show();
  $(".magicjar2").show();
  $(".magicjar3").show();
  $(".settingsoff").show();
  setTimeout("settingsOff()",6000);
}  

function settingsOff() {
  $(".settingsoff").hide("slow");
  $(".magicjar1").hide("slow");
  $(".magicjar2").hide("slow");
  $(".magicjar3").hide("slow");
  $(".settingson").show();
}  

function toolsOn() {
  settingsOn();
  $(".tools").show("slow");
    
  clearInterval(myToolsOnIntID);
}  

function setJar1On() {
  $(".magicjar1").css("background","url(/res/magicjar1.png)");
  $(".magicjar1").css("background-size","120px 120px");
  document.getElementsByClassName("magicjar1")[0].onclick=setJar1Off;
  document.getElementById("txtMagicJar1").value="1";
  document.getElementById("frmUpload").submit();
}

function setJar1Off() {
  $(".magicjar1").css("background","url(/res/magicjar1dis.png)");
  $(".magicjar1").css("background-size","120px 120px");
  document.getElementsByClassName("magicjar1")[0].onclick=setJar1On;
  document.getElementById("txtMagicJar1").value="0"; 
  document.getElementById("frmUpload").submit();
}

function setJar2On() {
  $(".magicjar2").css("background","url(/res/magicjar2.png)");
  $(".magicjar2").css("background-size","120px 120px");
  document.getElementsByClassName("magicjar2")[0].onclick=setJar2Off;
  document.getElementById("txtMagicJar2").value="1"; 
  document.getElementById("frmUpload").submit();
}

function setJar2Off() {
  $(".magicjar2").css("background","url(/res/magicjar2dis.png)");
  $(".magicjar2").css("background-size","120px 120px");
  document.getElementsByClassName("magicjar2")[0].onclick=setJar2On;
  document.getElementById("txtMagicJar2").value="0";
  document.getElementById("frmUpload").submit();
}

function setJar3On() {
  $(".magicjar3").css("background","url(/res/magicjar3.png)");
  $(".magicjar3").css("background-size","120px 120px");
  document.getElementsByClassName("magicjar3")[0].onclick=setJar3Off;
  document.getElementById("txtMagicJar3").value="1";
  document.getElementById("frmUpload").submit();
}

function setJar3Off() {
  $(".magicjar3").css("background","url(/res/magicjar3dis.png)");
  $(".magicjar3").css("background-size","120px 120px");
  document.getElementsByClassName("magicjar3")[0].onclick=setJar3On;
  document.getElementById("txtMagicJar3").value="0";
}
 
 function startApp() {

   hidePassword();
   
 }			

 function hidePassword() {
   $("#passworddisplay").css("visibility","hidden");
 }  

 /*
  * call to startApp
  * 
  * @returns void
  */
 function _startApp() {
   
   setTimeout("startApp()", 1000);    
 }
 
/*
 *  Display the current hash for the config file
 *  
 *  @returns void
 */
function showEncodedPassword() {
  if ($("#Password").val() === "") {
    $("#Password").addClass("emptyfield");
    return;  
  }
  //if ($("#Salt").val() === "") {
  //  $("#Salt").addClass("emptyfield");
  //  return;  
  //}	   	
  passw = encryptSha2( $("#Password").val() + $("#Salt").val());
  msg = "Please set your hash in the config file with this value:";
  alert(msg + "\n\n" + passw);	
}

function reload() {
  //window.location.reload(); 
  document.getElementById("frmUpload").submit();
}

function setContentPos() {                    
  h=parseInt(window.innerHeight);
  w=parseInt(window.innerWidth);

  mytop = parseInt(h - ($("#passworddisplay").height() + 60));
  $("#passworddisplay").css("top", mytop+"px");
} 

function setFooterPos() {
  if (document.getElementById("footerCont")) {
    tollerance = 16;
    $("#footerCont").css("top", parseInt( window.innerHeight - $("#footerCont").height() - tollerance ) + "px");
    $("#footer").css("top", parseInt( window.innerHeight - $("#footer").height() - tollerance ) + "px");
  }
}

function setOriginsPos() {
  h=parseInt(window.innerHeight);
  w=parseInt(window.innerWidth);
  mytop = parseInt(window.innerHeight - ($("#originsDisplay").height() + 60));
  $("#originsDisplay").css("top", mytop+"px");
  setTimeout("hideOrigins()",15000);
}

function hideOrigins() {
  $("#originsDisplay").css("visibility","hidden");
}  

window.addEventListener("load", function() {

  setTimeout("setContentPos()", 500);
  setTimeout("setFooterPos()", 1000);
  setTimeout("setOriginsPos()", 500);
  $("#originsDisplay").show();
 
  setTimeout("_startApp()", 10000);

}, true);

window.addEventListener("resize", function() {

  setTimeout("setContentPos()", 500);
  setTimeout("setFooterPos()", 1000);

}, true);
  
