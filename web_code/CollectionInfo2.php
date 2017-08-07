<?php
	include $_SERVER["DOCUMENT_ROOT"]."/landbaksa/database/inc_dbconnect.php";
	
	//header("Content-Type:text/html; charset=euc-kr");
	//header("Content-Encoding:utf-8");

	$result = '{"signupJson":"no"}';
	
	
	//11500/10800/1374/0000/
	
	$userid = $_GET["userid"]; //ok
	$username = $_GET["username"]; //ok
	$address = $_GET["address"]; //ok
	$lawcode = $_GET["law_code"]; //ok
	$jibun = $_GET["ji_bun"]; //ok
	$search_type = $_GET["search_type"]; //ok
	$apartment_type = $_GET["apartment_type"]; 
	
	$sell_price = $_GET["sellprice"]; //ok
	$bank_price = $_GET["bankprice"]; //ok
	$deposit_price = $_GET["depositprice"]; //ok
	$month_price = $_GET["monthprice"]; //ok
	
	$sil_price ='0';
	$gong_price ='0';
	$gong_land_price ='0';
	
	$need_price ='0';
	$profit = '0';
		
	//echo($address);
	
	$tempjibun = split('-', $jibun);
	
	$sigunguCd = substr($lawcode, 0,5);
	$bjdongCd = substr($lawcode, 5,9);
	$bun = "0000".$tempjibun[0];
	$bun = substr($bun, -4,4);
	$ji = "0000".$tempjibun[1];
	$ji = substr($ji, -4,4);
	
	$today = date('Ym'); //이번달
	

	
	$apikey ='sGT%2FVONLJV4Uj5UsA4AM9rLhFVEF2BGSGTvprzLt5jJtkQuuzSvAWchBH5y5v4s0tADgb%2Fjl%2BTZO2Tklz2zjSg%3D%3D';
	
	
	

	/****************************************************** 공시지가 부분 ************************************************************/

/*공통으로 사용되는 전역 변수 선언*/
	// 기본 URL
	$apartHousingPriceURL = 'http://apis.data.go.kr/1611000/nsdi/ApartHousingPriceService/attr/getApartHousingPriceAttr?ServiceKey='.$apikey;
	$indvdHousingPriceURL = 'http://apis.data.go.kr/1611000/nsdi/IndvdHousingPriceService/attr/getIndvdHousingPriceAttr?ServiceKey='.$apikey;
	$indvdLandPriceURL = 'http://apis.data.go.kr/1611000/nsdi/IndvdLandPriceService/attr/getIndvdLandPriceAttr?ServiceKey='.$apikey;

	// 오늘 날짜(년)
	$todayYear = date('Y');


/*변수 확인*/
	//	echo $lawcode."/".$bun."/".$ji."/".$todayYear.'<br/>';


