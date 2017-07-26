<?php
	include $_SERVER["DOCUMENT_ROOT"]."/landbaksa/database/inc_dbconnect.php";
	
	$result = '{"signupJson":"no"}';
	
	
	$userid = $_GET["userid"]; //ok
	$lawcode = $_GET["law_code"]; //ok
	$jibun = $_GET["ji_bun"]; //ok
	
	$tempjibun = split('-', $jibun);
	
	$sigunguCd = substr($lawcode, 0,5);
	$bjdongCd = substr($lawcode, 5,9);
	$bun = "0000".$tempjibun[0];
	$bun = substr($bun, -4,4);
	$ji = "0000".$tempjibun[1];
	$ji = substr($ji, -4,4);
	
	$today = date('Ym');
	
	echo $sigunguCd."/".$bjdongCd."/".$bun."/".$ji."/".$today;
	
	$apikey ='sGT%2FVONLJV4Uj5UsA4AM9rLhFVEF2BGSGTvprzLt5jJtkQuuzSvAWchBH5y5v4s0tADgb%2Fjl%2BTZO2Tklz2zjSg%3D%3D';
	
	// ====> 에너지 API
	$energy_query ='http://apis.data.go.kr/1611000/BldEngyService/getBeElctyUsgInfo?serviceKey='.$apikey.'&numOfRows=10&pageSize=10&pageNo=1&startPage=1&sigunguCd='.$sigunguCd.'&bjdongCd='.$bjdongCd.'&bun='.$bun.'&ji='.$ji.'&useYm=201601';
	
	//$response = get($energy_query); 
	$url = $energy_query;        //호출대상 URL
    $ch = curl_init(); //파라미터:url -선택사항
    
    curl_setopt($ch,CURLOPT_URL,$url); //여기선 url을 변수로
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_NOSIGNAL, 1);
    //curl_setopt($ch,CURLOPT_POST, 1); //Method를 POST로 지정.. 이 라인이 아예 없으면 GET
    
    $data = curl_exec($ch);
  
    
    curl_close($ch);
    
 
    $object = simplexml_load_string($data);
    $newobject = json_encode($object);
    $object_json = json_decode($newobject,true);
    
    echo($newobject."/////==>".$object_json."<===/////");
	echo "object_json[0] = ".$object_json->body->totalCount;
	echo("-----");
	echo $object_json['header'];
	echo("-----");
	echo $object_json['body'][0];
	
	
	 //echo($data."==>".$object."//날짜:".$resultCode."/".$useQty."/".$useYm);
	/*	
	$insertsql = "INSERT INTO landbaksa_user SET userid='$userid',pwd='$pwd',username='$username',regdate=NOW()";

	$temp = mysql_query($insertsql);
		
	//////////////	

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
	*/	
	
	
	echo $result;
?>
