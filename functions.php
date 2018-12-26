







 



 








 



 
<?
set_time_limit(60000);
?>
<?
function GetCommaSeparatedData($sql,$commapara)
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
$commasepdata="";
while ($db_field =mysql_fetch_assoc($result))
{
$commasepdata=$commasepdata.trim($db_field["$commapara"]).",";
}
mysql_close($con);
return trim($commasepdata) ;
}
?>

<?
// Don't edit any section after this point 
function getInfo($sql,$rtnfld)
{
Global $dbserver,$dbuser,$dbpwd,$dbname ; 
$con = @mysql_connect("$dbserver","$dbuser","$dbpwd");
if (!$con)
  {
  die(hash("sha256","couldnotconnect"));
  }
$db_found=mysql_select_db("$dbname", $con);
if (!$db_found){
echo hash("sha256","connectedbutdbcannotbeopened");
}
$result = mysql_query($sql);
while ($db_field =@mysql_fetch_assoc($result))
{
$id=$db_field[$rtnfld];
}
mysql_close($con);

return $id;
}

?>

<?
function upDate($sql)
{
Global $dbserver,$dbuser,$dbpwd,$dbname ; 
$con = @mysql_connect($dbserver,$dbuser,$dbpwd);
if (!$con)
  {
  die('CouldNotBeConnected');
  }
$db_found=mysql_select_db("$dbname", $con);
if (!$db_found){
echo hash("sha256","connectedbutdbcannotbeopened");
}
$result = mysql_query($sql);
mysql_close($con);
}
?>





<?php

function timeDiff($firstTime,$lastTime)
{

// convert to unix timestamps
$firstTime=strtotime($firstTime);
$lastTime=strtotime($lastTime);

// perform subtraction to get the difference (in seconds) between times
$timeDiff=$lastTime-$firstTime;

// return the difference
return $timeDiff;
}

?> 

<?php
 
function getipaddress()
{
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}
?>

<?php
function getRefNo()
{
$sql="SELECT * FROM `apaibipayment`";
$refno=getInfo($sql,"refno");
$refno=$refno+1;
return "$refno" ;
}
?>

<?php
function sendMymail($to,$subject,$message,$from)
{
$headers= 'MIME-Version: 1.0' . "\r\n";
$headers.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        //$headers. = "From:" . $from;
mail($to,$subject,$message,$headers);
}
?> 
<?
function AngaobaWakhalSing()
{
Global $schoolid,$dbserver, $dbuser, $dbname, $dbpwd,$key, $schoolname ;
//----------------------------------
$installdata=explode("~",trim(muthi("install.db",$str,"r")));
$schoolid=$installdata[0];
$dbserver=$installdata[5];
$dbuser=$installdata[6];
$dbname=$installdata[7];// 
$dbpwd=$installdata[8];
if (($dbpwd=='')  or ($schoolid==''))
{
return "";
}
$sql="SELECT * FROM `school_clients` WHERE `schoolid`='$schoolid'";
$schoolname=getInfo($sql,"name");
$key=getInfo($sql,"key");
}
?>

<?
function installsuccess()
{
$fp = fopen('install.php', 'w');
fwrite($fp, "");
fclose($fp);
}
?> 

<?
function createFileContent($flname,$str)
{
$fp = fopen("$flname", 'w');
fwrite($fp, "$str");
fclose($fp);
}

function muthi($flname,$str,$wr)
{
if (($str!='') and ($wr=="w"))
{
$str=base64_encode($str);
$fp = fopen("$flname", 'w');
fwrite($fp, "$str");
fclose($fp);
}

if (($wr=="r"))
{
$html=trim(file_get_contents("$flname"));
$subi=base64_decode($html);
return $subi ;
}

}
?>
<?
function CallInstallation($skey)
{
echo "<div class='loader'></div><br/>Wait installing...<br/>";
$chkkey=trim(file_get_contents("http://kulhu.com/ischool/install.php?key=$skey"));
$form="<div style='margin:auto;top: 0;right: 0;bottom: 0;left: 0; width:300px;height:300px;position: absolute;'>
<form method='get' action=''><img src='ischoollogo.jpg' border='0'><br/><font color='red'><b>You entered wrong Serial Key</b></font>
<input  style='border: 2px solid green; border-radius: 4px;' type='text' value='' name='key' placeholder='Enter Serial Key'>
<button type='submit' title='Submit'><em>Submit</em></button></form></div></body</html>";

if ($chkkey=='')
{
die( $form ) ;
} 
createFileContent("install.db",$chkkey);
Global $schoolid,$dbserver, $dbuser, $dbname, $dbpwd ;
$installdata=explode("~",trim(muthi("install.db",$str,"r")));
$schoolid=$installdata[0];
$dbserver=$installdata[5];
$dbuser=$installdata[6];
$dbname=$installdata[7];
$dbpwd1=$installdata[8];
$dbpwd2=$installdata[9];
$dbpwd=$dbpwd1;
$yathang=trim(file_get_contents("http://kulhu.com/ischool/install.php?key=$skey&action=update")); 
$yathang=base64_decode($yathang);
$yathangmachet=explode("~",$yathang);
$yathangsize=sizeof($yathangmachet);
$sl=0;
while ($yathangsize>=0)
{
$yathangsize=($yathangsize-1); 
$cmdsql=trim($yathangmachet[$sl]);
$sl=$sl+1;
upDate($cmdsql);
echo "<br/>Step $sl completed";
}
echo "<br>All Completed" ;
echo "<script>location.replace('pangkhatlu.php');</script>";
}
?>

<?
function curl_get_contents($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
?>
<?
function getserver()
{
$server="http://".gethostname()."/ischool"; 
gethostbyaddr($_SERVER['REMOTE_ADDR']);
$rtn="Access <br/> @ $server <br/> or <br/> $serverip";
$rtn.="http://".getHostByName(getHostName())."/ischool/";
return $rtn ;
}
?>