/*요청 생성 함수 정의*/
	/*
	 * $baseURL: 데이터 요청을 위한 기본 URL
	 * $lawCode: 10자리 법정동코드
	 * $mainAddr: 4자리 본번
	 * $subAddr: 4자리 부번
	 * $stdrYear: 데이터 조회 기준년도
	 * */
	function getRequestData($baseURL, $lawCode, $mainAddr, $subAddr, $stdrYear) {
		$numOfRows = 9999; // 한번에 가져올 데이터 수. 응답데이터의 totalCount에 따라 늘려야함
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


/*최근 5년간의 공시지가 데이터 삽입*/
	$years = array(2016, 2015, 2014);
//
	foreach ($years as $standardYear) {
		/*아파트 공시지가 데이터 삽입*/
		// 데이터 요청
		
		 
		if($apartment_type == 'Y')
		{
			// API 사용하기전 DB에 데이터가 있는지 확인후 없으면 API 호출시작
			
			$pregongprice_checksql = mysql_query("SELECT pnu FROM landbaksa_gongprice_apart_info WHERE ldCode='$lawcode' AND mnnmSlno ='$jibun' AND stdrYear='$standardYear'");
			$pregongprice_checksql_count = mysql_num_rows($pregongprice_checksql);
			if($pregongprice_checksql_count > 0)
			{
				
			}else
			{
				$responseData = getRequestData($apartHousingPriceURL, $lawcode, $bun, $ji, $standardYear);
				// 데이터 삽입
				insertData('landbaksa_gongprice_apart_info', $responseData['apartHousingPrices']['field'], array('pnu', 'stdrYear', 'stdrMt', 'prvuseAr', 'pblntfPc'));
			}
			

		}else
		{
			/*건물 공시지가 데이터 삽입*/
		// 데이터 요청
		// API 사용하기전 DB에 데이터가 있는지 확인후 없으면 API 호출시작
			
			$pregongprice_checksql = mysql_query("SELECT pnu FROM landbaksa_gongprice_building_info WHERE ldCode='$lawcode' AND mnnmSlno ='$jibun' AND stdrYear='$standardYear'");
			$pregongprice_checksql_count = mysql_num_rows($pregongprice_checksql);
			if($pregongprice_checksql_count > 0)
			{
				
			}else
			{
				$responseData = getRequestData($indvdHousingPriceURL, $lawcode, $bun, $ji, $standardYear);
				// 데이터 삽입
				insertData('landbaksa_gongprice_building_info', $responseData['indvdHousingPrices']['field'], array('pnu', 'stdrYear', 'stdrMt', 'ladRegstrAr', 'housePc'));
			}
		
		}
		
		$pregonglandprice_check = mysql_query("SELECT pnu FROM landbaksa_gongprice_land_info WHERE ldCode='$lawcode' AND mnnmSlno ='$jibun' AND stdrYear='$standardYear'");
		
		$pregonglandprice_check_count = mysql_num_rows($pregonglandprice_check);
		if($pregonglandprice_check_count > 0)
		{
			
		}else
		{
			/*토지 공시지가 데이터 삽입*/
			// 데이터 요청
			$responseData = getRequestData($indvdLandPriceURL, $lawcode, $bun, $ji, $standardYear);
			// 데이터 삽입
			insertData('landbaksa_gongprice_land_info', $responseData['indvdLandPrices']['field'], array('pnu', 'stdrYear', 'stdrMt', 'pblntfPclnd', 'pblntfDe'));
		}

		
	}


	/***************************************************** 공시지가 부분 끝 ***********************************************************/
	/**********************************************************************************************************************************/


	//////////////	

	// sample data : 법정코드 : 1165010800 / 지번 : 1332 / 우성3차 아파트
	// sample data : 번정코드 : 4111514100 / 지번 : 949-25 / 빌라,건물
	// sample data : 법정코드 : 1165010800 / 지번 : 1332 / 토지
	
	//실거래가 가져오기
	
	$silpricesql = mysql_query("SELECT avg(convert(replace(amount,',',''), decimal))*10000 as amount FROM landbaksa_silprice_info WHERE sigunguCd='$sigunguCd' AND bjdongCd='$bjdongCd' AND bun='$bun' AND ji='$ji'");
	
	
	//SELECT avg(convert(replace(amount,',',''), decimal))*10000 FROM landbaksa_silprice_info WHERE sigunguCd='11500' AND bjdongCd='10100' AND bun='0249' AND ji='0011'
	
	//echo "SELECT amount, month, day,size,story FROM landbaksa_silprice_info WHERE sigunguCd='$sigunguCd' AND bjdongCd='$bjdongCd' AND bun='$bun' AND ji='$ji' ORDER BY seq DESC LIMIT 1";
	
	$sil_pricesql_num = mysql_num_rows($silpricesql);
	if($sil_pricesql_num > 0)
	{
		$silprice_row = mysql_fetch_array($silpricesql);
		$sil_price = $silprice_row['amount'];
		//$sil_price = str_replace(",", "", $sil_price)."0000";
		//$sil_size = $silprice_row['size'];
	}else
	{
		$sil_price ='0';
	}

	
	// 공시지가 가져오기
	
	if($apartment_type == 'Y')
	{
		$gongpricesql = mysql_query("SELECT AVG(pblntfPc) as pblntfPc,prvuseAr FROM landbaksa_gongprice_apart_info WHERE ldCode ='$lawcode' AND mnnmSlno ='$jibun'");
		
		$gong_pricesql_num = mysql_num_rows($gongpricesql);
		if($gong_pricesql_num > 0)
		{
			$gongprice_row = mysql_fetch_array($gongpricesql);
			$gong_price = $gongprice_row['pblntfPc'];
		}	
	}else
	{
		$gongpricesql = mysql_query("SELECT housePc,buldCalcTotAr FROM landbaksa_gongprice_building_info WHERE ldCode ='$lawcode' AND mnnmSlno ='$jibun'  ORDER BY stdrYear DESC LIMIT 1");
		
		
		$gong_pricesql_num = mysql_num_rows($gongpricesql);
		if($gong_pricesql_num > 0)
		{
			$gongprice_row = mysql_fetch_array($gongpricesql);
			$gong_price = $gongprice_row['housePc'];
		}
		
	}
	
	$gonglandpricesql = mysql_query("SELECT pblntfPclnd FROM landbaksa_gongprice_land_info WHERE ldCode ='$lawcode' AND mnnmSlno ='$jibun' ORDER BY stdrYear DESC LIMIT 1");
	$gong_land_pricesql_num = mysql_num_rows($gonglandpricesql);
	if($gong_land_pricesql_num > 0)
	{
		$gong_landprice_row = mysql_fetch_array($gonglandpricesql);
		$gong_land_price = $gong_landprice_row['pblntfPclnd'];
	}

	
	
	//검색 히스토리 테이블 작업
	
	$historysql ="INSERT INTO landbaksa_search_history SET userid='$userid',username='$username',address='$address',law_code='$lawcode',jibun_code='$jibun',gong_price='$gong_price',gong_land_price='$gong_land_price',sil_price='$sil_price',regdate=NOW(),like_yn='n',type='$search_type'";
	$historyok = mysql_query($historysql);
	
	$gethistory = mysql_query("SELECT seq FROM landbaksa_search_history WHERE userid='$userid' AND law_code='$lawcode' AND jibun_code='$jibun' ORDER BY seq DESC LIMIT 1");
	$historyrow = mysql_fetch_array($gethistory);
	
	// 감정시작
	
	if($search_type == 'address')
	{
		$result_price ='0';
		$sell_price ='0';
		$bank_price ='0';
		$deposit_price ='0';
		$month_price ='0';
		$need_price ='0';
		$profit ='0';
		$income_tax ='0';
		$get_tax='0';
		$fee = '0';
		$rent_income_tax = '0';
		
		$history_info_sql ="INSERT INTO landbaksa_infosearch_history SET history_seq='$historyrow[seq]',result_price='$result_price',sell_price='$sell_price',bank_price='$bank_price',deposit_price='$deposit_price',month_price='$month_price',need_price='$need_price',profit='$profit',income_tax='$income_tax',get_tax='$get_tax',fee='$fee',rent_income_tax='$rent_income_tax'";
		$history_info_ok = mysql_query($history_info_sql);
		
	}else if($search_type =='info')
	{
		$result_price = (int)(str_replace(",", "",$sell_price))*1.02; //기본 감정가는 지난 실거래가에 취득세를 더한 가격으로 측정 
		$sell_price = (int)str_replace(",", "",$sell_price);
		$bank_price = (int)str_replace( ",", "",$bank_price);
		$deposit_price = (int)str_replace(",", "",$deposit_price);
		$month_price = (int)str_replace(",", "",$month_price);
		$need_price = (int)($sell_price - $bank_price - $deposit_price); //투자 비용 
		
		/*
			echo("result_price = ".$result_price."</br>");
		echo("sell_price = ".$sell_price."</br>");
		echo("bank_price = ".$bank_price."</br>");
		echo("deposit_price = ".$deposit_price."</br>");
		echo("month_price = ".$month_price."</br>");
		echo("need_price = ".$need_price."</br>");
		
		
		echo "profit = (int)($month_price * 12 / $need_price)*100</br>";
		echo "profit = ".$profit;
		*/
		
		if($need_price != '0')
		{
			$profit = ($month_price * 12 / $need_price)*100; //투자비용 대비 월세수익률  1억 : 100% = 120만월*12 : ?% 
			
			//echo "profit = ".$profit;
		}
		
		
		$get_tax='0'; //아파트 6억이하 1.1%, 6~9억 2.4% , 9억이상 : 3.5% //토지,상가,오피스텔 : 4.6% //
		if($apartment_type == 'Y')
		{
			if($sell_price < 600000000)
			{
				$get_tax = $sell_price * 0.011;
			}else if($sell_price > 600000000 && $sell_price <= 900000000)
			{
				$get_tax = $sell_price * 0.024;
			}else
			{
				$get_tax = $sell_price * 0.035;
			}
		}else
		{
			$get_tax = $sell_price * 0.046;
		}
		
		$fee = '0';
		if($sell_price < 50000000)
		{
			$fee = 250000;
		}else if($sell_price > 50000000 && $sell_price <= 200000000)
		{
			$fee = 800000;
		}else if($sell_price > 200000000 && $sell_price <= 600000000)
		{
			$fee = $sell_price * 0.0035;
		}else if($sell_price > 600000000 && $sell_price <= 900000000)
		{
			$fee = $sell_price * 0.003;
		}else if($sell_price > 900000000)
		{
			$fee = $sell_price * 0.002;
		}
		
		
		if($sil_price == '0')
		{
			$income_tax = '0'; //취득금액이 없기 때문에 양도소득세 계산 할수 없다.
		}else
		{
			$income_tax = $sell_price - $sil_price - $fee ; //양도소득금액   
			if($income_tax <= 10000000)
			{
				$income_tax= $income_tax * 0.16;
			}else if($income_tax > 10000000 && $income_tax <= 46000000)
			{
				$income_tax= $income_tax * 0.25 - 1080000;
			}else if($income_tax > 46000000 && $income_tax <= 88000000)
			{
				$income_tax= $income_tax * 0.34 - 5220000;
			}else if($income_tax > 88000000 && $income_tax <= 150000000)
			{
				$income_tax= $income_tax * 0.45 - 14900000;
			}else if($income_tax > 150000000 && $income_tax <= 500000000)
			{
				$income_tax= $income_tax * 0.48 - 19400000;
			}else if($income_tax > 500000000)
			{
				$income_tax= $income_tax * 0.50 - 29400000;
			}
		}
		
		$rent_income_tax = '0';
		if($month_price == '0')
		{
			$rent_income_tax = '0';
		}else
		{
			$rent_income_tax = $month_price*12*0.6*0.154;
		}
		
		//echo "INSERT INTO landbaksa_infosearch_history SET history_seq='$historyrow[seq]',result_price='$result_price',sell_price='$sell_price',bank_price='$bank_price',deposit_price='$deposit_price',month_price='$month_price',need_price='$need_price',profit='$profit',income_tax='$income_tax',get_tax='$get_tax',fee='$fee',rent_income_tax='$rent_income_tax'";
		
		$history_info_sql ="INSERT INTO landbaksa_infosearch_history SET history_seq='$historyrow[seq]',result_price='$result_price',sell_price='$sell_price',bank_price='$bank_price',deposit_price='$deposit_price',month_price='$month_price',need_price='$need_price',profit='$profit',income_tax='$income_tax',get_tax='$get_tax',fee='$fee',rent_income_tax='$rent_income_tax'";
		$history_info_ok = mysql_query($history_info_sql);
	}
	
	if($historyok)
	{
		
		
		$result ='{"signupJson":"insertok"';
		$result .= ',"username":"'.$userid.'"';
		$result .= ',"energy_count":"'.$energy_getcount.'"';
		$result .= ',"history_seq":"'.$historyrow['seq'].'"';
		$result .='}';
		
	}
	else
	{
		$result ='{"signupJson":"no"';
		$result .= ',"username":"'.$userid.'"';
		$result .='}';
	}
	
	
	
	echo $result;
?>
