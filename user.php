







 



 
<?php
$user=trim($_POST['user']);
$clientkey=trim($_POST['clientkey']);
$pwd=trim($_POST['pwd']);
$sessionid=trim($_GET['sessionid']);
//-----------------------------------------
if ($sessionid=='')
{
$sessionid=trim($_POST['sessionid']);
}
include 'functions.php';
AngaobaWakhalSing();
$sql="SELECT * FROM `school_clients` WHERE `key`='$clientkey'";
$url=trim(getInfo($sql,"url"));
$schoolid=trim(getInfo($sql,"schoolid"));
$schoolname=trim(getInfo($sql,"name"));
$pieces = explode("/", $referby);
$referby=$pieces[2]; // piece1
$referby=str_replace("www.", "", $referby);
?> 

<?
if ($sessionid=='')
{
$sql="SELECT * FROM `school_users` WHERE `user`='$user' and `schoolid`='$schoolid'";
$password=getInfo($sql,"pwd");
$enabled=getInfo($sql,"enabled");
$dt=@date("ymd");
if (($password=="$pwd")and ($pwd!='') and ($enabled=='yes'))
{
$name=getInfo($sql,"name");
$role=strtoupper(getInfo($sql,"role"));
$sessionid="S".$schoolid."U".$user."RAN".$dt.rand(100000,999999);
$sql="UPDATE `school_users`  SET `sessionid`='$sessionid' WHERE `user`='$user' And `schoolid`='$schoolid'";
upDate($sql);
$sessionid=$sessionid;
$loginmsg="Welcome ! $name ( $role ) ";
}
else
{
$loginmsg="<center>Wrong login credential<br/> <a href='securelogin.php'>Login again</a></center>" ;
}
}
else
{
$sql="SELECT * FROM `school_users` WHERE `sessionid`='$sessionid' ";
$thissession=getInfo($sql,"sessionid");
if ($thissession=='')
{
$loginmsg='<a href="securelogin.php">Login again<a/>' ;
$sessionid="";
}
else
{
$sessionid= "$thissession";
$name=getInfo($sql,"name");
$role=strtoupper(getInfo($sql,"role"));
}

}
//-----------------------------------------------------------------------------------------------------
?>

<!DOCTYPE html>
<html>
  <head>
<title>
<? echo $schoolname ;?>
</title>
<style type="text/css" media="screen">
#horizontalmenu ul {padding:1; margin:1; list-style:none; opacity: 1;}
#horizontalmenu li {float:left; position:relative; padding-right:100; display:block;border:6px solid #CC55FF; border-style:outset;width:150px;background-image: url(btn.png); opacity: 1;}
#horizontalmenu li ul {display:none;position:absolute; opacity: 1;}
#horizontalmenu li:hover ul{ display:block; background:yellow;height:auto; width:10em; opacity: 1;visibility: visible;}
#horizontalmenu li ul li{clear:both;border-style:solid;background-image: url(btn.png);background-repeat: repeat-x;width:180px; opacity: 1;}
</style>
<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 60px;
  height: 60px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<link rel="stylesheet" type="text/css" href="school.css">
<script>
var clientid='1' ; // Edit it. It will be got from kulhu.com
var schoolkey='0X1478947567842F' ;//Edit it . It will be got from Kulhu.com
var sessionid='<? echo $sessionid ; ?>';
var myurl='';
var name='';
var role='';
var regno='';
var std, section ;
var stringfromserver='';
</script>
<script src='jquery.txt'>
</script>
<script>
/*---------------------Photo Preview-----------------------*/
$(function(){
    Photoprev = {
        UpdatePreview: function(obj){
          // if IE < 10 doesn't support FileReader
          if(!window.FileReader){
             // don't know how to proceed to assign src to image tag
          } else
         {
             var reader = new FileReader();
             var target = null;
             reader.onload = function(e) {
              target =  e.target || e.srcElement;
               $("#photoprev").attr("src", target.result);
             };
              reader.readAsDataURL(obj.files[0]);                         
          }
        }
    };
});
/*---------------------------------------------------------------*/
$(function(){
    Signprev = {
        UpdatePreview: function(obj){
          // if IE < 10 doesn't support FileReader
          if(!window.FileReader){
             // don't know how to proceed to assign src to image tag
          } else
         {
             var reader = new FileReader();
             var target = null;
             reader.onload = function(e) {
              target =  e.target || e.srcElement;
               $("#signprev").attr("src", target.result);
             };
              reader.readAsDataURL(obj.files[0]);                         
          }
        }
    };
});
/*---------------------------------------------------------------*/
function UploadSign()
{
var rno=document.getElementById("regno").value;
var sessn=document.getElementById("session").value;
var photo = document.getElementById('sign');
var photoname=document.getElementById('sign').value;

if (rno=='')
{
alert("Select Reg.No./Session");
return ;
}
if (sessn=='')
{
alert("Select Reg.No./Session");
return ;
}
if (photoname=='')
{
alert("Select signature.");
return ;
}

var upphoto = photo.files[0];
var formData = new FormData();
formData.append('regno', rno);
formData.append('sessn', sessn);
formData.append('sessionid',sessionid);
formData.append('action', "uploadphoto");
formData.append('photosign', "Sign");
formData.append('photo', upphoto);
document.getElementById("info").innerHTML="<div class='loader'></div>";

  $.ajax({
        url: "action.php",
        type: 'POST',
        data: formData,
        async: false,
        success: function (resp) { document.getElementById("info").innerHTML=" "+resp ;},
        //error : function(r) { document.getElementById("info").innerHTML=r ; },
        cache: false,
        contentType: false,
        processData: false
    });

}
//--------------------------------------------------------------
function DisableEnable(userid,disableenable)
{
myurl="action.php?sessionid="+sessionid+"&action=disableenable&userid="+userid+"&disableenable="+disableenable;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );
}
//--------------------------------------------------------------
function PrintIt(what)
{

if (what=='attendance')
{
var date1=document.getElementById("date1").value;
var date2=document.getElementById("date2").value;
var std=document.getElementById("std").value;
var section=document.getElementById("section").value;
var roll=document.getElementById("roll").value;
var session=document.getElementById("session").value;
var prnturl="printaction.php?sessionid="+sessionid+"&toprint=attendance&std="+std+"&section="+section+"&date1="+date1+"&date2="+date2+"&roll="+roll+"&session="+session ;
window.open(prnturl);
}

if (what=='registration')
{
regno=document.getElementById("regno").value;
var prnturl="printaction.php?sessionid="+sessionid+"&toprint=registration&regno="+regno ;
window.open(prnturl);
}

if (what=='studentsinclass')
{
var std=document.getElementById("std").value;
var section=document.getElementById("section").value;
var session=document.getElementById("year").value;
var prnturl="printaction.php?sessionid="+sessionid+"&toprint=studentsinclass&std="+std+"&session="+session+"&section="+section ;
window.open(prnturl);
}

if (what=='idcard')
{
var std=document.getElementById("std").value;
var section=document.getElementById("section").value;
var session=document.getElementById("year").value;
var roll=document.getElementById("roll").value;
var extrainfo=document.getElementById("extrainfo").value;
var prnturl="printaction.php?sessionid="+sessionid+"&toprint=idcard&std="+std+"&session="+session+"&section="+section+"&roll="+roll+"&extrainfo="+extrainfo;
window.open(prnturl);
}

if (what=='admitcard')
{
var std=document.getElementById("std").value;
var exam=document.getElementById("exam").value;
var section=document.getElementById("section").value;
var session=document.getElementById("year").value;
var roll=document.getElementById("roll").value;
var prnturl="printaction.php?sessionid="+sessionid+"&toprint=admitcard&std="+std+"&session="+session+"&section="+section+"&exam="+exam+"&roll="+roll ;
window.open(prnturl);
}


if (what=='marks')
{
var examid=document.getElementById("examid").value;
var session=document.getElementById("session").value;
var subjectid=document.getElementById("subjectid").value;
var section=document.getElementById("section").value;
var roll=document.getElementById("roll").value;
var total=document.getElementById("total").value;
prnturl="printaction.php?sessionid="+sessionid+"&toprint=marks&examid="+examid+"&subid="+subjectid+"&section="+section+"&roll="+roll+"&session="+session+"&total="+total;
window.open(prnturl);
}


}
//---------------------------------------------------------------
function UploadPhoto()
{
var rno=document.getElementById("regno").value;
var sessn=document.getElementById("session").value;
var photo = document.getElementById('photo');
var photoname=document.getElementById('photo').value;
var ppsign="";

if  (document.getElementById("pp").checked==true)
{
ppsign="pp";
}

if  (document.getElementById("sign").checked==true)
{
ppsign="sign";
}

if (rno=='')
{
alert("Select Reg.No./Session");
return ;
}
if (sessn=='')
{
alert("Select Reg.No./Session");
return ;
}
if (photoname=='')
{
alert("Select photo.");
return ;
}

var upphoto = photo.files[0];
var formData = new FormData();
formData.append('regno', rno);
formData.append('sessn', sessn);
formData.append('sessionid',sessionid);
formData.append('action', "uploadphoto");
formData.append('photo', upphoto);
formData.append('ppsign', ppsign);
document.getElementById("info").innerHTML="<div class='loader'></div>";

  $.ajax({
        url: "action.php",
        type: 'POST',
        data: formData,
        async: false,
        success: function (resp) { document.getElementById("info").innerHTML=" "+resp ;},
        //error : function(r) { document.getElementById("info").innerHTML=r ; },
        cache: false,
        contentType: false,
        processData: false
    });

}

/*---------------------------------------------------------------*/
function SetMenus()
{
buttons="";
user=document.getElementById("user").value;
btn0=document.getElementById("btn0").checked;
btn1=document.getElementById("btn1").checked;
btn2=document.getElementById("btn2").checked;
btn3=document.getElementById("btn3").checked;
btn4=document.getElementById("btn4").checked;
btn5=document.getElementById("btn5").checked;
btn6=document.getElementById("btn6").checked;
btn7=document.getElementById("btn7").checked;
btn8=document.getElementById("btn8").checked;
btn9=document.getElementById("btn9").checked;
btn10=document.getElementById("btn10").checked;
btn11=document.getElementById("btn11").checked;
btn12=document.getElementById("btn12").checked;
btn13=document.getElementById("btn13").checked;
btn14=document.getElementById("btn14").checked;
btn15=document.getElementById("btn15").checked;
btn16=document.getElementById("btn16").checked;
btn17=document.getElementById("btn17").checked;
btn18=document.getElementById("btn18").checked;
btn19=document.getElementById("btn19").checked;
btn20=document.getElementById("btn20").checked;
btn21=document.getElementById("btn21").checked;
btn22=document.getElementById("btn22").checked;
btn23=document.getElementById("btn23").checked;
btn24=document.getElementById("btn24").checked;
btn25=document.getElementById("btn25").checked;
btn26=document.getElementById("btn26").checked;
btn27=document.getElementById("btn27").checked;
btn28=document.getElementById("btn28").checked;
btn29=document.getElementById("btn29").checked;
btn30=document.getElementById("btn30").checked;
btn31=document.getElementById("btn31").checked;
btn32=document.getElementById("btn32").checked;
btn33=document.getElementById("btn33").checked;
btn34=document.getElementById("btn34").checked;
btn35=document.getElementById("btn35").checked;
btn36=document.getElementById("btn36").checked;
btn37=document.getElementById("btn37").checked;
btn38=document.getElementById("btn38").checked;
btn39=document.getElementById("btn39").checked;
btn40=document.getElementById("btn40").checked;
btn41=document.getElementById("btn41").checked;
btn42=document.getElementById("btn42").checked;
btn43=document.getElementById("btn43").checked;
btn44=document.getElementById("btn44").checked;
btn45=document.getElementById("btn45").checked;
btn46=document.getElementById("btn46").checked;
buttons=user+"-"+btn0;
buttons=buttons+"-"+btn1;
buttons=buttons+"-"+btn2;
buttons=buttons+"-"+btn3;
buttons=buttons+"-"+btn4;
buttons=buttons+"-"+btn5;
buttons=buttons+"-"+btn6;
buttons=buttons+"-"+btn7;
buttons=buttons+"-"+btn8;
buttons=buttons+"-"+btn9;
buttons=buttons+"-"+btn10;
buttons=buttons+"-"+btn11;
buttons=buttons+"-"+btn12;
buttons=buttons+"-"+btn13;
buttons=buttons+"-"+btn14;
buttons=buttons+"-"+btn15;
buttons=buttons+"-"+btn16;
buttons=buttons+"-"+btn17;
buttons=buttons+"-"+btn18;
buttons=buttons+"-"+btn19;
buttons=buttons+"-"+btn20;
buttons=buttons+"-"+btn21;
buttons=buttons+"-"+btn22;
buttons=buttons+"-"+btn23;
buttons=buttons+"-"+btn24;
buttons=buttons+"-"+btn25;
buttons=buttons+"-"+btn26;
buttons=buttons+"-"+btn27;
buttons=buttons+"-"+btn28;
buttons=buttons+"-"+btn29;
buttons=buttons+"-"+btn30;
buttons=buttons+"-"+btn31;
buttons=buttons+"-"+btn32;
buttons=buttons+"-"+btn33;
buttons=buttons+"-"+btn34;
buttons=buttons+"-"+btn35;
buttons=buttons+"-"+btn36;
buttons=buttons+"-"+btn37;
buttons=buttons+"-"+btn38;
buttons=buttons+"-"+btn39;
buttons=buttons+"-"+btn40;
buttons=buttons+"-"+btn41;
buttons=buttons+"-"+btn42;
buttons=buttons+"-"+btn43;
buttons=buttons+"-"+btn44;
buttons=buttons+"-"+btn45;
buttons=buttons+"-"+btn46;
if (user=="")
{
alert("Select user");
return;
}
myurl="action.php?sessionid="+sessionid+"&action=setmenus&buttons="+buttons;
document.getElementById("info").innerHTML="<img src='status.gif' width='30px' height='30px'> <br/>Setting menus....";
doWebAction( );
}

