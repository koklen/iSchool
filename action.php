







 



 

<?
$sessionid=trim($_GET['sessionid']);
$action=trim($_GET['action']);
$name=strtoupper(trim($_GET['name']));
$attendance=strtoupper(trim($_GET['attendance']));
$disableenable=strtolower(trim($_GET['disableenable']));
$userid=trim($_GET['userid']);
$regno=strtoupper(trim($_GET['regno']));
$month=strtoupper(trim($_GET['month']));
$pwd1=trim($_GET['pwd1']);
$pwd2=trim($_GET['pwd2']);
$roleuser=trim($_GET['role']);
$std=strtoupper(trim($_GET['std']));
$section=strtoupper(trim($_GET['section']));
$year=strtoupper(trim($_GET['session']));
$roll=trim($_GET['roll']);
$amount=trim($_GET['amount']);
$allocation=strtoupper(trim($_GET['allocation']));
$remarks=trim($_GET['remarks']);
$father=strtoupper(trim($_GET['father']));
$mother=strtoupper(trim($_GET['mother']));
$address=strtoupper(trim($_GET['address']));
$phone=strtoupper(trim($_GET['phone']));
$po=strtoupper(trim($_GET['po']));
$ps=strtoupper(trim($_GET['ps']));
$district=strtoupper(trim($_GET['district']));
$state=strtoupper(trim($_GET['state']));
$country=strtoupper(trim($_GET['country']));
$pin=strtoupper(trim($_GET['pin']));
$dob=strtoupper(trim($_GET['dob']));
$sex=strtoupper(trim($_GET['sex']));
$nationality=strtoupper(trim($_GET['nationality']));
$religion=strtoupper(trim($_GET['religion']));
$caste=strtoupper(trim($_GET['caste']));
$occupationofparent=strtoupper(trim($_GET['occupationofparent']));
$incomeofparent=strtoupper(trim($_GET['incomeofparent']));
$sessn=strtoupper(trim($_GET['session']));
$getwhat=trim($_GET['getwhat']);
$year=trim($_GET['year']);
$date=trim($_GET['date']);
$date1=trim($_GET['date1']);
$stdid=strtoupper(trim($_GET['stdid']));
$update=trim($_GET['update']);
$subid=strtoupper(trim($_GET['subid']));
$subject=trim($_GET['subject']);
$allocationid=strtoupper(trim($_GET['allocationid']));
$examid=strtoupper(trim($_GET['examid']));
$exam=trim($_GET['exam']);
$mark=trim($_GET['mark']);
$maxmark=trim($_GET['maxmark']);
//<BM----Setmenus-----------------------
$buttons=trim($_GET['buttons']);
//---------BM>
//----------------Highlight------------------
$highlight=trim($_GET['highlight']);
$recordno=trim($_GET['recordno']);
//---------------------------------
$staffid=strtoupper(trim($_GET['staffid']));
$staffname=strtoupper(trim($_GET['staffname']));
$designation=strtoupper(trim($_GET['designation']));
$mobile=trim($_GET['mobile']);
$table=trim($_GET['table']);
$transid=trim($_GET['transid']);
//-----------------------------------------
$sms=trim($_GET['sms']);
$smsto=trim($_GET['smsto']);
//-----------------------------------------
if ($sessionid=='')
{
$sessionid=trim($_POST['sessionid']);
$roll=trim($_POST['roll']);
$sessn=trim($_POST['sessn']);
$regno=trim($_POST['regno']);
$std=trim($_POST['std']);
$section=trim($_POST['section']);
$action=trim($_POST['action']);
$photosign=trim($_POST['ppsign']);
$photoname = strtolower($_FILES['photo']['name']);
$errfile=$_FILES["photo"]["error"] ;
$size = $_FILES['photo']['size'];
$filetype=$_FILES["photo"]["type"];
//die("Admitted as Reg.No. $regno ; Class:$std ; Section:$section ; File:$file ($size KB); Action: $action; ");
}
$smss=ConvertToSmsText($sms);
include "functions.php"; 
AngaobaWakhalSing();
$sql="SELECT * FROM `school_users` WHERE `sessionid`='$sessionid' ";
$sesid=getInfo($sql,'sessionid');
$role=getInfo($sql,'role');
$enable=getInfo($sql,'enabled');
$schoolid=getInfo($sql,'schoolid');
$user=getInfo($sql,'user');
//---------------------------------
$sql="SELECT * FROM `school_clients` WHERE `schoolid`='$schoolid' ";
$abbreviation=getInfo($sql,'abbreviation');
$pcrole=getInfo($sql,'pcrole');
$smss="$smss%0A$abbreviation";
//--------------------------------------------------------------------------

if (($sesid!=$sessionid) Or ($sessionid==''))
{
die( "Access denied!");
}

if (($role!='admin') And ($enable=='no'))
{
die( "Access denied!");
}

