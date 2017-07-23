<?php
	include $_SERVER["DOCUMENT_ROOT"]."/landbaksa/database/inc_dbconnect.php";
	
	
	$data_back = json_decode(file_get_contents("php://input"));
	
	
	$userid = $_GET['userid'];
	$pwd = $_GET['pwd'];
	
	//$result = '{"signupJson":"error"}';
	
	$sqlresult = mysql_query("SELECT * FROM landbaksa_user WHERE userid='$userid' and pwd ='$pwd'");
	$count = mysql_num_rows($sqlresult);
	$row = mysql_fetch_array($sqlresult);
	
   
	if($count > 0)
	{
		setCookie("userid",$row['userid'],0,"/");			
		setCookie("username",$row['username'],0,"/");
		
		$result ='{"signupJson":"accountok"';
		$result .= ',"username":"'.$row['username'].'"';
		$result .= ',"userid":"'.$row['userid'].'"';
		$result .='}';
		
		//$result ='{"username":"'.$sql.'"}';
			//$result ='{"username":"'.$username.'"}';
	}
	else
	{
		//$result ='{"signupJson":"notsignup"}';
		$result ='{"signupJson":"notsignup"';
		$result .= ',"username":"'.$row['username'].'"';
		$result .= ',"userid":"'.$row['userid'].'"';
		$result .='}';
		//$result ='{"username":"'.$userid.$pwd.'"}';
	}
	
	echo $result;
?>
