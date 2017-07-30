<?php
	include $_SERVER["DOCUMENT_ROOT"]."/landbaksa/database/inc_dbconnect.php";

	function insertSilPrice($item) {
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
	    mysql_query($insertsql);
	}

	function callApi($sigunguCd, $deal_ym) {
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
	    echo($totalCount)."</br>";
	    
	    for($i=0; $i < $totalCount; $i++){
	        $item = $object_json['body']['items']['item'][$i];
	        
 	        insertSilPrice($item);
	    }
	}
	
	$sigunguCdArray = array('11110','11140','11170','11200','11215','11230','11260','11290','11305','11320','11350','11380','11410','11440','11470','11500','11530','11545','11560','11590','11620','11650','11680','11710','11740','26110','26140','26170','26200','26230','26260','26290','26320','26350','26380','26410','26440','26470','26500','26530','26710','27110','27140','27170','27200','27230','27260','27290','27710','28110','28140','28170','28185','28200','28237','28245','28260','28710','28720','29110','29140','29155','29170','29200','30110','30140','30170','30200','30230','31110','31140','31170','31200','31710','36110','41111','41113','41115','41117','41131','41133','41135','41150','41171','41173','41190','41210','41220','41250','41271','41273','41281','41285','41287','41290','41310','41360','41370','41390','41410','41430','41450','41461','41463','41465','41480','41500','41550','41570','41590','41610','41630','41650','41670','41800','41820','41830','42110','42130','42150','42170','42190','42210','42230','42720','42730','42750','42760','42770','42780','42790','42800','42810','42820','42830','43111','43112','43113','43114','43130','43150','43720','43730','43740','43745','43750','43760','43770','43800','44131','44133','44150','44180','44200','44210','44230','44250','44270','44710','44760','44770','44790','44800','44810','44825','45111','45113','45130','45140','45180','45190','45210','45710','45720','45730','45740','45750','45770','45790','45800','46110','46130','46150','46170','46230','46710','46720','46730','46770','46780','46790','46800','46810','46820','46830','46840','46860','46870','46880','46890','46900','46910','47111','47113','47130','47150','47170','47190','47210','47230','47250','47280','47290','47720','47730','47750','47760','47770','47820','47830','47840','47850','47900','47920','47930','47940','48121','48123','48125','48127','48129','48170','48220','48240','48250','48270','48310','48330','48720','48730','48740','48820','48840','48850','48860','48870','48880','48890','50110','50130');
	$start_month = "201601";
	$this_month = date('Y').date('m');
	$end_month = $this_month;
	
//서울
// 	$sigunguCdArray = array('11110','11140','11170','11200','11215','11230','11260','11290','11305','11320','11350','11380','11410','11440','11470','11500','11530','11545','11560','11590','11620','11650','11680','11710','11740');
//부산
// 	$sigunguCdArray = array('26110','26140','26170','26200','26230','26260','26290','26320','26350','26380','26410','26440','26470','26500','26530','26710','27110','27140','27170','27200','27230','27260','27290','27710','28110','28140','28170','28185','28200','28237','28245','28260','28710','28720','29110','29140','29155','29170','29200');
//그외
// $sigunguCdArray = array('30110','30140','30170','30200','30230','31110','31140','31170','31200','31710','36110','41111','41113','41115','41117','41131','41133','41135','41150','41171','41173','41190','41210','41220','41250','41271','41273','41281','41285','41287','41290','41310','41360','41370','41390','41410','41430','41450','41461','41463','41465','41480','41500','41550','41570','41590','41610','41630','41650','41670','41800','41820','41830','42110','42130','42150','42170','42190','42210','42230','42720','42730','42750','42760','42770','42780','42790','42800','42810','42820','42830','43111','43112','43113','43114','43130','43150','43720','43730','43740','43745','43750','43760','43770','43800','44131','44133','44150','44180','44200','44210','44230','44250','44270','44710','44760','44770','44790','44800','44810','44825','45111','45113','45130','45140','45180','45190','45210','45710','45720','45730','45740','45750','45770','45790','45800','46110','46130','46150','46170','46230','46710','46720','46730','46770','46780','46790','46800','46810','46820','46830','46840','46860','46870','46880','46890','46900','46910','47111','47113','47130','47150','47170','47190','47210','47230','47250','47280','47290','47720','47730','47750','47760','47770','47820','47830','47840','47850','47900','47920','47930','47940','48121','48123','48125','48127','48129','48170','48220','48240','48250','48270','48310','48330','48720','48730','48740','48820','48840','48850','48860','48870','48880','48890','50110','50130');
// $start_month = "201601";
// $end_month = "201605";

 	foreach ($sigunguCdArray as $sigunguCd){
 	    for($deal_ym = $start_month; $deal_ym <= $end_month; $deal_ym++){
	        if(substr($deal_ym,-2) > "12"){
	            $deal_ym=(substr($deal_ym, 0, 4)+1)."01";
	        }
	        callApi($sigunguCd, $deal_ym);
	    }
	}
?>