else
{
//-------------------------------------------------
if (($action=='sendsmstodirectmobile')and ($role=='admin'))
{
if ((strlen($sms)>150) or (strlen($sms)<=1))
{
die("Numbers of character should be in between 1 and 150.");
}
$sql="SELECT * FROM `school_sms` WHERE `schoolid`='$schoolid' ";
$smsvol=getInfo($sql,'smsvol');
if ($smsvol<=0)
{
die("Insufficient balance!");
}

$smsdate=@date("Y-m-d");
$smsid=@date("YmdHis")."S".$schoolid."U".$user."R".rand(100,999);
//---------------------------------------------------------------
$smstype="Mobile";
$phones=$mobile;
if($phones=='')
{
die("The mobile# not given!");
}
//---------------------------------
$totalsms=1;
if ($smsvol<$totalsms)
{
die("Insufficient balance!");
}
//-------------------------------
$sql="UPDATE `school_sms` SET `smsvol`=`smsvol`-$totalsms WHERE `schoolid`='$schoolid'";
upDate($sql);
//------------------------------
$resp=SendSMS($smss,$phones);
$sql="INSERT INTO `school_sms_data` (`schoolid`,`sms`,`smsid`,`status`,`smstype`,`smsdate`,`totalsms`)
                             VALUES ('$schoolid','$sms','$smsid','$resp','$smstype','$smsdate','$totalsms')";
upDate($sql);
die("The SMS \"$sms\" sent to following mobile number <br/>$phones");
}

        //----------------------------------------

if (($action=='sendsmstoparent')and ($role=='admin'))
{
if ((strlen($sms)>150) or (strlen($sms)<=1))
{
die("Numbers of character should be in between 1 and 150.");
}
$sql="SELECT * FROM `school_sms` WHERE `schoolid`='$schoolid' ";
$smsvol=getInfo($sql,'smsvol');
if ($smsvol<=0)
{
die("Insufficient balance!");
}

$smsdate=date("Y-m-d");
$smsid=date("YmdHis")."S".$schoolid."U".$user."R".rand(100,999);
//---------------------------------------------------------------
$smstype="parent";
$sql="SELECT * FROM `school_students` WHERE `schoolid`='$schoolid' And `regno`='$regno'";
$phones=trim(getInfo($sql,'phone'));
if($phones=='')
{
die("The mobile no. of parent is not found.");
}
//---------------------------------
$totalsms=1;
if ($smsvol<$totalsms)
{
die("Insufficient balance!");
}
//-------------------------------
$sql="UPDATE `school_sms` SET `smsvol`=`smsvol`-$totalsms WHERE `schoolid`='$schoolid'";
upDate($sql);
//------------------------------
$resp=SendSMS($smss,$phones);
$sql="INSERT INTO `school_sms_data` (`schoolid`,`sms`,`smsid`,`status`,`smstype`,`smsdate`,`totalsms`)
                             VALUES ('$schoolid','$sms','$smsid','$resp','$smstype','$smsdate','$totalsms')";
upDate($sql);
die("The SMS \"$sms\" sent to following mobile number of the parent <br/>$phones");
}

//--------------------------------------------------
if (($action=='sendsmstoroll')and ($role=='admin'))
{
if ((strlen($sms)>150) or (strlen($sms)<=1))
{
die("Numbers of character should be in between 1 and 150.");
}
$sql="SELECT * FROM `school_sms` WHERE `schoolid`='$schoolid' ";
$smsvol=getInfo($sql,'smsvol');
if ($smsvol<=0)
{
die("Insufficient balance!");
}

$smsdate=@date("Y-m-d");
$smsid=@date("YmdHis")."S".$schoolid."U".$user."R".rand(100,999);
//---------------------------------------------------------------
$smstype="parent";
$sql="SELECT * FROM `school_classes` WHERE `schoolid`='$schoolid' And `session`='$year' And `std`='$std' And `section`='$section' And `roll`='$roll'";
$regno=trim(getInfo($sql,'regno'));
$sql="SELECT * FROM `school_students` WHERE `schoolid`='$schoolid' And `regno`='$regno'";
$phones=trim(getInfo($sql,'phone'));

if($phones=='')
{
die("The mobile no. of parent is not found.");
}
//---------------------------------
$totalsms=1;
if ($smsvol<$totalsms)
{
die("Insufficient balance!");
}
//-------------------------------
$sql="UPDATE `school_sms` SET `smsvol`=`smsvol`-$totalsms WHERE `schoolid`='$schoolid'";
upDate($sql);
//------------------------------
$resp=SendSMS($smss,$phones);
$sql="INSERT INTO `school_sms_data` (`schoolid`,`sms`,`smsid`,`status`,`smstype`,`smsdate`,`totalsms`)
                             VALUES ('$schoolid','$sms','$smsid','$resp','$smstype','$smsdate','$totalsms')";
upDate($sql);
die("The SMS \"$sms\" sent to following mobile number of the parent <br/>$phones");
}

//-------------------------------------------------
if (($action=='sendsmstoparents')and ($role=='admin'))
{
if ((strlen($sms)>150) or (strlen($sms)<=1))
{
die("Numbers of character should be in between 1 and 150.");
}
$sql="SELECT * FROM `school_sms` WHERE `schoolid`='$schoolid' ";
$smsvol=getInfo($sql,'smsvol');
if ($smsvol<=0)
{
die("Insufficient balance!");
}

$smsdate=date("Y-m-d");
$smsid=date("YmdHis")."S".$schoolid."U".$user."R".rand(100,999);
//---------------------------------------------------------------
if ($smsto=='all')
{
$smstype="parents";
$sql="SELECT * FROM `school_classes` WHERE `schoolid`='$schoolid' And `session`='$sessn'";
$regnos=GetAllDataSeparatedByComa($sql,"regno");
$allregno=explode(",",$regnos);
$totalstudent=count($allregno)-1;
$i=0;
$phones='';
while ($i<$totalstudent)
{
$sql="SELECT * FROM `school_students` WHERE `schoolid`='$schoolid' And `regno`='$allregno[$i]'";
$phones="$phones".getInfo($sql,'phone').",";
$i=$i+1;
}
//---------------------------------
$totalno=explode(",",$phones);
$totalsms=count($totalno)-1;
if ($smsvol<$totalsms)
{
die("Insufficient balance!");
}
//-------------------------------
$sql="UPDATE `school_sms` SET `smsvol`=`smsvol`-$totalsms WHERE `schoolid`='$schoolid'";
upDate($sql);
//------------------------------
$resp=SendSMS($smss,$phones);
$sql="INSERT INTO `school_sms_data` (`schoolid`,`sms`,`smsid`,`status`,`smstype`,`smsdate`,`totalsms`)
                             VALUES ('$schoolid','$sms','$smsid','$resp','$smstype','$smsdate','$totalsms')";
upDate($sql);
die("The SMS \"$sms\" sent to following mobile number(s) of parent(s)<br/>$phones");
}

if ($smsto=='absentee')
{
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
$regnos=GetAllDataSeparatedByComa($sql,"regno");
$allregno=explode(",",$regnos);
$totalstudent=count($allregno)-1;
$i=0;
$phones='';
while ($i<$totalstudent)
{
$sql="SELECT * FROM `school_students` WHERE `schoolid`='$schoolid' And `regno`='$allregno[$i]'";
$phones="$phones".getInfo($sql,'phone').",";
$i=$i+1;
}
//---------------------------------
$totalno=explode(",",$phones);
$totalsms=count($totalno)-1;
if ($smsvol<$totalsms)
{
die("Insufficient balance!");
}
//-------------------------------
$sql="UPDATE `school_sms` SET `smsvol`=`smsvol`-$totalsms WHERE `schoolid`='$schoolid'";
upDate($sql);
//------------------------------
$resp=SendSMS($smss,$phones);
$sql="INSERT INTO `school_sms_data` (`schoolid`,`sms`,`smsid`,`status`,`smstype`,`smsdate`,`totalsms`)
                             VALUES ('$schoolid','$sms','$smsid','$resp','$smstype','$smsdate','$totalsms')";
upDate($sql);
die("The SMS \"$sms\" sent to parent of today's absentee.");
}

}
//-------------------------------------------------

if (($action=='sendsmsstaffs')and ($role=='admin'))
{
if ((strlen($sms)>150) or (strlen($sms)<=1))
{
die("Numbers of character should be in between 1 and 150.");
}
$sql="SELECT * FROM `school_sms` WHERE `schoolid`='$schoolid' ";
$smsvol=getInfo($sql,'smsvol');
if ($smsvol<=0)
{
die("Insufficient balance!");
}

$smsdate=date("Y-m-d");
$smstype="staff";
$smsid=date("YmdHis")."S".$schoolid."U".$user."R".rand(100,999);
//-------------------------------------------------------
if ($smsto=='all')
{
$sql="SELECT * FROM `school_staffs` WHERE `schoolid`='$schoolid'";
$phones=GetAllDataSeparatedByComa($sql,"mobile");
//---------------------------------
$totalno=explode(",",$phones);
$totalsms=count($totalno)-1;
if ($smsvol<$totalsms)
{
die("Insufficient balance!");
}
//-------------------------------
$sql="UPDATE `school_sms` SET `smsvol`=`smsvol`-$totalsms WHERE `schoolid`='$schoolid' ";
upDate($sql);
//------------------------------
$resp=SendSMS($smss,$phones);
$sql="INSERT INTO `school_sms_data` (`schoolid`,`sms`,`smsid`,`status`,`smstype`,`smsdate`,`totalsms`)
                             VALUES ('$schoolid','$sms','$smsid','$resp','$smstype','$smsdate','$totalsms')";
upDate($sql);
die("The SMS \"$sms\" sent to following mobile number(s) of staff(s).<br/> $phones .");
}

if ($smsto=='teacher')
{
$sql="SELECT * FROM `school_staffs` WHERE `schoolid`='$schoolid' and `designation`='teacher'";
$phones=GetAllDataSeparatedByComa($sql,"mobile");
$totalno=explode(",",$phones);
$totalsms=count($totalno)-1;
if ($smsvol<$totalsms)
{
die("Insufficient balance!");
}
//-------------------------------
$sql="UPDATE `school_sms` SET `smsvol`=`smsvol`-$totalsms WHERE `schoolid`='$schoolid' ";
upDate($sql);
//------------------------------
$resp=SendSMS($smss,$phones);
$sql="INSERT INTO `school_sms_data` (`schoolid`,`sms`,`smsid`,`status`,`smstype`,`smsdate`,`totalsms`)
                           VALUES ('$schoolid','$sms','$smsid','$resp','$smstype','$smsdate','$totalsms')";
upDate($sql);
die("The SMS \"$sms\" sent to following mobile number(s) of teacher(s).<br/> $phones .");
}

if ($smsto=='clerk')
{
$sql="SELECT * FROM `school_staffs` WHERE `schoolid`='$schoolid' and `designation`='clerk'";
$phones=GetAllDataSeparatedByComa($sql,"mobile");
$totalno=explode(",",$phones);
$totalsms=count($totalno)-1;
if ($smsvol<$totalsms)
{
die("Insufficient balance!");
}
//-------------------------------
$sql="UPDATE `school_sms` SET `smsvol`=`smsvol`-$totalsms WHERE `schoolid`='$schoolid' ";
upDate($sql);
//------------------------------
$resp=SendSMS($smss,$phones);
$sql="INSERT INTO `school_sms_data` (`schoolid`,`sms`,`smsid`,`status`,`smstype`,`smsdate`,`totalsms`)
                           VALUES ('$schoolid','$sms','$smsid','$resp','$smstype','$smsdate','$totalsms')";
upDate($sql);
die("The SMS \"$sms\" sent to following mobile number(s) of clerk(s).<br/>$phones .");
}

}
//-----<SM---Setmenus----------------------------------------------------
if (($action=='setmenus') and ($role=='admin'))
{
$buttonsArray=explode("-",$buttons);
$totalmenu=count($buttonsArray);
$usar=$buttonsArray[0];
if ($buttonsArray[0]=="")
{
die("No user was selected");
}
$sql="DELETE FROM `school_set_menu` WHERE (`schoolid`='$schoolid') And (`userid`='$usar') And (`userid`!='1111')";
upDate($sql);
$btnIndex=1;
while ($btnIndex<=$totalmenu)
{
if ($buttonsArray[$btnIndex]=="true")
{
$sql="INSERT INTO `school_set_menu` (`schoolid`,`userid`,`buttonid`) VALUES ('$schoolid','$usar','$btnIndex')";
upDate($sql);
}
$btnIndex=$btnIndex+1;
}
$msgg="Done setting.";
die("$msgg");
}
//------SM>
//------
if (($action=='updateregistration') and ($role=='admin'))
{

if (($regno=='') or($name=='') or ($father=='') Or ($mother=='') Or ($address=='') Or ($phone=='') Or ($po=='') Or ($ps=='') Or ($pin=='')
Or ($district=='') Or ($state=='') Or ($country=='') Or ($dob=='') Or ($sex=='') Or ($nationality=='') Or ($religion=='') Or ($caste=='') Or ($occupationofparent==''))
{
die("Empty field(s)");
} 

$sql="SELECT * FROM `school_users` WHERE `sessionid`='$sessionid' And `schoolid`='$schoolid'";
$userid=getInfo($sql,'user');

$sql="UPDATE `school_students` SET `name`='$name',`father`='$father',`mother`='$mother',`address`='$address',
`phone`='$phone',`po`='$po',`ps`='$ps',`district`='$district',`state`='$state',`country`='$country',`pin`='$pin',
`dob`='$dob',`sex`='$sex',`nationality`='$nationality',`religion`='$religion',`caste`='$caste',`occupationofparent`='$occupationofparent',
`incomeofparent`='$incomeofparent',`remarks`='$remarks',`userid`='$userid',`entry`='$sessn' WHERE `schoolid`='$schoolid' And `regno`='$regno'";
upDate($sql);
$msgg="Reg.No.$regno updated.";
die("$msgg");
}
//-------------------------------------------------
if ($action=='getMobileUpdateForm')
{
if ($regno=='')
{
$sql="SELECT * FROM `school_classes` WHERE  `std`='$std' And `section`='$section' and `roll`='$roll' And `schoolid`='$schoolid' ORDER BY `session` ASC";
$regno=trim(getInfo($sql,'regno'));
if ($regno=='')
{
die("No data found");
}
}

$sql="SELECT * FROM `school_students` WHERE (`schoolid`='$schoolid') And (`regno`='$regno')";
$chkrno=strtoupper(trim(getInfo($sql,'regno')));
$name=trim(getInfo($sql,'name'));
$father=trim(getInfo($sql,'father'));
$mother=trim(getInfo($sql,'mother'));
$phone=trim(getInfo($sql,'phone'));
$user=trim(getInfo($sql,'userid'));

if ($chkrno!="$regno")
{
die("$regno is not found.");
}

$frm='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"><u>Update of Mobile # of Student\'s</u> <button  onclick="x()">X</button>
<table><tr><td style="text-align:right;font-size:16px;">Last updated by User:'.$user.'<input type="hidden" id="regno" value="'.$regno.'" name="name" cols="200" ><br/>Reg.No.:'.$regno.'<br/>
<br/>'."Name:$name
<br/>Father:$father
<br/>Mother:$mother 
<br/>".'Mobile Ph. No.:<input type="text" id="phone" value="'.$phone.'" name="phone" cols="200" >
<br/><button id="registration" onclick="UpdateStudentMobileNo()">Update Mobile No.</button> 
</tb></tr></table>
</div>';

die("$frm");
}
//---------------------------------------------------------
if ($action=='updateMobile')
{
$sql="UPDATE `school_students` SET `phone`='$phone' WHERE `schoolid`='$schoolid' And `regno`='$regno' ";
upDate($sql);
die("Mobile number updated as $phone");
}
//------------------------------------------------------
if ($action=='getRegistrationForm')
{

if ($regno=='')
{
die("Reg.No. can not be blank.");
}

$sql="SELECT * FROM `school_students` WHERE `schoolid`='$schoolid' And `regno`='$regno' ";
$chkrno=strtoupper(trim(getInfo($sql,'regno')));
$entry=trim(getInfo($sql,'entry'));
$name=trim(getInfo($sql,'name'));
$father=trim(getInfo($sql,'father'));
$mother=trim(getInfo($sql,'mother'));
$address=trim(getInfo($sql,'address'));
$phone=trim(getInfo($sql,'phone'));
$po=trim(getInfo($sql,'po'));
$ps=trim(getInfo($sql,'ps'));
$district=trim(getInfo($sql,'district'));
$state=trim(getInfo($sql,'state'));
$country=trim(getInfo($sql,'country'));
$pin=trim(getInfo($sql,'pin'));
$dob=trim(getInfo($sql,'dob'));
$sex=trim(getInfo($sql,'sex'));
$nationality=trim(getInfo($sql,'nationality'));
$religion=trim(getInfo($sql,'religion'));
$caste=trim(getInfo($sql,'caste'));
$occupationofparent=trim(getInfo($sql,'occupationofparent'));
$incomeofparent=trim(getInfo($sql,'incomeofparent'));
$remarks=trim(getInfo($sql,'remarks'));
$user=trim(getInfo($sql,'userid'));

if ($chkrno!="$regno")
{
die("$regno is not found.");
}

$frm='<div style="background-color:yellow;padding: 10px; border: 2px solid yellow; border-radius: 10px;margin: auto;width: 350px;"><u>Update of Student\'s Registration</u> <button  onclick="x()">X</button>
<table><tr><td style="text-align:right;font-size:16px;">Last updated by User:'.$user.'<input type="hidden" id="regno" value="'.$regno.'" name="name" cols="200" ><br/>Reg.No.:'.$regno.'<br/>Entry Year/Session:<input type="text" id="session" value="'.$entry.'"  name="session" cols="200" >
<br/>Name:<input type="text" id="name" value="'.$name.'" name="name" cols="200" >
<br/>Father:<input type="text" id="father" value="'.$father.'" name="father" cols="200" >
<br/>Mother:<input type="text" id="mother" value="'.$mother.'" name="mother" cols="200" > 
<br/>Address:<input type="text" id="address" value="'.$address.'" name="address" cols="200" >
<br/>Mobile Ph. No.:<input type="text" id="phone" value="'.$phone.'" name="phone" cols="200" >
<br/>P.O.:<input type="text" id="po" value="'.$po.'" name="po" cols="200" >
<br/>P.S.:<input type="text" id="ps" value="'.$ps.'" name="ps" cols="200" >
<br/>District:<select id="district" name="district">
<option value="'.$district.'">'.$district.'</option>
<option value="Bisnupur">Bisnupur</option>
<option value="Chandel">Chandel</option> 
<option value="Churachandpur">Churachandpur</option>
 <option value="Imphal(E)">Imphal East</option>
<option value="Imphal(W)">Imphal West</option>
<option value="Senapati">Senapati</option>
<option value="Tamenglong">Tamenglong</option>
<option value="Thoubal">Thoubal</option>
<option value="Ukhrul">Ukhrul</option>
 </select>
<br/>State:<input type="text" id="state" value="'.$state.'" name="state" cols="200" >
<br/>Country:<input type="text" id="country" value="'.$country.'" name="country" cols="200" >
<br/>Postal Pin Code:<input type="text" id="pin" value="'.$pin.'" name="pin" cols="200" > 
<br/>D.O.B.(YYYY-MM-DD):<input type="text" id="dob" value="'.$dob.'" name="dob" cols="200" >
<br/><select id="sex" name="sex"> 
<option value="'.$sex.'">'.$sex.'</option>
<option value="female">Female</option> 
<option value="male">Male</option> 
 </select> 
<br/>Nationality:<input type="text" id="nationality" value="'.$nationality.'" name="nationality" cols="200" > 
<br/>Religion:<input type="text" id="religion" value="'.$religion.'" name="religion" cols="200" > 
<br/>Caste:<input type="text" id="caste" value="'.$caste.'" name="caste" cols="200" > 
<br/>Occupation of Parent:<input type="text" id="occupationofparent" value="'.$occupationofparent.'" name="occupationofparent" cols="200" > 
<br/>Annual Income of Parent:<input type="text" id="incomeofparent" value="'.$incomeofparent.'" name="incomeofparent" cols="200" > 
<br/>Remarks: <input type="text" id="remarks" value="'.$remarks.'" name="remarks" cols="200" > 
<br/><button id="registration" onclick="UpdateRegistration()">Update</button> 
</tb></tr></table>
</div>';

die("$frm");
}

//-------------------------------------------------
if (($action=='deletefee')And ($role=='admin'))
{
if ($transid=='')
{
die("Trans.ID can not be blank.");
}
else
{
$sql="DELETE FROM `school_fee` WHERE `schoolid`='$schoolid' And `transid`='$transid'";
upDate($sql);
die("The fee payment data deleted.");
}
}
//-----------------------------------------------------
if (($action=='deleteregistration')And ($role=='admin'))
{
if (($regno=='') Or ($sessn==''))
{
die("Reg.No. can not be blank.");
}
else
{
$sql="DELETE FROM `school_students` WHERE `schoolid`='$schoolid' And `regno`='$regno' And `session`='$sessn'";
        //upDate($sql);
die("The Registration deleted.");
}
}

 //----------------------------------------------------
if (($action=='deleteadmission')And ($role=='admin'))
{
if (($regno=='') Or ($sessn==''))
{
die("Reg.No. can not be blank.");
}
else
{
$sql="DELETE FROM `school_classes` WHERE `schoolid`='$schoolid' And `regno`='$regno' And `session`='$sessn'";
upDate($sql);
die("The admission deleted.");
}
}
//-------------------------------------------------
if (($action=='deletedata')And ($role=='admin'))
{
if ($table=='')
{
die("You did not select data to delete!");
}

if ($table=='attendance')
{
$table="Attendance data";
$sql="DELETE FROM `school_attendance` WHERE `schoolid`='$schoolid'";
upDate($sql);
}
if ($table=='smss')
{
$table="SMS data";
$sql="DELETE FROM `school_sms_data` WHERE `schoolid`='$schoolid'";
upDate($sql);
}
if ($table=='classes')
{
$table="Class data";
$sql="DELETE FROM `school_classes` WHERE `schoolid`='$schoolid'";
upDate($sql);
}
if ($table=='exam')
{
$table="Exam name data";
$sql="DELETE FROM `school_exams` WHERE `schoolid`='$schoolid'";
upDate($sql);
}
if ($table=='fee')
{
$table="Fee payment data";
$sql="DELETE FROM `school_fee` WHERE `schoolid`='$schoolid'";
upDate($sql);
}
if ($table=='marks')
{
$table="Mark data";
$sql="DELETE FROM `school_marks` WHERE `schoolid`='$schoolid'";
upDate($sql);
}
if ($table=='revheads')
{
$table="Revenue Head data";
$sql="DELETE FROM `school_revheads` WHERE `schoolid`='$schoolid'";
upDate($sql);
}
if ($table=='staffs')
{
$table="Staff data";
$sql="DELETE FROM `school_staffs` WHERE `schoolid`='$schoolid'";
upDate($sql);
}
if ($table=='stds')
{
$table="Standard/Class name data";
$sql="DELETE FROM `school_stds` WHERE `schoolid`='$schoolid'";
upDate($sql);
}

if ($table=='subjects')
{
$table="Subject name data";
$sql="DELETE FROM `school_subjects` WHERE `schoolid`='$schoolid'";
upDate($sql);
}
if ($table=='students')
{
$table="Student data";
$sql="DELETE FROM `school_students` WHERE `schoolid`='$schoolid'";
upDate($sql);
}

if ($table=='users')
{
$table="User data";
$sql="DELETE FROM `school_users` WHERE `schoolid`='$schoolid' And `role`!='admin'";
upDate($sql);
}
if ($table=='all')
{
$sql="DELETE FROM `school_users` WHERE `schoolid`='$schoolid' And `role`!='admin'";
upDate($sql);
$sql="DELETE FROM `school_attendance` WHERE `schoolid`='$schoolid'";
upDate($sql);
$sql="DELETE FROM `school_classes` WHERE `schoolid`='$schoolid'";
upDate($sql);
$sql="DELETE FROM `school_exams` WHERE `schoolid`='$schoolid'";
upDate($sql);
$sql="DELETE FROM `school_fee` WHERE `schoolid`='$schoolid'";
upDate($sql);
$sql="DELETE FROM `school_marks` WHERE `schoolid`='$schoolid'";
upDate($sql);
$sql="DELETE FROM `school_revheads` WHERE `schoolid`='$schoolid'";
upDate($sql);
$sql="DELETE FROM `school_sms_data` WHERE `schoolid`='$schoolid'";
upDate($sql);
$sql="DELETE FROM `school_staffs` WHERE `schoolid`='$schoolid'";
upDate($sql);
$sql="DELETE FROM `school_stds` WHERE `schoolid`='$schoolid'";
upDate($sql);
$sql="DELETE FROM `school_subjects` WHERE `schoolid`='$schoolid'";
upDate($sql);
$sql="DELETE FROM `school_students` WHERE `schoolid`='$schoolid'";
upDate($sql);
}
die("Data deleted");
}
//-------------------------------------------------
if (($action=='updatemark'))
{

if (($examid=='') Or ($subid=='') Or ($roll=='') Or ($mark=='') Or ($maxmark=='') )
{
die("Exam ID/Subject/Roll/Mark/Max.Mark can not be blank."); 
}

$sql="SELECT * FROM `school_subjects` WHERE `subjectid`='$subid' AND `schoolid`='$schoolid'";
$std=getInfo($sql,'stdid');
$sql="SELECT * FROM `school_classes` WHERE `std`='$std' And `section`='$section' And `roll`='$roll' And `session`='$sessn' AND `schoolid`='$schoolid'";
$regno=getInfo($sql,'regno');

//echo "Class:$std $section ; Roll No.:$roll ; Subject ID:$subid ; Mark:$mark ; Max.Mark:$maxmark; Session:$sessn ; UserId:$user <br/>";

if ($regno=='')
{
$sql="SELECT * FROM `school_classes` WHERE `std`='$std' And `regno`='$roll' And `session`='$sessn' AND `schoolid`='$schoolid'";
$regno=getInfo($sql,'regno');
if ($regno=='')
{
die("The student i.e. Class $std $section,Roll No.$roll, does not exist.");
}
}
//---------------------------------------------------------------------------------------
$sql="SELECT * FROM `school_marks` WHERE `subjectid`='$subid' AND `schoolid`='$schoolid' And `regno`='$regno' And `examid`='$examid' And `session`='$sessn'";
$chksubid=getInfo($sql,'subjectid');
//----------------------------------------------------------------------------------------

if ($update=='Add')
{

if ($chksubid==$subid)
{
die("Mark already entered for $examid $sessn");
}

$sql="INSERT INTO `school_marks` (`schoolid`,`regno`,`subjectid`,`session`,`examid`,`mark`,`maxmark`,`userid`)
                         VALUES ('$schoolid','$regno','$subid','$sessn','$examid','$mark','$maxmark','$user')";
upDate($sql);
die("Added mark of Class:$std $section ; Roll No.:$roll as Mark:$mark ; Max.Mark:$maxmark; Session:$sessn for ExamID=$examid"); 
}
if ($update=='Up')
{


if ($chksubid!=$subid) 
{
die("Mark has not been entered.");
}

if (($role!='admin'))
{
die("You have no privilege to change it.");
}

$sql="UPDATE `school_marks` SET `mark`='$mark',`maxmark`='$maxmark' WHERE `subjectid`='$subid' AND `schoolid`='$schoolid' And `regno`='$regno' And `examid`='$examid' And `session`='$sessn'";
upDate($sql);
$msg="Updated mark of Class:$std $section ; Roll No.:$roll as Mark:$mark ; Max.Mark:$maxmark; Session:$sessn for ExamID=$examid";
die($msg); 
}
if ($update=='Del')
{

if ($chksubid!=$subid) 
{
die("Mark has not been entered.");
}

if ($role!='admin')
{
die("You have no privilege to change it.");
}

$sql="DELETE FROM `school_marks` WHERE `subjectid`='$subid' AND `schoolid`='$schoolid' And `regno`='$regno' And `examid`='$examid' And `session`='$sessn'";
upDate($sql);
die("Deleted mark of Class:$std $section ; Roll No.:$roll as Mark:$mark ; Max.Mark:$maxmark; Session:$sessn for ExamID=$examid"); 
}


}
//------------------------------------------------
if (($action=='updatestaff') And ($role=='admin'))
{

if (($staffid=='') Or ($staffname=='') Or ($mobile=='') Or ($designation==''))
{
die("Staff ID/Staff Name/Design./Mobile No. can not be blank."); 
}

if ($mobile<=1000000000) 
{
die("Mobile No. is not correct."); 
}

$sql="SELECT * FROM `school_staffs` WHERE `staffid`='$staffid' AND `schoolid`='$schoolid'";
$chksubid=getInfo($sql,'staffid');

if ($update=='Add')
{

if ($chksubid==$staffid)
{
die("Staff ID $staffid already exists");
}

$sql="INSERT INTO `school_staffs` (`schoolid`,`staffid`,`staffname`,`mobile`,`designation`,`remarks`) VALUES ('$schoolid','$staffid','$staffname','$mobile','$designation','$remarks')";
upDate($sql);
die("Added $staffname (Staff ID=$staffid)."); 
}
if ($update=='Up')
{


if ($chksubid!=$staffid)
{
die("Staff ID= $staffid  does not exist");
}

$sql="UPDATE `school_staffs` SET `staffname`='$staffname',`mobile`='$mobile',`designation`='$designation',`remarks`='$remarks' WHERE `staffid`='$staffid' And `schoolid`='$schoolid'";
upDate($sql);
$msg="$staffname (Staff ID= $staffid ) updated.";
die($msg); 
}
if ($update=='Del')
{

if ($chksubid!=$staffid)
{
die("Staff ID= $staffid does not exist");
}

$sql="DELETE FROM `school_staffs` WHERE `staffid`='$staffid' AND `schoolid`='$schoolid'";
upDate($sql);
die("$staffname (Staff ID= $staffid )deleted."); 
}


}

//-------------------------------------------------
if (($action=='updateexam') And ($role=='admin'))
{

if (($examid=='') Or ($exam=='') )
{
die("Exam ID/Exam can not be blank."); 
}

$sql="SELECT * FROM `school_exam` WHERE `examid`='$examid' AND `schoolid`='$schoolid'";
$chksubid=getInfo($sql,'examid');

if ($update=='Add')
{

if ($chksubid==$examid)
{
die("Exam ID $examid already exists");
}

$sql="INSERT INTO `school_exam` (`schoolid`,`examid`,`exam`) VALUES ('$schoolid','$examid','$exam')";
upDate($sql);
die("Added $exam (Exam ID=$examid)."); 
}
if ($update=='Up')
{


if ($chksubid!=$examid)
{
die("Exam ID= $examid  does not exist");
}

$sql="UPDATE `school_exam` SET `exam`='$exam' WHERE `examid`='$examid' And `schoolid`='$schoolid'";
upDate($sql);
$msg="$exam (Exam ID= $examid ) updated.";
die($msg); 
}
if ($update=='Del')
{

if ($chksubid!=$examid)
{
die("Exam ID= $examid does not exist");
}

$sql="DELETE FROM `school_exam` WHERE `examid`='$examid' AND `schoolid`='$schoolid'";
upDate($sql);
die("$exam  (Exam ID= $examid )deleted."); 
}


}
//-------------------------------------------------
if (($action=='updateexpendhead') And ($role=='admin'))
{

if (($allocationid=='') Or ($allocation=='') )
{
die("Allocation ID/Allocation can not be blank."); 
}

$sql="SELECT * FROM `school_revheads` WHERE `allocationid`='$allocationid' AND `schoolid`='$schoolid'";
$chksubid=getInfo($sql,'allocationid');

if ($update=='Add')
{

if ($chksubid==$allocationid)
{
die("Allocation ID $allocationid already exists");
}

$sql="INSERT INTO `school_revheads` (`schoolid`,`allocationid`,`allocation`,`plusminus`) VALUES ('$schoolid','$allocationid','$allocation','-')";
upDate($sql);
die("Added $allocation (Allocation ID=$allocationid)."); 
}
if ($update=='Up')
{


if ($chksubid!=$allocationid)
{
die("Expenditure Allocation ID= $allocationid  does not exist");
}

$sql="UPDATE `school_revheads` SET `allocation`='$allocation' WHERE `allocationid`='$allocationid' And `schoolid`='$schoolid'";
upDate($sql);
$msg="$allocation (Allocation ID= $allocationid ) updated.";
die($msg); 
}
if ($update=='Del')
{

if ($chksubid!=$allocationid)
{
die("Allocation ID= $allocationid does not exist");
}

$sql="DELETE FROM `school_revheads` WHERE `allocationid`='$allocationid' AND `schoolid`='$schoolid'";
upDate($sql);
die("$allocation  (Allocation ID= $allocationid )deleted."); 
}


}

//-------------------------------------------------
if (($action=='updateallocation') And ($role=='admin'))
{

if (($allocationid=='') Or ($allocation=='') )
{
die("Allocation ID/Allocation can not be blank."); 
}

$sql="SELECT * FROM `school_revheads` WHERE `allocationid`='$allocationid' AND `schoolid`='$schoolid'";
$chksubid=getInfo($sql,'allocationid');

if ($update=='Add')
{

if ($chksubid==$allocationid)
{
die("Allocation ID $allocationid already exists");
}

$sql="INSERT INTO `school_revheads` (`schoolid`,`allocationid`,`allocation`,`plusminus`) VALUES ('$schoolid','$allocationid','$allocation','+')";
upDate($sql);
die("Added $allocation (Allocation ID=$allocationid)."); 
}
if ($update=='Up')
{


if ($chksubid!=$allocationid)
{
die("Revenue Allocation ID= $allocationid  does not exist");
}

$sql="UPDATE `school_revheads` SET `allocation`='$allocation' WHERE `allocationid`='$allocationid' And `schoolid`='$schoolid'";
upDate($sql);
$msg="$allocation (Allocation ID= $allocationid ) updated.";
die($msg); 
}
if ($update=='Del')
{

if ($chksubid!=$allocationid)
{
die("Allocation ID= $allocationid does not exist");
}

$sql="DELETE FROM `school_revheads` WHERE `allocationid`='$allocationid' AND `schoolid`='$schoolid'";
upDate($sql);
die("$allocation  (Allocation ID= $allocationid )deleted."); 
}


}
//-------------------------------------------------
if (($action=='updatewithheld') And ($role=='admin'))
{

if (($remarks=='') Or ($regno==''))
{
die("Reg.No./Remark(s) can not be blank."); 
}

$sql="SELECT * FROM `school_students` WHERE `regno`='$regno' AND `schoolid`='$schoolid'";
$chkid=trim(getInfo($sql,'regno'));
if ($chkid=='') die("Reg.No.$regno is not found.");

$sql="SELECT * FROM `school_withheld` WHERE `regno`='$regno' AND `schoolid`='$schoolid'";
$chkid=trim(getInfo($sql,'regno'));
if (($chkid=='') And ($update=='Up'))
{
$sql="INSERT INTO `school_withheld` (`regno`,`withheld`,`schoolid`,`user`,`remarks`) VALUES ('$regno','1','$schoolid','$user','$remarks')";
upDate($sql);
die("Reg.No. $regno withheld."); 
}

if ($update=='Up')
{
$sql="UPDATE `school_withheld` SET `withheld`='1', `remarks`='$remarks' WHERE `regno`='$regno' And `schoolid`='$schoolid'";
upDate($sql);
$msg="Withheld for Reg.No.$regno updated.";
die($msg); 
}
if ($update=='Del')
{
$sql="DELETE FROM `school_withheld` WHERE `regno`='$regno' AND `schoolid`='$schoolid'";
upDate($sql);
die("Reg.No.$regno is freed from withholding ."); 
}


}
//-------------------------------------------------
if (($action=='updatehighlight') And ($role=='admin'))
{

if (($highlight=='') Or ($regno==''))
{
die("Reg.No./Highlight can not be blank."); 
}

$sql="SELECT * FROM `school_highlight` WHERE `highlightid`='$recordno' AND `schoolid`='$schoolid'";
$chkid=getInfo($sql,'highlightid');

if ($update=='Add')
{
$highlightid=@date("YmdHis")."S".$schoolid."U".$user."R".rand(100,999);
$sql="INSERT INTO `school_highlight` (`highlightid`,`highlight`,`schoolid`,`regno`) VALUES ('$highlightid','$highlight','$schoolid','$regno')";
upDate($sql);
die("Added record/highlight (Record ID=$highlightid) for Reg.No. $regno ."); 
}

if ($recordno=='')
{
die("Record ID can not be blank."); 
}

if ($update=='Up')
{
if ($chkid!=$recordno)
{
die("Record ID= $recordno  does not exist");
}

$sql="UPDATE `school_highlight` SET `highlight`='$highlight' WHERE `highlightid`='$recordno' And `schoolid`='$schoolid'";
upDate($sql);
$msg="Record $recordno updated.";
die($msg); 
}
if ($update=='Del')
{

if ($chkid!=$recordno)
{
die("Subject ID= $subid does not exist");
}

$sql="DELETE FROM `school_highlight` WHERE `highlightid`='$recordno' AND `schoolid`='$schoolid'";
upDate($sql);
die("Record $recordno deleted."); 
}


}
//-----------------------------------------
if (($action=='updatestd') And ($role=='admin'))
{
if (($std=='') Or ($stdid==''))
{
die("Class ID/Class can not be blank."); 
}

if ($update=='Add')
{
$sql="SELECT * FROM `school_stds` WHERE `stdid`='$stdid' AND `schoolid`='$schoolid'";
$chkstd=getInfo($sql,'stdid');

if ($chkstd==$stdid)
{
die("Class ID already exists");
}

$sql="INSERT INTO `school_stds` (`schoolid`,`stdid`,`std`) VALUES ('$schoolid','$stdid','$std')";
upDate($sql);
$msg="Added Class $std (Class ID=$stdid)";
die("$msg"); 
}
if ($update=='Up')
{

$sql="SELECT * FROM `school_stds` WHERE `stdid`='$stdid' AND `schoolid`='$schoolid'";
$chkstd=getInfo($sql,'stdid');

if ($chkstd!=$stdid)
{
die("Class ID= $stdid  does not exist");
}

$sql="UPDATE `school_stds` SET `std`='$std' WHERE `stdid`='$stdid' AND `schoolid`='$schoolid'";
upDate($sql);
$msg="Class $std  (Class ID= $stdid ) updated.";
die($msg); 
}
if ($update=='Del')
{
$sql="SELECT * FROM `school_stds` WHERE `stdid`='$stdid' AND `schoolid`='$schoolid'";
$chkstd=getInfo($sql,'stdid');

if ($chkstd!=$stdid)
{
die("Class ID= $stdid does not exist");
}

$sql="DELETE FROM `school_stds` WHERE `stdid`='$stdid' AND `schoolid`='$schoolid'";
upDate($sql);
die("Class $std  (Class ID= $stdid ) deleted."); 
}


}

//-------------------------------------------------
if (($action=='updatesubject') And ($role=='admin'))
{

if (($subid=='') Or ($subject=='') Or ($stdid==''))
{
die("Subject ID/Subject/Class can not be blank."); 
}

$sql="SELECT * FROM `school_subjects` WHERE `subjectid`='$subid' AND `schoolid`='$schoolid'";
$chksubid=getInfo($sql,'subjectid');

if ($update=='Add')
{

if ($chksubid==$subid)
{
die("Subject ID $subid already exists");
}

$sql="INSERT INTO `school_subjects` (`schoolid`,`subjectid`,`subject`,`stdid`) VALUES ('$schoolid','$subid','$subject','$stdid')";
upDate($sql);
die("Added $subject (Subject ID=$subid) for Class $stdid "); 
}
if ($update=='Up')
{


if ($chksubid!=$subid)
{
die("Subject ID= $subid  does not exist");
}

$sql="UPDATE `school_subjects` SET `subject`='$subject',`stdid`='$stdid' WHERE `subjectid`='$subid' And `schoolid`='$schoolid'";
upDate($sql);
$msg="$subject (Subject ID= $subid ) for Class $stdid  updated.";
die($msg); 
}
if ($update=='Del')
{

if ($chksubid!=$subid)
{
die("Subject ID= $subid does not exist");
}

$sql="DELETE FROM `school_subjects` WHERE `subjectid`='$subid' AND `schoolid`='$schoolid'";
upDate($sql);
die("$subject  (Subject ID= $subid ) for Class $stdid deleted."); 
}


}
//-----------------------------------------
if (($action=='updatestd') And ($role=='admin'))
{
if (($std=='') Or ($stdid==''))
{
die("Class ID/Class can not be blank."); 
}

if ($update=='Add')
{
$sql="SELECT * FROM `school_stds` WHERE `stdid`='$stdid' AND `schoolid`='$schoolid'";
$chkstd=getInfo($sql,'stdid');

if ($chkstd==$stdid)
{
die("Class ID already exists");
}

$sql="INSERT INTO `school_stds` (`schoolid`,`stdid`,`std`) VALUES ('$schoolid','$stdid','$std')";
upDate($sql);
$msg="Added Class $std (Class ID=$stdid)";
die("$msg"); 
}
if ($update=='Up')
{

$sql="SELECT * FROM `school_stds` WHERE `stdid`='$stdid' AND `schoolid`='$schoolid'";
$chkstd=getInfo($sql,'stdid');

if ($chkstd!=$stdid)
{
die("Class ID= $stdid  does not exist");
}

$sql="UPDATE `school_stds` SET `std`='$std' WHERE `stdid`='$stdid' AND `schoolid`='$schoolid'";
upDate($sql);
$msg="Class $std  (Class ID= $stdid ) updated.";
die($msg); 
}
if ($update=='Del')
{
$sql="SELECT * FROM `school_stds` WHERE `stdid`='$stdid' AND `schoolid`='$schoolid'";
$chkstd=getInfo($sql,'stdid');

if ($chkstd!=$stdid)
{
die("Class ID= $stdid does not exist");
}

$sql="DELETE FROM `school_stds` WHERE `stdid`='$stdid' AND `schoolid`='$schoolid'";
upDate($sql);
die("Class $std  (Class ID= $stdid ) deleted."); 
}


}
//------------------------------------------------------------------
if ($action=='updateattendance')
{
$rolls=explode(",",$roll);
$nosofroll=count($rolls);
$i=0;
$j=$nosofroll;
while ($nosofroll>0)
{
$sql="SELECT * FROM `school_classes` WHERE `schoolid`='$schoolid' and `std`='$std' and `section`='$section' and `roll`='$rolls[$i]'";
$reg=getInfo($sql,'regno');

if ($reg!='')
{
$sql="SELECT * FROM `school_attendance` WHERE `schoolid`='$schoolid' and DATE_FORMAT(`date`,'%Y-%m-%d')='$date' and `std`='$std' and `section`='$section' and `roll`='$rolls[$i]'";
$regno=getInfo($sql,'regno');

if (($regno=='') And ($reg!=''))
{
$sql="INSERT INTO `school_attendance` (`schoolid`,`regno`,`date`,`std`,`section`,`roll`,`attendance`,`remarks`,`user`) 
                               VALUES ('$schoolid','$reg','$date','$std','$section','$rolls[$i]','$attendance','$remarks','$user') ";
}
else
{
$sql="UPDATE `school_attendance` SET `attendance`='$attendance',`user`='$user',`remarks`='$remarks',`user`='$user' 
      WHERE  `regno`='$reg' And `schoolid`='$schoolid' And DATE_FORMAT(`date`,'%Y-%m-%d')='$date' ";
}
upDate($sql);
$rll=$rll.$rolls[$i].",";
}

$i=$i+1;
$nosofroll=$nosofroll-1;
}


die("Roll No. $rll of Class $std$section updated as $attendance on $date.");
}
//-----------------------------------------
if (($action=='viewsmsbalance')and ($role=='admin'))
{
$sql="SELECT * FROM `school_sms` WHERE `schoolid`='$schoolid' ";
$smsvol=getInfo($sql,'smsvol');
die("Available SMS Balance:$smsvol");
}
//------------------------------------------
//------------------------------------------
if ($action=='viewfeepaymentdate')
{
if(($role=='admin') or ($role=='clerk') or ($role=='cashier') or ($role=='spl') )
{
$sql="SELECT * FROM `school_classes` WHERE `schoolid`='$schoolid' and `std`='$std' and `section`='$section' and `roll`='$roll' And `session`='$year'";
$reg=getInfo($sql,'regno');
$date3=@strtotime($date1);
$date2=@strtotime($date);

if ($allocation=="ALL")
{
if ($std=="ALL") 
{
$sql="SELECT * FROM `school_fee` WHERE (`schoolid`='$schoolid') And (`paydate`<='$date1') And (`paydate`>='$date') ORDER BY `paydate` ASC";
$sqlsum="SELECT SUM(`amount`) AS Totalrs FROM `school_fee` WHERE `schoolid`='$schoolid' And `paydate`<='$date1' And `paydate`>='$date'";
$rs=getInfo($sqlsum,'Totalrs');
$total="Payment details for <br/>Start Date: $date<br/> End Date:$date1<br/>".viewfeepaymentdate($sql);
die("$total <br/>Total Rs.$rs");
}

if (($std=="ALL") And ($section!="ALL"))
{
$sql="SELECT * FROM `school_fee` WHERE (`schoolid`='$schoolid') And (`paydate`<='$date1') And (`paydate`>='$date') ORDER BY `paydate` ASC";
$sqlsum="SELECT SUM(`amount`) AS Totalrs FROM `school_fee` WHERE `schoolid`='$schoolid' And `paydate`<='$date1' And `paydate`>='$date'";
$rs=getInfo($sqlsum,'Totalrs');
$total="Payment details for <br/>Start Date: $date<br/> End Date:$date1<br/>".viewfeepaymentdate($sql);
die("$total <br/>Total Rs.$rs");
}


if (($std!="ALL") And ($section=="ALL") )
{
$sql="SELECT * FROM `school_fee` WHERE (`schoolid`='$schoolid') And (`paydate`<='$date1') And (`paydate`>='$date') And (`std`='$std') ORDER BY `paydate` ASC";
$sqlsum="SELECT SUM(`amount`) AS Totalrs FROM `school_fee` WHERE `schoolid`='$schoolid' And `paydate`<='$date1' And `paydate`>='$date' And (`std`='$std') ";
$rs=getInfo($sqlsum,'Totalrs');
$total="Payment details for Class-$std<br/>Start Date: $date<br/> End Date:$date1<br/>".viewfeepaymentdate($sql);
die("$total <br/>Total Rs.$rs");
}



if (($std!="ALL") And ($section=="ALL"))
{
$sql="SELECT * FROM `school_fee` WHERE (`schoolid`='$schoolid') And (`paydate`<='$date1') And (`paydate`>='$date') And (`std`='$std') ORDER BY `paydate` ASC";
$sqlsum="SELECT SUM(`amount`) AS Totalrs FROM `school_fee` WHERE `schoolid`='$schoolid' And `paydate`<='$date1' And `paydate`>='$date' And (`std`='$std') ";
$rs=getInfo($sqlsum,'Totalrs');
$total="Payment details for Class-$std <br/>Start Date: $date<br/> End Date:$date1<br/>".viewfeepaymentdate($sql);
die("$total <br/>Total Rs.$rs");
}


if (($std!="ALL") And ($section!="ALL") And ($reg!=""))
{
$sql="SELECT * FROM `school_fee` WHERE `schoolid`='$schoolid' And `paydate`<='$date1' And `paydate`>='$date' And `regno`='$reg' ORDER BY `paydate` ASC";
$sqlsum="SELECT SUM(`amount`) AS Totalrs FROM `school_fee` WHERE `schoolid`='$schoolid' And `paydate`<='$date1' And `paydate`>='$date' And `regno`='$reg'";
$rs=getInfo($sqlsum,'Totalrs');
$total="Payment details for Class-$std $section ; Roll-$roll ;<br/>Start Date: $date<br/> End Date:$date1<br/>".viewfeepaymentdate($sql);
die("$total <br/>Total Rs.$rs");
}

if (($std!="ALL") And ($section!="ALL"))
{
$sql="SELECT * FROM `school_fee` WHERE `schoolid`='$schoolid' And `paydate`<='$date1' And `paydate`>='$date' And `regno`='$reg' ORDER BY `paydate` ASC";
$sqlsum="SELECT SUM(`amount`) AS Totalrs FROM `school_fee` WHERE `schoolid`='$schoolid' And `paydate`<='$date1' And `paydate`>='$date' ";
$rs=getInfo($sqlsum,'Totalrs');
$total="Payment details for Class-$std $section ; Roll-$roll ;<br/>Start Date: $date<br/> End Date:$date1<br/>".viewfeepaymentdate($sql);
die("$total <br/>Total Rs.$rs");
}

}

if ($allocation!="ALL")
{
if ($std=="ALL") 
{
$sql="SELECT * FROM `school_fee` WHERE (`schoolid`='$schoolid') And (`paydate`<='$date1') And (`paydate`>='$date') And (`allocation`='$allocation') ORDER BY `paydate` ASC";
$sqlsum="SELECT SUM(`amount`) AS Totalrs FROM `school_fee` WHERE `schoolid`='$schoolid' And `paydate`<='$date1' And `paydate`>='$date' And (`allocation`='$allocation')";
$rs=getInfo($sqlsum,'Totalrs');
$total="Payment details for all Classes and sections <br/>Start Date: $date<br/> End Date:$date1<br/>".viewfeepaymentdate($sql);
die("$total <br/>Total Rs.$rs");
}

if (($std!="ALL") And ($section=="ALL"))
{
$sql="SELECT * FROM `school_fee` WHERE (`schoolid`='$schoolid') And (`paydate`<='$date1') And (`paydate`>='$date') And (`std`='$std') And (`allocation`='$allocation') ORDER BY `paydate` ASC";
$sqlsum="SELECT SUM(`amount`) AS Totalrs FROM `school_fee` WHERE `schoolid`='$schoolid' And `paydate`<='$date1' And `paydate`>='$date' And (`std`='$std') And (`allocation`='$allocation') ";
$rs=getInfo($sqlsum,'Totalrs');
$total="Payment details for Class-$std <br/>Start Date: $date<br/> End Date:$date1<br/>".viewfeepaymentdate($sql);
die("$total <br/>Total Rs.$rs");
}


if (($std!="ALL") And ($section!="ALL"))
{
$sql="SELECT * FROM `school_fee` WHERE `schoolid`='$schoolid' And `paydate`<='$date1' And `paydate`>='$date' And `regno`='$reg' And `allocation`='$allocation' ORDER BY `paydate` ASC";
$sqlsum="SELECT SUM(`amount`) AS Totalrs FROM `school_fee` WHERE `schoolid`='$schoolid' And `paydate`<='$date1' And `paydate`>='$date' And `regno`='$reg' And (`allocation`='$allocation')";
$rs=getInfo($sqlsum,'Totalrs');
$total="Payment details for Class-$std $section ; Roll-$roll ;<br/>Start Date: $date<br/> End Date:$date1<br/>".viewfeepaymentdate($sql);
die("$total <br/>Total Rs.$rs");
}
}

}
else
{
die("You are not authorised.");
}

}
//------------------------------------
if ($action=='viewfeepayment')
{
if(($role=='admin') or ($role=='clerk') or ($role=='cashier') or ($role=='spl'))
{
$sql="SELECT * FROM `school_classes` WHERE `schoolid`='$schoolid' And `std`='$std' And `section`='$section' And `roll`='$roll' And DATE_FORMAT(`session`,'%Y')='$sessn'";
$regno=trim(getInfo($sql,'regno'));
$sql="SELECT * FROM `school_fee` WHERE `schoolid`='$schoolid' And `regno`='$regno' and DATE_FORMAT(`paydate`,'%Y')='$sessn' ORDER BY `std`,`section`,`roll`,`paydate` ASC";
$sqlsum="SELECT SUM(`amount`) AS Totalrs FROM `school_fee` WHERE `schoolid`='$schoolid' And `regno`='$regno' and DATE_FORMAT(`paydate`,'%Y')='$sessn'";

if ($std=='ALL') 
{
$sql="SELECT * FROM `school_fee` WHERE `schoolid`='$schoolid' and DATE_FORMAT(`paydate`,'%Y')='$sessn' ORDER BY `std`,`section`,`roll`,`paydate` ASC";
$sqlsum="SELECT SUM(`amount`) AS Totalrs FROM `school_fee` WHERE `schoolid`='$schoolid' and DATE_FORMAT(`paydate`,'%Y')='$sessn'";
}

if (($std=='ALL') And ($section=='ALL') )
{
$sql="SELECT * FROM `school_fee` WHERE `schoolid`='$schoolid' and DATE_FORMAT(`paydate`,'%Y')='$sessn' ORDER BY `std`,`section`,`roll`,`paydate` ASC";
$sqlsum="SELECT SUM(`amount`) AS Totalrs FROM `school_fee` WHERE `schoolid`='$schoolid' and DATE_FORMAT(`paydate`,'%Y')='$sessn'";
}
if (($section!='ALL') And ($std=='ALL'))
{
$sql="SELECT * FROM `school_fee` WHERE `schoolid`='$schoolid' And `section`='$section' And DATE_FORMAT(`paydate`,'%Y')='$sessn' ORDER BY `std`,`section`,`roll`,`paydate` ASC";
$sqlsum="SELECT SUM(`amount`) AS Totalrs FROM `school_fee` WHERE `schoolid`='$schoolid' And `section`='$section' And DATE_FORMAT(`paydate`,'%Y')='$sessn'";

}
if (($section=='ALL') And ($std!='ALL'))
{
$sql="SELECT * FROM `school_fee` WHERE `schoolid`='$schoolid' And `std`='$std' And DATE_FORMAT(`paydate`,'%Y')='$sessn' ORDER BY `std`,`section`,`roll`,`paydate` ASC";
$sqlsum="SELECT SUM(`amount`) AS Totalrs FROM `school_fee` WHERE `schoolid`='$schoolid' And `std`='$std' And DATE_FORMAT(`paydate`,'%Y')='$sessn'";

}
if (($section!='ALL') And ($std!='ALL') And ($roll!=''))
{
$sql="SELECT * FROM `school_classes` WHERE `schoolid`='$schoolid' And `std`='$std' And `section`='$section' And `roll`='$roll'";
$regno=trim(getInfo($sql,'regno'));
$sql="SELECT * FROM `school_fee` WHERE `schoolid`='$schoolid' And `regno`='$regno' and DATE_FORMAT(`paydate`,'%Y')='$sessn' ORDER BY `std`,`section`,`roll`,`paydate` ASC";
$sqlsum="SELECT SUM(`amount`) AS Totalrs FROM `school_fee` WHERE `schoolid`='$schoolid' And `regno`='$regno' and DATE_FORMAT(`paydate`,'%Y')='$sessn'";

}


}
else
{
die("You are not authorised.");
}
$rs=getInfo($sqlsum,'Totalrs');
$total="Payment details for $sessn<br/>".viewfeepayment($sql);
die("$total <br/>Total Rs.$rs");
}
//------------------------------------
if ($action=='expenditure')
{
if ($role=='admin')
{

if ($allocation=='ALL')
{
if ($month=='ALL')
{
$sql="SELECT SUM(`amount`) as total FROM `school_expenditure` WHERE (`schoolid`='$schoolid') and DATE_FORMAT(`paydate`,'%Y')='$year'";
}
else
{
$sql="SELECT SUM(`amount`) as total FROM `school_expenditure` WHERE (`schoolid`='$schoolid') and DATE_FORMAT(`paydate`,'%m-%Y')='$month-$year'";
}
}

else 
{
if ($month=='ALL')
{
$sql="SELECT SUM(`amount`) as total FROM `school_expenditure` WHERE (`schoolid`='$schoolid') and DATE_FORMAT(`paydate`,'%Y')='$year' And `allocation`='$allocation'";
}
else
{
$sql="SELECT SUM(`amount`) as total FROM `school_expenditure` WHERE (`schoolid`='$schoolid') and DATE_FORMAT(`paydate`,'%m-%Y')='$month-$year' And `allocation`='$allocation'";
}

}
$total=getInfo($sql,'total');
if ($total=='')
{
$total="00";
}
die("Expenditure to $allocation in $month-$year is Rs. $total .");
}
}
//-----------------------------------
if ($action=='revenue')
{
if ($role=='admin')
{

if ($allocation=='ALL')
{
if ($month=='ALL')
{
$sql="SELECT SUM(`amount`) as total FROM `school_fee` WHERE (`schoolid`='$schoolid') and DATE_FORMAT(`paydate`,'%Y')='$year'";
}
else
{
$sql="SELECT SUM(`amount`) as total FROM `school_fee` WHERE (`schoolid`='$schoolid') and DATE_FORMAT(`paydate`,'%m-%Y')='$month-$year'";
}
}

else 
{
if ($month=='ALL')
{
$sql="SELECT SUM(`amount`) as total FROM `school_fee` WHERE (`schoolid`='$schoolid') and DATE_FORMAT(`paydate`,'%Y')='$year' And `allocation`='$allocation'";
}
else
{
$sql="SELECT SUM(`amount`) as total FROM `school_fee` WHERE (`schoolid`='$schoolid') and DATE_FORMAT(`paydate`,'%m-%Y')='$month-$year' And `allocation`='$allocation'";
}

}
$total=getInfo($sql,'total');
if ($total=='')
{
$total="00";
}
die("Revenue from $allocation in $month-$year is Rs. $total .");
}
}
//-----------------------------------
if ($action=='uploadphoto')
{
if (($role=='admin') Or ($role='clerk') )
{
//echo "Photo : $photoname ; Photo Error: $errfile ; Photo Size: $size ; Photo Type: $filetype ";
$filetype=strtolower($filetype);
$sql="SELECT * FROM `school_classes` WHERE  `session`='$sessn' And `regno`='$regno' ";
$chkregno=getInfo($sql,'regno');

if ($chkregno=='')
{
die("Please get admission first.");
}

$name=getInfo($sql,'name');
$std=getInfo($sql,'std');
$section=getInfo($sql,'section');
$roll=getInfo($sql,'roll');


if (($size>20000) Or ( ($filetype!='image/jpg') And ($filetype!='image/jpeg') ) Or ($errfile>0) )
{
die("Photo not OK. <br/>  Photo size should be 5KB-20KB in JPEG/JPG format only"); 
}
if ($size<3000) 
{
die("Photo is smaller .");
}
$year=@date("Y");

if ($photosign=='')
{
die("Select Passport or Signature");
}

if ($photosign=='pp')
{
$filenaming="S$schoolid"."RN".$regno."Y".$year."PH".".jpg";
move_uploaded_file($_FILES["photo"]["tmp_name"],"photo/" . $filenaming);
$wd="120px";
$ht="150px";
$sql="UPDATE `school_classes` SET `photo`='$filenaming' WHERE  `session`='$sessn' And `regno`='$regno' And `schoolid`='$schoolid' ";
}
if ($photosign=='sign')
{
$filenaming="S$schoolid"."RN".$regno."Y".$year."SIGN".".jpg";
move_uploaded_file($_FILES["photo"]["tmp_name"],"photo/" . $filenaming);
$wd="180px";
$ht="60px";
$sql="UPDATE `school_classes` SET `sign`='$filenaming' WHERE  `session`='$sessn' And `regno`='$regno' And `schoolid`='$schoolid' ";
}
upDate($sql);



echo "Name: $name ; Class:$std $section ; Roll No.:$roll <br/><img src='photo/$filenaming' width='$wd' height='$ht'
alt='$photosign of $name'><br/>".strtoupper($photosign);

//--------
}
}



//---------------------------------------------
if ($action=='admission')
{
if (($role=='admin') Or ($role='clerk') )
{
if ($std=='')
{
die("Class not selected");
}

$sql="SELECT * FROM `school_classes` WHERE  `session`='$sessn' And `regno`='$regno' And `schoolid`='$schoolid' ";
$chkregno=getInfo($sql,'regno');
$clss=getInfo($sql,'std');
$ses=getInfo($sql,'session');
$sexn=getInfo($sql,'section');

if ($chkregno!='')
{
die("Already admitted in Class-$clss  $sexn for session $ses");
}

$sql="SELECT * FROM `school_students` WHERE `regno`='$regno'  And `schoolid`='$schoolid' ";
$name=getInfo($sql,'name');

if ($name=='')
{
die("Please register the student first then get admision.");
}

if ($roll=='')
{
$sql="SELECT Max(`roll`) as rollno FROM `school_classes` WHERE `std`='$std' And `section`='$section' And `session`='$sessn' And `schoolid`='$schoolid' ";
$rol=getInfo($sql,'rollno');
$roll=$rol+1;
}

$sql="INSERT INTO `school_classes` (`name`,`std`,`section`,`roll`,`session`,`regno`,`photo`,`schoolid`) 
VALUES ('$name','$std','$section','$roll','$sessn','$regno','$filenaming','$schoolid') ";
upDate($sql);

echo "Admitted as below <br/>
<table><tr>
<td  bgcolor='yellow'>
<br/>Name:$name;
<br/>Session:$sessn;
<br/> Class:$std $section;
<br/>Roll.No.:$roll;
<br/>Reg.No. $regno ;</td></tr></table>";
}
}
//------------------------------
if ($action=='biodata')
{
echo "Biodata of Student !";
}
//------------------------------
if ($action=='registration')
{
if (($role=='admin') Or ($role='clerk') )
{
if (($name=='') or ($father=='') Or ($mother=='') Or ($address=='') Or ($phone=='') Or ($po=='') Or ($ps=='') Or ($pin=='')
Or ($district=='') Or ($state=='') Or ($country=='') Or ($dob=='') Or ($sex=='') Or ($nationality=='') Or ($religion=='') Or ($caste=='') Or ($occupationofparent==''))
{
die("Empty field(s)");
} 

$sql="SELECT * FROM `school_users` WHERE `sessionid`='$sessionid' And `schoolid`='$schoolid'";
$userid=getInfo($sql,'user');

$sql="SELECT Max(`regno`) as regnos FROM `school_students` WHERE `schoolid`='$schoolid' ";
$newregno=getInfo($sql,'regnos')+1;
if ($newregno==1)
{
$regno=1000 ;
}
else
{
$regno=$newregno;
}

$sql="INSERT INTO `school_students` (`name`,`father`,`mother`,`address`,`phone`,`po`,`ps`,`district`,`state`,`country`,`pin`,`dob`,`sex`,`nationality`,`religion`,`caste`,`occupationofparent`,`incomeofparent`,`regno`,`remarks`,`userid`,`entry`,`schoolid`) 
VALUES ('$name','$father','$mother','$address','$phone','$po','$ps','$district','$state','$country','$pin','$dob','$sex','$nationality','$religion','$caste','$occupationofparent','$incomeofparent','$regno','$remarks','$userid','$sessn','$schoolid') ";
upDate($sql);
echo "Registration done <br/> Reg.No. of the student is $regno . <br/>
You are advised to convey the registration number to the concerned(s).<br/><a href='printaction.php?sessionid=$sessionid&toprint=registration&regno=$regno' target='_blank'>Print Registration Certificate</a>";
}
}
//---------------------------------
if ($action=='studentdetails')
{
//Name only
$sql="SELECT * FROM `school_students` WHERE  `regno`='$roll' And `schoolid`='$schoolid'";
$rtndata=trim(getInfo($sql,'name'));

if ($rtndata=='')
{
$sql="SELECT * FROM `school_classes` WHERE (`std`='$std') And (`roll`='$roll') And (`session`='$sessn') And (`section`='$section' ) And (`schoolid`='$schoolid') ";
$rtndata=trim(getInfo($sql,'name'));
}

if ($rtndata=='')
{
$rtndata="No data found";
}

die($rtndata);
}
//--------------------------------------
if ($action=='getfeedue')
{
$sql="SELECT * FROM `school_classes` WHERE `std`='$std' And `roll`='$roll' And `session`='$sessn' And `section`='$section' And `schoolid`='$schoolid'";
$regno=trim(getInfo($sql,'regno'));
if ($regno=='')
{
$regno=$roll;
}
$year1=explode("-",$sessn);
$year=$year1[0];
$nxyr=$year+1;

$sql="SELECT * FROM `school_fee` WHERE `schoolid`='$schoolid' And `regno`='$regno' And ((DATE_FORMAT(`paydate`,'%Y')='$year') Or (DATE_FORMAT(`paydate`,'%Y')='$nxyr')) ";
$rtndata=GetAllDataSeparatedByComa($sql,"month");
if ($rtndata=='')
{
die("No monthly payment done for the $sessn");
}


die("Tuition fee already paid for <br/>$rtndata");
}
//------------------------------------------------------------------------
if ($action=='studentfulldetails')
{
if ($regno!='')
{
$sql="SELECT * FROM `school_students` WHERE  `regno`='$regno' And `schoolid`='$schoolid'";
$name=getInfo($sql,'name');
$father=getInfo($sql,'father');
$mother=getInfo($sql,'mother');
$entry=getInfo($sql,'entry');
$address=getInfo($sql,'address');
$address=$address." ".getInfo($sql,'po')."-".getInfo($sql,'pin');
$phone=trim(getInfo($sql,'phone'));
$district=trim(getInfo($sql,'district'));
$state=trim(getInfo($sql,'state'));
$country=trim(getInfo($sql,'country'));
$address=$address."<br/>State:$state <br/>Country:$country";
//-----------------------------------
$sql="SELECT * FROM `school_students` WHERE `schoolid`='$schoolid' And `regno`='$regno' ";
$chkrno=strtoupper(trim(getInfo($sql,'regno')));
$entry=trim(getInfo($sql,'entry'));
//------------------------------------
$dob=trim(getInfo($sql,'dob'));
$sex=trim(getInfo($sql,'sex'));
$nationality=trim(getInfo($sql,'nationality'));
$religion=trim(getInfo($sql,'religion'));
$caste=trim(getInfo($sql,'caste'));
$occupationofparent=trim(getInfo($sql,'occupationofparent'));
$incomeofparent=trim(getInfo($sql,'incomeofparent'));
$remarks=trim(getInfo($sql,'remarks'));


$fulldetails="Reg.No.:$regno </br/> Name:$name <br/> Sex:$sex <br/>Father:$father <br/> Mother:$mother <br/> Year of entry:$entry<br/> Address:$address
<br/>Phone No.:$phone<br/>D.O.B.:$dob<br/>Nationality:$nationality<br/>Religion:$religion<br/>Caste:$caste<br/>Occupation of parent:$occupationofparent<br/>
Income Of Parent:$incomeofparent<br/>Remarks:$remarks";

if ($sessn!='')
{
$sql="SELECT * FROM `school_classes` WHERE  `regno`='$regno' And `schoolid`='$schoolid' And `session`='$sessn' ORDER BY `session` ASC";
}
if ($sessn=='')
{
$sql="SELECT * FROM `school_classes` WHERE  `regno`='$regno' And `schoolid`='$schoolid' ORDER BY `session` ASC";
}

$std=getInfo($sql,'std');
$section=getInfo($sql,'section');
$sessn=getInfo($sql,'session');
$roll=getInfo($sql,'roll');
$fulldetails="$fulldetails <hr>Session:$sessn <br/> Class:$std$section Roll No.:$roll";
if ($name=='')
{
die("No data found!");
}
die($fulldetails);
}

if ($regno=='')
{
if ($sessn!='')
{
$sql="SELECT * FROM `school_classes` WHERE  `std`='$std' And `section`='$section' and `roll`='$roll' And `schoolid`='$schoolid' And `session`='$sessn' ORDER BY `session` ASC";
}

if ($sessn=='')
{
$sql="SELECT * FROM `school_classes` WHERE  `std`='$std' And `section`='$section' and `roll`='$roll' And `schoolid`='$schoolid' ORDER BY `session` ASC";
}

$regno=getInfo($sql,'regno');
$sql="SELECT * FROM `school_students` WHERE  `regno`='$regno' And `schoolid`='$schoolid'";
$name=getInfo($sql,'name');
$father=getInfo($sql,'father');
$mother=getInfo($sql,'mother');
$entry=getInfo($sql,'entry');
$address=getInfo($sql,'address');
$address=$address." ".getInfo($sql,'po')."-".getInfo($sql,'pin');
$fulldetails="Reg.No.:$regno </br/> Name:$name <br/> Father:$father <br/> Mother:$mother <br/> Year of entry:$entry<br/> Address:$address";
$fulldetails="$fulldetails <hr>Session:$sessn <br/> Class:$std$section Roll No.:$roll";

if ($name=='')
{
die("No data found!");
}
die($fulldetails);

}

}
//------------------------------------------------
if ($action=='studentdetails4admission')
{
//Name only
$sql="SELECT * FROM `school_students` WHERE `regno`='$regno' And `schoolid`='$schoolid' ";
$rtndata=getInfo($sql,'name');
if ($rtndata=='')
{
$rtndata="No data found";
}
else
{
$rtndata= "Reg.No.:$regno<br/>"."Name: $rtndata <br/> Father:".getInfo($sql,'father');
$rtndata= $rtndata."<br/> Mother:".getInfo($sql,'mother');
$rtndata= $rtndata."<br/> Address:".getInfo($sql,'address');
}
echo $rtndata ;
}
//------------Start of signing out--------------------


if ($action=='signout')
{
$sql="UPDATE `school_users` SET `sessionid`='' WHERE `sessionid`='$sessionid' And `schoolid`='$schoolid' ";
upDate($sql);
echo "Signed out. <a href='login.php'>Login again</a>";
}
//-----------Start of viewing users -----------------------
if ($action=='viewclasses')
{
echo "Class Details<br/>";
echo viewClasses() ;
}
if ($action=='viewexams')
{
echo "Exam Details<br/>";
echo viewExams() ;
}
//------------------------------------------------
if ($action=='viewsubjects')
{
echo "Subject Details<br/>";
echo viewSubjects() ;
}
//-----------------------------------------
if ($action=='viewstaffs')
{
echo "Staffs' Details<br/>"; 
echo viewStaffs() ;
}
//-----------------------------------------
if ($action=='viewrevenueheads')
{
echo "Revenue And Expenditure Heads/Allocations<br/>";
echo viewRevenueHeads() ;
}
//------------------------------------------
if ($action=='viewhighlight')
{
$sql="SELECT  * FROM `school_students` WHERE `regno`='$regno' And `schoolid`='$schoolid' ";
$name=trim(getInfo($sql,'name'));
if ($name=='')
{
die("No record found.");
}
echo "Record/Highlight of $name (Reg.No.:$regno)<br/>";
echo ViewHighlight($regno) ;
}
//--------------------------------------
if ($action=='viewsms')
{
echo "SMS Details of $date<br/>";
echo viewSMSDetails($date) ;
}
//------------------------------------------------
if ($action=='viewusers')
{
if ($role=='admin')
{
echo "User Details<br/>"; 
die(viewUsers($schoolid));
}
}
//-------------------------------
if ($action=='getUserSettingForm')
{
if ($role=='admin')
{
$settingForm='<p  style="float:center; background-color:yellow;"><center>
You are going to to change previleges of users/authority<button  onclick="x()">X</button></center>';
$settingForm=$settingForm."Setting of Users' priveleges<hr/>".getUserSettingForm($schoolid); 
$settingForm=$settingForm.'<hr/><center><div  style="text-align: left;width:250px;background-color:yellow;"><u>Select the privelege(s)</u><br/>
<input id="btn0" type="checkbox" name="btn0" value="1">View Users<br/>
<input id="btn1" type="checkbox" name="btn1" value="1">View Staffs<br/>
<input id="btn2" type="checkbox" name="btn2" value="1">View Classes<br/>
<input id="btn3" type="checkbox" name="btn3" value="1">View Subjects<br/>
<input id="btn4" type="checkbox" name="btn4" value="1">View Exams<br/>
<input id="btn5" type="checkbox" name="btn5" value="1">View Rev Heads<br/>
<input id="btn6" type="checkbox" name="btn6" value="1">View Student details<br/>
<input id="btn7" type="checkbox" name="btn7" value="1">View Highlights<br/>
<input id="btn8" type="checkbox" name="btn8" value="1">View Withheld<br/>
<input id="btn9" type="checkbox" name="btn9" value="1">View Payment(Year)<br/>
<input id="btn10" type="checkbox" name="btn10" value="1">View Payment(Period)<br/>
<input id="btn11" type="checkbox" name="btn11" value="1">View Revenue<br/>
<input id="btn12" type="checkbox" name="btn12" value="1">View SMS<br/>
<input id="btn13" type="checkbox" name="btn13" value="1">View SMS balance<br/>
<input id="btn14" type="checkbox" name="btn14" value="1">View Registration<br/>
<input id="btn15" type="checkbox" name="btn15" value="1">View Students in Class<br/>
<input id="btn16" type="checkbox" name="btn16" value="1">View Admit Card<br/>
<input id="btn17" type="checkbox" name="btn17" value="1">View Attendance<br/>
<input id="btn18" type="checkbox" name="btn18" value="1">View Marks<br/>
<input id="btn19" type="checkbox" name="btn19" value="1">View Reciept<br/>
<input id="btn20" type="checkbox" name="btn20" value="1">Update User<br/>
<input id="btn21" type="checkbox" name="btn21" value="1">Update Staff<br/>
<input id="btn22" type="checkbox" name="btn22" value="1">Update Class<br/>
<input id="btn23" type="checkbox" name="btn23" value="1">Update Subject<br/>
<input id="btn24" type="checkbox" name="btn24" value="1">Update Exam<br/>
<input id="btn25" type="checkbox" name="btn25" value="1">Update Mark<br/>
<input id="btn26" type="checkbox" name="btn26" value="1">Update Revenue Head<br/>
<input id="btn27" type="checkbox" name="btn27" value="1">Update Registration<br/>
<input id="btn28" type="checkbox" name="btn28" value="1">Update Mobile #<br/>
<input id="btn29" type="checkbox" name="btn29" value="1">Update Student Data  <br/>
<input id="btn30" type="checkbox" name="btn30" value="1">Admission<br/>
<input id="btn31" type="checkbox" name="btn31" value="1">Payment<br/>
<input id="btn32" type="checkbox" name="btn32" value="1">Payment(Misc.)<br/>
<input id="btn33" type="checkbox" name="btn33" value="1">Upload Photo(s)<br/>
<input id="btn34" type="checkbox" name="btn34" value="1">Attendance<br/>
<input id="btn35" type="checkbox" name="btn35" value="1">Withheld<br/>
<input id="btn36" type="checkbox" name="btn36" value="1">Highlight<br/>
<input id="btn37" type="checkbox" name="btn37" value="1">SMS with Roll#<br/>
<input id="btn38" type="checkbox" name="btn38" value="1">SMS with Reg.#<br/>
<input id="btn39" type="checkbox" name="btn39" value="1">SMS to Mobile.#<br/>
<input id="btn40" type="checkbox" name="btn40" value="1">SMS to Absentees<br/>
<input id="btn41" type="checkbox" name="btn41" value="1">SMS to Class<br/>
<input id="btn42" type="checkbox" name="btn42" value="1">SMS to Staffs<br/>
<input id="btn43" type="checkbox" name="btn43" value="1">Delete Wrong Admission<br/>
<input id="btn44" type="checkbox" name="btn44" value="1">Delete Wrong Payment<br/>
<input id="btn45" type="checkbox" name="btn45" value="1">Delete Group data<br/>
<input id="btn46" type="checkbox" name="btn46" value="1">Backup Data<br/>
</div>
</center>
<hr/>
</span><button id="creating" onclick="SetMenus()">Set Priveleges</button> 
</p>';
die($settingForm);
}
}

if ($action=='viewwithheld')
{
echo "Student withheld details<br/>"; 
echo ViewWithheld($schoolid) ;
}

//-----Function for Disabling/Enabling a user
if ($action=='disableenable')
{
if ($role=='admin')
{
$sql="SELECT  * FROM `school_users` WHERE `user`='$userid' And `schoolid`='$schoolid' ";
$name=getInfo($sql,'name');
if ($disableenable=='disable')
{
$enabled="no";
}
if ($disableenable=='enable')
{
$enabled="yes";
}
$sql="UPDATE `school_users`  SET `enabled`='$enabled'  WHERE `user`='$userid' And `schoolid`='$schoolid'";
upDate($sql);
$disableenable="$name ($userid) is $disableenable"."d.";
echo "$disableenable"; 
}
}

//--------------Start Create user----------------------------------
if(($action=='createuser') And ($name!='') And ($roleuser!=''))
{
if ($role=='admin')
{
$sql="SELECT Max(`user`) as NewUser FROM `school_users` WHERE `schoolid`='$schoolid' ";
$user=getInfo($sql,'NewUser')+1;
$pwd=rand(10000,99999);

$sql="INSERT INTO `school_users` (`user`,`name`,`pwd`,`role`,`sessionid`,`enabled`,`schoolid`) VALUES ('$user', '$name','$pwd','$roleuser','','yes','$schoolid') ";
upDate($sql);
echo "User Details<br/>"; 
echo "Created user : $user <br/>  <br/> Name : $name <br/> Authority : $roleuser <br/> Password : $pwd <br/> " ;
}
}
//---------------Start change password-------------------------------
if ($action=='changepassword')
{
if (($pwd1==$pwd2) And ($pwd1!='') )
{
$sql="SELECT  * FROM `school_users` WHERE `sessionid`='$sessionid' And `schoolid`='$schoolid' ";
$user=getInfo($sql,'user');
$sql="UPDATE `school_users`  SET `pwd`='$pwd1'  WHERE `sessionid`='$sessionid' And `schoolid`='$schoolid'";
upDate($sql);
echo "The password changed. ";
}
}
//------------------------
if ($action=='changemasterpassword')
{
if (($pwd1==$pwd2) And ($pwd1!='') and ($role=='admin' ))
{
$sql="UPDATE `school_clients`  SET `masterkey`='$pwd1'  WHERE `schoolid`='$schoolid'";
upDate($sql);
echo "The Master Password changed. ";
}
else
{
die("Wrong data entered.");
}
}
//-----------------------------------
if ($action=='paymisc')
{
if (($role=='admin') Or ($role='cashier') )
{

if ($name=="")
{
die("Name can not be blank");
}

if ($std=="")
{
die("Class can not be blank");
}

if ($amount=="")
{
die( "Amount can not be blank");
}

if ($allocation=="")
{
die("Select 'Payment for' can not be blank");
}

if ((is_numeric($amount)==FALSE) Or ($amount<=0))
{
die("Wrong amount!");
}
$mnths=$month;
$sql="SELECT * FROM `school_users` WHERE `sessionid`='$sessionid' And `schoolid`='$schoolid'";
$user=getInfo($sql,'user');

$sql="SELECT * FROM `school_fee` WHERE `schoolid`='$schoolid'";
$transid=getInfo($sql,"transid");
$rcpno=explode("-",$transid);
$transid=$rcpno[1];
if (is_nan($transid)==true)
{
$transid=1;
}
else
{
$transid=$transid+1;
}
$y=@date("y");
$transid="$y-$transid";

$paydate=@date("Y-m-d");
$sql="INSERT INTO `school_fee` (`transid`,`userid`,`paydate`,`name`,`regno`,`session`,`std`,`section`,`roll`,`allocation`,`amount`,`remarks`,`month`,`schoolid`) VALUES
('$transid','$user','$paydate','$name','','$sessn','$std','$section','$roll','$allocation','$amount','$remarks','$mnths','$schoolid') ";
upDate($sql);
echo "Updated <br/>
Transaction ID : $transid ; Date: $paydate 
<br/>Reg.No.:Nil ; Name : $name ;  Class : $std $section ; Amount: $amount ; Allocation : $allocation ; Remarks: $remarks
<br/><a href='printaction.php?sessionid=$sessionid&toprint=receipt&tid=$transid' target='_blank'>Print Receipt</a>";

}
}
//-------------------------------------------------------

if ($action=='payfee')
{
if (($role=='admin') Or ($role='cashier') )
{

if ($name=="")
{
die("Name can not be blank");
}

if ($std=="")
{
die("Class can not be blank");
}

if ($amount=="")
{
die( "Amount can not be blank");
}

if ($allocation=="")
{
die("Select 'Payment for' can not be blank");
}

if ($roll=="")
{
die("Roll No. can not be blank");
}
if ((is_numeric($amount)==FALSE) Or ($amount<=0))
{
die("Wrong amount!");
}

$mnths=$month;

$sql="SELECT * FROM `school_classes` WHERE (`std`='$std') And (`roll`='$roll') And (`session`='$sessn') And (`section`='$section' ) And (`schoolid`='$schoolid')";
$regno=trim(getInfo($sql,'regno'));

if($regno=='') 
{
$sql="SELECT * FROM `school_students` WHERE (`regno`='$roll') And (`schoolid`='$schoolid')";
$regno=getInfo($sql,'regno');
$sql="SELECT * FROM `school_classes` WHERE (`regno`='$roll') And (`schoolid`='$schoolid')";
$std=getInfo($sql,'std');
$roll=getInfo($sql,'roll');
$section=getInfo($sql,'section');
}
if (($regno==''))
{
die("Check student's details.");
}
//------Check of Last fees---- 
$regn=checkMonthlyFee($month,$regno,$sessn,$allocation); 
if ($regn>0)
{
die("Payment already done in the $regn selected month(s) in $mnths</br>Please check previous payment.");
}
//------End of Last Fees---------
$sql="SELECT * FROM `school_users` WHERE `sessionid`='$sessionid' And `schoolid`='$schoolid'";
$user=getInfo($sql,'user');
$sql="SELECT * FROM `school_fee` WHERE `schoolid`='$schoolid'";
$transid=getInfo($sql,"transid");
$rcpno=explode("-",$transid);
$transid=$rcpno[1];
if (is_nan($transid)==true)
{
$transid=1;
}
else
{
$transid=$transid+1;
}
$y=@date("y");
$transid="$y-$transid";
$paydate=@date("Y-m-d");
$sql="INSERT INTO `school_fee` (`transid`,`userid`,`paydate`,`name`,`regno`,`session`,`std`,`section`,`roll`,`allocation`,`amount`,`remarks`,`month`,`schoolid`) VALUES
('$transid','$user','$paydate','$name','$regno','$sessn','$std','$section','$roll','$allocation','$amount','$remarks','$mnths','$schoolid') ";
upDate($sql);
echo "Updated <br/>
Transaction ID : $transid ; Date: $paydate 
<br/>Reg.No.:$regno ; Name : $name ;  Class : $std $section ; Amount: $amount ; Allocation : $allocation ; Remarks: $remarks
<br/><a href='printaction.php?sessionid=$sessionid&toprint=receipt&tid=$transid' target='_blank'>Print Receipt</a>";

}
}

//------------------------------------------------------
}
?>

