







 



 








 



 
<?
$action=$_GET['action'];
$clientkey=trim($_GET['schoolkey']);
$schoolid=trim($_GET['schoolid']);
$sms=trim($_GET['sms']);
$regno=strtoupper(trim($_GET['regno']));
$std=strtoupper(trim($_GET['std']));
$section=strtoupper(trim($_GET['section']));
$year=strtoupper(trim($_GET['year']));
$roll=trim($_GET['roll']);
$mobile=trim($_GET['mobile']);
$smsto=trim($_GET['smsto']);
include 'functions.php';
AngaobaWakhalSing();
$smsori=$sms ;

    // use 80 for http or 443 for https protocol
    $connected = @fsockopen("kulhu.com",80);
    if ($connected){
        fclose($connected);
   }
    else 
    {
    die("Internet connection is needed for SMS related services");
    }



if ($action=="viewsmsbalance")
{
	$url="http://kulhu.com/ischool/action.php";
	$myvars="action=viewsmsbalance&schoolid=$schoolid&clientkey=$clientkey";
	$smsbalance=PostFormSubmit($url,$myvars);
	die("$smsbalance");
}
$sms=urlencode($sms);

//--------------------------------------------------------------
if (($action=='sendsmstodirectmobile'))
{
	if ((strlen($smsori)>150) or (strlen($smsori)<=1))
	{
	die("Numbers of character should be in between 1 and 150.");
	}
	//------------------------------
	$url="http://kulhu.com/ischool/action.php";
	$myvars="schoolid=$schoolid&clientkey=$clientkey&action=sendsms&sms=$sms&mobile=$mobile,";
	$resp=trim(PostFormSubmit($url,$myvars));
	if (is_numeric($resp))
	{
	$smsdate=@date("Y-m-d");
	$smsid=@date("YmdHis")."S".$schoolid."U".$user."R".rand(100,999);
	//---------------------------------------------------------------
	$smstype="Mobile";
	$phones=$mobile;
	$sql="INSERT INTO `school_sms_data` (`schoolid`,`sms`,`smsid`,`status`,`smstype`,`smsdate`,`totalsms`)
                             VALUES ('$schoolid','$smsori','$smsid','sent','$smstype','$smsdate','$resp')";
	upDate($sql);
	die("The SMS \"$smsori\" sent to following $resp mobile number <br/>$phones");
	}
	else { die($resp);}
}//end of sensmstodirectmobile 

if (($action=='sendsmstoroll')) //<<<AA
	{
	if ((strlen($smsori)>150) or (strlen($smsori)<=1))
	{
	die("Numbers of character should be in between 1 and 150.");
	}
	//---------------------------------------------------------------
	$sql="SELECT * FROM `school_classes` WHERE `schoolid`='$schoolid' And `session`='$year' And `std`='$std' And `section`='$section' And `roll`='$roll'";
	$regno=trim(getInfo($sql,'regno'));
	$sql="SELECT * FROM `school_students` WHERE `schoolid`='$schoolid' And `regno`='$regno'";
	$mobile=trim(getInfo($sql,"phone"));
	if ($mobile==''){ die("Mobile No. of Parent of [Session: $year ;Class: $std $section ; Roll No.:$roll ] is not found.");}
	//$resp=trim(@file_get_contents("http://kulhu.com/ischool/action.php?schoolid=$schoolid&clientkey=$clientkey&action=sendsms&sms=$sms&mobile=$mobile,"));
	$url="http://kulhu.com/ischool/action.php";
	$myvars="schoolid=$schoolid&clientkey=$clientkey&action=sendsms&sms=$sms&mobile=$mobile,";
	$resp=trim(PostFormSubmit($url,$myvars));
	if (is_numeric($resp))
	{
	$smsdate=@date("Y-m-d");
	$smsid=@date("YmdHis")."S".$schoolid."U".$user."R".rand(100,999);
	//---------------------------------------------------------------
	$smstype="Mobile";
	$smstype="parent";
	$sql="INSERT INTO `school_sms_data` (`schoolid`,`sms`,`smsid`,`status`,`smstype`,`smsdate`,`totalsms`)
                             VALUES ('$schoolid','$smsori','$smsid','sent','$smstype','$smsdate','$resp')";
	upDate($sql);
	die("The SMS \"$smsori\" sent to mobile number $mobile");
	}
	else { die($resp);}
}//AA>>>
if (($action=='sendsmstoparent')) //<<<BB
	{
	if ((strlen($smsori)>150) or (strlen($smsori)<=1))
	{
	die("Numbers of character should be in between 1 and 150.");
	}
	//---------------------------------------------------------------
	$sql="SELECT * FROM `school_students` WHERE `schoolid`='$schoolid' And `regno`='$regno'";
	$mobile=trim(getInfo($sql,"phone"));
	if ($mobile==''){ die("Mobile No. $mobile of parent bearing Reg.No.$regno is not found.");}
	$url="http://kulhu.com/ischool/action.php";
	$myvars="schoolid=$schoolid&clientkey=$clientkey&action=sendsms&sms=$sms&mobile=$mobile,";
	$resp=trim(PostFormSubmit($url,$myvars));
	if (is_numeric($resp))
	{
	$smsdate=@date("Y-m-d");
	$smsid=@date("YmdHis")."S".$schoolid."U".$user."R".rand(100,999);
	//---------------------------------------------------------------
	$smstype="Mobile";
	$smstype="parent";
	$sql="INSERT INTO `school_sms_data` (`schoolid`,`sms`,`smsid`,`status`,`smstype`,`smsdate`,`totalsms`)
                             VALUES ('$schoolid','$smsori','$smsid','sent','$smstype','$smsdate','$resp')";
	upDate($sql);
	die("The SMS \"$smsori\" sent to $resp mobile number $mobile");
	}
	else { die($resp);}	
}//BB>>>

