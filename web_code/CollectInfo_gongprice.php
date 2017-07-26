<?php
	include $_SERVER["DOCUMENT_ROOT"]."/landbaksa/database/inc_dbconnect.php";

/*공통으로 사용되는 전역 변수 선언*/
    // OPEN API KEY
    $apikey = 'sGT%2FVONLJV4Uj5UsA4AM9rLhFVEF2BGSGTvprzLt5jJtkQuuzSvAWchBH5y5v4s0tADgb%2Fjl%2BTZO2Tklz2zjSg%3D%3D';

    // 기본 URL
    $apartHousingPriceURL = 'http://apis.data.go.kr/1611000/nsdi/ApartHousingPriceService/attr/getApartHousingPriceAttr?ServiceKey='.$apikey;
    $indvdHousingPriceURL = 'http://apis.data.go.kr/1611000/nsdi/IndvdHousingPriceService/attr/getIndvdHousingPriceAttr?ServiceKey='.$apikey;
    $indvdLandPriceURL = 'http://apis.data.go.kr/1611000/nsdi/IndvdLandPriceService/attr/getIndvdLandPriceAttr?ServiceKey='.$apikey;

    // 사용자 이름, 법정동코드, 지번
	$userid = $_GET["userid"]; //ok
	$lawcode = $_GET["law_code"]; //ok
	$jibun = $_GET["ji_bun"]; //ok

    // URL 형식에 맞게 지번 가공
	$tempjibun = split('-', $jibun);
	$bun = "0000".$tempjibun[0];
	$bun = substr($bun, -4,4);
	$ji = "0000".$tempjibun[1];
	$ji = substr($ji, -4,4);

	// 오늘 날짜(년월)
	$today = date('Ym');


/*변수 확인*/
	echo $lawcode."/".$bun."/".$ji."/".$today.'<br/>';


/*요청 생성 함수 정의*/
    function getRequestData($baseURL, $lawCode, $mainAddr, $subAddr) {
        $numOfRows = 10; // 한번에 가져올 데이터 수. 응답데이터의 totalCount에 따라 늘려야함
        $stdrYear = 2012; // 데이터 조회 기준년도
        $landBookCode = 1; // 토지(임야)대장구분
        $format = 'json'; // 응답데이터 형식 구분

        // 요청 URL 생성
        $url = $baseURL.'&pnu='.$lawCode.$landBookCode.$mainAddr.$subAddr.'&numOfRows='.$numOfRows.'&pageNo=1&stdrYear='.$stdrYear.'&format='.$format;

        // curl 세션 초기화
        $ch = curl_init(); //파라미터:url -선택사항

        // curl 옵션 세팅
        curl_setopt($ch,CURLOPT_URL, $url); //여기선 url을 변수로
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_NOSIGNAL, 1);
        //curl_setopt($ch,CURLOPT_POST, 1); //Method를 POST로 지정.. 이 라인이 아예 없으면 GET

        // curl 실행
        $data = curl_exec($ch);

        // curl 세션 종료
        curl_close($ch);

        // JSON 일경우 PHP 배열화
        if($format == 'json') {
            $data = json_decode($data, true);
        }
        return $data;
    }

    $responseData = getRequestData($indvdHousingPriceURL, $lawcode, $bun, $ji);

    echo $responseData['indvdHousingPrices']['field'][0]['ldCode'];
//    var_dump($data['indvdHousingPrices']['field'][0]['ldCode']);
//    var_dump($data->indvdHousingPrices);
//    echo $data['indvdHousingPrices']['field'];
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