<?
//-----Function for Checking Monthly Fee Payment ------------
function checkMonthlyFee($months,$regno,$session,$allocation)
{
$months=strtoupper($months);
$dts=@Date("Y-01-01");
$montharr=explode(";","$months");
$arrlen=sizeof($montharr);
$paid=0;
$arrlen=$arrlen-1;
while ($arrlen>=0)
{
$FeeForMonth=$montharr[$arrlen];
//----------AAAA-------------------
if (($FeeForMonth!=';') And ($FeeForMonth!="") )
{
$sql="SELECT * FROM `school_fee` WHERE `regno`='$regno' And `paydate`>='$dts' And `session`='$session' And `allocation`='$allocation' And `month` LIKE '%$FeeForMonth%'";
$rno=getInfo($sql,'regno');
if ($rno=="$regno")
{
$paid=$paid+1;
$rno="notpaid";
}
}
//--------AAA---------------------
$arrlen=$arrlen-1;
} 
return $paid;
}
?>

<?

function viewfeepayment($sql)
{
Global $dbserver,$dbuser,$dbpwd,$dbname,$schoolid,$user ; 
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
$table="<table><tr><th bgcolor='green'><u>Class-Roll</u></th><th bgcolor='green'><u>Reg. No.</u></th><th bgcolor='green'><u>Name</u></th><th bgcolor='green'><u>Head</u></th><th bgcolor='green'><u>Amount</u></th>
<th bgcolor='green'><u>Payment date</u></th><th bgcolor='green'><u>Trans.ID</u></th><th bgcolor='green'><u>Remarks</u></th>";
while ($db_field =mysql_fetch_assoc($result))
{
$name=$db_field['name'];
$regno=$db_field['regno'];
$allocation=strtoupper($db_field['allocation']);
$amount=strtoupper($db_field['amount']);
$paydate=strtoupper($db_field['paydate']);
$transid=strtoupper($db_field['transid']);
$remarks=strtoupper($db_field['remarks']);
$month=strtoupper($db_field['month']);
$std=strtoupper($db_field['std']);
$section=strtoupper($db_field['section']);
$roll=strtoupper($db_field['roll']);

$table="$table<tr><td bgcolor='yellow'>$std$section-$roll</td><td bgcolor='yellow'>$regno</td><td bgcolor='yellow'>$name</td><td bgcolor='yellow'>$allocation</td>
<td bgcolor='yellow'>$amount</td><td bgcolor='yellow'>$paydate</td><td bgcolor='yellow'>$transid</td><td bgcolor='yellow'> $remarks $month ;</td></tr>";
}
mysql_close($con);
$table="$table</table>";
return $table;
}
?>


