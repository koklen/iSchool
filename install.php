







 



 








 


<? 
set_time_limit(60000);
$skey=trim($_GET["key"]);
$slkey=$skey;
$form="<b><font color='red'>Warning !<br/> If you have already installed iSchool,all the data of your institute
will be lost.<br/> If you intend to reinstall it, it is better backup your data first</font>
</b><div style='margin: auto;  top: 0;right: 0;bottom: 0;left: 0; width:300px;height:300px;position: absolute;'>
<form method='get' action=''><img src='ischoollogo.jpg' border='0'><br/><input  style='border: 2px solid green; border-radius: 4px;' type='text' value='' name='key' placeholder='Enter Serial Key'>
<button type='submit' title='Submit'><em>Submit</em></button>
</form>
</div>";
?>
<!DOCTYPE html>
<html>
<head>
<title>iSchool installation
</title>
<link rel="stylesheet" type="text/css" href="kulhustyle.css">
<meta name="description" content="Login page of Smart School/">
<meta name="keywords" content=""/>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 60px;
  height: 60px;
  -webkit-animation: spin 1s linear infinite; /* Safari */
  animation: spin 1s linear infinite;
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

.parent {
  position: relative;
}
.child {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

</style>
</head>
<body>
<center>
<?
if ($skey=='')
{
echo "$form";
} 
else
{
@mkdir("photo");//Making a photo directory 
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=action.php&slkey=$slkey","action.php");
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=user.php&slkey=$slkey","user.php");
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=backupdb.php&slkey=$slkey","backupdb.php");
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=barcode.php&slkey=$slkey","barcode.php");
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=certificate.css&slkey=$slkey","certificate.css");
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=comwithkulhu.php&slkey=$slkey","comwithkulhu.php");
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=dumper.php&slkey=$slkey","dumper.php");
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=functions.php&slkey=$slkey","functions.php");
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=html2pdf.php&slkey=$slkey","html2pdf.php");
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=install.db&slkey=$slkey","install.db");
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=javascriptpost.php&slkey=$slkey","javascriptpost.php");
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=jscript.txt&slkey=$slkey","jscript.txt");
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=jscriptexample.txt&slkey=$slkey","jscriptexample.txt");
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=pangkhatlu.php&slkey=$slkey","pangkhatlu.php");
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=printaction.php&slkey=$slkey","printaction.php");
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=readme.txt&slkey=$slkey","readme.txt");
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=school.css&slkey=$slkey","school.css");
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=securelogin.php&slkey=$slkey","securelogin.php");
$checkerr=trim(File_Get_Contents("securelogin.php"));
if (($checkerr=="Failed") or  ($checkerr==""))
{
die("$form");
}
else
{
@copyAndPasteFile("http://kulhu.com/ischool/installation/abcfiles.php?filename=index.php&slkey=$slkey","index.php");
include("functions.php"); 
CallInstallation($skey); 
}
}
?>
</center>
</body>
</html>


<?php
function copyAndPasteFile($source,$destination)
{
if (!copy($source, $destination)) {
    echo "failed to copy $file...\n";
}
}
?>