//---------------------------------------------------------------
function FormSubmitAdmission()
{
regno=document.getElementById("regno").value;
var sessn=document.getElementById("session").value;
var roll=document.getElementById("roll").value;
std=document.getElementById("std").value;
section=document.getElementById("section").value;
if (std=='')
{
alert("Select Class/Section");
return ;
}
myurl="action.php?sessionid="+sessionid+"&action=admission&regno="+regno+"&std="+std+"&section="+section+"&session="+sessn;
document.getElementById("info").innerHTML="<img src='status.gif' width='30px' height='30px'> <br/>Setting menus....";
doWebAction( );  
}

/*-----------------------------------------------------------------*/
function SubmitForAdmission(){
regno=document.getElementById("regno").value;
std=document.getElementById("std").value;
section=document.getElementById("section").value;
var file=document.getElementById("file").value;
var fileInput = document.getElementById('file');
var file = fileInput.files[0];
document.getElementById("info").innerHTML="Submitting...";
  $.post("action.php",  {   regno:regno,  std:std, section:section,file:file, sessionid:sessionid ,action:"admission"  },   function(data,status){    document.getElementById("info").innerHTML=data ;   });
}
</script>
 <script>
function writeMsg(msg,action)
{
document.getElementById("info").innerHTML='<p  style="float:center; background-color:yellow;"><button  onclick="x()">X</button> </br>' + msg +  '</p>';

}
/*---------------------------------------*/
function Actions(action)
{
if (action=="delwrongadmission")
{
document.getElementById("info").innerHTML='<p  style="float:center; background-color:yellow;"> \
<center><u>Deletion of wrong admission.</u></center>\
<br/><font color="red">Warning !<br/>You are going to delete data.<br/>If deleted, the data can not be recovered.</font>\
<br/>Session:<input type="text" id="session" name="session" value="<? $yyyy=@date("Y") ; $yy=@date("y") ; $next=$yy+1; echo "$yyyy-$next"; ?>" style="width:70px;">\
<br/>Reg.No.:<input type="text" id="regno" name="regno" value="" style="width:70px;">\
<br/><button id="creating" onclick="DelAdmission()">Delete</button><button  onclick="x()">Do not Delete</button>\
</p>';
}
//----------------------------------------------
if (action=="delwrongpayment")
{
document.getElementById("info").innerHTML='<p  style="float:center; background-color:yellow;"> \
<center><u>Deletion of wrong payment.</u></center>\
<br/><font color="red">Warning !<br/>You are going to delete data.<br/>If deleted, the data can not be recovered.</font>\
<br/>Transaction ID<input type="text" id="transid" name="transid" value="" style="width:250px;">\
<br/><button id="creating" onclick="DelFee()">Delete</button><button  onclick="x()">Do not Delete</button>\
</p>';
}
//--------------------------------------------------------------------------------------------------
if (action=="delall")
{
document.getElementById("info").innerHTML='<p  style="float:center; background-color:yellow;"> \
<center><u>Data deletion.</u></center>\
<br/><font color="red">Warning !<br/>You are going to delete data.<br/>If deleted, the data can not be recovered.</font>\
<br/>Select Data to delete <select id="table" name="table"> \
 <option value="">Select Data</option>\
 <option value="attendance">Attendance data</option>\
 <option value="classes">Class data</option>\
  <option value="exams">Exam name data</option> \
  <option value="fee">Fee data</option> \
  <option value="marks">Mark data</option> \
  <option value="revheads">Revenue Head data</option> \
  <option value="smss">SMS data</option> \
  <option value="staffs">Staff data</option> \
  <option value="stds">Standard/Class Name data</option> \
  <option value="students">Student data</option> \
  <option value="subjects">Subject data</option> \
  <option value="users">User data</option> \
  <option value="all">All data</option> \
 </select> \
<br/><button id="creating" onclick="DelTable()">Delete</button><button  onclick="x()">Do not Delete</button>\
</p>';
}
//---------------------------------------------------------
if (action=="viewusers")
{
myurl="action.php?sessionid="+sessionid+"&action=viewusers" ;
document.getElementById("info").innerHTML='<div class="loader"></div>';
doWebAction( );
}

if (action=="viewwithheld")
{
myurl="action.php?sessionid="+sessionid+"&action=viewwithheld" ;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );
}
if (action=="viewsmsbalance")
{
myurl="comwithkulhu.php?action=viewsmsbalance&schoolid="+clientid+"&schoolkey="+schoolkey ;
document.getElementById("info").innerHTML='<div class="loader"></div>';
doWebAction( );
}
if (action=="viewstaffs")
{
myurl="action.php?sessionid="+sessionid+"&action=viewstaffs" ;
document.getElementById("info").innerHTML='<div class="loader"></div>';
doWebAction( );
}

if (action=="viewclasses")
{
myurl="action.php?sessionid="+sessionid+"&action=viewclasses" ;
document.getElementById("info").innerHTML='<div class="loader"></div>';
doWebAction( );
}

if (action=="viewsubjects")
{
myurl="action.php?sessionid="+sessionid+"&action=viewsubjects" ;
document.getElementById("info").innerHTML='<div class="loader"></div>';
doWebAction( );
}

if (action=="viewexams")
{
myurl="action.php?sessionid="+sessionid+"&action=viewexams" ;
document.getElementById("info").innerHTML='<div class="loader"></div>';
doWebAction( );
}

if (action=="viewrevenueheads")
{
myurl="action.php?sessionid="+sessionid+"&action=viewrevenueheads" ;
document.getElementById("info").innerHTML='<div class="loader"></div>';
doWebAction( );
}
//------------------------------------------------------------------------------

//-----------------------------------------------------------------

if (action=="signout")
{
myurl="action.php?sessionid="+sessionid+"&action=signout" ;
document.getElementById("info").innerHTML='<p  style="float:center; background-color:yellow;">Signing out ...</p>';
doWebAction( );
document.getElementById("projector").innerHTML='<center><p  style="float:center; background-color:yellow;border-radius:100%;margin: auto;width: 250px;">Signed out ! <br/> <a href="securelogin.php">Sign in </a></p></center>';
}
/*---------------------------------------------------------------------------------------------------*/
if (action=="createuser")
{
myurl="action.php?sessionid="+sessionid+"&action=createuser" ;
document.getElementById("info").innerHTML='<center>You are going to create a user.<button  onclick="x()">X</button></center>\
<div  style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"> \
Name:<input type="text" id="name" value="" name="name" > \
 <select id="role" name="role"> \
 <option value="">Select Authority</option>\
   <option value="spl">Special</option> \
  <option value="principal">Principal</option> \
  <option value="teacher">Teacher</option> \
  <option value="cashier">Cashier</option> \
  <option value="clerk">Clerk</option> \
 </select> \
<br/><button id="creating" onclick="CreateUser()">Create User </button> \
</div>';
}
/*---------------------------------------------------------------------------------------------------*/
if (action=="changemasterpassword")
{
myurl="action.php?sessionid="+sessionid+"&action=changepassword" ;
document.getElementById("info").innerHTML='<p  style="float:center; background-color:yellow;"> \
<center>You are going to to change your Master Password.</center> <button  onclick="x()">X</button>   \
<br/>New Master password:<input type="password" id="pwd1" value="" name="pwd1" > \
 Re-enter the new Master Password:<input type="password" id="pwd2" value="" name="pwd2" > \
<br/><button id="creating" onclick="ChangeMasterPassword()">Change Master password </button> \
</p>';
}
//---------------------------------------------------------------------------------
if (action=="setting")
{
myurl="action.php?sessionid="+sessionid+"&action=getUserSettingForm" ;
document.getElementById("info").innerHTML='<div class="loader"></div>';
doWebAction( );
}
//---------------------------------
if (action=="changepassword")
{
myurl="action.php?sessionid="+sessionid+"&action=changepassword" ;
document.getElementById("info").innerHTML='<p  style="float:center; background-color:yellow;"> \
<center>You are going to to change your password.</center> <button  onclick="x()">X</button>   \
<br/>New password:<input type="password" id="pwd1" value="" name="pwd1" > \
 Re-enter the new password:<input type="password" id="pwd2" value="" name="pwd2" > \
<br/><button id="creating" onclick="ChangePassword()">Change password </button> \
</p>';
}
/*-------------------------------------------------------------------*/
if (action=='uploadphoto') 
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 650px;"> \
<u>Upload photo/signature of student</u> <button  onclick="x()">X</button> \
<br/><table><tr><td>\
<form action="action.php"  name="admissionform" id="uploadform" enctype="multipart/form-data" method="post" style="text-align: right;">\
<br/> Registration Number:<input type="text" id="regno" value="" name="regno" cols="200"  onchange="updateRegNo()"> \
<br/>Session :<input type="text" id="session" value="<? $yyyy=@date("Y") ; $yy=@date("y") ; $next=$yy+1; echo "$yyyy-$next"; ?>"  name="session" cols="200"> \
<br/>Passport Photo:<input type="radio" id="pp" name="pp_sign" value="pp"> Signature: <input type="radio" id="sign" name="pp_sign" value="sign">\
<br/><input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' "> <input type="hidden" name="action" value="admission">\
<br/><hr>Select Photo <br/>(3KB-20KB JPG/JPEG):<br/> <input type="file" name="photo" id="photo" onchange="Photoprev.UpdatePreview(this)" >\
<hr/><img id="photoprev" src="#" alt="Photo will be shown here" width="250"/>\
</form>\
<hr/>\
<br/><button id="frmph" onclick="UploadPhoto()">Upload the photo</button>\
<hr></button> </td><td valing="top"><center>###STUDENT DETAILS###</center><br/><b id="studentdata" > Details of student will be shown here </b><br/><center>################</center></td></tr></table> \
</div>';

}
//----------------------------------------------------------------
if (action=='viewfeepaymentdate') 
{
document.getElementById("info").innerHTML='<span style="margin:auto;"> \
<u>Periodical Payment Details</u><button  onclick="x()">X</button> \
<table style="background-color:yellow;border-radius: 10px;padding: 10px;"><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/>Payment for :<? echo RevenueAllocationWithAll($schoolid) ; ?>\
<br/> From Date:<input type="date"  data-date-format="YYYY-MM-DD" id="date" value=""  name="date" style="width:115px;"> \
<br/> To Date  :<input type="date" data-date-format="YYYY-MM-DD" id="date1" value=""  name="date1"  style="width:115px;"> \
<br/> Session :<br/><input type="text" id="year" value="<? $yyyy=@date("Y") ; $yy=@date("y") ; $nxt=$yy+1; echo "$yyyy-$nxt"; ?>"  name="year" cols="200" style="width:80px;"> \
<br/>Class:<br/><? echo ClassDropDownListStdWithAll($schoolid); ?>\
<br/>Section:<br/><select id="section" name="section"> \
<? if (($role=="ADMIN") And ($role=="CASHIER"))
{ 
echo '<option value="ALL">ALL</option>';
} 
?>\
<option value="">None</option> \
 <option value="A">Section-A</option> \
  <option value="B">Section-B</option> \
<option value="C">Section-C</option> \
 </select> \
<br/>Roll No. :<br/><input type="text" id="roll"  name="roll" cols="5" style="width:30px;" > \
<br/>\
<button onclick="ViewFeePaymentDate()">Payments in the period </button> </td></td></tr></table> \
</span>';
}
//----------------------------------------------------------------
if (action=='viewfeepayment') 
{
document.getElementById("info").innerHTML='<span style="margin:auto;"> \
<u>Payment Details in a year</u> <button  onclick="x()">X</button> \
<table style="background-color:yellow;border-radius: 10px;padding: 10px;"><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/> Year :<input type="text" id="year" value="<? $yyyy=@date("Y") ; $yy=@date("y") ;echo "$yyyy"; ?>"  name="year" cols="200" style="width:80px;"> \
<br/>Class:<? echo ClassDropDownListStdWithAll($schoolid); ?>\
<br/>Section:<select id="section" name="section"> \
 <option value="ALL">ALL</option>\
 <option value="">None</option> \
 <option value="A">Section-A</option> \
  <option value="B">Section-B</option> \
<option value="C">Section-C</option> \
 </select> \
<br/>Roll No. :<input type="text" id="roll"  name="roll" cols="5" style="width:30px;" > \
<br/>\
<button onclick="ViewFeePayment()">Payment Details </button> </td></td></tr></table> \
</span>';
}
//----------------------------------------------------------------
if (action=='updatestaff')
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"> \
<u>Add/Update Staff</u> <button  onclick="x()">X</button>\
<br/><table><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/>Staff ID/Code:<br/><input type="text" id="staffid" value=""  name="staffid" style="width:150px;" > \
<br/>Name of Staff:<br/><input type="text" id="staffname" value=""  name="staffname" style="width:150px;" > \
<br/>Mobile No.:<br/><input type="text" id="mobile" value=""  name="mobile" style="width:150px;" > \
<br/>Designation:<br/><select id="designation" name="designation"> \
 <option value="teacher">Teacher</option> \
 <option value="clerk">Clerk</option>\
 </select> \
<br/>Remarks:<br/><input type="text" id="remarks" value=""  name="remarks" style="width:150px;" > \
<br/>Action:<br/><select id="update" name="update"> \
 <option value="Add">Add</option> \
 <option value="Up">Update</option>\
 <option value="Del">Delete</option> \
 </select> \
<br/><button onclick="DoUpdateStaff()">Update</button> </td></td></tr></table> \
</div>';
}
//----------------------------------------------------------------
if (action=='sendsmsstaffs') 
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"> \
<u>SMS to staffs</u> <button  onclick="x()">X</button> \
<br/><table><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/>SMS to: <select id="smsto" name="smsto"> \
 <option value="all">All Staffs</option>\
 <option value="teacher">Teachers</option> \
 <option value="clerk">Clerks</option> \
 </select> \
<br/> SMS(Max. Of 150 Characters):<br/><input type="text" id="sms" value=""  name="sms" style="width:300px;"> \
<br/> <button onclick="SendSMS(\'staffs\')">Send SMS </button> </td></td></tr></table> \
</div>';
}
//-------------------------------------------------------------
if (action=='sendsmsparent') 
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"> \
<u>SMS to parent</u> <button  onclick="x()">X</button> \
<br/><table><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/>Reg.No.:<input type="text" id="regno" name="regno" value="">\
<br/> SMS(Max. Of 150 Characters):<br/><input type="text" id="sms" value=""  name="sms" style="width:300px;"> \
<br/> <button onclick="SendSMS(\'parent\')">Send SMS </button> </td></td></tr></table> \
</div>';
}