<?

function viewfeepaymentdate($sql)
{
Global $dbserver,$dbuser,$dbpwd,$dbname,$schoolid,$user ; 
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
$table="<table><tr><th bgcolor='green'><u>Class-Roll</u></th><th bgcolor='green'><u>Reg. No.</u></th><th bgcolor='green'><u>Name</u></th><th bgcolor='green'><u>Head</u></th><th bgcolor='green'><u>Amount</u></th>
<th bgcolor='green'><u>Payment date</u></th><th bgcolor='green'><u>Trans.ID</u></th><th bgcolor='green'><u>Remarks</u></th>";
while ($db_field =mysql_fetch_assoc($result))
{
$name=$db_field['name'];
$regno=$db_field['regno'];

$allocation=strtoupper($db_field['allocation']);
$amount=strtoupper($db_field['amount']);
$paydate=strtoupper($db_field['paydate']);
$transid=strtoupper($db_field['transid']);
$remarks=strtoupper($db_field['remarks']);
$month=strtoupper($db_field['month']);

$std=strtoupper($db_field['std']);
$section=strtoupper($db_field['section']);
$roll=strtoupper($db_field['roll']);

$table="$table<tr><td bgcolor='yellow'>$std$section-$roll</td><td bgcolor='yellow'>$regno</td><td bgcolor='yellow'>$name</td><td bgcolor='yellow'>$allocation</td>
<td bgcolor='yellow'>$amount</td><td bgcolor='yellow'>$paydate</td><td bgcolor='yellow'>$transid</td><td bgcolor='yellow'> $remarks $month ;</td></tr>";
}
mysql_close($con);
$table="$table</table>";
return $table;
}
?>


