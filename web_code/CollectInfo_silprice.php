<?php
	include $_SERVER["DOCUMENT_ROOT"]."/landbaksa/database/inc_dbconnect.php";
	
	//header("Content-Type:text/html; charset=euc-kr");
	//header("Content-Encoding:utf-8");

	$result = '{"signupJson":"no"}';
	
 	$userid = $_GET["userid"]; //ok
	$lawcode = $_GET["law_code"]; //ok
	$jibun = $_GET["ji_bun"]; //ok
	
	$tempjibun = split('-', $jibun);
	
	//for test
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
	
	$this_month = date('Y').date('m');
	$start_month = "201709";
// 	$start_month = "201601";
	for($deal_ym = $start_month; $deal_ym <= $this_month; $deal_ym++){
	    if(substr($deal_ym,-2) > "12"){
	        $deal_ym=(substr($deal_ym, 0, 4)+1)."01";
	    }
// 	    echo($deal_ym)."</br>";
	    $apikey ='sGT%2FVONLJV4Uj5UsA4AM9rLhFVEF2BGSGTvprzLt5jJtkQuuzSvAWchBH5y5v4s0tADgb%2Fjl%2BTZO2Tklz2zjSg%3D%3D';
	    $silprice_url = 'http://openapi.molit.go.kr/OpenAPI_ToolInstallPackage/service/rest/RTMSOBJSvc/getRTMSDataSvcAptTradeDev?LAWD_CD='.$sigunguCd.'&DEAL_YMD='.$deal_ym.'&serviceKey='.$apikey.'&numOfRows=9999';
	    
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
	    echo($totalCount);
	    
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
    	}
	}
?>
