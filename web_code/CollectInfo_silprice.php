<?php
	include $_SERVER["DOCUMENT_ROOT"]."/landbaksa/database/inc_dbconnect.php";
	
	//header("Content-Type:text/html; charset=euc-kr");
	//header("Content-Encoding:utf-8");

	$result = '{"signupJson":"no"}';
	
	$userid = "test10";
// 	$userid = $_GET["userid"]; //ok
	$lawcode = $_GET["law_code"]; //ok
	$jibun = $_GET["ji_bun"]; //ok
	
	$tempjibun = split('-', $jibun);
	
	//sample code
	$sigunguCd = "11500"; 
	$bjdongCd = "10800";
	$bun = "1374"; 
	$bun = substr($bun, -4,4);
	$ji = "0000";
	$ji = substr($ji, -4,4);

// 	$sigunguCd = substr($lawcode, 0,5); // 11500
// 	$bjdongCd = substr($lawcode, 5,9); // 10800
// 	$bun = "0000".$tempjibun[0]; // 1374
// 	$bun = substr($bun, -4,4); 
// 	$ji = "0000".$tempjibun[1]; // 0000
// 	$ji = substr($ji, -4,4);
	
	$today = date('Ym');
	
// 	echo $sigunguCd."/".$bjdongCd."/".$bun."/".$ji."/".$today;

	$apikey ='sGT%2FVONLJV4Uj5UsA4AM9rLhFVEF2BGSGTvprzLt5jJtkQuuzSvAWchBH5y5v4s0tADgb%2Fjl%2BTZO2Tklz2zjSg%3D%3D';

	//http://openapi.molit.go.kr:8081/OpenAPI_ToolInstallPackage/service/rest/RTMSOBJSvc/getRTMSDataSvcAptTrade?LAWD_CD=11110&DEAL_YMD=201512&serviceKey=W3w3lbvP9c0%2FCEY0x%2Fib74UFOE3On6w%2BprL9Z1yw3GfwotJl8CvFWWpestfb4OvRabuVnoCRaAhrVqvXL4V%2B5w%3D%3D

	//[start] 실거래가 API
	//http://openapi.molit.go.kr/OpenAPI_ToolInstallPackage/service/rest/RTMSOBJSvc/getRTMSDataSvcAptTradeDev?LAWD_CD=11110&DEAL_YMD=201512&serviceKey=sGT%2FVONLJV4Uj5UsA4AM9rLhFVEF2BGSGTvprzLt5jJtkQuuzSvAWchBH5y5v4s0tADgb%2Fjl%2BTZO2Tklz2zjSg%3D%3D&format=json
	
	//요약url
	//$silprice_url = 'http://openapi.molit.go.kr:8081/OpenAPI_ToolInstallPackage/service/rest/RTMSOBJSvc/getRTMSDataSvcAptTrade?LAWD_CD='.$sigunguCd.'&DEAL_YMD=201512&serviceKey='.$apikey;
	//상세url
	$silprice_url = 'http://openapi.molit.go.kr/OpenAPI_ToolInstallPackage/service/rest/RTMSOBJSvc/getRTMSDataSvcAptTradeDev?LAWD_CD='.$sigunguCd.'&DEAL_YMD=201512&serviceKey='.$apikey.'&numOfRows=9999';
	
	$ch = curl_init();
	
	curl_setopt($ch,CURLOPT_URL,$silprice_url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_NOSIGNAL, 1);
	
	$data = curl_exec($ch);
	curl_close($ch);
	
	$object = simplexml_load_string($data);
// 	foreach($$object as $k => $v){
// 	    $$object[$k] = trim($v);
// 	}
	$newobject = json_encode($object);
	$object_json = json_decode($newobject,true);
	
// 	echo($newobject);
	
// 	echo("totalCount=".$object_json['body']['totalCount']."</br>");
// 	echo("body=".$object_json['body']."</br>");
// 	echo("items=".$object_json['body']['items']."</br>");
// 	echo("body=".$object_json['body']['items']['item'][0]."</br>");
// 	echo("body마지막=".$object_json['body']['items']['item'][0]['거래금액']."</br>");
	
	$totalCount = $object_json['body']['totalCount'];
	for($i=0; $i < $totalCount; $i++){
	    $item = $object_json['body']['items']['item'][$i];
	    echo $item['거래금액']." ";
	    echo $item['건축년도']." ";
	    echo $item['년']." ";
	    echo $item['도로명']." ";
	    echo $item['도로명건물본번호코드']." ";
	    echo $item['도로명건물부번호코드']." ";
	    echo $item['도로명시군구코드']." ";
	    echo $item['도로명일련번호코드']." ";
	    echo $item['도로명지상지하코드']." ";
	    echo $item['도로명코드']." ";
	    echo $item['법정동']." ";
	    echo $item['법정동본번코드']." ";
	    echo $item['법정동부번코드']." ";
	    echo $item['법정동시군구코드']." ";
	    echo $item['법정동읍면동코드']." ";
	    echo $item['법정동지번코드']." ";
	    echo $item['아파트']." ";
	    echo $item['월']." ";
	    echo $item['일']." ";
	    echo $item['일련번호']." ";
	    echo $item['전용면적']." ";
	    echo $item['지번']." ";
	    echo $item['지역코드']." ";
	    echo $item['층']." ";
	    echo "</br>";
	}
	
	// 	//[end] 실거래가 API
// // 	$query = 'http://openapi.molit.go.kr:8081/OpenAPI_ToolInstallPackage/service/rest/RTMSOBJSvc/getRTMSDataSvcAptTrade?LAWD_CD=11110&DEAL_YMD=201512&serviceKey=W3w3lbvP9c0%2FCEY0x%2Fib74UFOE3On6w%2BprL9Z1yw3GfwotJl8CvFWWpestfb4OvRabuVnoCRaAhrVqvXL4V%2B5w%3D%3D';

// 	// ====> 에너지 API
// 	$energy_query ='http://apis.data.go.kr/1611000/BldEngyService/getBeElctyUsgInfo?serviceKey='.$apikey.'&numOfRows=10&pageSize=10&pageNo=1&startPage=1&sigunguCd='.$sigunguCd.'&bjdongCd='.$bjdongCd.'&bun='.$bun.'&ji='.$ji.'&useYm=201601';
	
// 	//$response = get($energy_query); 
// 	$url = $energy_query;        //호출대상 URL
//     $ch = curl_init(); //파라미터:url -선택사항
    
//     curl_setopt($ch,CURLOPT_URL,$url); //여기선 url을 변수로
//     curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch,CURLOPT_NOSIGNAL, 1);
//     //curl_setopt($ch,CURLOPT_POST, 1); //Method를 POST로 지정.. 이 라인이 아예 없으면 GET
    
//     $data = curl_exec($ch);
  
    
//     curl_close($ch);
    
 
//     $object = simplexml_load_string($data);
//     $newobject = json_encode($object);
//     $object_json = json_decode($newobject,true);
    
//     echo("totalCount=".$object_json['body']['totalCount']."</br>");
	
	
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
	
	
// 	echo $result;
?>
