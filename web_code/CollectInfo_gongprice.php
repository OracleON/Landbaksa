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
/*
 * $baseURL: 데이터 요청을 위한 기본 URL
 * $lawCode: 10자리 법정동코드
 * $mainAddr: 4자리 본번
 * $subAddr: 4자리 부번
 * */
    function getRequestData($baseURL, $lawCode, $mainAddr, $subAddr) {
        $numOfRows = 9999; // 한번에 가져올 데이터 수. 응답데이터의 totalCount에 따라 늘려야함
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


/*레코드 중복 확인 함수 정의*/
/*
 * $tableName: 레코드 중복을 확인할 테이블 이름
 * $conditionData: 중복을 확인에 기준으로 사용될 컬럼 데이터들. Key와 Value로 구성
 * */
    function containsRecord($tableName, $conditionData) {
        // 쿼리의 검색조건절에 사용할 문자열 선언
        $whereCondition = "";

        // 조건절 생성
        foreach ($conditionData as $key => $value) {
            // 조건절에 조건이 하나라도 채워져 있으면 AND 구문을 추가
            if($whereCondition !== "")
                $whereCondition .= " AND ";
            $filteredVal = mysql_real_escape_string($value); // SQL 인젝션을 방지하기 위한 함수
            $whereCondition .= $key."="."'$filteredVal'";
        }

        // 검색 실행
        $selectTarget = 'pnu';
        $preSql = mysql_query("SELECT $selectTarget FROM $tableName WHERE $whereCondition");
        $preCount = mysql_num_rows($preSql);

        return $preCount;
    }


/*데이터 삽입 함수 정의*/
/*
 * $tableName: 데이터를 삽입할 테이블 이름
 * $dataArray: 삽입할 데이터들의 배열. 배열에서 요소 1개는 Key와 Value로 이루어진 데이터들의 집합
 * $dupRules: 데이터 중복을 확인할 기준이 되는 컬럼 이름들. 데이터를 삽입하기전 중복된 데이터가 이미 데이터베이스에 있는지 확인하는데 사용
 * */
    function insertData($tableName, $dataArray, $dupRules) {
        // Key,Value들로 구성된 배열의 요소를 하나씩 읽어가며 주어진 테이블에 레코드를 삽입
        // 레코드를 삽입하기 전에 중복된 데이터가 있는지 확인함
        foreach ($dataArray as $elem) {
            // 중복된 데이터를 검색하기 위한 기준데이터 배열 선언
            $dupCriteria = array();

            // 중복된 데이터를 검색하기 위한 데이터를 얻어옴
            foreach ($dupRules as $rule) {
                if(array_key_exists($rule, $elem)) {
                    $dupCriteria[$rule] = $elem[$rule];
                }
            }

            // 중복된 데이터가 있는지 검색
            $dupRecordCount = containsRecord($tableName, $dupCriteria);

            // 중복된 데이터가 1개라도 있으면 스킵
            if($dupRecordCount > 0)
                continue;

            // Key, Value 쌍들을 컬럼이름과 그것의 값으로 저장하기 위한 배열 선언
            $cols = array();
            $vals = array();
            foreach ($elem as $key => $value) {
                $cols[] = $key;
                $vals[] = mysql_real_escape_string($value); // SQL 인젝션을 방지하기 위한 함수
            }

            // 컬럼이름과 그것의 값들을 콤마로 구분하여 하나의 문자열로 생성
            $colNames = "`".implode("`, `", $cols)."`";
            $colVals = "'".implode("', '", $vals)."'";

            // 삽입 쿼리 실행
            $insertSql = "INSERT INTO $tableName ($colNames) VALUES ($colVals)";
            $temp = mysql_query($insertSql);

            // 사용한 배열 해제
            unset($cols, $vals);
        }
    }


/*아파트 공시지가 데이터 삽입*/
    // 데이터 요청
    $responseData = getRequestData($apartHousingPriceURL, $lawcode, $bun, $ji);
    echo '1. Apartment Housing Price<br/>';
    // 데이터 갯수 확인
    echo 'totalCount: '.$responseData['apartHousingPrices']['totalCount'].'<br/>';
    // 데이터 삽입
    insertData('landbaksa_gongprice_apart_info', $responseData['apartHousingPrices']['field'], array('pnu', 'stdrYear', 'stdrMt', 'prvuseAr', 'pblntfPc'));


/*건물 공시지가 데이터 삽입*/
    // 데이터 요청
    $responseData = getRequestData($indvdHousingPriceURL, $lawcode, $bun, $ji);
    echo '2. Building Housing Price<br/>';
    // 데이터 갯수 확인
    echo 'totalCount: '.$responseData['indvdHousingPrices']['totalCount'].'<br/>';
    // 데이터 삽입
    insertData('landbaksa_gongprice_building_info', $responseData['indvdHousingPrices']['field'], array('pnu', 'stdrYear', 'stdrMt', 'ladRegstrAr', 'housePc'));


/*토지 공시지가 데이터 삽입*/
    // 데이터 요청
    $responseData = getRequestData($indvdLandPriceURL, $lawcode, $bun, $ji);
    echo '3. Land Housing Price<br/>';
    // 데이터 갯수 확인
    echo 'totalCount: '.$responseData['indvdLandPrices']['totalCount'].'<br/>';
    // 데이터 삽입
    insertData('landbaksa_gongprice_land_info', $responseData['indvdLandPrices']['field'], array('pnu', 'stdrYear', 'stdrMt', 'pblntfPclnd', 'pblntfDe'));

    echo 'Done!';
?>
