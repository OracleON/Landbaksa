<?php
	include $_SERVER["DOCUMENT_ROOT"]."/landbaksa/database/inc_dbconnect.php";
	
//	$result = '{"signupJson":"no"}';
	
/*공통으로 사용되는 전역 변수 선언*/
    $apikey = 'sGT%2FVONLJV4Uj5UsA4AM9rLhFVEF2BGSGTvprzLt5jJtkQuuzSvAWchBH5y5v4s0tADgb%2Fjl%2BTZO2Tklz2zjSg%3D%3D';

    $apartHousingPriceURL = 'http://apis.data.go.kr/1611000/nsdi/ApartHousingPriceService/attr/getApartHousingPriceAttr?ServiceKey='.$apikey;
    $indvdHousingPriceURL = 'http://apis.data.go.kr/1611000/nsdi/IndvdHousingPriceService/attr/getIndvdHousingPriceAttr?ServiceKey='.$apikey;
    $indvdLandPriceURL = 'http://apis.data.go.kr/1611000/nsdi/IndvdLandPriceService/attr/getIndvdLandPriceAttr?ServiceKey='.$apikey;

	$userid = $_GET["userid"]; //ok
	$lawcode = $_GET["law_code"]; //ok
	$jibun = $_GET["ji_bun"]; //ok
	
	$tempjibun = split('-', $jibun);
	
//	$sigunguCd = substr($lawcode, 0,5);
//	$bjdongCd = substr($lawcode, 5,9);
	$bun = "0000".$tempjibun[0];
	$bun = substr($bun, -4,4);
	$ji = "0000".$tempjibun[1];
	$ji = substr($ji, -4,4);
	
	$today = date('Ym');

/*변수 확인*/
	echo $lawcode."/".$bun."/".$ji."/".$today;

	$numOfRows = 10; // 한번에 가져올 데이터 수. 응답데이터의 totalCount에 따라 늘려야함
    $stdrYear = 2012; // 데이터 조회 기준년도
    $landBookCode = 1; // 토지(임야)대장구분

	// ====> 아파트 공시지가 API
	$apart_url = $indvdHousingPriceURL.'&pnu='.$lawcode.$landBookCode.$bun.$ji.'&numOfRows='.$numOfRows.'&pageNo=1&stdrYear='.$stdrYear;
	
	//$response = get($energy_query); 
	$url = $apart_url;        //호출대상 URL
    $ch = curl_init(); //파라미터:url -선택사항
    
    curl_setopt($ch,CURLOPT_URL,$url); //여기선 url을 변수로
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_NOSIGNAL, 1);
    //curl_setopt($ch,CURLOPT_POST, 1); //Method를 POST로 지정.. 이 라인이 아예 없으면 GET
    
    $data = curl_exec($ch);

    curl_close($ch);

    echo $data;
    /*$object = simplexml_load_string($data);
    $newobject = json_encode($object);
    $object_json = json_decode($newobject,true);
    
    echo($newobject."/////==>".$object_json."<===/////");
	echo "object_json[0] = ".$object_json->body->totalCount;
	echo("-----");
	echo $object_json['header'];
	echo("-----");
	echo $object_json['body'][0];*/

	
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
	
	
//	echo $result;
?>
