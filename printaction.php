







 



 








 



 
<?
$sessionid=trim($_GET['sessionid']);
$toprint=trim($_GET['toprint']);
$name=strtoupper(trim($_GET['name']));
$exam=strtoupper(trim($_GET['exam']));
$tid=strtoupper(trim($_GET['tid']));
$regno=strtoupper(trim($_GET['regno']));
$month=strtoupper(trim($_GET['month']));
$std=strtoupper(trim($_GET['std']));
$section=strtoupper(trim($_GET['section']));
$year=strtoupper(trim($_GET['session']));
$roll=trim($_GET['roll']);
$date1=trim($_GET['date1']);
$date2=trim($_GET['date2']);
$percent=trim($_GET['percent']);
$extrainfo=trim($_GET['extrainfo']);
//------------For printing marks---------
$subid=strtoupper(trim($_GET['subid']));
$examid=strtoupper(trim($_GET['examid']));
$total=strtoupper(trim($_GET['total']));
//---------------------------------------
set_time_limit(60000);
include "functions.php"; 
AngaobaWakhalSing();
$sql="SELECT * FROM `school_users` WHERE `sessionid`='$sessionid'";
$sesid=getInfo($sql,'sessionid');
$schoolid=getInfo($sql,'schoolid');
$role=getInfo($sql,'role');
$enabled=getInfo($sql,'enabled');
//--------------------------------------------------------------------------
if (($sesid!=$sessionid) Or ($sessionid==''))
{
die( "Access denied!");
}
if ($enabled=='no')
{
die( "Access denied!");
}