<?
function ViewWithheld($schoolid)
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
$sql="SELECT * FROM `school_withheld` WHERE `schoolid`='$schoolid' ORDER BY `regno` ASC";
$result = mysql_query($sql);
$table="<table><tr><th bgcolor='green'><u>Reg.No.</u></th><th bgcolor='green'><u>Remarks</u></th><th bgcolor='green'><u>Data Entered by </u></th></tr>";
while ($db_field = mysql_fetch_assoc($result))
{
$regno=$db_field['regno'];
$remarks=$db_field['remarks'];
$user=$db_field['user'];
$table="$table<tr><td bgcolor='yellow'>$regno</td><td bgcolor='yellow'>$remarks</td><td bgcolor='yellow'>$user</td>  </tr>";
}
mysql_close($con);
$table="$table</table>";
return $table;
}
//-------------------------------------------------------------
function viewExams()
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
$sql="SELECT * FROM `school_exam` WHERE `schoolid`='$schoolid' ORDER BY `exam` ASC";
$result = mysql_query($sql);
$table="<table><tr><th bgcolor='green'><u>Exam ID</u></th><th bgcolor='green'><u>Exam Name</u></th></tr>";
while ($db_field =mysql_fetch_assoc($result))
{
$examid=$db_field['examid'];
$exam=$db_field['exam'];
$table="$table<tr><td bgcolor='yellow'>$examid</td><td bgcolor='yellow'>$exam</td> </tr>";
}
mysql_close($con);
$table="$table</table>";
return $table;
}
//-----------------------------------------------------

