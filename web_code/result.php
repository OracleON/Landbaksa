<HTML>
<head>
	<? include $_SERVER["DOCUMENT_ROOT"]."/landbaksa/database/inc_dbconnect.php"; ?>
	<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="echarts.english.js/echarts-all-english-v2.js"></script>
    <script type="text/javascript" src="echarts.english.js/green.js"></script>

</head>

<?
	$userid = $_COOKIE["userid"];
	$username = $_COOKIE["username"];
	
	$cate =$_GET["cate"];
	$apartment=$_GET["apartment"];
	
	/*
	$address=$_GET["address"];
	$lawcode=$_GET["lawcode"];
	$jibun=$_GET["jibun"];
	$searchtype = $_GET["searchtype"];
	*/
	$historyseq = $_GET["history_seq"];
	
	
	//echo($address_new);
	//echo($cate);
	$search_history = mysql_query("SELECT * FROM landbaksa_search_history WHERE userid='$userid' AND seq = '$historyseq'");
	$search_row = mysql_fetch_array($search_history);
	
	//모든 검색은 검색테이블에 먼저 넣고 테이블에서 가져오는방법으로 변경
	$address=$search_row["address"];
	$lawcode=$search_row["law_code"];
	$jibun=$search_row["jibun_code"];
	$searchtype = $search_row["type"];
	
	
	$sigunguCd = substr($lawcode, 0,5);
	$bjdongCd = substr($lawcode, 5,9);
	$jibunArray = split("-", $jibun);
	$bun = "0000".$jibunArray[0];
	$bun = substr($bun, -4,4);
	$ji = "0000".$jibunArray[1];
	$ji = substr($ji, -4,4);
	
	$address_new = str_replace(',',' ',$address);
	
	$search_history_detail = mysql_query("SELECT * FROM landbaksa_infosearch_history WHERE history_seq = '$search_row[seq]'");
	$detail_row = mysql_fetch_array($search_history_detail);
	
	$result_price = $detail_row["result_price"]; //감정가
	$sell_price = $detail_row["sell_price"]; //매매가
	$bank_price = $detail_row["bank_price"]; //대출금
	$deposit_price = $detail_row["deposit_price"]; //보증금
	$month_price = $detail_row["month_price"]; //월세
	$need_price = $detail_row["need_price"]; //필요자본
	$profit = $detail_row["profit"]; //수익율
	$income_tax = $detail_row["income_tax"]; //양도소득세
	$get_tax = $detail_row["get_tax"]; //취득세
	$fee = $detail_row["fee"]; //부동산수수료
	$rent_income_tax = $detail_row["rent_income_tax"]; //임대사업자소득세
	
	$sil_price = $search_row["sil_price"]; //실 거래가
	$gong_price = $search_row["gong_price"]; // 공시지가
	
	
	$fore_profit ='2.1';
	
	if($need_price == '0')
	{
		$predict_money1 ='0'; //4% 수익률일때 월세 3억 : 100 % = x : 4
		$predict_money2 ='0'; //5% 수익률일때 월세
		$predict_money3 ='0'; //6% 수익률일때 월세
		$predict_money4 ='0'; //7% 수익률일때 월세
	}else
	{
		$predict_money1 = ($need_price * 4 / 100)/12; //4% 수익률일때 월세 3억 : 100 % = x : 4
		$predict_money2 =($need_price * 5 / 100)/12; //5% 수익률일때 월세
		$predict_money3 =($need_price * 6 / 100)/12; //6% 수익률일때 월세
		$predict_money4 =($need_price * 7 / 100)/12; //7% 수익률일때 월세
	}
	
?>