else
{
$sql="SELECT * FROM `school_clients` WHERE `schoolid`='$schoolid'";
$schoolname=getInfo($sql,'name');
$schooladdress=getInfo($sql,'address');
//Astart---------------------------------
if ($toprint=='registration')
{
$sql="SELECT * FROM `school_students` WHERE `schoolid`='$schoolid' And `regno`='$regno'";
$name=getInfo($sql,"name");
$father=getInfo($sql,'father');
$entry=getInfo($sql,'entry');
$sex=getInfo($sql,'sex');
if ($sex=='MALE')
{
$sexs="Son";
}
if ($sex=='FEMALE')
{
$sexs="Daughter";
}
$address=getInfo($sql,'address');
$po=getInfo($sql,'po');
$pin=getInfo($sql,'pin');
$state=getInfo($sql,'state');
$sql="SELECT * FROM `school_classes` WHERE `schoolid`='$schoolid' And `regno`='$regno'";
$photo=getInfo($sql,'photo');
$sign=getInfo($sql,'sign');
$msg='<center>
<div style="background-image: url(certificateback.png);opacity:0.8; width:100%; height:100%;">
<table style="opacity:1;border: 1px solid green;">';
$msg=$msg."<tr><td colspan='2' style='border: 1px solid green;font-size:25px'><img src='schoollogo.png' width='40px' height:'40px'> <b>$schoolname </b></td><td style='border: 1px solid green;font-size:20px'><center><b>REGISTRATION<br/>CERTIFICATE</b></center></td></tr>
<tr><td style='border: 1px solid green; font-size:18px'>Registration No.:<b> $regno</b></td><td colspan='2' style='border: 1px solid green;font-size:18px'> Year :$entry </td></tr>
<td colspan='2' style='border: 1px solid green;valign:top'>
<br/><b>Student Name: $name
<br/>$sexs of :$father
<br/>
<br/><u>Address</u>:
<br>$address <br/> $po 
<br/>$state, PIN-$pin </b>
<br/>
<br/>
<img alt='' src='barcode.php?text=$regno&size=40'>
</td><td><center><img src='photo/$photo' width='150px' height='170px'>
<br/> <img src='photo/$sign' width='150px' height='60px'>
<br/> <hr>
<br/>
<br/>
..............</center>
</td></tr></table></div>";
die($msg);
}
if ($toprint=='receipt')
{
$msg='<p style="page-break-after: always;">'.Receipt($tid)."</p>";
$bd1="<html>
<head>
<style type='text/css' media='print'>
@page {
  size: 4in 4in;
}
</style><body><center>";
$bd2="</center></body></html>";
$url="html2pdf.php?sessionid=$sessionid&toprint=$toprint&tid=$tid";
die("$bd1 $msg <br/> $printondotmatrix $bd2");
}

//Aend------------------------------------------------------

//B---------------------------------
if ($toprint=='studentsinclass')
{
if ($std=='ALL')
{
if ($section=='ALL') 
{
$sql="SELECT * FROM `school_classes` WHERE `session`='$year' And `schoolid`='$schoolid' ORDER BY `std`,`section`,`roll`";
}
else
{
$sql="SELECT * FROM `school_classes` WHERE `session`='$year' And `schoolid`='$schoolid' And `section`='$section' ORDER BY `std`,`section`,`roll`";
}
}
else
{

if ($section=='ALL') 
{
$sql="SELECT * FROM `school_classes` WHERE `session`='$year' And `schoolid`='$schoolid' And `std`='$std'  ORDER BY `std`,`section`,`roll`";
}
else
{
$sql="SELECT * FROM `school_classes` WHERE `session`='$year' And `schoolid`='$schoolid' And `std`='$std' And `section`='$section' ORDER BY `std`,`section`,`roll`";
}

}

$bd1="<html><body><center>";
$bd2="</center></body></html>"; 
$msg="<center>".classes($sql)."</center>";
die("$bd1 $msg $bd2");
}

//B end------------------------------------------------------
//C-------------------------------------------------------
if ($toprint=='admitcard')
{

$sql="SELECT * FROM `school_classes` WHERE `session`='$year' And `schoolid`='$schoolid' And `std`='$std' And `section`='$section' And `roll`='$roll' ORDER BY `std`,`section`,`roll`";
if ($roll=='')
{
$sql="SELECT * FROM `school_classes` WHERE `session`='$year' And `schoolid`='$schoolid' And `std`='$std' And `section`='$section' ORDER BY `std`,`section`,`roll`";
}

if ($section=='ALL')
{
$sql="SELECT * FROM `school_classes` WHERE `session`='$year' And `schoolid`='$schoolid' And `std`='$std' ORDER BY `std`,`section`,`roll`";
}

if (($std=='ALL') And ($section=='ALL')) 
{
$sql="SELECT * FROM `school_classes` WHERE `session`='$year' And `schoolid`='$schoolid' ORDER BY `std`,`section`,`roll`";
}
$ms=PrintAdmitCards($sql);
$msg="$ms";
$bd1="<html><body><center>";
$bd2="</center></body></html>";
die("$bd1 $msg $bd2");

}

//--------------------ID Card Printing----------------------
if ($toprint=='idcard')
{

$sql="SELECT * FROM `school_classes` WHERE `session`='$year' And `schoolid`='$schoolid' And `std`='$std' And `section`='$section' And `roll`='$roll' ORDER BY `std`,`section`,`roll`";
if ($roll=='')
{
$sql="SELECT * FROM `school_classes` WHERE `session`='$year' And `schoolid`='$schoolid' And `std`='$std' And `section`='$section' ORDER BY `std`,`section`,`roll`";
}

if ($section=='ALL')
{
$sql="SELECT * FROM `school_classes` WHERE `session`='$year' And `schoolid`='$schoolid' And `std`='$std' ORDER BY `std`,`section`,`roll`";
}

if (($std=='ALL') And ($section=='ALL')) 
{
$sql="SELECT * FROM `school_classes` WHERE `session`='$year' And `schoolid`='$schoolid' ORDER BY `std`,`section`,`roll`";
}
$ms=PrintIDCards($sql);
$msg="$ms";
die("$msg");

}


//C end-------------------------------------------------------

//D---------------------------------
if ($toprint=='attendance')
{
$diff = @abs(strtotime($date2) - strtotime($date1));
$dt1=@date_format(date_create($date1),'d-M-Y');
$dt2=@date_format(date_create($date2),'d-M-Y');
$days = floor(($diff/(60*60*24)));
if ($days<1)
{
die("<html><body>Wrong data !</body></html>");
}
$sql="SELECT DISTINCT(`regno`) FROM `school_classes` WHERE `session`='$year' And `schoolid`='$schoolid' And `std`='$std' And `section`='$section' And `roll`='$roll'";

if ($std=='ALL') 
{
$sql="SELECT DISTINCT(`regno`) FROM `school_classes` WHERE `session`='$year' And `schoolid`='$schoolid' ORDER BY `regno` ASC";
}

if (($std=='ALL') And ($section=='ALL') )
{
$sql="SELECT DISTINCT(`regno`) FROM `school_classes` WHERE `session`='$year' And `schoolid`='$schoolid' ORDER BY `regno` ASC";
}

if (($std!='ALL') And ($section=='ALL') )
{
$sql="SELECT DISTINCT(`regno`) FROM `school_classes` WHERE `session`='$year' And `schoolid`='$schoolid' ORDER BY `regno` ASC";
}

if ($section!='ALL') 
{
$sql="SELECT DISTINCT(`regno`) FROM `school_classes` WHERE `session`='$year' And `schoolid`='$schoolid' And `section`='$section' ORDER BY `regno` ASC";
}

if (($roll!='')And (($std!='ALL') And ($section!='ALL') ))
{
$sql="SELECT * FROM `school_classes` WHERE `schoolid`='$schoolid' And `session`='$year' And `std`='$std' And `section`='$section' And `roll`='$roll'" ;
//-----------------------------
$name=getInfo($sql,'name');
$regno=getInfo($sql,'regno');
$std=getInfo($sql,'std');
$section=getInfo($sql,'section');
$roll=getInfo($sql,'roll');
if ($name=='')
{
die("<html><body>No data found</body></html>");
}
//--------------------------------------
$sqlabsent="SELECT COUNT(*) As absent FROM `school_attendance` WHERE `date`>='$date1' and `date`<='$date2' And `schoolid`='$schoolid' And `regno`='$regno' And `attendance`='A'" ;
$absent=getInfo($sqlabsent,'absent');
$attend=round((($days-$absent)*100/$days),2);
$table="<b>Attendance of $name (Class:$std $section ; Roll No.:$roll) for $days days i.e. from $dt1 to $dt2  is $attend % .</b> <br/>";
if ($absent!=0)
{
$table=$table."Absent dates are shown below<br/>";
$sql="SELECT * FROM `school_attendance` WHERE `date`>='$date1' and `date`<='$date2' And `schoolid`='$schoolid' And `regno`='$regno' And `attendance`='A' ORDER BY `date`" ;
$table="$table".SingleStudentDateWise($sql);
}
$msg=$table ;
}
else
{
$regn=explode(",",AllStudentRegNo($sql));
$total=count($regn)-1;
$cnt=0;

$table="<table><tr><th bgcolor='green'><u>Class</u></th><th bgcolor='green'><u>Roll No.(Name)</u></th><th bgcolor='green'><u>Absent days</u><th bgcolor='green'><u>Attendance(%)</u></th></tr>";
while ($cnt<$total)
{
$sqlabsent="SELECT COUNT(*) As absent FROM `school_attendance` WHERE `date`>='$date1' and `date`<='$date2' And `schoolid`='$schoolid' And `regno`='$regn[$cnt]' And `attendance`='A'" ;
$sql="SELECT * FROM `school_classes` WHERE `schoolid`='$schoolid' And `regno`='$regn[$cnt]' And `session`='$year'" ;
$absent=getInfo($sqlabsent,'absent');
$name=getInfo($sql,'name');
$std=getInfo($sql,'std');
$section=getInfo($sql,'section');
$roll=getInfo($sql,'roll');
$attend=round((($days-$absent)*100/$days),2);
$table="$table<tr><td bgcolor='yellow'>$std$section</td><td bgcolor='yellow'>$roll ($name)</td><td bgcolor='yellow'>$absent</td><td bgcolor='yellow'>$attend</td></tr>";
$cnt=$cnt+1;
}
$table="$table</table>";
$msg="<center>Total Days from $dt1 to $dt2=$days <br/> $table</center>";
}
$bd1="<html><body><center>";
$bd2="</center></body></html>";
die("$bd1 $msg $bd2");
}

//D end------------------------------------------------------
//E-----------------------------------------
if ($toprint=='marks')
{
if (($examid=='') Or ($subid=='') Or ($year==''))
{
die("Exam ID/Subject/ can not be blank."); 
}

//----------------------------------------------
$sql="SELECT * FROM `school_subjects` WHERE `subjectid`='$subid' And `schoolid`='$schoolid' ";
$std=getInfo($sql,'stdid');

$sql="SELECT * FROM `school_classes` WHERE `std`='$std' And `section`='$section' And `roll`='$roll' And `session`='$year' AND `schoolid`='$schoolid' ORDER BY `std`,`section`,`roll`";

if ((($subid=='ALL') and ($section=='ALL')) Or ($subid=='ALL'))
{
$sql="SELECT * FROM `school_classes` WHERE `session`='$year' And `schoolid`='$schoolid' ORDER BY `std`,`section`,`roll`";
}

//------------------All regno for marks-------------------------------------------
Global $dbserver,$dbuser,$dbpwd,$dbname,$schoolname,$exam ; 
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
$student="";
$i=0;
while ($db_field =mysql_fetch_assoc($result))
{
$std=$db_field['std'];
$section=$db_field['section'];
$roll=$db_field['roll'];
$rno=$db_field['regno'];
$student=$student."<$rno>$std $section Roll No.$roll </$rno>";
$regnos[$i]=$rno;
$i=$i+1;
}
mysql_close($con);
//-----------------------------------------------------------------------------------
$table="<b>$examid Students' Marks for the year $year</b><br/><table><tr><th bgcolor='green'><u>Class-Roll No./Reg.No.</u><th bgcolor='green'><u>Exam ID </u></th><th bgcolor='green'><u>Subject ID</u></th></th><th bgcolor='green'><u>Mark</u></th><th bgcolor='green'><u>Max.Mark</u></th><th bgcolor='green'><u>Percentage</u></th></tr>";
if ($total=="YES")
{
$table="<b>$examid Students' Marks for the year $year</b><br/><table><tr><th bgcolor='green'><u>Class-Roll No./Reg.No.</u></th><th bgcolor='green'><u>Mark</u></th><th bgcolor='green'><u>Max.Mark</u></th><th bgcolor='green'><u>Percentage</u></th></tr>";
}

$nos=0;
while ($nos<$i)
{

if ($total!="YES")
{
$sqlmark="SELECT * FROM `school_marks` WHERE `session`='$year' And `schoolid`='$schoolid' And `examid`='$examid' And `subjectid`='$subid' And `regno`='$regnos[$nos]'  ORDER BY `examid`,`subjectid`";

if  (($examid!='ALL') And ($subid=='ALL'))
{
$sqlmark="SELECT * FROM `school_marks` WHERE `session`='$year' And `schoolid`='$schoolid' And `examid`='$examid' And `regno`='$regnos[$nos]'  ORDER BY `examid`,`subjectid`";
}
if (($examid=='ALL') And ($subid!='ALL'))
{
$sqlmark="SELECT * FROM `school_marks` WHERE `session`='$year' And `schoolid`='$schoolid' And `subjectid`='$subid' And `regno`='$regnos[$nos]'  ORDER BY `examid`,`subjectid`";
}


if (($examid=='ALL') And ($subid=='ALL'))
{
$sqlmark="SELECT * FROM `school_marks` WHERE `session`='$year' And `schoolid`='$schoolid' And `regno`='$regnos[$nos]'  ORDER BY `examid`,`subjectid`";
}
}
else
{
//---------------------------------------------------
$sqlmark="SELECT `regno`,`examid`,SUM(`mark`) as totalmark,SUM(`maxmark`) as totalmaxmark FROM `school_marks` WHERE `session`='$year' And `schoolid`='$schoolid' And `examid`='$examid' And `regno`='$regnos[$nos]'  ORDER BY `totalmark`";

if  (($examid!='ALL') And ($subid=='ALL'))
{
$sqlmark="SELECT `regno`,`examid`,SUM(`mark`) as totalmark,SUM(`maxmark`) as totalmaxmark FROM `school_marks` WHERE `session`='$year' And `schoolid`='$schoolid' And `examid`='$examid' And `regno`='$regnos[$nos]'  ORDER BY `totalmark`";
}
if (($examid=='ALL') And ($subid!='ALL'))
{
$sqlmark="SELECT `regno`,`examid`,SUM(`mark`) as totalmark,SUM(`maxmark`) as totalmaxmark FROM `school_marks` WHERE `session`='$year' And `schoolid`='$schoolid' And `subjectid`='$subid' And `regno`='$regnos[$nos]'  ORDER BY `totalmark`";
}

if (($examid=='ALL') And ($subid=='ALL'))
{
$sqlmark="SELECT `regno`,`examid`,SUM(`mark`) as totalmark,SUM(`maxmark`) as totalmaxmark FROM `school_marks` WHERE `session`='$year' And `schoolid`='$schoolid' And `regno`='$regnos[$nos]'  ORDER BY `totalmark`";
}
//---------------------------------------------------
}

$tabledata=$tabledata.PrintMarks($student,$sqlmark,$total);
$nos=$nos+1;
}
}
$table="$table $tabledata </table>";
$msg ="<center>".$table."</center>";

$bd1="<html><body><center>";
$bd2="</center></body></html>";
die("$bd1 $msg $bd2");
}

