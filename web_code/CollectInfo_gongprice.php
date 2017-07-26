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

	// 오늘 날짜(년)
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


/*아파트 공시지가 데이터 삽입*/
// 데이터 요청
$responseData = getRequestData($apartHousingPriceURL, $lawcode, $bun, $ji);

// 데이터 갯수 확인
echo $responseData['apartHousingPrices']['totalCount'];

// 모든 데이터 삽입 시작
$totalCount = $responseData['apartHousingPrices']['totalCount'];
for($i = 0; $i < $totalCount; $i++) {
//        var_dump($responseData['apartHousingPrices']['field'][$i]);
    // 단위 요소 데이터
    $elem = $responseData['apartHousingPrices']['field'][$i];

    // 데이터 묶음. 16개
    $pnu = $elem['pnu'];
    $ldCode = $elem['ldCode'];
    $ldCodeNm = $elem['ldCodeNm'];
    $regstrSeCode = $elem['regstrSeCode'];
    $regstrSeCodeNm = $elem['regstrSeCodeNm'];
    $mnnmSlno = $elem['mnnmSlno'];
    $stdrYear = $elem['stdrYear'];
    $stdrMt = $elem['stdrMt'];
    $aphusCode = $elem['aphusCode'];
    $aphusSeCode = $elem['aphusSeCode'];
    $aphusSeCodeNm = $elem['aphusSeCodeNm'];
    $spclLandNm = $elem['spclLandNm'];
    $aphusNm = $elem['aphusNm'];
    $prvuseAr = $elem['prvuseAr'];
    $pblntfPc = $elem['pblntfPc'];
    $lastUpdtDt = $elem['lastUpdtDt'];

    // 삽입 쿼리 실행
    $insertsql = "INSERT INTO  landbaksa_gongprice_apart_info SET pnu='$pnu', ldCode='$ldCode', ldCodeNm='$ldCodeNm', regstrSeCode='$regstrSeCode', regstrSeCodeNm='$regstrSeCodeNm', mnnmSlno='$mnnmSlno', stdrYear='$stdrYear', stdrMt='$stdrMt', aphusCode='$aphusCode', aphusSeCode='$aphusSeCode', aphusSeCodeNm='$aphusSeCodeNm', spclLandNm='$spclLandNm', aphusNm='$aphusNm', prvuseAr='$prvuseAr', pblntfPc='$pblntfPc', lastUpdtDt='$lastUpdtDt'";
    $temp = mysql_query($insertsql);
}

// 만에 하나 있을 데이터 요청 오류에 대비한 카운트 초기화
$totalCount = 0;


/*건물 공시지가 데이터 삽입*/
    // 데이터 요청
    $responseData = getRequestData($indvdHousingPriceURL, $lawcode, $bun, $ji);

    // 데이터 갯수 확인
    echo $responseData['indvdHousingPrices']['totalCount'];

    // 모든 데이터 삽입 시작
    $totalCount = $responseData['indvdHousingPrices']['totalCount'];
    for($i = 0; $i < $totalCount; $i++) {
//        var_dump($responseData['indvdHousingPrices']['field'][$i]);
        // 단위 요소 데이터
        $elem = $responseData['indvdHousingPrices']['field'][$i];

        // 데이터 묶음. 17개
        $pnu = $elem['pnu'];
        $ldCode = $elem['ldCode'];
        $ldCodeNm = $elem['ldCodeNm'];
        $regstrSeCode = $elem['regstrSeCode'];
        $regstrSeCodeNm = $elem['regstrSeCodeNm'];
        $mnnmSlno = $elem['mnnmSlno'];
        $bildRegstrEsntlNo = $elem['bildRegstrEsntlNo'];
        $stdrYear = $elem['stdrYear'];
        $stdrMt = $elem['stdrMt'];
        $dongCode = $elem['dongCode'];
        $ladRegstrAr = $elem['ladRegstrAr'];
        $calcPlotAr = $elem['calcPlotAr'];
        $buldAllTotAr = $elem['buldAllTotAr'];
        $buldCalcTotAr = $elem['buldCalcTotAr'];
        $housePc = $elem['housePc'];
        $stdLandAt = $elem['stdLandAt'];
        $lastUpdtDt = $elem['lastUpdtDt'];

        // 삽입 쿼리 실행
        $insertsql = "INSERT INTO landbaksa_gongprice_building_info SET pnu='$pnu', ldCode='$ldCode', ldCodeNm='$ldCodeNm', regstrSeCode='$regstrSeCode', regstrSeCodeNm='$regstrSeCodeNm', mnnmSlno='$mnnmSlno', bildRegstrEsntlNo='$bildRegstrEsntlNo', stdrYear='$stdrYear', stdrMt='$stdrMt', dongCode='$dongCode', ladRegstrAr='$ladRegstrAr', calcPlotAr='$calcPlotAr', buldAllTotAr='$buldAllTotAr', buldCalcTotAr='$buldCalcTotAr', housePc='$housePc', stdLandAt='$stdLandAt', lastUpdtDt='$lastUpdtDt'";
        $temp = mysql_query($insertsql);
    }

    // 만에 하나 있을 데이터 요청 오류에 대비한 카운트 초기화
    $totalCount = 0;


?>