function viewRevenueHeads()
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
$sql="SELECT * FROM `school_revheads` WHERE `schoolid`='$schoolid' ORDER BY `allocation` ASC";
$result = mysql_query($sql);
$table="<table><tr><th bgcolor='green'><u>ID</u></th><th bgcolor='green'><u>Rev./Expend. Name</u></th> <th bgcolor='green'><u>Revenue/Expenditure </u></th></tr>";
while ($db_field =mysql_fetch_assoc($result))
{
$allocationid=$db_field['allocationid'];
$allocation=$db_field['allocation'];
$plusminus=$db_field['plusminus'];
if ($plusminus=='-')
{
$expend="Expenditure";
}
if ($plusminus=='+')
{
$expend="Revenue";
}

$table="$table<tr><td bgcolor='yellow'>$allocationid</td><td bgcolor='yellow'>$allocation</td> <td bgcolor='yellow'>$expend</td> </tr>";
}
mysql_close($con);
$table="$table</table>";
return $table;
}
//------------------------------------------------
function ViewHighlight($regno)
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
$sql="SELECT * FROM `school_highlight` WHERE `schoolid`='$schoolid' And `regno`='$regno'";
$result = mysql_query($sql);
$table="<table><tr><th bgcolor='green'><u>Record ID</u></th><th bgcolor='green'><u>Record/Highlight</u></th></tr>";
while ($db_field =mysql_fetch_assoc($result))
{
$highlightid=$db_field['highlightid'];
$highlight=$db_field['highlight'];
$table="$table<tr><td bgcolor='yellow'>$highlightid</td><td bgcolor='yellow'>$highlight</td></tr>";
}
mysql_close($con);
$table="$table</table>";
return $table;
}
//--------------------------------------------------------
//-------------------------------------------------
function viewSMSDetails($date)
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
$sql="SELECT * FROM `school_sms_data` WHERE `schoolid`='$schoolid' And `smsdate`='$date'";
$result = mysql_query($sql);
$table="<table><tr><th bgcolor='green'><u>SMS ID</u></th><th bgcolor='green'><u>SMS</u></th><th bgcolor='green'><u>SMS Type</u></th><th bgcolor='green'><u>Nos. of recipients</u></th></tr>";
while ($db_field =mysql_fetch_assoc($result))
{
$smsid=$db_field['smsid'];
$sms=$db_field['sms'];
$smstype=strtoupper($db_field['smstype']);
$totalsms=strtoupper($db_field['totalsms']);
$table="$table<tr><td bgcolor='yellow'>$smsid</td><td bgcolor='yellow'>$sms</td> <td bgcolor='yellow'>$smstype</td><td bgcolor='yellow'><center>$totalsms</center></td></tr>";
}
mysql_close($con);
$table="$table</table>";
return $table;
}
//--------------------------------------------------------
function viewStaffs()
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
$sql="SELECT * FROM `school_staffs` WHERE `schoolid`='$schoolid' ORDER BY `designation` ASC";
$result = mysql_query($sql);
$table="<table><tr><th bgcolor='green'><u>Staff ID</u></th> <th bgcolor='green'><u>Staff Name</u></th> <th bgcolor='green'><u>Designation</u></th><th bgcolor='green'><u>Mobile No.</u></th> <th bgcolor='green'><u>Remarks</u></th></tr>";
while ($db_field =mysql_fetch_assoc($result))
{
$staffid=$db_field['staffid'];
$staffname=$db_field['staffname'];
$designation=$db_field['designation'];
$mobile=$db_field['mobile'];
$remarks=$db_field['remarks'];
$table="$table<tr><td bgcolor='yellow'>$staffid</td><td bgcolor='yellow'>$staffname</td> <td bgcolor='yellow'>$designation</td><td bgcolor='yellow'>$mobile</td><td bgcolor='yellow'>$remarks</td></tr>";
}
mysql_close($con);
$table="$table</table>";
return $table;
}
//-------------------------------------------------------