//E end-----------------------------
?>


<!DOCTYPE html>
<html>
<head>
<title>
<?
echo "Print $toprint";
?>
</title>
<link rel="stylesheet" type="text/css" href="">
</head>
<body>
<center>
<div id="container">
<div>
<?
echo $msg ;
?>
</div>
</div>
</center>
</body>
</html>

<?php
//--------------String between two string---------------------------
echo getStringBetween($str,$from,$to);

function getStringBetween($str,$from,$to)
{
    $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
    return substr($sub,0,strpos($sub,$to));
}
//------------------------------------------------
?>


<?
//-------------Attendance-------------------------------

function AllStudentRegNo($sql)
{
Global $dbserver,$dbuser,$dbpwd,$dbname,$schoolid ; 
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
$reg=1;
while ($db_field =mysql_fetch_assoc($result))
{
$rno1=trim($db_field['regno']);
if ($rno1!='')
{
$rno=$rno.$rno1.",";
$reg=$reg+1;
}
}
mysql_close($con);
return $rno;
}
//------------------------
function SingleStudentDateWise($sql)
{
Global $dbserver,$dbuser,$dbpwd,$dbname,$schoolid ; 
$con = @mysql_connect("$dbserver","$dbuser","$dbpwd");
if (!$con)
  {
  die('Could not connect');
  }
$db_found=mysql_select_db("$dbname", $con);
if (!$db_found){
echo "Connected but database can not be opened";
}

$absentdate="<OL>";
$result = mysql_query($sql);
while ($db_field =mysql_fetch_assoc($result))
{
$dte=$db_field['date'];
if($dte!='')
{
$absentdate=$absentdate."<li>$dte</li>";
}
}
$absentdate=$absentdate."</OL>";
mysql_close($con);
return $absentdate;
}
//----------------------End of Attendance-------------------
?>


