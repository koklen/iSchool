







 



 








 



 
<!DOCTYPE html>
<html>
  <head>
<?
include("functions.php");
AngaobaWakhalSing();
?>
<title>
<?
echo $schoolname;
?>
</title>
<link rel="stylesheet" type="text/css" href="school.css">
</head>
<body style="background-color:green";>
<div id="container" style="padding: 10px; border: 2px solid yellow; border-radius: 10px;width:80%;height:80%;margin:auto;">
<div style="background-color: #b0c4de;">
<center>
<b>
<br/>
<?
echo $schoolname;
?>
<br/>
</b>
<form method="post" action="user.php">
<br/>
<input type="hidden" value="<? echo $key ; ?>" name="clientkey">
User - ID:<input type="text" value="" name="user">
<br/>
<br/>
Password:<input type="password" value="" name="pwd">
<br/>
<br/>
<input type="submit" value="Sign in " name="submit">
<br/>
</form>
<br/>
</center>
  <div/>
        <div/>
<body/>
<html/>
