







 



 








 



 
<?
$sessionid=trim($_GET['sessionid']);
include "functions.php"; 
AngaobaWakhalSing();
$sql="SELECT * FROM `school_users` WHERE `sessionid`='$sessionid' ";
$sesid=getInfo($sql,'sessionid');
$role=getInfo($sql,'role');
$enable=getInfo($sql,'enabled');
$schoolid=getInfo($sql,'schoolid');
$user=getInfo($sql,'user');
//--------------------------------------------------------------------------

if (($sesid!=$sessionid) Or ($sessionid==''))
{
die( "<html><head><title>Access denied!</title></head><body>Oops !Access denied!</body></html>");
}

if (($role!='admin') And ($enable=='no'))
{
die( "<html><head><title>Access denied!</title></head><body>Oops !Access denied!</body></html>");
}
?>

<?php
Global $dbserver,$dbuser,$dbpwd,$dbname ; 
$dbhost="$dbserver";
$dbuser="$dbuser";
$dbpass="$dbpwd";
$dbname="$dbname";
$backupdate=@date("Y-m-d");

include ('dumper.php');

try {
	$world_dumper = Shuttle_Dumper::create(array('host' => '','username' => 'root','password' => $dbpwd,'db_name' => "$dbname",));

	// dump the database to gzipped file
	$world_dumper->dump('ischooldb.gz');

	// dump the database to plain text file
	//$world_dumper->dump('world.sql');

} catch(Shuttle_Exception $e) {
	die( "<html><body> Couldn't dump database: " . $e->getMessage()."</body></html>");
}
$str=file_get_contents("ischooldb.gz");
muthi("ischooldb.gz","$str","w");
echo '<html><body><center>Backuped ! <br/> <a href="ischooldb.gz">Download it</a></a>
<br/>
</body></html>';
       
?>


