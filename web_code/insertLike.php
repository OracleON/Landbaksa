<?php
	include $_SERVER["DOCUMENT_ROOT"]."/landbaksa/database/inc_dbconnect.php";
	
	//header("Content-Type:text/html; charset=euc-kr");
	//header("Content-Encoding:utf-8");
	
	$data_back = json_decode(file_get_contents("php://input"));
	
	//$data = file_get_contents("php://input");
	
		
	if(isset($_POST["search_seq"]))
		$search_seq = $_POST['search_seq'];
	else
		$search_seq = "";
	
	$like ='n';
	
	
	$result = '{"signupJson":"error","message":"서버에 접속할수 없습니다."}';
	
	$presql = mysql_query("SELECT like_yn FROM landbaksa_search_history  WHERE seq ='$search_seq'");
	$row = mysql_fetch_array($presql);
	
	if($row['like_yn'] == 'y')
	{
		$like = 'n';
	}else
	{
		$like ='y';
	}
	

	$updatesql = mysql_query("UPDATE landbaksa_search_history SET like_yn='$like' WHERE seq ='$search_seq'");
	if($updatesql)
	{
		//이미 해당 번호로 등록되어 있는 id 존재한다.
		$result ='{"signupJson":"'.$like.'","message":"관심 감정으로 등록."}';
	}else
	{
		$result ='{"signupJson":"'.$like.'","message":"관심 감정으로 등록실패"}';		
		
	}
	
	echo $result;
?>