function viewSubjects()
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
$sql="SELECT * FROM `school_subjects` WHERE `schoolid`='$schoolid' ORDER BY `stdid` ASC";
$result = mysql_query($sql);
$table="<table><tr><th bgcolor='green'><u>Class ID</u></th> <th bgcolor='green'><u>Subject ID</u></th> <th bgcolor='green'><u>Subject</u></th></th></tr>";
while ($db_field =mysql_fetch_assoc($result))
{
$stdid=$db_field['stdid'];
$std=$db_field['std'];
$subjectid=$db_field['subjectid'];
$subject=$db_field['subject'];
$table="$table<tr><td bgcolor='yellow'>$stdid</td><td bgcolor='yellow'>$subjectid</td> <td bgcolor='yellow'>$subject</td></tr>";
}
mysql_close($con);
$table="$table</table>";
return $table;
}
//--------------------------------------------------------------
function viewClasses()
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
$sql="SELECT * FROM `school_stds` WHERE `schoolid`='$schoolid' ORDER BY `stdid` ASC";
$result = mysql_query($sql);
$table="<table><tr><th bgcolor='green'><u>Class ID</u></th><th bgcolor='green'><u>Class</u></th></th></tr>";
while ($db_field =mysql_fetch_assoc($result))
{
$stdid=$db_field['stdid'];
$std=$db_field['std'];
$table="$table<tr><td bgcolor='yellow'>$stdid</td><td bgcolor='yellow'>$std</td></tr>";
}
mysql_close($con);
$table="$table</table>";
return $table;
}
?>