if (action=='sendsmsroll') 
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"> \
<u>SMS to parent</u> <button  onclick="x()">X</button> \
<br/><table><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/> Session :<input type="text" id="year" value="<? $yyyy=@date("Y") ; $yy=@date("y") ; $next=$yy+1; echo "$yyyy-$next"; ?>"  name="year" cols="200"> \
<br/>Class:<? echo ClassDropDownListStd($schoolid) ; ?>\
<br/>Section:<select id="section" name="section"> \
 <option value="ALL">ALL</option>\
 <option value="">None</option> \
 <option value="A">Section-A</option> \
  <option value="B">Section-B</option> \
<option value="C">Section-C</option> \
 </select> \
<br/>\
Roll No.:<input type="text" id="roll" value=""  name="roll" cols="200" style="width:25px"> \
<br/> SMS(Max. Of 150 Characters):<br/><input type="text" id="sms" value=""  name="sms" style="width:300px;"> \
<br/> <button onclick="SendSMS(\'roll\')">Send SMS </button> </td></td></tr></table> \
</div>';
}

//----------------------------------------
if (action=='sendsmsnos') 
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"> \
<u>SMS to Mobiles</u> <button  onclick="x()">X</button> \
<br/><table><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/>Mobile #:<br/>\
<input type="text" id="mobile" name="mobile" value="" style="width:200px;">\
<br/> SMS(Max. Of 150 Characters):\
<br/><input type="text" id="sms" name="sms" value="" style="width:600px;">\
<br/><button onclick="SendSMS(\'directmobile\')">Send SMS </button> </td></td></tr></table> \
</div>';
}
        //------------------------------
if (action=='sendsmsparentsstd') 
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"> \
<u>SMS to parents of class</u> <button  onclick="x()">X</button> \
<br/><table><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/> Session :<input type="text" id="session" value="<? $yyyy=@date("Y") ; $yy=@date("y") ; $next=$yy+1; echo "$yyyy-$next"; ?>"  name="session" cols="200"> \
<br/>To whom :Parents of <? echo ClassDropDownListStd($schoolid) ; ?>\
<br/> SMS(Max. Of 150 Characters):<br/><input type="text" id="sms" value=""  name="sms" style="width:300px;"> \
<br/> <button onclick="SendSMS(\'stdparents\')">Send SMS </button> </td></td></tr></table> \
</div>';
}
//--------------------------------

if (action=='sendsmsparents') 
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"> \
<u>SMS to parents</u> <button  onclick="x()">X</button> \
<br/><table><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/> Session :<input type="text" id="session" value="<? $yyyy=@date("Y") ; $yy=@date("y") ; $next=$yy+1; echo "$yyyy-$next"; ?>"  name="session" cols="200"> \
<br/>To whom :<br/><select id="smsto" name="smsto"> \
 <option value=""></option> \
 <option value="absentee">Parents of today\'s absentee</option> \
 </select> \
<br/> SMS(Max. Of 150 Characters):<br/><input type="text" id="sms" value=""  name="sms" style="width:300px;"> \
<br/> <button onclick="SendSMS(\'parents\')">Send SMS </button> </td></td></tr></table> \
</div>';
}
//------------------------------------------------------

if (action=='printmarks') 
{
document.getElementById("info").innerHTML='<span style="background-color:yellow; width: 450px; align-content:center; text-align: right;"> \
<center>Marks/Merit List of the student(s)<button  onclick="x()">X</button>\
<table style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;"><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/> Session :<input type="text" id="session" value="<? $yyyy=@date("Y") ; $yy=@date("y") ; $next=$yy+1; echo "$yyyy-$next"; ?>"  name="session" cols="200"> \
<br/>Exam :<? echo ExamsWithAll($schoolid); ?>\
<br/>Class-Subject:<? echo ClassSubjectWithAll($schoolid) ; ?>\
<br/>Section:<select id="section" name="section"> \
 <option value="">None</option> \
 <option value="A">Section-A</option> \
  <option value="B">Section-B</option> \
  <option value="C">Section-C</option> \
 <option value="ALL">ALL</option> \
 </select> \
<br/>\
Roll No.:<input type="text" id="roll" name="roll" value="" style="width:25px;">\
<br/>Total Marks/Merit:<select id="total" name="total"> \
 <option value="">No</option> \
 <option value="YES">Yes</option> \
</select> \
<br/><button onclick="PrintIt(\'marks\')">Print Marks</button>\
</td></td></tr></table> \
</span></center>';
}
//----------------------------------------------------------------
if (action=='printregistration') 
{
document.getElementById("info").innerHTML='<span style="background-color:yellow; width: 450px; align-content:center; text-align: right;"> \
Registration Certificate<button  onclick="x()">X</button> \
<table style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;width:80%;margin: auto;"><tr><td>\
<br/> Registration Number:<input type="text" id="regno" value="" name="regno" onchange="updateRegNo()"> \
<br/><input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + '"> \
<br/><button onclick="PrintIt(\'registration\')">Print the Certificate</button>\
<hr> </td><td valing="top"><center>###STUDENT DETAILS###</center><br/><b id="studentdata" >Details of student will be shown here </b><br/></td></tr></table> \
</span>';

}
//--------------Printing Duplicate Receipt
if (action=='printreceipt') 
{
document.getElementById("info").innerHTML='<u>Receipt</u><button  onclick="x()">X</button> \
<div style="background-color:yellow;padding: 10px;width: 250px; border: 2px solid yellow; border-radius: 10px;margin: auto;"> \
<br/><center><form action="printaction.php" method="GET" target="_blank"> \
<input type="hidden" name="sessionid" value="<? echo $sessionid ; ?>">\
<input type="hidden" name="toprint" value="receipt">\
Receipt #:<input type="text"   name="tid" value="">\
<br/><input type="submit"  name="submit" value="Print Reciept">\
</form>\
</center></div>';

}
//----------------------------------------------------------------
if (action=='printattendance') 
{
document.getElementById("info").innerHTML='<span style="background-color:yellow; width: 450px; align-content:center; text-align: right;"> \
Attendance in class<button  onclick="x()">X</button> \
<table style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;"><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/> Session(e.g.2015-16):<input type="text" id="session" value=""  name="session" style="width:50px"> \
<br/> First Date :<input type="date" id="date1" value=""  name="date1" cols="200"> \
<br/> Last Date :<input type="date" id="date2" value=""  name="date2" cols="200"> \
<br/>Class:<? echo ClassDropDownListStd($schoolid) ; ?> \
<br/>Section:<select id="section" name="section"> \
 <option value="ALL">ALL</option>\
 <option value="A">Section-A</option> \
  <option value="B">Section-B</option> \
<option value="C">Section-C</option> \
 </select> \
<br/> Roll No. :<input type="text" id="roll" value=""  name="roll" style="width:25px"> \
<br/><button onclick="PrintIt(\'attendance\')">Attendance(s) in class(es) </button> </td></td></tr></table> \
</p>';
}
//----------------------------------------------------------------
if (action=='printstudentsinclass') 
{
document.getElementById("info").innerHTML='<span style="background-color:yellow; width: 450px; align-content:center; text-align: right;"> \
Students in class <button  onclick="x()">X</button> \
<table style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;"><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/> Session :<input type="text" id="year" value="<? $yyyy=@date("Y") ; $yy=@date("y") ; $next=$yy+1; echo "$yyyy-$next"; ?>"  name="year" cols="200"> \
<br/>Class : <? echo ClassDropDownListStd($schoolid) ; ?>\
<br/>Section:<select id="section" name="section"> \
 <option value="ALL">ALL</option>\
 <option value="A">Section-A</option> \
  <option value="B">Section-B</option> \
<option value="C">Section-C</option> \
 </select> \
<br/>\
<button onclick="PrintIt(\'studentsinclass\')">Students in class </button> </td></td></tr></table> \
</span>';
}
//----------------------------------------------------------------
if (action=='printID') 
{
document.getElementById("info").innerHTML='<span style="background-color:yellow; align-content:center; text-align: right;"> \
Print ID Card<button  onclick="x()">X</button> \
<table style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;"><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/> Session :<input type="text" id="year" value="<? $yyyy=@date("Y") ; $yy=@date("y") ; $next=$yy+1; echo "$yyyy-$next"; ?>"  name="year" cols="200"> \
<br/>Class:<? echo ClassDropDownListStd($schoolid) ; ?>\
<br/>Section:<select id="section" name="section"> \
 <option value="ALL">ALL</option>\
 <option value="">None</option> \
 <option value="A">Section-A</option> \
  <option value="B">Section-B</option> \
<option value="C">Section-C</option> \
 </select> \
Roll No.:<input type="text" id="roll" value=""  name="roll" cols="200" style="width:25px"> \
<br/>\
Extra Info:<input type="text" id="extrainfo" name="extrainfo" value="">\
<br/>\
<button onclick="PrintIt(\'idcard\')">Get ID Cards</button> </td></td></tr></table> \
</p>';
}
//---------------------------------------
if (action=='printadmitcard') 
{
document.getElementById("info").innerHTML='<span style="background-color:yellow; align-content:center; text-align: right;"> \
Admit Cards <button  onclick="x()">X</button> \
<table style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;"><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
Exam:<input type="text" id="exam" value=""  name="exam" cols="200"> \
<br/> Session :<input type="text" id="year" value="<? $yyyy=@date("Y") ; $yy=@date("y") ; $next=$yy+1; echo "$yyyy-$next"; ?>"  name="year" cols="200"> \
<br/>Class:<? echo ClassDropDownListStd($schoolid) ; ?>\
<br/>Section:<select id="section" name="section"> \
 <option value="ALL">ALL</option>\
 <option value="">None</option> \
 <option value="A">Section-A</option> \
  <option value="B">Section-B</option> \
<option value="C">Section-C</option> \
 </select> \
<br/>\
Roll No.:<input type="text" id="roll" value=""  name="roll" cols="200" style="width:25px"> \
<br/>\
<button onclick="PrintIt(\'admitcard\')">Admit Cards</button> </td></td></tr></table> \
</p>';
}

//----------------------------------------------------------------
if (action=='updatemark') 
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"> \
<u>Enter/Update Mark obtained in Exam</u> <button  onclick="x()">X</button> \
<br/><table><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/> Session :<input type="text" id="session" value="<? $yyyy=@date("Y") ; $yy=@date("y") ; $next=$yy+1; echo "$yyyy-$next"; ?>"  name="session" cols="200"> \
<br/>Exam :<? echo Exams($schoolid); ?>\
<br/>Class-Subject:<? echo ClassSubject($schoolid) ; ?>\
<br/>Section:<select id="section" name="section"> \
 <option value="">None</option> \
 <option value="A">Section-A</option> \
  <option value="B">Section-B</option> \
<option value="C">Section-C</option> \
 </select> \
<br/>\
Roll No.:<input type="text" id="roll" name="roll" value="" style="width:25px;">\
<br/>\
Mark obtained:<input type="text" id="mark" name="mark" value="" style="width:25px;">\
<br/>\
Maximum Mark:<input type="text" id="maxmark" name="maxmark" value="100" style="width:25px;">\
<hr>Action:<br/><select id="update" name="update"> \
 <option value="Add">Add</option> \
 <option value="Up">Update</option>\
 <option value="Del">Delete</option> \
 </select> \
<br/><button onclick="DoUpdateMark()">Update</button> </td></td></tr></table> \
</div>';
}
//----------------------------------------------------------------
if (action=='updatewithheld') 
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"> \
<u>Withhold student</u> <button  onclick="x()">X</button> \
<br/><table><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/>Reg.No.:<br><input type="text" id="regno" value=""  name="regno"> \
<br/><hr><textarea name="remarks" id="remarks">Enter reason for withheld...</textarea> \
<hr>Action:<br/><select id="update" name="update"> \
 <option value="Up">Update</option>\
 <option value="Del">Delete</option> \
 </select> \
<br/><button onclick="DoUpdateWithheld()">Update</button> </td></td></tr></table> \
</div>';
}
//----------------------------------------------------------------------------
if (action=='updatehighlight') 
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"> \
<u>Important record/highlight of student</u> <button  onclick="x()">X</button> \
<br/><table><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/>Reg.No.:<br><input type="text" id="regno" value=""  name="regno"> \
<br/>Record No.(Leave it blank for the new):<br/><input type="text" id="roll" value=""  name="roll" \
<br/><hr><textarea name="remarks" id="remarks">Enter record here...</textarea> \
<hr>Action:<br/><select id="update" name="update"> \
 <option value="Add">Add</option> \
 <option value="Up">Update</option>\
 <option value="Del">Delete</option> \
 </select> \
<br/><button onclick="DoUpdateHighlight()">Update</button> </td></td></tr></table> \
</p>';
}
//----------------------------------------------------------------