if (($action=='sendsmstoparents') and ($smsto=='all')) //<<<ccccc
	{
	if ((strlen($smsori)>150) or (strlen($smsori)<=1))
	{
	die("Numbers of character should be in between 1 and 150.");
	}
	//---------------------------------------------------------------
	$sql="SELECT * FROM `school_classes` WHERE `schoolid`='$schoolid' And `session`='$year'";
        $regnos=GetCommaSeparatedDataStr($sql,'regno');
	$regnos=str_replace(",''","","$regnos");
        $sql="SELECT * FROM `school_students` WHERE `schoolid`='$schoolid' And `regno` IN ($regnos)";
        $mobile=GetCommaSeparatedData($sql,'phone')."x";
        $mobile=str_replace(",x","","$mobile");
      	if ($mobile==''){ die("Mobile No. of parent is not found.");}
        $url="http://kulhu.com/ischool/action.php";
	$myvars="schoolid=$schoolid&clientkey=$clientkey&action=sendsms&sms=$sms&mobile=$mobile,";
        $resp=trim(PostFormSubmit($url,$myvars));
	//$resp=trim(@file_get_contents("http://kulhu.com/ischool/action.php?schoolid=$schoolid&clientkey=$clientkey&action=sendsms&sms=$sms&mobile=$mobile,"));
	if (is_numeric($resp))
	{
	$smsdate=@date("Y-m-d");
	$smsid=@date("YmdHis")."S".$schoolid."U".$user."R".rand(100,999);
	//---------------------------------------------------------------
	$smstype="Mobile";
	$smstype="parent";
	$sql="INSERT INTO `school_sms_data` (`schoolid`,`sms`,`smsid`,`status`,`smstype`,`smsdate`,`totalsms`)
                             VALUES ('$schoolid','$smsori','$smsid','sent','$smstype','$smsdate','$resp')";
	upDate($sql);
	die("The SMS \"$smsori\" sent to $resp mobile number");
	}
	else { die($resp);}
}//ccccccccc>>>


if (($action=='sendsmstoparents') and ($smsto=='absentee')) //<<<ddddddddddddddddddddddd
	{
	$smsdate=@date("Y-m-d");
	if ((strlen($smsori)>150) or (strlen($smsori)<=1))
	{
	die("Numbers of character should be in between 1 and 150.");
	}
	//---------------------------------------------------------------
	$sql="SELECT * FROM `school_sms_data` WHERE `schoolid`='$schoolid' And `smsdate`='$smsdate' And `smstype`='attendance'";
        $csmsid=getInfo($sql,'smsid');
        if ($csmsid!='')
        {
	die("Absence information already sent to parents for today.");
	}
	$smstype="attendance";
	$sql="SELECT * FROM `school_attendance` WHERE `schoolid`='$schoolid' And `attendance`='A' And `date`='$smsdate'";
	$regno=getInfo($sql,'regno');
	if ($regno=='')
	{
	die("There is no absentee today.");
	}
	$regnos=GetCommaSeparatedDataStr($sql,'regno');
	$regnos=str_replace(",''","","$regnos");
        $sql="SELECT * FROM `school_students` WHERE `schoolid`='$schoolid' And `regno` IN ($regnos)";
        $mobile=GetCommaSeparatedData($sql,'phone')."x";
        $mobile=str_replace(",x","","$mobile");  
	if ($mobile==''){ die("Mobile No. of parent is not found.");}
        $url="http://kulhu.com/ischool/action.php";
	$myvars="schoolid=$schoolid&clientkey=$clientkey&action=sendsms&sms=$sms&mobile=$mobile,";
        $resp=trim(PostFormSubmit($url,$myvars));
	if (is_numeric($resp))
	{
	$smsdate=@date("Y-m-d");
	$smsid=@date("YmdHis")."S".$schoolid."U".$user."R".rand(100,999);
	//---------------------------------------------------------------
	$smstype="Mobile";
	$smstype="attendance";
	$sql="INSERT INTO `school_sms_data` (`schoolid`,`sms`,`smsid`,`status`,`smstype`,`smsdate`,`totalsms`)
                             VALUES ('$schoolid','$smsori','$smsid','sent','$smstype','$smsdate','$resp')";
	upDate($sql);
	die("The SMS \"$smsori\" sent to $resp mobile number");
	}
	else { die($resp);}
}//dddddddddddddddddddddd>>>

