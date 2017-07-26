<?php
	include $_SERVER["DOCUMENT_ROOT"]."/landbaksa/database/inc_dbconnect.php";
	
	//header("Content-Type:text/html; charset=euc-kr");
	//header("Content-Encoding:utf-8");

	$result = '{"signupJson":"no"}';
	
 	$userid = $_GET["userid"]; //ok
	$lawcode = $_GET["law_code"]; //ok
	$jibun = $_GET["ji_bun"]; //ok
	
	$tempjibun = split('-', $jibun);
	
// 	//for test
// 	$userid = "test10";
// 	$sigunguCd = "11500"; 
// 	$bjdongCd = "10800";
// 	$bun = "1374"; 
// 	$bun = substr($bun, -4,4);
// 	$ji = "0000";
// 	$ji = substr($ji, -4,4);

	$sigunguCd = substr($lawcode, 0,5);
	$bjdongCd = substr($lawcode, 5,9);
	$bun = "0000".$tempjibun[0];
	$bun = substr($bun, -4,4); 
	$ji = "0000".$tempjibun[1];
	$ji = substr($ji, -4,4);
	
	$today = date('Ym');
	
	$silprice_url = 'http://openapi.molit.go.kr/OpenAPI_ToolInstallPackage/service/rest/RTMSOBJSvc/getRTMSDataSvcAptTradeDev?LAWD_CD='.$sigunguCd.'&DEAL_YMD=201512&serviceKey='.$apikey.'&numOfRows=9999';
	
	$ch = curl_init();
	
	curl_setopt($ch,CURLOPT_URL,$silprice_url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_NOSIGNAL, 1);
	
	$data = curl_exec($ch);
	curl_close($ch);
	
	$object = simplexml_load_string($data);
	$newobject = json_encode($object);
	$object_json = json_decode($newobject,true);
	$result = $newobject;
	
	$totalCount = $object_json['body']['totalCount'];
	
	for($i=0; $i < $totalCount; $i++){
	    $item = $object_json['body']['items']['item'][$i];

	    $amount = trim($item['거래금액']);
	    $build_year = trim($item['건축년도']);
	    $year = trim($item['년']);
	    $road_nm = trim($item['도로명']);
	    $law_location_nm = trim($item['법정동']);
	    $t_bun = trim($item['법정동본번코드']);
	    $t_ji = trim($item['법정동부번코드']);
	    $t_sigunguCd = trim($item['법정동시군구코드']);
	    $t_bjdongCd = trim($item['법정동읍면동코드']);
	    $apartment_nm = trim($item['아파트']);
	    $month = trim($item['월']);
	    $day = trim($item['일']);
	    $silprice_seq = trim($item['일련번호']);
	    $size = trim($item['전용면적']);
	    $story = trim($item['층']);

	    $insertsql = "INSERT INTO landbaksa_silprice_info SET amount='$amount',build_year='$build_year',year='$year',road_nm='$road_nm',law_location_nm='$law_location_nm',bun='$t_bun',ji='$t_ji',sigunguCd='$t_sigunguCd',bjdongCd='$t_bjdongCd',apartment_nm='$apartment_nm',month='$month',day='$day',silprice_seq='$silprice_seq',size='$size',story='$story'";
	    $temp = mysql_query($insertsql);
	    
// 	    echo $item['거래금액']." ";
// 	    echo $item['건축년도']." ";
// 	    echo $item['년']." ";
// 	    echo $item['도로명']." ";
// 	    echo $item['도로명건물본번호코드']." ";
// 	    echo $item['도로명건물부번호코드']." ";
// 	    echo $item['도로명시군구코드']." ";
// 	    echo $item['도로명일련번호코드']." ";
// 	    echo $item['도로명지상지하코드']." ";
// 	    echo $item['도로명코드']." ";
// 	    echo $item['법정동']." ";
// 	    echo $item['법정동본번코드']." ";
// 	    echo $item['법정동부번코드']." ";
// 	    echo $item['법정동시군구코드']." ";
// 	    echo $item['법정동읍면동코드']." ";
// 	    echo $item['법정동지번코드']." ";
// 	    echo $item['아파트']." ";
// 	    echo $item['월']." ";
// 	    echo $item['일']." ";
// 	    echo $item['일련번호']." ";
// 	    echo $item['전용면적']." ";
// 	    echo $item['지번']." ";
// 	    echo $item['지역코드']." ";
// 	    echo $item['층']." ";
// 	    echo "</br>";
	}

	echo $result;
?>