//---------------------------------------------------------------
if (action=='updateexam') 
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"> \
<u>Create/Update Exam</u> <button  onclick="x()">X</button> \
<br/><table><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
Exam ID/Code: <br/> <input type="text" id="examid" value=""  name="examid" style="width:150px;" > \
<br/>Exam Name:<br/><input type="text" id="exam" value=""  name="exam" style="width:150px;" > \
<br/>Action:<br/><select id="update" name="update"> \
 <option value="Add">Add</option> \
 <option value="Up">Update</option>\
 <option value="Del">Delete</option> \
 </select> \
<br/><button onclick="DoUpdateExam()">Update</button> </td></td></tr></table> \
</div>';
}
//----------------------------------------------------------------
if (action=='updateallocation') 
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"> \
<u>Create/Update Revenue Allocation</u> <button  onclick="x()">X</button> \
<br/><table><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
Revenue Allocation ID: <br/> <input type="text" id="allocationid" value=""  name="allocationid" style="width:100px;" > \
<br/>Allocation Name:<br/><input type="text" id="allocation" value=""  name="allocation" style="width:100px;" > \
<br/>Action:<br/><select id="update" name="update"> \
 <option value="Add">Add</option> \
 <option value="Up">Update</option>\
 <option value="Del">Delete</option> \
 </select> \
<br/><button onclick="DoUpdateAllocation()">Update</button> </td></td></tr></table> \
</div>';
}
//----------------------------------------------------------------
if (action=='updatebiometric') 
{
document.getElementById("info").innerHTML='<u>Fingerprint Update</u><button  onclick="x()">X</button> \
<div style="background-color:yellow;padding: 10px;width: 250px; border: 2px solid yellow; border-radius: 10px;margin: auto;"> \
<br/><center><form action="bsas/biometricupdate.php" method="GET" target="_blank"> \
<input type="hidden" name="sessionid" value="<? echo $sessionid ; ?>">\
<br/><select id="cat" name="cat"> \
 <option value=""> -Select Cat. -</option> \
  <option value="staff">Staff</option> \
 <option value="student">Student</option>\
 </select> \
<br/>Staff ID/Student Reg. #:<input type="text"   name="id" value="">\
<br/><input type="submit"  name="submit" value="Update fingerprint">\
</form>\
</center></div>';
}
//---------------------------------------------------------------
if (action=='updateclass') 
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"> \
<u>Create/Update Class</u> <button  onclick="x()">X</button> \
<br/><table><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
Class ID/Code: <br/> <input type="text" id="stdid" value=""  name="stdid" style="width:50px;" > \
<br/>Class:<br/><input type="text" id="std" value=""  name="std" style="width:50px;" > \
<br/>Action:<br/><select id="update" name="update"> \
 <option value="Add">Add</option> \
 <option value="Up">Update</option>\
 <option value="Del">Delete</option> \
 </select> \
<br/><button onclick="DoUpdateClass()">Update</button> </td></td></tr></table> \
</div>';
}
//---------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------
if (action=='updatesubject') 
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"> \
<u>Create/Update Subject</u> <button  onclick="x()">X</button> \
<br/><table><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/>Subject ID/Code:<br/><input type="text" id="subid" value=""  name="subid" style="width:150px;" > \
<br/>Subject Name:<br/><input type="text" id="subject" value=""  name="subject" style="width:150px;" > \
<br/>Class:<br/><? echo ClassDropDownList($schoolid); ?> \
<br/>Action:<br/><select id="update" name="update"> \
 <option value="Add">Add</option> \
 <option value="Up">Update</option>\
 <option value="Del">Delete</option> \
 </select> \
<br/><button onclick="DoUpdateSubject()">Update</button> </td></td></tr></table> \
</div>';
}
//----------------------------------------------------------------
if (action=='updateattendance') 
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;""> \
<u>Update Attendance</u> <button  onclick="x()">X</button> \
<br/><table><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/> Date(YYYY-MM-DD) :<br/> <input type="date" id="dates" value="<? echo @date("Y-m-d") ; ?>"  name="dates" cols="200"> \
<br/>Class:<br/><? echo ClassDropDownListStd($schoolid); ?>\
<br/>Section:<br/><select id="section" name="section"> \
 <option value="">None</option>\
 <option value="A">Section-A</option> \
  <option value="B">Section-B</option> \
<option value="C">Section-C</option> \
 </select> \
<br/>Attendance:<br/><select id="attendance" name="attendance"> \
 <option value="A">Absent</option>\
 <option value="P">Present</option> \
 </select> \
<br/>Roll No(s). Multiple Roll Nos. can be entered separated by comma(s)<br/>\
(e.g. <? echo rand(0,50).",".rand(51,100) ; ?>):<br/><input type="text" id="roll" value=""  name="roll" style="width:300px;" > \
<br/>\
Remarks<br/><input type="text" id="remarks" value=""  name="remarks" style="width:300px;" > \
<br/><button onclick="DoUpdateAttendance()">Update</button> </td></td></tr></table> \
</div>';
}
//----------------------------------------------------------------
if (action=='viewrevenue')
{
document.getElementById("info").innerHTML='<span style="margin:auto;"> \
<u>Periodical Revenue</u> <button  onclick="x()">X</button> \
<table style="background-color:yellow;border-radius: 10px;padding: 10px;"><tr><td>\
Revenue from :<? echo RevenueAllocationWithAll($schoolid) ; ?>\
<br/><input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/> Year :<input type="text" id="year" value="<? echo @date("Y") ; ?>"  name="year" cols="200"> \
<br/>Month<select id="month" name="month"> \
 <option value="all">ALL</option>\
 <option value="01">JAN</option> \
  <option value="02">FEB</option> \
  <option value="03">MAR</option> \
   <option value="04">APR</option> \
   <option value="05">MAY</option> \
    <option value="06">JUN</option> \
    <option value="07">JUL</option> \
   <option value="08">AUG</option> \
  <option value="09">SEP</option> \
   <option value="10">OCT</option> \
  <option value="11">NOV</option> \
   <option value="12">DEC</option> \
 </select> \
<br/>\
<button id="frm" onclick="ViewRevenue()">Revenue</button> </td></td></tr></table> \
</span>';
}
//-----------------------------------------------------------------

        //--------------------------------------------
if (action=='viewhighlight')
{
document.getElementById("info").innerHTML='<span style="margin:auto;"> \
<u>View record/highlight of student</u> <button  onclick="x()">X</button> \
<table style="background-color:yellow;border-radius: 10px;padding: 10px;"><tr><td>\
<input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/>Reg.No.:<input type="text" id="regno" value=""  name="regno"> \
<br/>\
<button id="frm" onclick="ViewHighlight()">View record/highlight</button> </td></td></tr></table> \
</span>';
}
//------------------------------------------------------------------
if (action=='viewsms')
{
document.getElementById("info").innerHTML='<span style="margin:auto;"> \
<u>View SMS Details</u> <button  onclick="x()">X</button> \
<table style="background-color:yellow;border-radius: 10px;padding: 10px;"><tr><td>\
<br/><input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' ">\
<br/>SMS Date :<input type="date" id="date" value=""  name="date"> \
<br/>\
<button id="frm" onclick="ViewSMS()">SMS Details</button> </td></td></tr></table> \
</span>';
}
//------------------------------------------------------------------
if (action=="viewstudentdetails")
{
document.getElementById("info").innerHTML='<p  style="float:center;margin:auto;"> \
Know the student<button  onclick="x()">X</button>\
<table style="background-color:yellow;border-radius: 10px;padding: 10px;"><tr><td style="text-align:right;">\
<br/>Reg.No.:<input type="text" id="regno" value="" name="regno" >\
<hr/>\
<br/>Session:<input type="text" id="session" value="<? $yyyy=@date("Y") ; $yy=@date("y") ; $next=$yy+1; echo "$yyyy-$next"; ?>" name="session" cols="200" > \
<br/>Class:<? echo ClassDropDownListStd($schoolid) ;?>\
<br/>Section:<select id="section" name="section"> \
 <option value="">None</option>\
 <option value="A">Section-A</option> \
  <option value="B">Section-B</option> \
<option value="C">Section-C</option> \
 </select> \
<br/>\
<br/>Roll:<input type="text" id="roll" value="" name="roll" > \
<br/><button id="creating" onclick="ViewStudentDetails()">Know the student </button> \
</td></tr></table>\
</p>';
}
/*----------------------------------------------------------------*/

if (action=='admission')
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 650px;"> \
<u>Admission of Student</u> <button  onclick="x()">X</button> \
<br/><table><tr><td>\
<form action="action.php"  name="admissionform" id="admissionform" enctype="multipart/form-data" method="post" style="text-align: right;">\
<br/> Registration Number:<input type="text" id="regno" value="" name="regno" cols="200"  onchange="updateRegNo()"> \
<br/><input type="hidden" id="sessionid" name="sessionid" value="' + sessionid + ' "> <input type="hidden" name="action" value="admission">\
<br/> Session :<input type="text" id="session" value="<? $yyyy=@date("Y") ; $yy=@date("y") ; $next=$yy+1; echo "$yyyy-$next"; ?>"  name="session" cols="200"> \
<br/>Class: <? echo ClassDropDownListStd($schoolid) ; ?> \
<br/>Section:<select id="section" name="section"> \
 <option value="">None</option>\
 <option value="A">Section-A</option> \
  <option value="B">Section-B</option> \
<option value="C">Section-C</option> \
 </select> \
<br/> Roll No.(Leave it blank, if not yet enrolled.) :<input type="text" id="roll" value="" name="session" cols="roll"> \
<br/>\
</form><button id="frm" onclick="FormSubmitAdmission()">Submit for admision</button> </td><td valing="top">###STUDENT DETAILS###<br/><b id="studentdata" > Details of student will be shown here </b><br/></td></tr></table> \
</p>';
}
/*------------------------------------------*/
if (action=='frmUpdateRegistration')
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"> \
<u>Update registration data of Student</u> <button  onclick="x()">X</button> \
<br/>Reg.No.:<input type="text" id="regno" value="" name="regno" cols="200" > \
<br/><button id="registration" onclick="GetUpdateRegistration()">Get Data for update</button> \
</div>';
}
//--------------------------------------------
if (action=="frmUpdateMobileOfStudent")
{
document.getElementById("info").innerHTML='<p  style="float:center;margin:auto;"> \
Update Mobile No. of the student<button  onclick="x()">X</button>\
<table style="background-color:yellow;border-radius: 10px;padding: 10px;"><tr><td style="text-align:right;">\
<br/>Reg.No.:<input type="text" id="regno" value="" name="regno" >\
<hr/>\
<br/>Session:<input type="text" id="session" value="<? $yyyy=@date("Y") ; $yy=@date("y") ; $next=$yy+1; echo "$yyyy-$next"; ?>" name="session" cols="200" > \
<br/>Class:<? echo ClassDropDownListStd($schoolid) ;?>\
<br/>Section:<select id="section" name="section"> \
 <option value="">None</option>\
 <option value="A">Section-A</option> \
  <option value="B">Section-B</option> \
<option value="C">Section-C</option> \
 </select> \
<br/>\
<br/>Roll:<input type="text" id="roll" value="" name="roll" > \
<br/><button id="creating" onclick="getUpdateMobileOfStudent()">Update Mobile# of student\'s</button> \
</td></tr></table>\
</p>';
}
//--------------------------------------------------
if (action=='register')
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"> \
<u>Registration of Student</u> <button  onclick="x()">X</button> \
<table><tr><td style="text-align:right;font-size:13px;">\
<br/>Entry Year/Session:<input type="text" id="session" value="<? $yyyy=@date("Y") ; $yy=@date("y") ; $next=$yy+1; echo "$yyyy-$next"; ?>"  name="session" cols="200" > \
<br/>Name:<input type="text" id="name" value="" name="name" cols="200" > \
<br/>Father:<input type="text" id="father" value="" name="father" cols="200" > \
<br/>Mother:<input type="text" id="mother" value="" name="mother" cols="200" > \
<br/>Address:<input type="text" id="address" value="" name="address" cols="200" > \
<br/>Mobile No.:<input type="text" id="phone" value="" name="phone" cols="200" > \
<br/>P.O.:<input type="text" id="po" value="" name="po" cols="200" > \
<br/>P.S.:<input type="text" id="ps" value="" name="ps" cols="200" > \
<br/><select id="district" name="district"> \
 <option value="">Select district</option>\
<option value="Bisnupur">Bisnupur</option> \
<option value="Chandel">Chandel</option> \
<option value="Churachandpur">Churachandpur</option> \
 <option value="Imphal(E)">Imphal East</option> \
<option value="Imphal(W)">Imphal West</option> \
<option value="Senapati">Senapati</option> \
<option value="Tamenglong">Tamenglong</option> \
<option value="Thoubal">Thoubal</option> \
<option value="Ukhrul">Ukhrul</option> \
 </select> \
<br/>State:<input type="text" id="state" value="Manipur" name="state" cols="200" > \
<br/>Country:<input type="text" id="country" value="India" name="country" cols="200" > \
<br/>Postal Pin Code:<input type="text" id="pin" value="" name="pin" cols="200" > \
<br/>D.O.B.:<input type="date" id="dob" value="" name="dob" cols="200" > \
<br/><select id="sex" name="sex"> \
 <option value="">Select Gender</option>\
 <option value="female">Female</option> \
<option value="male">Male</option> \
 </select> \
<br/>Nationality:<input type="text" id="nationality" value="Indian" name="nationality" cols="200" > \
<br/>Religion:<input type="text" id="religion" value="" name="religion" cols="200" > \
<br/>Caste:<input type="text" id="caste" value="" name="caste" cols="200" > \
<br/>Occupation of Parent:<input type="text" id="occupationofparent" value="" name="occupationofparent" cols="200" > \
<br/>Income of Parent(yearly):<input type="text" id="incomeofparent" value="" name="incomeofparent" cols="200" > \
<br/>Remarks: <input type="text" id="remarks" value="" name="remarks" cols="200" > \
<br/><button id="registration" onclick="Registration()">Register</button></td></tr></table>\
</div>';
}
/*------------------------------------------*/
if (action=="payfee")
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 650px;"> \
<u>Entry of Payment</u> <button  onclick="x()">X</button> \
<table><tr><td>\
<form name="payfe" style="width: 450px; align-content:center; text-align: right;">\
<br/>Allocation : <? echo RevenueAllocation($schoolid); ?>\
<br/>Class: <? echo ClassDropDownListStd($schoolid) ; ?> \
<br/>Section:<select id="section" name="section"> \
 <option value="">None</option>\
 <option value="A">Section-A</option> \
  <option value="B">Section-B</option> \
<option value="C">Section-C</option> \
 </select> \
<br/>Session:<input type="text" id="session" value="<? $yyyy=@date("Y") ; $yy=@date("y") ; $next=$yy+1; echo "$yyyy-$next"; ?>" name="session" cols="200" > \
<br/>Roll/Reg. No.:<input type="text" id="roll" value="" name="roll" cols="200" onchange="getStudentData(\'name\')" > \
<br/>Name:<input type="text" id="name" value="" name="name" cols="200" > \
<br/>Amount(Rs.): <input type="text" id="amount" value="" name="amount" cols="200" onchange="writeText()" > \
<br/><b id="wordamount"> </b>\
<br/>Select month(s) ,if it links with Montly/Tuition fee:\
<br/>\
<input id="mnts1" type="checkbox" name="mnt" value="JAN">JAN\
<input id="mnts2" type="checkbox" name="mnt" value="FEB">FEB\
<input id="mnts3" type="checkbox" name="mnt" value="MAR">MAR\
<input id="mnts4" type="checkbox" name="mnt" value="APR">APR\
<input id="mnts5" type="checkbox" name="mnt" value="MAY">MAY\
<input id="mnts6" type="checkbox" name="mnt" value="JUN">JUN\
<br/>\
<input id="mnts7" type="checkbox" name="mnt" value="JUL">JUL\
<input id="mnts8" type="checkbox" name="mnt" value="AUG">AUG\
<input id="mnts9" type="checkbox" name="mnt" value="SEP">SEP\
<input id="mnts10" type="checkbox" name="mnt" value="OCT">OCT\
<input id="mnts11" type="checkbox" name="mnt" value="NOV">NOV\
<input id="mnts12" type="checkbox" name="mnt" value="DEC">DEC\
<br/>Remarks: <input type="text" id="remarks" value="" name="remarks" cols="200" > \
</form>\
<br/><button id="payfees" onclick="PayFee()">Enter payment</button></td> \
<td style="width:150px;"><b id="lastfees" valign="top">Last payments will be shown here</b></td></tr></table>\
</p>';
}
////--------------------------------------
if (action=="paymisc")
{
document.getElementById("info").innerHTML='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 650px;"> \
<u>Payment for non-registered student/payer </u> <button  onclick="x()">X</button> \
<table><tr><td>\
<form name="payfe" style="width: 450px; align-content:center; text-align: right;">\
<br/>Allocation : <? echo RevenueAllocation($schoolid); ?>\
<br/>Class: <? echo ClassDropDownListStd($schoolid) ; ?> \
<br/>Section:<select id="section" name="section"> \
 <option value="">None</option>\
 <option value="A">Section-A</option> \
  <option value="B">Section-B</option> \
<option value="C">Section-C</option> \
 </select> \
<br/>Session:<input type="text" id="session" value="<? $yyyy=@date("Y") ; $yy=@date("y") ; $next=$yy+1; echo "$yyyy-$next"; ?>" name="session" cols="200"> \
<br/>Name:<input type="text" id="name" value="" name="name" cols="200" > \
<br/>Amount(Rs.): <input type="text" id="amount" value="" name="amount" cols="200" onchange="writeText()" > \
<br/><b id="wordamount"> </b>\
<br/>Remarks: <input type="text" id="remarks" value="" name="remarks" cols="200" > \
</form>\
<br/><button id="payfees" onclick="PayMisc()">Enter payment</button></td> \
<td style="width:150px;"><b id="lastfees" valign="top">Honesty is best policy</b></td></tr></table>\
</div>';
}
}
//////////------------------------------------------------