<?
function PrintMarks($student,$sql,$total)
{
Global $dbserver,$dbuser,$dbpwd,$dbname,$schoolname,$exam ; 
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
if ($total=='YES')
{
while ($db_field =mysql_fetch_assoc($result))
{
$examid=$db_field['examid'];
$mark=$db_field['totalmark'];
$maxmark=$db_field['totalmaxmark'];
$rno=$db_field['regno'];
if ($maxmark==0)
{
$maxmark=1;
}
$percent=($mark*100)/$maxmark;
$percent=round($percent,2);
$studentdetails=getStringBetween($student,"<$rno>","</$rno>");
$table=$table."<tr><td bgcolor='yellow'>$studentdetails/Reg.No.$rno</td><td bgcolor='yellow'>$mark</td><td bgcolor='yellow'>$maxmark</td><td bgcolor='yellow'>$percent</td></tr>";
}
}
else
{
while ($db_field =mysql_fetch_assoc($result))
{
$examid=$db_field['examid'];
$subid=$db_field['subjectid'];
$mark=$db_field['mark'];
$maxmark=$db_field['maxmark'];
$rno=$db_field['regno'];
$percent=($mark*100)/$maxmark;
$percent=round($percent,2);
$studentdetails=getStringBetween($student,"<$rno>","</$rno>");
$table=$table."<tr><td bgcolor='yellow'>$studentdetails/Reg.No.$rno</td><td bgcolor='yellow'>$examid</td><td bgcolor='yellow'>$subid</td><td bgcolor='yellow'>$mark</td><td bgcolor='yellow'>$maxmark</td><td bgcolor='yellow'>$percent</td></tr>";
}
}
mysql_close($con);

return $table;
}
?>