if ($action=='sendsmstostdparents') //<<<eeeeeeeee
	{
	if ((strlen($smsori)>150) or (strlen($smsori)<=1))
	{
	die("Numbers of character should be in between 1 and 150.");
	}
	//---------------------------------------------------------------
	$sql="SELECT * FROM `school_classes` WHERE `schoolid`='$schoolid' And `session`='$year' And `std`='$std'";
        $regnos=GetCommaSeparatedDataStr($sql,'regno');
	$regnos=str_replace(",''","","$regnos");
        $sql="SELECT * FROM `school_students` WHERE `schoolid`='$schoolid' And `regno` IN ($regnos)";
        $mobile=GetCommaSeparatedData($sql,'phone')."x";
        $mobile=str_replace(",x","","$mobile");
      	if ($mobile==''){ die("Mobile No. of parent is not found.");}
        $url="http://kulhu.com/ischool/action.php";
	$myvars="schoolid=$schoolid&clientkey=$clientkey&action=sendsms&sms=$sms&mobile=$mobile,";
        $resp=trim(PostFormSubmit($url,$myvars));
	//$resp=trim(@file_get_contents("http://kulhu.com/ischool/action.php?schoolid=$schoolid&clientkey=$clientkey&action=sendsms&sms=$sms&mobile=$mobile,"));
	if (is_numeric($resp))
	{
	$smsdate=@date("Y-m-d");
	$smsid=@date("YmdHis")."S".$schoolid."U".$user."R".rand(100,999);
	//---------------------------------------------------------------
	$smstype="Mobile";
	$smstype="parent";
	$sql="INSERT INTO `school_sms_data` (`schoolid`,`sms`,`smsid`,`status`,`smstype`,`smsdate`,`totalsms`)
                             VALUES ('$schoolid','$smsori','$smsid','sent','$smstype','$smsdate','$resp')";
	upDate($sql);
	die("The SMS \"$smsori\" sent to $resp mobile number");
	}
	else { die($resp);}
}//eeeeee>>>

if ($action=='sendsmsstaffs')//<<<ffffff
	{
	if ((strlen($smsori)>150) or (strlen($smsori)<=1))
	{
	die("Numbers of character should be in between 1 and 150.");
	}
	if ($smsto=='all')
	{
		$sql="SELECT * FROM `school_staffs` WHERE `schoolid`='$schoolid'";
	}

	if ($smsto=='teacher')
	{
	$sql="SELECT * FROM `school_staffs` WHERE `schoolid`='$schoolid' and `designation`='teacher'";
	}
	if ($smsto=='clerk')
	{
	$sql="SELECT * FROM `school_staffs` WHERE `schoolid`='$schoolid' and `designation`='clerk'";
	}

	//---------------------------------------------------------------
	$mobile=GetCommaSeparatedData($sql,'mobile')."x";
        $mobile=str_replace(",x","","$mobile");
      	if ($mobile==''){ die("No mobile of staff is found.");}
        $url="http://kulhu.com/ischool/action.php";
	$myvars="schoolid=$schoolid&clientkey=$clientkey&action=sendsms&sms=$sms&mobile=$mobile,";
        $resp=trim(PostFormSubmit($url,$myvars));
	if (is_numeric($resp))
	{
	$smsdate=@date("Y-m-d");
	$smsid=@date("YmdHis")."S".$schoolid."U".$user."R".rand(100,999);
	//---------------------------------------------------------------
	$smstype="staff";
	$sql="INSERT INTO `school_sms_data` (`schoolid`,`sms`,`smsid`,`status`,`smstype`,`smsdate`,`totalsms`)
                             VALUES ('$schoolid','$smsori','$smsid','sent','$smstype','$smsdate','$resp')";
	upDate($sql);
	die("The SMS \"$smsori\" sent to $resp mobile number");
	}
	else { die($resp);}
}//fffffffff>>>

?>

<?
function GetCommaSeparatedDataStr($sql,$commapara)
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
$commasepdata.="'".$db_field["$commapara"]."',";
}
mysql_close($con);
return "$commasepdata''" ;
}
?>




<?
function ConvertToSmsText($sms)
{
$smsarray=explode(" ",$sms);
$nosofwrd=count($smsarray);
$i=0;
$smstext=$smsarray[0];
while($i<$nosofwrd)
{
$i=$i+1;
$smstext=$smstext."+".$smsarray[$i];
}

return $smstext ;
}
?>

<?
function PostFormSubmit($url,$myvars)
{
//$url = "http://www.kulhu.com/ischool/action.php";
//$myvars = "=' . $myvar1 . '&myvar2=' . $myvar2";
$ch = curl_init( $url );
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt( $ch, CURLOPT_HEADER, 0);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec( $ch );
return $response ;
}
?>