////////////////////////////////////////////////
//------All functions are given below--------- //
////////////////////////////////////////////////
function UpdateRegistration()
{
var regno,session,name,father,mother,address,phone,po,ps,district,state,country,dob,sex,nationality,religion,caste,occupationofparent,incomeofparent,remarks,pin ;
session=document.getElementById("session").value;
regno=document.getElementById("regno").value;
name=document.getElementById("name").value;
father=document.getElementById("father").value;
mother=document.getElementById("mother").value;
phone=document.getElementById("phone").value;
address=document.getElementById("address").value;
po=document.getElementById("po").value;
ps=document.getElementById("ps").value;
district=document.getElementById("district").value;
state=document.getElementById("state").value;
country=document.getElementById("country").value;
dob=document.getElementById("dob").value;
sex=document.getElementById("sex").value;
nationality=document.getElementById("nationality").value;
religion=document.getElementById("religion").value;
caste=document.getElementById("caste").value;
occupationofparent=document.getElementById("occupationofparent").value;
remarks=document.getElementById("remarks").value;
pin=document.getElementById("pin").value;
incomeofparent=document.getElementById("incomeofparent").value;
if (name==='') 
{
alert( "Name can not be blank.");
return ;
}
if (father==='') 
{
alert( "Father can not be blank.");
return ;
}
if (mother==='') 
{
alert( "Mother can not be blank.");
return ;
}
if (address==='')  
{
alert( "Address can not be blank.");
return ;
}
if (phone==='')  
{
alert( "Phone can not be blank.");
return ;
}
if (po==='')  
{
alert( "P.O. can not be blank.");
return ;
}
if (ps==='')  
{
alert( "P.S. can not be blank.");
return ;
}

if (district==='')  
{
alert( "District can not be blank.");
return ;
}
if (state==='')  
{
alert( "State can not be blank.");
return ;
}
if (country==='')  
{
alert( "Country can not be blank.");
return ;
}

if (dob==='')  
{
alert( "D.O.B. can not be blank.");
return ;
}
if (sex==='')  
{
alert( "Sex can not be blank.");
return ;
}
if (nationality==='')  
{
alert( "state can not be blank.");
return ;
}
if (religion==='')  
{
alert( "Religion can not be blank.");
return ;
}
if (caste==='')  
{
alert( "Caste can not be blank.");
return ;
}
if (occupationofparent==='')  
{
alert( "Occupation of parent can not be blank.");
return ;
}

if (pin==='')  
{
alert( "Pin can not be blank.");
return ;
}
if (incomeofparent==='')  
{
alert( "Income of parent can not be blank.");
return ;
}

myurl="action.php?sessionid="+sessionid+"&action=updateregistration&session="+session+"&name="+name+"&father="+father+"&mother="+mother+"&phone="+phone+"&address="+address;
myurl=myurl+"&po="+po+"&ps="+ps+"&district="+district+"&state="+state+"&country="+country+"&dob="+dob+"&sex="+sex+"&nationality="+nationality;
myurl=myurl+"&religion="+religion+"&caste="+caste+"&occupationofparent="+occupationofparent+"&incomeofparent="+incomeofparent+"&remarks="+remarks+"&pin="+pin+"&regno="+regno;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );
}
//---------------------------------------------
function GetUpdateRegistration()
{
var regno=document.getElementById("regno").value;
myurl="action.php?sessionid="+sessionid+"&action=getRegistrationForm&regno="+regno;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );
}
//--------------------------
function UpdateStudentMobileNo()
{
var regno=document.getElementById("regno").value;
var phone=document.getElementById("phone").value;
myurl="action.php?sessionid="+sessionid+"&action=updateMobile&regno="+regno+"&phone="+phone;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );
}
//-------------------------
function getUpdateMobileOfStudent()//for getting update form
{
var regno=document.getElementById("regno").value;
myurl="action.php?sessionid="+sessionid+"&action=getMobileUpdateForm&regno="+regno;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );
}
//-------------------------------------------------------
function DelAdmission()
{
var regno=document.getElementById("regno").value;
var session=document.getElementById("session").value;
myurl="action.php?sessionid="+sessionid+"&action=deleteadmission&regno="+regno+"&session="+session;
document.getElementById("info").innerHTML="<img src='status.gif' width='30px' height='30px'> <br/>Deleting data....";
doWebAction( );
}
//----------------------------------------
function DelFee()
{
var transid=document.getElementById("transid").value;
myurl="action.php?sessionid="+sessionid+"&action=deletefee&transid="+transid;
document.getElementById("info").innerHTML="<img src='status.gif' width='30px' height='30px'> <br/>Deleting data....";
doWebAction( );
}
//----------------------------------
function DelTable()
{
var table=document.getElementById("table").value;
myurl="action.php?sessionid="+sessionid+"&action=deletedata&table="+table;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );
}
//--------------------------------------------
function SendSMS(smsto)
{
myurl="comwithkulhu.php?sessionid="+sessionid+"&schoolid="+clientid+"&schoolkey="+schoolkey ;

if (smsto=='directmobile')
{
var mobiles=document.getElementById("mobile").value;
var sms=document.getElementById("sms").value;
myurl=myurl+"&action=sendsmstodirectmobile&"+"&mobile="+mobiles+"&sms="+sms;
document.getElementById("info").innerHTML="<div class='loader'></div>" ;
doWebAction( );
}

if (smsto=='roll')
{
var year=document.getElementById("year").value;
var std=document.getElementById("std").value;
var section=document.getElementById("section").value;
var roll=document.getElementById("roll").value;
var sms=document.getElementById("sms").value;
myurl=myurl+"&action=sendsmstoroll&year="+year+"&section="+section+"&std="+std+"&roll="+roll+"&sms="+sms;
document.getElementById("info").innerHTML="<div class='loader'></div>" ;
doWebAction( );
}


if (smsto=='parent')
{
var regno=document.getElementById("regno").value;
var sms=document.getElementById("sms").value;
myurl=myurl+"&action=sendsmstoparent&regno="+regno+"&sms="+sms;
document.getElementById("info").innerHTML="<div class='loader'></div>" ;
doWebAction( );
}
if (smsto=='parents')
{
var session=document.getElementById("session").value;
var smsto=document.getElementById("smsto").value;
var sms=document.getElementById("sms").value;
myurl=myurl+"&action=sendsmstoparents&"+"&smsto="+smsto+"&sms="+sms+"&year="+session;
document.getElementById("info").innerHTML="<div class='loader'></div>" ;
doWebAction( );
}
if (smsto=='stdparents')
{
var session=document.getElementById("session").value;
var std=document.getElementById("std").value;
var sms=document.getElementById("sms").value;
myurl=myurl+"&action=sendsmstostdparents&"+"&std="+std+"&sms="+sms+"&year="+session;
document.getElementById("info").innerHTML="<div class='loader'></div>" ;
doWebAction( );
}
if (smsto=='staffs')
{
var smsto=document.getElementById("smsto").value;
var sms=document.getElementById("sms").value;
myurl=myurl+"&action=sendsmsstaffs&smsto="+smsto+"&sms="+sms;
document.getElementById("info").innerHTML="<div class='loader'></div>" ;
doWebAction( );
}
}
//---------------------------------------
function DoUpdateStaff()
{
var staffid=document.getElementById("staffid").value;
var staffname=document.getElementById("staffname").value;
var mobile=document.getElementById("mobile").value;
var designation=document.getElementById("designation").value;
var remarks=document.getElementById("remarks").value;
var update=document.getElementById("update").value;
myurl="action.php?sessionid="+sessionid+"&action=updatestaff&staffid="+staffid+"&staffname="+staffname+"&mobile="+mobile+"&designation="+designation+"&remarks="+remarks+"&update="+update;
document.getElementById("info").innerHTML='<div class="loader"></div>';
doWebAction( );
}

//---------------------------------------
function DoUpdateMark()
{
var examid=document.getElementById("examid").value;
var session=document.getElementById("session").value;
var subjectid=document.getElementById("subjectid").value;
var section=document.getElementById("section").value;
var roll=document.getElementById("roll").value;
var mark=document.getElementById("mark").value;
var maxmark=document.getElementById("maxmark").value;
var update=document.getElementById("update").value;
myurl="action.php?sessionid="+sessionid+"&action=updatemark&examid="+examid+"&subid="+subjectid+"&section="+section+"&roll="+roll+"&mark="+mark+"&maxmark="+maxmark+"&session="+session+"&update="+update;
document.getElementById("info").innerHTML="<div class='loader' ></div>";
doWebAction( );
}

function DoUpdateHighlight()
{
var regno=document.getElementById("regno").value;
var roll=document.getElementById("roll").value;
//---roll as record no.----------
var remarks=document.getElementById("remarks").value;
var update=document.getElementById("update").value;
myurl="action.php?sessionid="+sessionid+"&action=updatehighlight&regno="+regno+"&recordno="+roll+"&highlight="+remarks+"&update="+update;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );
}

