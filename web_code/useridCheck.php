<?php
	include $_SERVER["DOCUMENT_ROOT"]."/landbaksa/database/inc_dbconnect.php";
	
	//header("Content-Type:text/html; charset=euc-kr");
	//header("Content-Encoding:utf-8");
	
	$data_back = json_decode(file_get_contents("php://input"));
	
	//$data = file_get_contents("php://input");
	
		
	if(isset($_POST["userid"]))
		$userid = $_POST['userid'];
	else
		$userid = "";
	
	
	
	$result = '{"signupJson":"error","message":"서버에 접속할수 없습니다."}';
	
	
	
	$presql = mysql_query("SELECT userid FROM landbaksa_user WHERE userid ='$userid'");
	$precount = mysql_num_rows($presql);
	
	if($precount > 0)
	{
		//이미 해당 번호로 등록되어 있는 id 존재한다.
		$result ='{"signupJson":"no","message":"이미 존재하는 아이디 입니다."}';
	}else
	{
		$result ='{"signupJson":"ok","message":"등록가능한 아이디 입니다."}';		
		
	}
	
	echo $result;
?>