<?
function PrintAdmitCards($sql)
{
Global $dbserver,$dbuser,$dbpwd,$dbname,$schoolname,$exam ; 
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
$table="";
while ($db_field=mysql_fetch_assoc($result))
{
$sid=$db_field['schoolid'];
$regno=$db_field['regno'];
$std=$db_field['std'];
$sessn=$db_field['session'];
$section=$db_field['section'];
$roll=strtoupper($db_field['roll']);
$name=strtoupper($db_field['name']);
$photo=trim($db_field['photo']);
$url="admitcard.php?ref=".$sid."A".$sessn."A".$regno;
$table=$table.'<p style="page-break-after: always;"><table style="opacity:1; background-image: url(schoollogo.png);background-repeat: no-repeat;	background-position: center;  border: 1px solid green;"><tr><td style="border: 1px solid green;font-size:25px">'."$schoolname</td><td style='border: 1px solid green;font-size:25px'>Admit Card</td></tr>
<tr><td  style='width:6cm;'><b>Exam:$exam($sessn)<br/>Name:$name <br/>
Class: $std$section 
<br/>Roll No.:$roll
<br/>Reg.No.$regno</b>
<br/><br/>"."<img alt='' src='barcode.php?text=$regno&size=40'>"."</td><td><b><center><img src='photo/$photo' width='100px' height='120px'><br/><br/><br/>Principal<br/><br/><br/></center></b></td></tr></table></p>";
}
mysql_close($con);
return "$table";
}
?>