function DoUpdateWithheld()
{
var regno=document.getElementById("regno").value;
var remarks=document.getElementById("remarks").value;
var update=document.getElementById("update").value;
myurl="action.php?sessionid="+sessionid+"&action=updatewithheld&regno="+regno+"&remarks="+remarks+"&update="+update;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );
}

function DoUpdateExam()
{
var examid=document.getElementById("examid").value;
var exam=document.getElementById("exam").value;
var update=document.getElementById("update").value;
myurl="action.php?sessionid="+sessionid+"&action=updateexam&examid="+examid+"&exam="+exam+"&update="+update;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );
}

function DoUpdateAllocation()
{
var allocationid=document.getElementById("allocationid").value;
var allocation=document.getElementById("allocation").value;
var update=document.getElementById("update").value;
myurl="action.php?sessionid="+sessionid+"&action=updateallocation&allocationid="+allocationid+"&allocation="+allocation+"&update="+update;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );
}

function DoUpdateSubject()
{
var stdid=document.getElementById("stdid").value;
var subid=document.getElementById("subid").value;
var subject=document.getElementById("subject").value;
var update=document.getElementById("update").value;
myurl="action.php?sessionid="+sessionid+"&action=updatesubject&subid="+subid+"&stdid="+stdid+"&subject="+subject+"&update="+update;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );
}
//----------------------------------------------
function DoUpdateClass()
{
var stdid=document.getElementById("stdid").value;
var std=document.getElementById("std").value;
var update=document.getElementById("update").value;
myurl="action.php?sessionid="+sessionid+"&action=updatestd&std="+std+"&stdid="+stdid+"&update="+update;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );
}
//----------------------------------------------
function DoUpdateAttendance()
{
var dates=document.getElementById("dates").value;
var std=document.getElementById("std").value;
var section=document.getElementById("section").value;
var roll=document.getElementById("roll").value;
var attendance=document.getElementById("attendance").value;
var remarks=document.getElementById("remarks").value;
myurl="action.php?sessionid="+sessionid+"&action=updateattendance&std="+std+"&section="+section+"&date="+dates+"&roll="+roll+"&attendance="+attendance+"&remarks="+remarks;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );
}
//----------------------------------------------
function ViewFeePayment()
{
var session=document.getElementById("year").value;
var std=document.getElementById("std").value;
var section=document.getElementById("section").value;
var roll=document.getElementById("roll").value;
myurl="action.php?sessionid="+sessionid+"&action=viewfeepayment&std="+std+"&section="+section+"&session="+session+"&roll="+roll;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );
}
//-------------------------------------------------------
function ViewFeePaymentDate()
{
var allocation=document.getElementById("allocation").value;

var date=document.getElementById("date").value;
var date1=document.getElementById("date1").value;
var session=document.getElementById("year").value;
var std=document.getElementById("std").value;
var section=document.getElementById("section").value;
var roll=document.getElementById("roll").value;
myurl="action.php?sessionid="+sessionid+"&action=viewfeepaymentdate&date="+date+"&date1="+date1+"&std="+std+"&section="+section+"&year="+session+"&roll="+roll+"&allocation="+allocation;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );
}
//----------------------------------------------
function ViewStudentDetails()
{
regno=document.getElementById("regno").value;
var session=document.getElementById("session").value;
var std=document.getElementById("std").value;
var section=document.getElementById("section").value;
var roll=document.getElementById("roll").value;
myurl="action.php?sessionid="+sessionid+"&action=studentfulldetails&regno="+regno+"&std="+std+"&section="+section+"&session="+session+"&roll="+roll;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );
}
//-------------------------------------------------------
function ViewHighlight() 
{
var regno=document.getElementById("regno").value;
myurl="action.php?sessionid="+sessionid+"&action=viewhighlight&regno="+regno ;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );

}
//-------------------------------------------------------
function ViewSMS() 
{
var date=document.getElementById("date").value;
myurl="action.php?sessionid="+sessionid+"&action=viewsms&date="+date ;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );

}
//-------------------------------------------------------
function ViewRevenue() 
{
var month=document.getElementById("month").value;
var allocation=document.getElementById("allocation").value;
var year=document.getElementById("year").value;
myurl="action.php?sessionid="+sessionid+"&action=revenue&year="+year+"&month="+month+"&allocation="+allocation;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );

}

function DisplayPhoto()
{
var photo=document.getElementById("file").value;
document.getElementById("photo").innerHTML="<img src='"+photo+"' width='100px' height='150px'>";
}
/*------------------------------------------*/
function updateRegNo()
{
regno=document.getElementById("regno").value;
myurl="action.php?sessionid="+sessionid+"&action=studentdetails4admission&regno="+regno ;
document.getElementById("studentdata").innerHTML="Retrieving student data...";
doWebActionForId("studentdata");
}
/*--------------------------------------------------------------*/
function updateStudentMobile()
{
regno=document.getElementById("regno").value;
var session=document.getElementById("session").value;
var std=document.getElementById("std").value;
var section=document.getElementById("section").value;
var roll=document.getElementById("roll").value;
myurl="action.php?sessionid="+sessionid+"&action=getform4studentmobile&regno="+regno+"&std="+std+"&section="+section+"&session="+session+"&roll="+roll;
document.getElementById("info").innerHTML="<div class='loader'></div>";
doWebAction( );
}
//------------------------------------------------
function Registration()
{
var session,name,father,mother,address,phone,po,ps,district,state,country,dob,sex,nationality,religion,caste,occupationofparent,incomeofparent,remarks,pin ;
session=document.getElementById("session").value;
name=document.getElementById("name").value;
father=document.getElementById("father").value;
mother=document.getElementById("mother").value;
phone=document.getElementById("phone").value;
address=document.getElementById("address").value;
po=document.getElementById("po").value;
ps=document.getElementById("ps").value;
district=document.getElementById("district").value;
state=document.getElementById("state").value;
country=document.getElementById("country").value;
dob=document.getElementById("dob").value;
sex=document.getElementById("sex").value;
nationality=document.getElementById("nationality").value;
religion=document.getElementById("religion").value;
caste=document.getElementById("caste").value;
occupationofparent=document.getElementById("occupationofparent").value;
remarks=document.getElementById("remarks").value;
pin=document.getElementById("pin").value;
incomeofparent=document.getElementById("incomeofparent").value;
if (name==='') 
{
alert( "Name can not be blank.");
return ;
}
if (father==='') 
{
alert( "Father can not be blank.");
return ;
}
if (mother==='') 
{
alert( "Mother can not be blank.");
return ;
}
if (address==='')  
{
alert( "Address can not be blank.");
return ;
}
if (phone==='')  
{
alert( "Phone can not be blank.");
return ;
}
if (po==='')  
{
alert( "P.O. can not be blank.");
return ;
}
if (ps==='')  
{
alert( "P.S. can not be blank.");
return ;
}

if (district==='')  
{
alert( "District can not be blank.");
return ;
}
if (state==='')  
{
alert( "State can not be blank.");
return ;
}
if (country==='')  
{
alert( "Country can not be blank.");
return ;
}

if (dob==='')  
{
alert( "D.O.B. can not be blank.");
return ;
}
if (sex==='')  
{
alert( "Sex can not be blank.");
return ;
}
if (nationality==='')  
{
alert( "state can not be blank.");
return ;
}
if (religion==='')  
{
alert( "Religion can not be blank.");
return ;
}
if (caste==='')  
{
alert( "Caste can not be blank.");
return ;
}
if (occupationofparent==='')  
{
alert( "Occupation of parent can not be blank.");
return ;
}

if (pin==='')  
{
alert( "Pin can not be blank.");
return ;
}
if (incomeofparent==='')  
{
alert( "Income of parent can not be blank.");
return ;
}

myurl="action.php?sessionid="+sessionid+"&action=registration&session="+session+"&name="+name+"&father="+father+"&mother="+mother+"&phone="+phone+"&address="+address;
myurl=myurl+"&po="+po+"&ps="+ps+"&district="+district+"&state="+state+"&country="+country+"&dob="+dob+"&sex="+sex+"&nationality="+nationality;
myurl=myurl+"&religion="+religion+"&caste="+caste+"&occupationofparent="+occupationofparent+"&incomeofparent="+incomeofparent+"&remarks="+remarks+"&pin="+pin;
document.getElementById("info").innerHTML="<img src='status.gif' width='30px' height='30px'> <br/> Wait registering...";
doWebAction( );
}
/*---------------------------------------------*/
function getStudentData(getwhat)
{
var name="", std="",section="",roll="",amount="",allocation="",remarks=""; 
session=document.getElementById("session").value;
std=document.getElementById("std").value;
section=document.getElementById("section").value;
roll=document.getElementById("roll").value;
myurl="action.php?sessionid="+sessionid+"&action=studentdetails&std="+std+"&section="+section+"&session="+session+"&roll="+roll+"&getwhat="+getwhat +"&regno="+regno;
document.getElementById("name").value="";
document.getElementById("lastfees").innerHTML="Retrieving name";
doWebActionFillFormField( "name");
getFeeDue();
}
/*---------------------------*/
function getFeeDue()
{
var name="", std="",section="",roll="",amount="",allocation="",remarks=""; 
session=document.getElementById("session").value;
std=document.getElementById("std").value;
section=document.getElementById("section").value;
roll=document.getElementById("roll").value;
myurl="action.php?sessionid="+sessionid+"&action=getfeedue&std="+std+"&section="+section+"&session="+session+"&roll="+roll;
document.getElementById("lastfees").innerHTML="Retrieving payment data...";
doWebActionForId("lastfees");
}
/*---------------------------*/

 function writeText()
{
var amount=document.getElementById("amount").value;
document.getElementById("wordamount").innerHTML="Rupees "+toWords(amount);
}
/*--------------------------*/
function PayMisc()
{
var name="", std="",section="",roll="",amount="",allocation="",remarks="",sessn="";
name=document.getElementById("name").value;
std=document.getElementById("std").value;
section=document.getElementById("section").value;
amount=document.getElementById("amount").value;
allocation=document.getElementById("allocation").value;
sessn=document.getElementById("session").value;
remarks=document.getElementById("remarks").value;

if (name==='') 
{
alert( "Name can not be blank.");
return ;
}
if (std==='') 
{
alert("Class can not be blank.");
return ;
}

if (allocation==='') 
{
alert("Payment for' can not be blank.") ;
return ;
}

if (amount==='') 
{
alert("Amount can not be blank.") ;
return ;
}

document.getElementById("info").innerHTML="<div class='loader'></div>";
myurl="action.php?sessionid="+sessionid+"&action=paymisc&name="+name+"&std="+std+"&section="+section+"&amount="+amount+"&allocation="+allocation+"&remarks="+remarks+"&session="+sessn;
doWebAction( );
name="";
std="";
sessn="";
section="";
roll="";
amount="";
allocation="";
remarks="";
}
//----------------------------
function PayFee()
{
var name="", std="",section="",roll="",amount="",allocation="",remarks="",sessn="",month="";

if (document.getElementById("mnts1").checked==true) month=month +"JAN" +";" ;
if (document.getElementById("mnts2").checked==true) month=month +"FEB" +";" ;
if (document.getElementById("mnts3").checked==true) month=month +"MAR" +";" ;
if (document.getElementById("mnts4").checked==true) month=month +"APR" +";" ;
if (document.getElementById("mnts5").checked==true) month=month +"MAY" +";" ;
if (document.getElementById("mnts6").checked==true) month=month +"JUN" +";" ;
if (document.getElementById("mnts7").checked==true) month=month +"JUL" +";" ;
if (document.getElementById("mnts8").checked==true) month=month +"AUG" +";" ;
if (document.getElementById("mnts9").checked==true) month=month +"SEP" +";" ;
if (document.getElementById("mnts10").checked==true) month=month +"OCT" +";" ;
if (document.getElementById("mnts11").checked==true) month=month +"NOV" +";" ;
if (document.getElementById("mnts12").checked==true) month=month +"DEC" +";" ;

name=document.getElementById("name").value;
std=document.getElementById("std").value;
section=document.getElementById("section").value;
roll=document.getElementById("roll").value;
amount=document.getElementById("amount").value;
allocation=document.getElementById("allocation").value;
sessn=document.getElementById("session").value;
remarks=document.getElementById("remarks").value;

if (name==='') 
{
alert( "Name can not be blank.");
return ;
}
if (std==='') 
{
alert("Class can not be blank.");
return ;
}

if (allocation==='') 
{
alert("Payment for' can not be blank.") ;
return ;
}

if (amount==='') 
{
alert("Amount can not be blank.") ;
return ;
}

document.getElementById("info").innerHTML="<div class='loader'></div>";
myurl="action.php?sessionid="+sessionid+"&action=payfee&name="+name+"&std="+std+"&section="+section+"&roll="+roll+"&amount="+amount+"&allocation="+allocation+"&remarks="+remarks+"&session="+sessn +"&month="+month;
doWebAction( );
name="";
std="";
sessn="";
section="";
roll="";
amount="";
allocation="";
remarks="";
}
/*--------------------------------------------*/
function ChangeMasterPassword()
{
var pwd1,pwd2;
pwd1=document.getElementById("pwd1").value;
pwd2=document.getElementById("pwd2").value;
myurl="action.php?sessionid="+sessionid+"&action=changemasterpassword&pwd1="+pwd1+"&pwd2="+pwd2 ;
document.getElementById("info").innerHTML="<img src='status.gif' width='30px' height='30px'><br/> Wait processing...";
doWebAction( );
pwd1="";
pwd2="";
}
//-----------------------