<script>
	$(function (){
		var cate = '<?echo $cate?>';
		if(cate == '1')
		{
			$("#hearder_frame_menu1").attr('class','hearder_frame_menu_select');
		}else if (cate == '2')
		{
			$("#hearder_frame_menu2").attr('class','hearder_frame_menu_select');
		}
		
		var address = '<?echo $address_new?>';
		$('#address_info_frame').html(address);
		
	});
	
	Number.prototype.format = function(){
	    if(this==0) return 0;
	 
	    var reg = /(^[+-]?\d+)(\d{3})/;
	    var n = (this + '');
	 
	    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');
	 
	    return n;
	};
	
	function changelike()
	{
		  	
		$.ajax({
              type: "POST",
              url: "insertLike.php",
              data: ({search_seq: '<?echo $historyseq?>'}),
              cache: false,
              dataType: "json",
              success:function(msg) 
			  {
				   if(msg.signupJson == 'y')
				   {
				   	    $('#like_bt').attr('src','/landbaksa/images/star_bt.png');
				   }else
				   {
					   $('#like_bt').attr('src','/landbaksa/images/star2_bt.png');
				   }
				   
			  }
			
           });
	}
	
</script>



<style>
	#wrap{width: 1000px; height: auto; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	#top_warp{float: left; width: 1000px; height: auto; margin-left: auto; margin-right: auto;}
	#body_wrap{float: left;width: 1000px; height: auto; min-height: 1000px; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	#bottom_wrap{float: left;width: 1000px; height: 80px; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	
	#body_warp_frame{float: left; width: 900px; height: auto; min-height: 500px; text-align: center; background:#f8f8f8; margin-left: 50px; margin-top: 30px;}
	
	#address_info_frame{float: left; width: 900px; height: 30px; line-height: 30px; color:#2e323e; font-size: 16px; font-weight: 600; text-align: left; margin-bottom: 10px;}
	
	#like_frame{float: left; width: 900px; height: 120px; border: 1px solid #e3e7e9; margin-top: 10px; background: white;}
	
	#like_frame_1{float: left; width: 900px; height: 60px; }
	#address_title{float: left; width: 800px; height: 60px; line-height: 60px; color: #2e323e; font-size: 16px; font-weight: 600; text-align: left; padding-left: 30px;}
	#like_frame_bt{float: left; width: 70px;height: 60px;}
	#like_bt{width: 50px; height: 50px; margin-top: 5px;}
	#like_frame_2{float: left; width: 900px; height: 1px; background: #e3e7e9;}
	#like_frame_3{float: left; width: 900px; height: 60px; }
	#regdate_frame{float: left; width: 120px; height: 60px; background: #7ecef4;}
	#regdate_frame_title1{float: left; width: 120px; height: 20px; line-height: 20px; font-size: 13px; color: #354166; text-align: center; margin-top: 10px;}
	#regdate_frame_title2{float: left; width: 120px; height: 30px; line-height: 30px; font-size: 16px; color: white; font-weight: 800; text-align: center;}
	#sellprice_frame{float: left; width: 780px; height: 60px; background: white;}
	#sellprice_frame_title{float: left; width: 90px; height: 60px; margin-left: 10px; line-height: 60px;text-align: center; color: #6a6e79; font-size: 16px;}
	#sellprice{float: left; width: auto; height: 60px; line-height: 60px; text-align: center; color: #0068b7; font-size: 21px; font-weight: 700;}
	#sellprice_frame_title2{float: left; width: 30px; height: 60px; line-height: 60px; color: #2e323e; font-style: 15px;
	}
	#sellprice_line{float: left; width: 1px; height: 60px; background:#e3e7e9; margin-left: 30px; }
	
	#basic_info_frame{float: left; width: 900px; height: 153px;border: 1px solid #e3e7e9; margin-top: 20px; background: white; margin-bottom: 20px;}
	.info_frame_line{float: left; width: 900px; height: 1px; background:#e3e7e9; }
	.basic_info_frame_title{float: left; width: 129px; height: 50px; line-height: 50px; color: #6a6e79; background: #f7f7f7; text-align: left; font-size: 15px; padding-left: 20px; border-right: 1px solid #e3e7e9;}
	.basic_info_frame_content{float: left; width: 280px; height: 50px; line-height: 50px; background: white; color: #2e323e; font-size: 18px; text-align: left; padding-left: 20px;}
	
	#tax_frame{float: left; width: 400px; height: auto;}
	.tax_frame_title{float: left; width: 100%; height: 30px; line-height: 30px; text-align: left; color: #2e323e; font-size: 18px; font-weight: 700; margin-left: 10px; margin-bottom: 5px;}
	.tax_frame_title2{float: left; left; width: 126px; height: 50px; line-height: 50px; color: #6a6e79; background: #f7f7f7; text-align: left; font-size: 15px; padding-left: 20px; border: 1px solid #e3e7e9;}
	.tax_frame_content{float: left; width: 230px; height: 50px; line-height: 50px; background: white; color: #2e323e; font-size: 18px; text-align: left; padding-left: 20px; border: 1px solid #e3e7e9;}
	
	#interest_frame{float: left; width: 480px; height: auto; margin-left: 20px;}
	.interest_frame_title2{float: left; left; width: 205px; height: 50px; line-height: 50px; color: #6a6e79; background: #f7f7f7; text-align: left; font-size: 15px; padding-left: 20px; border: 1px solid #e3e7e9;}
	.interest_frame_content{float: left; width: 230px; height: 50px; line-height: 50px; background: white; color: #0068b7; font-size: 18px; text-align: left; padding-left: 20px; border: 1px solid #e3e7e9;}
	
	#basic_price_state_frame{float: left; width: 900px; height: 400px; margin-top: 20px;}
	.basic_price_state_frame_title{float: left; width: 100%; height: 30px; line-height: 30px;  color: #2e323e; font-size: 18px; font-weight: 700; margin-left: 10px; margin-bottom: 5px; text-align: left;}
	#basic_price_state_graph{float: left; width: 100%; height: 350px; border: 1px solid #e3e7e9; background: white;}
	
	#real_price_state_frame{float: left; width: 900px; height: 400px; margin-top: 20px;}
	#real_price_state_graph{float: left; width: 100%; height: 350px; border: 1px solid #e3e7e9; background: white;}
	
	#energy_state_frame{float: left; width: 900px; height: 400px; margin-top: 20px;}
	#energy_state_graph{float: left; width: 100%; height: 350px; border: 1px solid #e3e7e9; background: white;}
	
	
	#sil_price_frame{float: left; width: 900px; height: 280px; min-height: 100px; margin-top: 20px;}
	#sil_price_info_frame{float: left; width: 900px; height: 200px;border: 1px solid #e3e7e9; background: white;}
	
	#gong_price_frame{float: left; width: 900px; height: auto; min-height: 100px; margin-top: 20px;}
	#gong_price_info_frame{float: left; width: 900px; height: auto; min-height: 150px; border: 1px solid #e3e7e9; background: white;}
	
	#sil_price_info_fram_title{float: left; width: 900px; height: 40px; line-height: 40px; border-bottom: 1px solid #e3e7e9;}
	.sil_price_size{float: left; width: 100px; height: 40px; line-height: 40px;text-align: center; font-size: 13px; color: #8c8e90;}
	.sil_price_story{float: left; width: 100px; height: 40px; line-height: 40px;text-align: center; font-size: 13px; color: #8c8e90;}
	.sil_price_amount{float: left; width: 200px; height: 40px; line-height: 40px;text-align: center; font-size: 13px; color: #8c8e90;}
	.sil_price_regdate{float: left; width: 200px; height: 40px; line-height: 40px;text-align: center; font-size: 13px; color: #8c8e90;}
	.sil_price_apart_name{float: left; width: 300px; height: 40px; line-height: 40px;text-align: center; font-size: 13px; color: #8c8e90;}
	
	.sil_price_info_data_box{float: left; width: 900px; height: 200px;  border: 1px solid #e3e7e9; background: white; overflow-y: auto;}
	.sel_price_info_frame{float: left; width: 900px; height: 40px; line-height: 40px; border-bottom: 1px solid #e3e7e9;}
	.sil_price_size2{float: left; width: 100px; height: 40px; line-height: 40px;text-align: center; font-size: 13px; color: #2e343e;}
	.sil_price_story2{float: left; width: 100px; height: 40px; line-height: 40px;text-align: center; font-size: 13px; color: #2e343e;}
	.sil_price_amount2{float: left; width: 190px; height: 40px; line-height: 40px;text-align:right; font-size: 13px; color: #2e343e;}
	.sil_price_regdate2{float: left; width: 200px; height: 40px; line-height: 40px;text-align: center; font-size: 13px; color: #2e343e;}
	.sil_price_apart_name2{float: left; width: 300px; height: 40px; line-height: 40px;text-align: center; font-size: 13px; color: #2e343e;}
	
	#gong_price_info_fram_title{float: left; width: 900px; height: 40px; line-height: 40px; border-bottom: 1px solid #e3e7e9;}
	.gong_price_info_data_box{float: left; width: 900px; height: 200px;  border: 1px solid #e3e7e9; background: white; overflow-y: auto;}
</style>


<body bgcolor="#f0f0f0">
	
	<div id="wrap">
		
		<div id='top_warp'>
			<?php include_once $_SERVER["DOCUMENT_ROOT"]."/landbaksa/web_code/common_top.php"; ?>
		</div>
		<div id="body_wrap">
			<div id="body_warp_frame">
				<!-- 주소란 --->
				<div id="address_info_frame">
				</div>
				
				<!-- 지도 -->
				<div id="map" style="width:900px;height:400px;"></div>
				<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=08fa048642ada725927f74c2b890a313&libraries=services"></script>
				<script>
				var mapContainer = document.getElementById('map'), // 지도를 표시할 div 
				    mapOption = {
				        center: new daum.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
				        level: 2 // 지도의 확대 레벨
				    };  
				
				// 지도를 생성합니다    
				var map = new daum.maps.Map(mapContainer, mapOption); 
				
				// 주소-좌표 변환 객체를 생성합니다
				var geocoder = new daum.maps.services.Geocoder();
				
				// 주소로 좌표를 검색합니다
				geocoder.addressSearch('<?echo $address_new?>', function(result, status) {
				
				    // 정상적으로 검색이 완료됐으면 
				     if (status === daum.maps.services.Status.OK) {
				
				        var coords = new daum.maps.LatLng(result[0].y, result[0].x);
				
				        // 결과값으로 받은 위치를 마커로 표시합니다
				        var marker = new daum.maps.Marker({
				            map: map,
				            position: coords
				        });
				
				        // 인포윈도우로 장소에 대한 설명을 표시합니다
				        var infowindow = new daum.maps.InfoWindow({
				            content: '<div style="width:150px;text-align:center;padding:6px 0;">바로이곳!</div>'
				        });
				        infowindow.open(map, marker);
				
				        // 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
				        map.setCenter(coords);
				    } 
				});    
				</script>
				<!-- 주소와 즐겨찾기 -->
				<div id="like_frame">
					<div id="like_frame_1">
						<div id="address_title"><?echo $address?></div>
						<div id="like_frame_bt">
							<?
								if($search_row['like_yn'] == 'y')
								{
									echo '<a href="javascript:changelike()">
												<img src="/landbaksa/images/star_bt.png" id="like_bt">
											</a>';
								}else
								{
									echo '<a href="javascript:changelike()">
												<img src="/landbaksa/images/star2_bt.png" id="like_bt">
											</a>';
								}
							?>
							
						</div>
					</div>
					<div id="like_frame_2"></div>
					<div id="like_frame_3">
						<div id="regdate_frame">
							<div id="regdate_frame_title1">감정일자</div>
							<div id="regdate_frame_title2"><?echo substr($search_row["regdate"], 0,10) ?></div>
						</div>
						<div id="sellprice_frame">
							<div id="sellprice_frame_title">감정가</div>
							<div id="sellprice"><? echo number_format($result_price)?></div>
							<div id="sellprice_frame_title2">원</div>
							
							<div id="sellprice_line"></div>
							
							<div id="sellprice_frame_title">매매가</div>
							<div id="sellprice"><? echo number_format($sell_price)?></div>
							<div id="sellprice_frame_title2">원</div>
						</div>
					</div>
				</div>
				<!-- 매매,정보 -->
				<div id="basic_info_frame">
					<!--
					<div class="basic_info_frame_title">공시지가</div>
					<div class="basic_info_frame_content"><? echo number_format($gong_price)?> 원</div>
					<div class="basic_info_frame_title">최근 실거래가</div>
					<div class="basic_info_frame_content"><? echo number_format($sil_price)?> 원 </div>
					<div class="info_frame_line"></div>
					-->
					<div class="basic_info_frame_title">대출금</div>
					<div class="basic_info_frame_content"><? echo number_format($bank_price)?> 원</div>
					<div class="basic_info_frame_title">보증금</div>
					<div class="basic_info_frame_content"><? echo number_format($deposit_price)?> 원</div>
					<div class="info_frame_line"></div>
					<div class="basic_info_frame_title">투자금</div>
					<div class="basic_info_frame_content"><? echo number_format($need_price)?> 원</div>
					<div class="basic_info_frame_title">월세</div>
					<div class="basic_info_frame_content"><? echo number_format($month_price)?> 원</div>
					<div class="info_frame_line"></div>
					<div class="basic_info_frame_title">현 수익률</div>
					<div class="basic_info_frame_content"><? echo $profit?>%</div>
					<div class="basic_info_frame_title">예상 성장률</div>
					<div class="basic_info_frame_content"><? echo $fore_profit?>% (최근3년 공시지가평균 성장율)</div>
				</div>
				
				<!-- 세금,수익률 정보 -->
				<div id="tax_frame">
					<div class="tax_frame_title">예상비용</div>
					<div class="tax_frame_title2">양도소득세</div>
					<div class="tax_frame_content"><? echo number_format($income_tax)?> 원</div>
					<div class="tax_frame_title2">취득세</div>
					<div class="tax_frame_content"><? echo number_format($get_tax)?> 원</div>
					<div class="tax_frame_title2">중계수수료</div>
					<div class="tax_frame_content">평균 <? echo number_format($fee)?> 원</div>
					<div class="tax_frame_title2">임대소득세</div>
					<div class="tax_frame_content"><? echo number_format($rent_income_tax)?> 원 (분리과세시)</div>
				</div>
				
				<div id="interest_frame">
					<div class="tax_frame_title">임대업 수익가치</div>
					<div class="interest_frame_title2">연 7% 수익률 기준 월세 예상액</div>
					<div class="interest_frame_content"><? echo number_format($predict_money4)?> 원</div>
					<div class="interest_frame_title2">연 6% 수익률 기준 월세 예상액</div>
					<div class="interest_frame_content"><? echo number_format($predict_money3)?> 원</div>
					<div class="interest_frame_title2">연 5% 수익률 기준 월세 예상액</div>
					<div class="interest_frame_content"><? echo number_format($predict_money2)?> 원</div>
					<div class="interest_frame_title2">연 4% 수익률 기준 월세 예상액</div>
					<div class="interest_frame_content"><? echo number_format($predict_money1)?> 원</div>
				</div>
				
				<div id="sil_price_frame">
					<div class="basic_price_state_frame_title">실거래가 정보</div>
					<div id="sil_price_info_frame">
						<div id="sil_price_info_fram_title">
							<div class='sil_price_size'>전용면적</div>
							<div class='sil_price_story'>층</div>
							<div class='sil_price_amount'>매매 실거래가</div>
							<div class='sil_price_regdate'>등록일자</div>
							<div class='sil_price_apart_name'>이름</div>
						</div>
						<div class="sil_price_info_data_box">
						<?
							$sil_pricesql = mysql_query("SELECT * FROM landbaksa_silprice_info WHERE sigunguCd='$sigunguCd' AND bjdongCd='$bjdongCd' AND bun='$bun' AND ji='$ji' ORDER BY story ASC");
							
							$silprice_count = mysql_num_rows($sil_pricesql);
							if($silprice_count > 1)
							{
								while($silprice_row = mysql_fetch_array($sil_pricesql))
								{
									echo '<div class="sel_price_info_frame">
												<div class="sil_price_size2">'.$silprice_row[size].'</div>
												<div class="sil_price_story2">'.$silprice_row[story].'</div>
												<div class="sil_price_amount2">'.$silprice_row[amount].",000 원".'</div>
												<div class="sil_price_regdate2">'.$silprice_row[year].'-'.$silprice_row[month].'-'.$silprice_row[day].'</div>
												<div class="sil_price_apart_name2">'.$silprice_row[apartment_nm].'</div>
											</div>';
								}
							}else
							{
								echo '<div class="sel_price_info_frame">
										해당 물건은 실거래가 신고내역이 없습니다.
									</div>';
							}
							
						?>
						</div>
						
					</div>	
				</div>
				
				<div id="gong_price_frame">
					<div class="basic_price_state_frame_title">공시지가 정보</div>
					<div id="gong_price_info_frame">
						<div id="gong_price_info_fram_title">
							<div class='sil_price_size'>전용면적</div>
							<div class='sil_price_story'>구분</div>
							<div class='sil_price_amount'>공시지가</div>
							<div class='sil_price_regdate'>기준일자</div>
							<div class='sil_price_apart_name'>이름</div>
						</div>
						<div class="gong_price_info_data_box">
						<?
							
							
							$gong_pricesql = mysql_query("SELECT * FROM landbaksa_gongprice_apart_info WHERE ldCode='$lawcode' AND mnnmSlno='$jibun' ORDER BY seq DESC");
							//echo "SELECT * FROM landbaksa_gongprice_apart_info WHERE ldCode='$lawcode' AND mnnmSlno='$jibun' ORDER BY seq DESC";
							$gongprice_count = mysql_num_rows($gong_pricesql);
							if($gongprice_count > 1)
							{
								while($gongprice_row = mysql_fetch_array($gong_pricesql))
								{
									echo '<div class="sel_price_info_frame">
												<div class="sil_price_size2">'.$gongprice_row[prvuseAr].'</div>
												<div class="sil_price_story2">아파트</div>
												<div class="sil_price_amount2">'.number_format($gongprice_row[pbIntfPc]).' 원</div>
												<div class="sil_price_regdate2">'.$gongprice_row[lastUpdtDt].'</div>
												<div class="sil_price_apart_name2">'.$gongprice_row[aphusNm].'</div>
											</div>';
								}
							}else
							{
								echo '<div class="sel_price_info_frame">
										해당 물건은 공시지가 공개내역이 없습니다.
									</div>';
							}
							
						?>
						</div>
					</div>	
				</div>
				
				<!-- 공시지가 성장률 -->
				<div id="basic_price_state_frame">
					<div class="basic_price_state_frame_title">공시지가 성장률</div>
					<div id="basic_price_state_graph">
                        해당 물건은 공시지가 공개내역이 없습니다.
					</div>
				</div>
				
				<!-- 실거래가 성장률 -->
				<div id="real_price_state_frame">
					<div class="basic_price_state_frame_title">실거래가 성장률</div>
					<div id="real_price_state_graph">
                        해당 물건은 실거래가 공개내역이 없습니다.
					</div>
                    <?
                        // 실거래가 데이터
                        $silPriceResult = array("기준년월" => array(), "데이터" => array());
                        $queryResrc = mysql_query("SELECT "."year, month, size, story"." FROM landbaksa_silprice_info WHERE sigunguCd=$sigunguCd AND bjdongCd=$bjdongCd AND bun=$bun AND ji=$ji");
                        if(mysql_num_rows($queryResrc) > 0) {
                            $yearMonthSet = array();
                            $sizeSet = array();
                            $storySet = array();
                            while ($row = mysql_fetch_assoc($queryResrc)) {
                                $month = "00".$row['month'];
                                $month = substr($month, -2,2);
                                $yearMonthSet[$row['year'].$month] = 0;
                                $sizeSet[$row['size'].''] = 0;
                                if($row['story'] != NULL)
                                    $storySet[$row['story'].''] = 0;
                            }

                            $yearMonthList = array_keys($yearMonthSet);
                            sort($yearMonthList);
                            $sizeList = array_keys($sizeSet);
                            $storyList = array_keys($storySet);

                            $silPriceResult['기준년월'] = $yearMonthList;

                            $yearMonthToIndex = array();
                            $countVal = 0;
                            foreach ($yearMonthList as $yearMonth) {
                                $yearMonthToIndex[$yearMonth] = $countVal;
                                $countVal++;
                            }

                            foreach ($sizeList as $size) {
                                if(empty($storyList)) {
                                    $apartSilResult = array_fill(0, count($yearMonthList), 0);
                                    $queryResrc = mysql_query("SELECT "."year, month, amount FROM landbaksa_silprice_info WHERE sigunguCd=$sigunguCd AND bjdongCd=$bjdongCd AND bun=$bun AND ji=$ji AND size=$size");
                                    if(mysql_num_rows($queryResrc) > 0) {
                                        while ($row = mysql_fetch_assoc($queryResrc)) {
                                            $month = "00".$row['month'];
                                            $month = substr($month, -2,2);
                                            $apartSilResult[$yearMonthToIndex[$row['year'].$month]] = str_replace(',', '', $row['amount']);
                                        }
                                        $silPriceResult['데이터']['아파트('.$size.')'] = $apartSilResult;
                                    }
                                }
                                else {
                                    foreach ($storyList as $story) {
                                        $apartSilResult = array_fill(0, count($yearMonthList), 0);
                                        $queryResrc = mysql_query("SELECT "."year, month, amount FROM landbaksa_silprice_info WHERE sigunguCd=$sigunguCd AND bjdongCd=$bjdongCd AND bun=$bun AND ji=$ji AND size=$size AND story=$story");
                                        if(mysql_num_rows($queryResrc) > 0) {
                                            while ($row = mysql_fetch_assoc($queryResrc)) {
                                                $month = "00".$row['month'];
                                                $month = substr($month, -2,2);
                                                $apartSilResult[$yearMonthToIndex[$row['year'].$month]] = str_replace(',', '', $row['amount']);
                                            }
                                            $silPriceResult['데이터']['아파트('.$size.', '.$story.'층)'] = $apartSilResult;
                                        }
                                    }
                                }
                            }
                        }
                        $silPriceResult = json_encode($silPriceResult);
                    ?>
				</div>
				
				<!-- 에너지 사용량 성장률 -->
				<div id="energy_state_frame">
					<div class="basic_price_state_frame_title">에너지 사용량 분석</div>
					<div id="energy_state_graph">
                        해당 물건은 에너지 사용량 공개내역이 없습니다.
					</div>
                    <?
                        // 에너지 사용량 데이터
                        $energyResult = array("기준년월" => array(), "데이터" => array());
                        $queryResrc = mysql_query("SELECT useYm, useQty FROM landbaksa_energy_info WHERE law_code=$lawcode AND bun=$bun AND ji=$ji ORDER BY useYm");
                        if(mysql_num_rows($queryResrc) > 0) {
                            $useQtyList = array();
                            while ($row = mysql_fetch_assoc($queryResrc)) {
                                $energyResult['기준년월'][] = $row['useYm'];
                                $useQtyList[] = $row['useQty'];
                            }
                            $energyResult['데이터']['에너지 사용량'] = $useQtyList;
                        }
                        $energyResult = json_encode($energyResult);
                    ?>
				</div>

                <script>
                    function getOption(legend, xData, ySeriesData, titleText, yAxisName) {
                        var graphOption = {
                            title : {
                                text: titleText
                            },
                            tooltip : {
                                trigger: 'axis'
                            },
                            legend: {
                                data: legend
                            },
                            toolbox: {
                                show : false,
                                feature : {
                                    dataZoom : {show: true},
                                    dataView : {show: true, readOnly: true},
                                    magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
                                    restore : {show: true},
                                    saveAsImage : {show: true}
                                }
                            },
                            calculable : true,
                            dataZoom : {show : true, start : 20, end : 80},
                            xAxis : [
                                {
                                    type : 'category',
                                    // boundaryGap : false,
                                    data : xData
                                }
                            ],
                            yAxis : [
                                {
                                    type : 'value',
                                    name : yAxisName
                                }
                            ],
                            series : (function(){
                                var result = [];
                                legend.forEach(function(legendName) {
                                    var dataForm = {
                                        name: undefined,
                                        type: 'bar',
                                        smooth: true,
                                        // itemStyle: {normal: {areaStyle: {type: 'default'}}},
                                        data: undefined
                                    };
                                    dataForm.name = legendName;
                                    dataForm.data = ySeriesData[legendName];
                                    result.push(dataForm);
                                });
                                return result;
                            })(),
                        };
                        return graphOption;
                    }


                    $.ajax({
                        type: "GET",
                        url: "CollectInfo_gongprice.php",
                        data: ({userid: '<?echo $userid?>', law_code: '<?echo $lawcode?>', ji_bun: '<?echo $jibun?>'}),
                        cache: false,
//                        dataType: "json",
                        success: function(data)
                        {
//                            console.log(data);
                            var responseJSON = JSON.parse(data);
                            console.log(responseJSON);

                            // 공시지가 그래프 생성
                            var gongPriceGraph = echarts.init(document.getElementById('basic_price_state_graph'), theme);
                            gongPriceGraph.setOption(getOption(Object.keys(responseJSON['데이터']), responseJSON['기준년도'], responseJSON['데이터'], '', '공시가격'));
                            window.addEventListener("resize", function() {
                                gongPriceGraph.resize();
                            });
                        }
                    });

                    // 실거래가 그래프 생성
                    var silPriceJSON = JSON.parse('<?echo $silPriceResult?>');
                    console.log(silPriceJSON);
                    if(silPriceJSON['기준년월'].length) {
                        var silPriceGraph = echarts.init(document.getElementById('real_price_state_graph'), theme);
                        silPriceGraph.setOption(getOption(Object.keys(silPriceJSON['데이터']), silPriceJSON['기준년월'], silPriceJSON['데이터'], '', '실거래가격'));
                        window.addEventListener("resize", function() {
                            silPriceGraph.resize();
                        });
                    }

                    // 에너지 그래프 생성
                    var energyJSON = JSON.parse('<?echo $energyResult?>');
                    console.log(energyJSON);
                    if(energyJSON['기준년월'].length) {
                        var energyGraph = echarts.init(document.getElementById('energy_state_graph'), theme);
                        energyGraph.setOption(getOption(['에너지 사용량'], energyJSON['기준년월'], energyJSON['데이터'], '', '사용량'));
                        window.addEventListener("resize", function() {
                            energyGraph.resize();
                        });
                    }
                </script>
				 
			</div>
		</div>
		<div id="bottom_wrap">
			
		</div>
	</div>	
	
</body>	
</HTML>