<?
//---------------------function for sending SMS on sms.globalbulksms.com----------------------------------
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
//-------------------------------------------------------------------------
function SendSMS($sms,$phones)
{
return $resps ;
}
?>

<?
//--------------------------Get All Data Separated by comas-------------------
function GetAllDataSeparatedByComa($sql,$rtnfld)
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
while ($db_field =mysql_fetch_assoc($result))
{
$id=$id.$db_field[$rtnfld].",";
}
mysql_close($con);

return $id;
}
?>

<?
function getUserSettingForm($schoolid)
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
$sql="SELECT * FROM `school_users` WHERE `schoolid`='$schoolid' ORDER BY `user` ASC";
$result = mysql_query($sql);
while ($db_field =mysql_fetch_assoc($result))
{
$name=$db_field['name'];
$user=$db_field['user'];
$role=strtoupper($db_field['role']);
if ($role!="ADMIN")
{
$option="$option".'<option value="'.$user.'">USER-ID:'."$user($name)</option>";
}
}
mysql_close($con);
$dropdownlist='<select id="user" name="user"><option value="">Select User</option>';
$dropdownlist="$dropdownlist $option</select><br/>";
return "$dropdownlist";
}
?>

<?
function viewUsers($schoolid)
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
$sql="SELECT * FROM `school_users` WHERE `schoolid`='$schoolid' ORDER BY `user` ASC";
$result = mysql_query($sql);
$table="<table><tr><th bgcolor='green'><u>UserID</u></th><th bgcolor='green'><u>Name</u></th><th bgcolor='green'><u>Authority</u></th>
<th bgcolor='green'><u>Enabled ?</u></th></tr>";
while ($db_field =mysql_fetch_assoc($result))
{
$name=$db_field['name'];
$user=$db_field['user'];
$role=strtoupper($db_field['role']);
$enabled=strtoupper($db_field['enabled']);
if ($enabled=='NO')
{
$operate="Enable";
}
if ($enabled=='YES') 
{
$operate="Disable";
}
if ($role=='ADMIN') 
{

$operate="";
}

$table="$table<tr><td bgcolor='yellow'>$user</td><td bgcolor='yellow'>$name</td><td bgcolor='yellow'>$role</td><td bgcolor='yellow'><center>$enabled ".'<a id="operate" onclick="DisableEnable('."'$user','$operate'".')"'."href='#'>$operate</a></center></td></tr>";
}
mysql_close($con);
$table="$table</table>";
return $table;
}
//----------------------------End of function-------------------------------------------
?>