<?
function PrintIDCards($sql)
{
Global $dbserver,$dbuser,$dbpwd,$dbname,$schoolname,$exam ; 
Global $extrainfo,$schooladdress ;
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
$table="";
$width="56mm";
$height="85mm";
$page="<html><body style='width:$width;height:$height; margin: 0mm 0mm 0mm 0mm;'><center>";
while ($db_field=mysql_fetch_assoc($result))
{
//-------------------------------------------------------
$sid.="~".$db_field['schoolid'];
$regno.="~".$db_field['regno'];
$std.="~".$db_field['std'];
$sessn.="~".$db_field['session'];
$section.="~".$db_field['section'];
$roll.="~".strtoupper($db_field['roll']);
$name.="~".strtoupper($db_field['name']);
$photo.="~".trim($db_field['photo']);
//-----------------------------------------------------------
}
mysql_close($con);
$regnoArr=explode("~",$regno);
$sidArr=explode("~",$sid);
$stdArr=explode("~",$std);
$sectionArr=explode("~",$section);
$rollArr=explode("~",$roll);
$sessnArr=explode("~",$sessn);
$nameArr=explode("~",$name);
$photoArr=explode("~",$photo);
$total=count($regnoArr)-1;
while ($total>0)
{
$sql="SELECT * FROM `school_students`  WHERE `schoolid`='$sidArr[$total]' and `regno`='$regnoArr[$total]'";
$address=getInfo($sql,"address");
$father=getInfo($sql,"father");
$mother=getInfo($sql,"mother");
$sex=getInfo($sql,"sex");
$address=getInfo($sql,"address");
$dob=getInfo($sql,"dob");
$std=$stdArr[$total];
$sessn=$sessnArr[$total];
$section=$sectionArr[$total];
$roll=$rollArr[$total];
$photo=$photoArr[$total];
$name=$nameArr[$total];
if ($sex=="FEMALE") $childsex="D/o";
if ($sex=="MALE") $childsex="S/o";
if (($father=="")  or ($father=="-"))$father=$mother;

//--------------------------------
$page.="<div style='opacity: 1; border: 2px solid green; background-color:white; border-radius: 5%;width:$width;height:$height;'>".'<table style="border: 2px;opacity:1;border-radius: 10%;margin: auto;">
<center><span>'."<font style='font-size:16;'><b>$schoolname</b></font></span><br/>$schooladdress<hr/></center>
<tr><td ><center><b>$sessn <br/><img src='schoollogo.png' style='width:10mm; height:10mm;'> </td> <td> <img src='photo/$photo'  style='width:16mm; height:24mm;'></center></td></tr>
<tr><td colspan='2'><center>$name </center></td></tr>
<tr><td> $childsex: $father <br/>Class: $std$section <br/>Roll No.:$roll<br/>$extrainfo 
<br/>Address:$address</td><td><b><center><br/><br/><br/><br/><br/>Principal<br/><br/><br/></center></b></td></tr></table></div><br/> ";
//-----------------------------------------------------------------------
$total=$total-1;
}
return "$page</center></body></html>";
}
?>