function ChangePassword()
{
name=document.getElementById("pwd1").value;
role=document.getElementById("pwd2").value;
if (name==='') 
{
document.getElementById("info").innerHTML='<p  style="float:center; background-color:yellow;">Warning !<button  onclick="x()">X</button> </br>' + "Password can not be blank."+  '</p>';
return ;
}

if (role==='') 
{
document.getElementById("info").innerHTML='<p  style="float:center; background-color:yellow;">Warning !<button  onclick="x()">X</button> </br>' + "Password can not be blank."+  '</p>';
return ;
}

if (role!=name) 
{
document.getElementById("info").innerHTML='<p  style="float:center; background-color:yellow;">Warning !<button  onclick="x()">X</button> </br>' + "Password mismatched."+  '</p>';
return ;
}

myurl="action.php?sessionid="+sessionid+"&action=changepassword&pwd1="+name+"&pwd2="+role ;
pwd1="";
pwd2="";
role="";
document.getElementById("info").innerHTML="<img src='status.gif' width='30px' height='30px'><br/> Wait processing...";
doWebAction( );

}

function CreateUser()
{
name=document.getElementById("name").value;
role=document.getElementById("role").value;
if (name==='') 
{
document.getElementById("info").innerHTML='<p  style="float:center; background-color:yellow;">Warning !<button  onclick="x()">X</button> </br>' + "Authority and name can not be blank."+  '</p>';
return ;
}

if (role==='') 
{
document.getElementById("info").innerHTML='<p  style="float:center; background-color:yellow;">Warning !<button  onclick="x()">X</button> </br>' + "Authority and name can not be blank."+  '</p>';
return ;
}

myurl="action.php?sessionid="+sessionid+"&action=createuser&name="+name+"&role="+role ;
document.getElementById("info").innerHTML="Wait processing...";
doWebAction( );
}

/*----------------------------------------*/
function x()
{
document.getElementById("info").innerHTML="";
}
function xClose()
{
document.getElementById("instmsg").innerHTML="";
}
/*---------------------------------------------*/
function updateURL(updateurls)
{
myurl=updateurls;
}

function Signout()
{
myurl=updateurls;
}

/*-----------------------------------------------*/
function doWebActionForId(id)
{
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
   if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById(id).innerHTML=xmlhttp.responseText.trim();
     myurl='';
      }
   }
xmlhttp.open("GET",myurl,true);
xmlhttp.send();
}

/*-------------------------------------------*/
function doWebAction( )
{
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
   if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("info").innerHTML=xmlhttp.responseText.trim();
     myurl='';
      }
   }
xmlhttp.open("GET",myurl,true);
xmlhttp.send();
}
/*-------------------------------------------*/

function doWebActionFillFormField( IdToDisplay)
{
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
   if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById(IdToDisplay).value=xmlhttp.responseText.trim();
     myurl='';
      }
   }
xmlhttp.open("GET",myurl,true);
xmlhttp.send();
}

/*----------------------------Number to words----------------------------------------------*/

var th = ['','Thousand','Million', 'Billion','Trillion'];
var dg = ['Zero','One','Two','Three','Four', 'Five','Six','Seven','Eight','Nine'];
var tn = ['Ten','Eleven','Twelve','Thirteen', 'Fourteen','Fifteen','Sixteen', 'Seventeen','Eighteen','Nineteen'];
var tw = ['Twenty','Thirty','Forty','Fifty', 'Sixty','Seventy','Eighty','Ninety'];

function toWords(s) {
    s = s.toString();
    s = s.replace(/[\, ]/g,'');
    if (s != parseFloat(s)) return 'not a number';
    var x = s.indexOf('.');
    if (x == -1)
        x = s.length;
    if (x > 15)
        return 'too big';
    var n = s.split(''); 
    var str = '';
    var sk = 0;
    for (var i=0;   i < x;  i++) {
        if ((x-i)%3==2) { 
            if (n[i] == '1') {
                str += tn[Number(n[i+1])] + ' ';
                i++;
                sk=1;
            } else if (n[i]!=0) {
                str += tw[n[i]-2] + ' ';
                sk=1;
            }
        } else if (n[i]!=0) { // 0235
            str += dg[n[i]] +' ';
            if ((x-i)%3==0) str += 'Hundred ';
            sk=1;
        }
        if ((x-i)%3==1) {
            if (sk)
                str += th[(x-i-1)/3] + ' ';
            sk=0;
        }
    }

    if (x != s.length) {
        var y = s.length;
        str += 'point ';
        for (var i=x+1; i<y; i++)
            str += dg[n[i]] +' ';
    }
    return str.replace(/\s+/g,' ');
}



</script>

</head>
<body style="background-color:green;">
<div id="container" style="padding: 10px; border: 2px solid yellow; border-radius: 10px;width:80%;height:80%;margin:auto;">
<div style="background-color: #b0c4de;padding:5px;" id="projector" >
<center><u><font style="font-size:20px">iSchool | <? echo $schoolname ;?></font></u></center>
<? 
echo "$loginmsg  ";
echo DisplayAsPerRole($role);
?>
<center>
<hr/>
<br/>
<span id="info" style="background-color:yellow;border-radius:">
</span>
</center>
<? echo $msg ; ?>
<input id="sessionid" type="hidden" name="sessionid" value="<? echo "$sessionid"; ?>">
<hr/>
<span id="instmsg" style="background-color:yellow;border-radius: 10px;">
</span>
    <div/>
        <div/>
</body>
</html>

<?
function RevenueAllocation($schoolid)
{
$sql="SELECT * FROM `school_revheads` WHERE `schoolid`='$schoolid' ORDER BY `allocationid` ASC";
Global $dbserver,$dbuser,$dbpwd,$dbname ; 
$con = @mysql_connect("$dbserver","$dbuser","$dbpwd");
if (!$con)
  {
  die('Could not connect');
  }
$db_found=mysql_select_db("$dbname", $con);
if (!$db_found){
echo "Connected but database can not be opened";
}
$result = mysql_query($sql);
$table='<select id="allocation" name="allocation">';
while ($db_field =mysql_fetch_assoc($result))
{
$allocation=$db_field['allocation'];
$allocationid=$db_field['allocationid'];
$table=$table.'<option value="'.$allocationid.'">'.$allocation.'</option>';
}
mysql_close($con);
$table="$table</select>";
return $table;
}
?>

<?
function RevenueAllocationWithAll($schoolid)
{
$sql="SELECT * FROM `school_revheads` WHERE `schoolid`='$schoolid' ORDER BY `allocationid` ASC";
Global $dbserver,$dbuser,$dbpwd,$dbname ; 
$con = @mysql_connect("$dbserver","$dbuser","$dbpwd");
if (!$con)
  {
  die('Could not connect');
  }
$db_found=mysql_select_db("$dbname", $con);
if (!$db_found){
echo "Connected but database can not be opened";
}
$result = mysql_query($sql);
$table='<select id="allocation" name="allocation"><option value="ALL">ALL</option>';
while ($db_field =mysql_fetch_assoc($result))
{
$allocation=$db_field['allocation'];
$allocationid=$db_field['allocationid'];
$table=$table.'<option value="'.$allocationid.'">'.$allocation.'</option>';
}
mysql_close($con);
$table="$table</select>";
return $table;
}
?>

<?
function ClassSubjectWithAll($schoolid)
{
$sql="SELECT * FROM `school_subjects` WHERE `schoolid`='$schoolid' ORDER BY `stdid`,`subject` ASC";
Global $dbserver,$dbuser,$dbpwd,$dbname ; 
$con = @mysql_connect("$dbserver","$dbuser","$dbpwd");
if (!$con)
  {
  die('Could not connect');
  }
$db_found=mysql_select_db("$dbname", $con);
if (!$db_found){
echo "Connected but database can not be opened";
}
$result = mysql_query($sql);
$table='<select id="subjectid" name="subjectid">';
while ($db_field =mysql_fetch_assoc($result))
{
$stdid=$db_field['stdid'];
$subject=$db_field['subject'];
$subjectid=$db_field['subjectid'];
$table=$table.'<option value="'.$subjectid.'">'."Class-$stdid:$subject".'</option>';
}
mysql_close($con);
$table=$table.'<option value="ALL">ALL</option></select>';
return $table;
}
//-----------------------------------------
function ClassSubject($schoolid)
{
$sql="SELECT * FROM `school_subjects` WHERE `schoolid`='$schoolid' ORDER BY `stdid`,`subject` ASC";
Global $dbserver,$dbuser,$dbpwd,$dbname ; 
$con = @mysql_connect("$dbserver","$dbuser","$dbpwd");
if (!$con)
  {
  die('Could not connect');
  }
$db_found=mysql_select_db("$dbname", $con);
if (!$db_found){
echo "Connected but database can not be opened";
}
$result = mysql_query($sql);
$table='<select id="subjectid" name="subjectid">';
while ($db_field =mysql_fetch_assoc($result))
{
$stdid=$db_field['stdid'];
$subject=$db_field['subject'];
$subjectid=$db_field['subjectid'];
$table=$table.'<option value="'.$subjectid.'">'."Class-$stdid:$subject".'</option>';
}
mysql_close($con);
$table="$table</select>";
return $table;
}
?>


<?
function ClassDropDownList($schoolid)
{
$sql="SELECT * FROM `school_stds` WHERE `schoolid`='$schoolid' ORDER BY `stdid` ASC";
Global $dbserver,$dbuser,$dbpwd,$dbname,$role ; 
$con = @mysql_connect("$dbserver","$dbuser","$dbpwd");
if (!$con)
  {
  die('Could not connect');
  }
$db_found=mysql_select_db("$dbname", $con);
if (!$db_found){
echo "Connected but database can not be opened";
}
$result = mysql_query($sql);
$table='<select id="stdid" name="stdid">';
if (($role=='ADMIN') And ($role=='CASHIER'))
{
$table=$table.'<option value="ALL">ALL</option>;';
}
while ($db_field =mysql_fetch_assoc($result))
{
$std=$db_field['std'];
$stdid=$db_field['stdid'];
$table=$table.'<option value="'.$stdid.'">'.$std.'</option>';
}
mysql_close($con);
$table="$table</select>";
return $table;
}
?>

<?
function Exams($schoolid)
{
$sql="SELECT * FROM `school_exam` WHERE `schoolid`='$schoolid' ORDER BY `exam` ASC";
Global $dbserver,$dbuser,$dbpwd,$dbname ; 
$con = @mysql_connect("$dbserver","$dbuser","$dbpwd");
if (!$con)
  {
  die('Could not connect');
  }
$db_found=mysql_select_db("$dbname", $con);
if (!$db_found){
echo "Connected but database can not be opened";
}
$result = mysql_query($sql);
$table='<select id="examid" name="examid">';
while ($db_field =mysql_fetch_assoc($result))
{
$examid=$db_field['examid'];
$exam=$db_field['exam'];
$table=$table.'<option value="'.$examid.'">'.$exam.'</option>';
}
mysql_close($con);
$table="$table</select>";
return $table;
}

function ExamsWithAll($schoolid)
{
$sql="SELECT * FROM `school_exam` WHERE `schoolid`='$schoolid' ORDER BY `exam` ASC";
Global $dbserver,$dbuser,$dbpwd,$dbname ; 
$con = @mysql_connect("$dbserver","$dbuser","$dbpwd");
if (!$con)
  {
  die('Could not connect');
  }
$db_found=mysql_select_db("$dbname", $con);
if (!$db_found){
echo "Connected but database can not be opened";
}
$result = mysql_query($sql);
$table='<select id="examid" name="examid">';
while ($db_field =mysql_fetch_assoc($result))
{
$examid=$db_field['examid'];
$exam=$db_field['exam'];
$table=$table.'<option value="'.$examid.'">'.$exam.'</option>';
}
mysql_close($con);
$table=$table.'<option value="ALL">ALL</option></select>';
return $table;
}

?>

<?
function ClassDropDownListStdWithAll($schoolid)
{

$sql="SELECT * FROM `school_stds` WHERE `schoolid`='$schoolid' ORDER BY `stdid` ASC";
Global $dbserver,$dbuser,$dbpwd,$dbname,$role ; 
$con = @mysql_connect("$dbserver","$dbuser","$dbpwd");
if (!$con)
  {
  die('Could not connect');
  }
$db_found=mysql_select_db("$dbname", $con);
if (!$db_found){
echo "Connected but database can not be opened";
}
$result = mysql_query($sql);
$table='<select id="std" name="std">';
$table=$table.'<option value="ALL">ALL</option>';

while ($db_field =mysql_fetch_assoc($result))
{
$std=$db_field['std'];
$stdid=$db_field['stdid'];
$table=$table.'<option value="'.$stdid.'">'.$std.'</option>';
}
mysql_close($con);

$table="$table</select>";
return $table;
}
?>
<?
function ClassDropDownListStd($schoolid)
{
$sql="SELECT * FROM `school_stds` WHERE `schoolid`='$schoolid' ORDER BY `stdid` ASC";
Global $dbserver,$dbuser,$dbpwd,$dbname, $role ;  
$con = @mysql_connect("$dbserver","$dbuser","$dbpwd");
if (!$con)
  {
  die('Could not connect');
  }
$db_found=mysql_select_db("$dbname", $con);
if (!$db_found){
echo "Connected but database can not be opened";
}
$result = mysql_query($sql);
$table='<select id="std" name="std">';
while ($db_field =mysql_fetch_assoc($result))
{
$std=$db_field['std'];
$stdid=$db_field['stdid'];
$table=$table.'<option value="'.$stdid.'">'.$std.'</option>';
}
mysql_close($con);
$table="$table</select>";
return $table;
}
?>

