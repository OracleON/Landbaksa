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
	
	$sell_price = $_GET["sellprice"]; //ok
	$bank_price = $_GET["bankprice"]; //ok
	$deposit_price = $_GET["depositprice"]; //ok
	$month_price = $_GET["monthprice"]; //ok
	
	//echo($address);
	
	$tempjibun = split('-', $jibun);
	
	$sigunguCd = substr($lawcode, 0,5);
	$bjdongCd = substr($lawcode, 5,9);
	$bun = "0000".$tempjibun[0];
	$bun = substr($bun, -4,4);
	$ji = "0000".$tempjibun[1];
	$ji = substr($ji, -4,4);
	
	$today = date('Ym'); //이번달
	
	$monthArray = array('201601','201602','201603','201604','201605','201606','201607','201608','201609','201610','201611','201612');
	
	$energy_getcount = 0;
	
	$apikey ='sGT%2FVONLJV4Uj5UsA4AM9rLhFVEF2BGSGTvprzLt5jJtkQuuzSvAWchBH5y5v4s0tADgb%2Fjl%2BTZO2Tklz2zjSg%3D%3D';
	
	foreach($monthArray as $monthinfo) //2016년 1년치 API 반복 호출
	{
		//echo $monthinfo;
		// ====> 에너지 API
		$energy_query ='http://apis.data.go.kr/1611000/BldEngyService/getBeElctyUsgInfo?serviceKey='.$apikey.'&numOfRows=10&pageSize=10&pageNo=1&startPage=1&sigunguCd='.$sigunguCd.'&bjdongCd='.$bjdongCd.'&bun='.$bun.'&ji='.$ji.'&useYm='.$monthinfo;
		
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
	    
	    $totalCount = $object_json['body']['totalCount'];
	    $useYm = $object_json['body']['items']['item']['useYm'];
	    $useQty = $object_json['body']['items']['item']['useQty'];
	    $sigunguCd = $object_json['body']['items']['item']['sigunguCd'];
	    $bjdongCd = $object_json['body']['items']['item']['bjdongCd'];
	    $bun = $object_json['body']['items']['item']['bun'];
	    $ji = $object_json['body']['items']['item']['ji'];
	    $platPlc =$object_json['body']['items']['item']['platPlc'];
	    $newPlatPlc = $object_json['body']['items']['item']['newPlatPlc'];
	    $naRoadCd = $object_json['body']['items']['item']['naRoadCd'];
	    $naUgrndCd = $object_json['body']['items']['item']['naUgrndCd'];
	    $naMainBun = $object_json['body']['items']['item']['naMainBun'];
	    $naSubBun = $object_json['body']['items']['item']['naSubBun'];
	    $law_code = $lawcode;
	    
	    /*
	    echo("totalCount=".$object_json['body']['totalCount']."</br>"); //응답결과 데이터수
	    echo("useYm=".$object_json['body']['items']['item']['useYm']."</br>"); //조회일자
	    echo("useQty=".$object_json['body']['items']['item']['useQty']."</br>"); //전기사용량
	    echo("sigunguCd=".$object_json['body']['items']['item']['sigunguCd']."</br>"); //시군코드
	    echo("bjdongCd=".$object_json['body']['items']['item']['bjdongCd']."</br>"); //동읍코드
	    echo("bun=".$object_json['body']['items']['item']['bun']."</br>"); //번
	    echo("ji=".$object_json['body']['items']['item']['ji']."</br>"); //지
	    echo("platPlc=".$object_json['body']['items']['item']['platPlc']."</br>");
	    echo("newPlatPlc=".$object_json['body']['items']['item']['newPlatPlc']."</br>"); //새주소명
		echo("naRoadCd=".$object_json['body']['items']['item']['naRoadCd']."</br>"); //새주소 도로코드
		echo("naUgrndCd=".$object_json['body']['items']['item']['naUgrndCd']."</br>"); //새주소 지상지하코드
		echo("naMainBun=".$object_json['body']['items']['item']['naMainBun']."</br>"); //새주소 번
		echo("naSubBun=".$object_json['body']['items']['item']['naSubBun']."</br>"); //새주소 지
		*/
		
		if($totalCount > 0)
		{
			$presql = mysql_query("SELECT useYm FROM landbaksa_energy_info WHERE sigunguCd='$sigunguCd' AND bjdongCd='$bjdongCd' AND useYm='$useYm'");
			$precount = mysql_num_rows($presql);
			
			if($precount > 0)
			{
			}else
			{
				$insertsql = "INSERT INTO landbaksa_energy_info SET useYm='$useYm',useQty='$useQty',sigunguCd='$sigunguCd',bjdongCd='$bjdongCd',bun='$bun',ji='$ji',platPlc='$platPlc',newPlatPlc='$newPlatPlc',naRoadCd='$naRoadCd',naUgrndCd='$naUgrndCd',naMainBun='$naMainBun',naSubBun='$naSubBun',law_code='$law_code', regdate=NOW()";
				//echo $insertsql;
				$temp = mysql_query($insertsql);
				
				$energy_getcount++;
	
			}
		}	

	}
	
		
		
	//////////////	


	//검색 히스토리 테이블 작업
	
	$historysql ="INSERT INTO landbaksa_search_history SET userid='$userid',username='$username',address='$address',law_code='$lawcode',jibun_code='$jibun',gong_price='',sil_price='',regdate=NOW(),like_yn='n',type='$search_type'";
	$historyok = mysql_query($historysql);
	
	
	if($historyok)
	{
		
		
		$result ='{"signupJson":"insertok"';
		$result .= ',"username":"'.$userid.'"';
		$result .= ',"energy_count":"'.$energy_getcount.'"';
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