<?
function QRImage($url)
{
$encodedurl=urlencode($url);
$qrurl="http://api.qrserver.com/v1/create-qr-code/?data=$encodedurl&size=100x100";
$imageData = base64_encode(@file_get_contents($qrurl));
$src = '<img src="data:image/png;base64,'.$imageData.'">';
return $src ;
}
?>

<?
function classes($sql)
{
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
$table="<table><tr><th bgcolor='green'><u>Class</u></th><th bgcolor='green'><u>Roll No.</u></th><th bgcolor='green'><u>Name</u></th></tr>";
while ($db_field =mysql_fetch_assoc($result))
{
$std=$db_field['std'];
$section=$db_field['section'];
$roll=strtoupper($db_field['roll']);
$name=strtoupper($db_field['name']);

$table="$table<tr><td bgcolor='yellow'>$std$section</td><td bgcolor='yellow'>$roll</td><td bgcolor='yellow'>$name</td></tr>";
}
mysql_close($con);
$table="$table</table>";
return $table;
}
?>


<?
function Receipt($tid)
{
$tid=trim($tid);
$sql="SELECT * FROM `school_fee` WHERE `transid`='$tid'";
$name=trim(getInfo($sql,'name'));
$schoolid=getInfo($sql,'schoolid');
if (($schoolid=='') or ($name==''))
{
return "<center>Wrong Receipt#</center>";
}
$regno=getInfo($sql,'regno');
$roll=getInfo($sql,'roll');
$allocation=getInfo($sql,'allocation');
$std=getInfo($sql,'std');
$user=getInfo($sql,'userid');
$section=getInfo($sql,'section');
$amount=getInfo($sql,'amount');
$month=getInfo($sql,'month');
$rupees=number_to_word( "$amount");
$remarks=getInfo($sql,'remarks');
$rcvdate=@date("d-m-Y");
//------------------------------------------------------
$sql="SELECT * FROM `school_fee` WHERE `transid`='$tid'";
$schoolid=getInfo($sql,'schoolid');
//------------------------------------------------------
$sql="SELECT * FROM `school_clients` WHERE `schoolid`='$schoolid'";
$schoolname=getInfo($sql,'name');
$address=getInfo($sql,'address');
$sql="SELECT * FROM `school_students` WHERE `regno`='$regno' And `schoolid`='$schoolid'";
$father=getInfo($sql,'father');

$msg='<center><table style="opacity:1;border: 1px solid green;">';
$msg=$msg."<tr>
<td colspan='3' width='200px' style='opacity:1;border: 1px solid green;'>
<center><img src='schoolico.png' width='0px' height:'0px'> </center>
<b><center><font style='font-size:12pt;'>$schoolname</font></center> </b>
<center>
<font style='font-size:11pt;'>$address</font> 
</br></center></td></tr>
<tr><td style='opacity:1;border: 1px solid green;'><center><b>Money Receipt </b></center>
<br/> <img alt='' src='barcode.php?text=$tid&size=15'>
<br/>No.: $tid <br/>Date:$rcvdate 
<br/>Reg.No.:$regno
Class: $std $section  ; Roll No.:$roll
<td style='opacity:1;border: 1px solid green;'> <font style='font-size:10pt;'><b>
Student Name: $name 
<br/>Father : $father 
<br>Amount Paid : Rs.$amount (Rupees $rupees ) only 
<br/>Payment made for :$allocation
<br/>Monthly/Tuition fee included :$month
<br/>Remarks: $remarks 
<br/> <p style='text-align:right;'>Cashier($user)</p></font>
</td></tr></table>";
return $msg ;
}