<?
function DisplayAsPerRole($role)
{
Global $sessionid,$user ;
if ($role=='ADMIN')
{
$stylemenu='<div id="horizontalmenu">
<b id="changepwd" onclick="'."Actions('changepassword')".'"> <a href="#">Change password</a></b> 
<b id="setting" onclick="'."Actions('setting')".'"> <a href="#">User Setting</a></b> 

 <b onclick="'."Actions('signout')".'"><a href="#">Signout</a></b><hr>
        
         <ul><li><a href="#">View </a>
                <ul> <li onclick="'."Actions('viewusers')".'"><a href="#">Users</a></li> 
                     <li onclick="'."Actions('viewstaffs')".'"><a href="#">Staffs</a></li> 
                    <li onclick="'."Actions('viewclasses')".'"><a href="#">Classes</a></li>
                     <li onclick="'."Actions('viewsubjects')".'"><a href="#">Subjects</a></li>
                     <li onclick="'."Actions('viewexams')".'"><a href="#">Examinations</a></li>
                     <li onclick="'."Actions('viewrevenueheads')".'"><a href="#">Revenue Heads</a></li>
                     <li onclick="'."Actions('viewstudentdetails')".'"><a href="#">Student Details</a></li>
                     <li onclick="'."Actions('viewhighlight')".'"><a href="#">Student Highlight</a></li>
                     <li onclick="'."Actions('viewwithheld')".'"><a href="#">Withheld</a> <br/></li>
                     <li onclick="'."Actions('viewfeepayment')".'"><a href="#">Payment(Year) </a></li>
                     <li onclick="'."Actions('viewfeepaymentdate')".'"><a href="#">Payment(Period)</a></li>
                     <li onclick="'."Actions('viewrevenue')".'"><a href="#">Revenue</a></li>
                     <li onclick="'."Actions('viewsms')".'"><a href="#">SMSs</a></li>
                     <li onclick="'."Actions('viewsmsbalance')".'"><a href="#">SMS Balance</a> <br/></li>
                    
                </ul>
             </li>

             <li><a href="#">Print/View </a>
		<ul> <li onclick="'."Actions('printregistration')".'"><a href="#">Registration</a></li> 
                     <li onclick="'."Actions('printstudentsinclass')".'"><a href="#">Class(es)</a></li> 
	<li onclick="'."Actions('printID')".'"><a href="#">Identity Card</a></li>
                     <li onclick="'."Actions('printadmitcard')".'"><a href="#">Admit Card</a></li> 
                     <li onclick="'."Actions('printattendance')".'"><a href="#">Attendance</a></li> 
                     <li onclick="'."Actions('printmarks')".'"><a href="#">Marks</a><br/></li>
                     <li onclick="'."Actions('printreceipt')".'"><a href="#">Receipt</a><br/></li>                    
               </ul>
             </li>
             <li><a href="#">Add/Update </a>
                <ul> <li onclick="'."Actions('createuser')".'" ><a href="#">User</a></li> 
                     <li onclick="'."Actions('updatestaff')".'" ><a href="#">Staff</a></li> 
                     <li onclick="'."Actions('updateclass')".'"><a href="#">Class</a></li> 
                     <li onclick="'."Actions('updatesubject')".'"><a href="#">Subject</a></li> 
                     <li onclick="'."Actions('updateexam')".'"><a href="#">Exam</a></li> 
                     <li onclick="'."Actions('updatemark')".'"><a href="#">Mark</a></li> 
                     <li onclick="'."Actions('updateallocation')".'"><a href="#">Revenue Head</a></li> 
                     <li onclick="'."Actions('register')".'"><a href="#">Registration</a></li>
                     <li onclick="'."Actions('frmUpdateMobileOfStudent')".'"><a href="#">Phone of parent</a></li>
                     <li onclick="'."Actions('frmUpdateRegistration')".'"><a href="#">Student Data</a></li>
                     <li onclick="'."Actions('admission')".'"><a href="#">Admission</a></li> 
                     <li onclick="'."Actions('payfee')".'"><a href="#">Pay Fee</a></li>
	             <li onclick="'."Actions('paymisc')".'"><a href="#">Pay Misc.</a></li>
                     <li onclick="'."Actions('uploadphoto')".'"><a href="#">Upload Photo(s)</a></li> 
                     <li onclick="'."Actions('updateattendance')".'"><a href="#">Attendance</a></li> 
                     <li onclick="'."Actions('updatewithheld')".'"><a href="#">Withheld</a><br/></li> 
                     <li onclick="'."Actions('updatehighlight')".'"><a href="#">Highlight</a><br/></li> 
                     <li onclick="'."Actions('updatebiometric')".'"><a href="#">Biometric</a><br/></li> 
                     
                </ul>
             </li>
             <li><a href="#">Arrange Exam</a>
		<ul> <li onclick="'."Actions('exam-schedule-invigilator')".'"><a href="#">Schedule&Invigilators</a></li>
                                     
                </ul>
              </li> 
               <li><a href="#">Send SMS </a>
		<ul> <li onclick="'."Actions('sendsmsroll')".'"><a href="#">using Roll No.</a></li> 
                     <li onclick="'."Actions('sendsmsparent')".'"><a href="#">using Reg.No.</a></li> 
                     <li onclick="'."Actions('sendsmsnos')".'"><a href="#">using Mobile#</a><br/></li> 
                     <li onclick="'."Actions('sendsmsparents')".'"><a href="#">to absentees</a></li>
	             <li onclick="'."Actions('sendsmsparentsstd')".'"><a href="#">to class</a></li>
                     <li onclick="'."Actions('sendsmsstaffs')".'"><a href="#">to staffs</a><br/></li> 
                         
                     
                </ul>
              </li> 
              <li><a href="#">Delete </a>
		<ul> <li onclick="'."Actions('delwrongadmission')".'"><a href="#">Wrong Admission</a></li> 
                     <li onclick="'."Actions('delwrongpayment')".'"><a href="#">Wrong payment</a></li> 
                     <li onclick="'."Actions('delall')".'"><a href="#">Group(s) of data</a> <br/> </li>
                  
                </ul>
	<li><a href="bsas" target="_blank">BioAttendance</a></li>
                <li>'."<a href='backupdb.php?sessionid=$sessionid' target='_blank'>Backup data</a></li>".'
               </ul><br/>
</div><br/>';
}
else
{
$stylemenu=GetMenusForUser("$user");
}
echo $stylemenu;
}
?>

<?
function GetMenusForUser($user)
{
Global $schoolid;
$sql="SELECT * FROM `school_set_menu` Where `schoolid`='$schoolid' And `userid`='$user'";
$buttonid=GetCommaSeparatedData($sql,"buttonid");
$buttonidArr=explode(",",$buttonid);
$stylemenu='<div id="horizontalmenu">
<b id="changepwd" onclick="'."Actions('changepassword')".'"> <a href="#">Change password</a></b> 
<b onclick="'."Actions('signout')".'"><a href="#">Signout</a></b><hr>
    
         <ul><li><a href="#">View </a>
                <ul>'; 
	if (in_array("1",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('viewusers')".'"><a href="#">Users</a></li>'; 
                     if (in_array("2",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('viewstaffs')".'"><a href="#">Staffs</a></li>'; 
                     if (in_array("3",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('viewclasses')".'"><a href="#">Classes</a></li>';
                     if (in_array("4",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('viewsubjects')".'"><a href="#">Subjects</a></li>';
                     if (in_array("5",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('viewexams')".'"><a href="#">Examinations</a></li>';
                     if (in_array("6",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('viewrevenueheads')".'"><a href="#">Revenue Heads</a></li>';
                     if (in_array("7",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('viewstudentdetails')".'"><a href="#">Student Details</a></li>';
                     if (in_array("8",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('viewhighlight')".'"><a href="#">Student Highlight</a></li>';
                     if (in_array("9",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('viewwithheld')".'"><a href="#">Withheld</a> <br/></li>';
                     if (in_array("10",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('viewfeepayment')".'"><a href="#">Payment(Year) </a></li>';
                     if (in_array("11",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('viewfeepaymentdate')".'"><a href="#">Payment(Period)</a></li>';
                     if (in_array("12",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('viewrevenue')".'"><a href="#">Revenue</a></li>';
                     if (in_array("13",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('viewsms')".'"><a href="#">SMSs</a></li>';
                     if (in_array("14",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('viewsmsbalance')".'"><a href="#">SMS Balance</a> <br/></li>';
                    
              $stylemenu=$stylemenu.'  </ul>
             </li>';

            $stylemenu=$stylemenu.' <li><a href="#">Print/View </a>
		<ul>';
		    if (in_array("15",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('printregistration')".'"><a href="#">Registration</a></li> ';
                    if (in_array("16",$buttonidArr)) $stylemenu=$stylemenu.' <li onclick="'."Actions('printstudentsinclass')".'"><a href="#">Class(es)</a></li> ';
                    if (in_array("49",$buttonidArr)) $stylemenu=$stylemenu.' <li onclick="'."Actions('printID')".'"><a href="#">Identity Card</a></li>';
                    if (in_array("17",$buttonidArr)) $stylemenu=$stylemenu.' <li onclick="'."Actions('printadmitcard')".'"><a href="#">Admit Card</a></li>';
                    if (in_array("18",$buttonidArr)) $stylemenu=$stylemenu.' <li onclick="'."Actions('printattendance')".'"><a href="#">Attendance</a></li>'; 
                    if (in_array("19",$buttonidArr)) $stylemenu=$stylemenu.' <li onclick="'."Actions('printmarks')".'"><a href="#">Marks</a><br/></li>';
                    if (in_array("20",$buttonidArr)) $stylemenu=$stylemenu.' <li onclick="'."Actions('printreceipt')".'"><a href="#">Receipt</a><br/></li>';                   
             $stylemenu=$stylemenu.'  </ul>
             </li>';
	    $stylemenu=$stylemenu.'<li><a href="#">Add/Update </a>
                <ul>';
	             if (in_array("21",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('createuser')".'" ><a href="#">User</a></li>'; 
                     if (in_array("22",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('updatestaff')".'" ><a href="#">Staff</a></li>'; 
                     if (in_array("23",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('updateclass')".'"><a href="#">Class</a></li>'; 
                     if (in_array("24",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('updatesubject')".'"><a href="#">Subject</a></li>'; 
                     if (in_array("25",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('updateexam')".'"><a href="#">Exam</a></li>'; 
                     if (in_array("26",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('updatemark')".'"><a href="#">Mark</a></li>'; 
                     if (in_array("27",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('updateallocation')".'"><a href="#">Revenue Head</a></li>'; 
                     if (in_array("28",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('register')".'"><a href="#">Registration</a></li>';
                     if (in_array("29",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('frmUpdateMobileOfStudent')".'"><a href="#">Phone of parent</a></li>';
                     if (in_array("30",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('frmUpdateRegistration')".'"><a href="#">Student Data</a></li>';
                     if (in_array("31",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('admission')".'"><a href="#">Admission</a></li>'; 
                     if (in_array("32",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('payfee')".'"><a href="#">Pay Fee</a></li>';
	             if (in_array("33",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('paymisc')".'"><a href="#">Pay Misc.</a></li>';
                     if (in_array("34",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('uploadphoto')".'"><a href="#">Upload Photo(s)</a></li> ';
                     if (in_array("35",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('updateattendance')".'"><a href="#">Attendance</a></li>'; 
                     if (in_array("36",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('updatewithheld')".'"><a href="#">Withheld</a><br/></li>'; 
                     if (in_array("37",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('updatehighlight')".'"><a href="#">Highlight</a><br/></li>'; 
                     
            $stylemenu=$stylemenu.' </ul>
             </li>
             <li><a href="#">Arrange Exam</a>
		<ul>';
		  if (in_array("38",$buttonidArr)) $stylemenu=$stylemenu.' <li onclick="'."Actions('exam-schedule-invigilator')".'"><a href="#">Schedule&Invigilators</a></li>';
                    $stylemenu=$stylemenu.'                   
                </ul>
              </li> 
               <li><a href="#">Send SMS </a>
		<ul>';
		  if (in_array("39",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('sendsmsroll')".'"><a href="#">using Roll No.</a></li>'; 
                   if (in_array("40",$buttonidArr)) $stylemenu=$stylemenu.'   <li onclick="'."Actions('sendsmsparent')".'"><a href="#">using Reg.No.</a></li>'; 
                    if (in_array("41",$buttonidArr)) $stylemenu=$stylemenu.'  <li onclick="'."Actions('sendsmsnos')".'"><a href="#">using Mobile#</a><br/></li> ';
                    if (in_array("42",$buttonidArr)) $stylemenu=$stylemenu.'  <li onclick="'."Actions('sendsmsparents')".'"><a href="#">to absentees</a></li>';
	             if (in_array("43",$buttonidArr)) $stylemenu=$stylemenu.' <li onclick="'."Actions('sendsmsparentsstd')".'"><a href="#">to class</a></li>';
                      if (in_array("44",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('sendsmsstaffs')".'"><a href="#">to staffs</a><br/></li>'; 
               $stylemenu=$stylemenu.'           
                     
                </ul>
              </li> 
              <li><a href="#">Delete </a>
		<ul>';
	      if (in_array("45",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('delwrongadmission')".'"><a href="#">Wrong Admission</a></li>'; 
               if (in_array("46",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('delwrongpayment')".'"><a href="#">Wrong payment</a></li>'; 
                 if (in_array("47",$buttonidArr)) $stylemenu=$stylemenu.'<li onclick="'."Actions('delall')".'"><a href="#">Group(s) of data</a> <br/> </li>';
               $stylemenu=$stylemenu.'    
                </ul>';
		if (in_array("48",$buttonidArr)) $stylemenu=$stylemenu.'<li>'."<a href='backupdb.php?sessionid=$sessionid' target='_blank'>Backup data</a></li>";
$stylemenu=$stylemenu.' 
               </ul><br/>
</div><br/>';
return $stylemenu;
}

?>


