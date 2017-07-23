<?php
	include $_SERVER["DOCUMENT_ROOT"]."/landbaksa/database/inc_dbconnect.php";
	
	//header("Content-Type:text/html; charset=euc-kr");
	//header("Content-Encoding:utf-8");

	$result = '{"signupJson":"error"}';
	
	
	$userid = mysql_escape_string($_GET["userid"]); //ok
	$pwd = mysql_escape_string($_GET["pwd"]); //ok
	$username = mysql_escape_string($_GET["username"]); //ok
	
		
		
	$insertsql = "INSERT INTO landbaksa_user SET userid='$userid',pwd='$pwd',username='$username',regdate=NOW()";

	$temp = mysql_query($insertsql);
		
		

		if($temp)
		{
			
			
			$result ='{"signupJson":"insertok"';
			$result .= ',"username":"'.$username.'"';
			$result .='}';
			
		}
		else
		{
				$result ='{"signupJson":"no"}';
		}
		
	//}
	
	echo $result;
?>