?>

<?

function viewUsers()
{
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
$sql="SELECT * FROM `school_users`";
$result = mysql_query($sql);
$table="<table><tr><th bgcolor='green'><u>UserID</u></th><th bgcolor='green'><u>Name</u></th><th bgcolor='green'><u>Authority</u></th></tr>";
while ($db_field =mysql_fetch_assoc($result))
{
$name=$db_field['name'];
$user=$db_field['user'];
$role=strtoupper($db_field['role']);
$table="$table<tr><td bgcolor='yellow'>$user</td><td bgcolor='yellow'>$name</td><td bgcolor='yellow'>$role</td></tr>";
}
mysql_close($con);
$table="$table</table>";
return $table;
}
//----------------------------Function for admission---------------------------------------------
?>

<?
    function number_to_word( $num = '' )
    {
        $num    = ( string ) ( ( int ) $num );
       
        if( ( int ) ( $num ) && ctype_digit( $num ) )
        {
            $words  = array( );
           
            $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
           
            $list1  = array('','one','two','three','four','five','six','seven',
                'eight','nine','ten','eleven','twelve','thirteen','fourteen',
                'fifteen','sixteen','seventeen','eighteen','nineteen');
           
            $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
                'seventy','eighty','ninety','hundred');
           
            $list3  = array('','thousand','million','billion','trillion',
                'quadrillion','quintillion','sextillion','septillion',
                'octillion','nonillion','decillion','undecillion',
                'duodecillion','tredecillion','quattuordecillion',
                'quindecillion','sexdecillion','septendecillion',
                'octodecillion','novemdecillion','vigintillion');
           
            $num_length = strlen( $num );
            $levels = ( int ) ( ( $num_length + 2 ) / 3 );
            $max_length = $levels * 3;
            $num    = substr( '00'.$num , -$max_length );
            $num_levels = str_split( $num , 3 );
           
            foreach( $num_levels as $num_part )
            {
                $levels--;
                $hundreds   = ( int ) ( $num_part / 100 );
                $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
                $tens       = ( int ) ( $num_part % 100 );
                $singles    = '';
               
                if( $tens < 20 )
                {
                    $tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
                }
                else
                {
                    $tens   = ( int ) ( $tens / 10 );
                    $tens   = ' ' . $list2[$tens] . ' ';
                    $singles    = ( int ) ( $num_part % 10 );
                    $singles    = ' ' . $list1[$singles] . ' ';
                }
                $words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
            }
           
            $commas = count( $words );
           
            if( $commas > 1 )
            {
                $commas = $commas - 1;
            }
           
            $words  = implode( ', ' , $words );
           
            //Some Finishing Touch
            //Replacing multiples of spaces with one space
            $words  = trim( str_replace( ' ,' , ',' , trim_all( ucwords( $words ) ) ) , ', ' );
            if( $commas )
            {
                $words  = str_replace_last( ',' , ' and' , $words );
            }
           
            return $words;
        }
        else if( ! ( ( int ) $num ) )
        {
            return 'Zero';
        }
        return '';
    }

    function str_replace_last( $search , $replace , $str ) {
        if( ( $pos = strrpos( $str , $search ) ) !== false ) {
            $search_length  = strlen( $search );
            $str    = substr_replace( $str , $replace , $pos , $search_length );
        }
        return $str;
    }


    function trim_all( $str , $what = NULL , $with = ' ' )
    {
        if( $what === NULL )
        {
            //  Character      Decimal      Use
            //  "\0"            0           Null Character
            //  "\t"            9           Tab
            //  "\n"           10           New line
            //  "\x0B"         11           Vertical Tab
            //  "\r"           13           New Line in Mac
            //  " "            32           Space
           
            $what   = "\\x00-\\x20";    //all white-spaces and control chars
        }
       
        return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
    }
//-----------------------------------------------------------------End---------------------------
?>

