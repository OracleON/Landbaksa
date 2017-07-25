<HTML>
<head>
	<? include $_SERVER["DOCUMENT_ROOT"]."/landbaksa/database/inc_dbconnect.php"; ?>
	<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

</head>

<?
	$cate =$_GET["cate"];
	$address=$_GET["address"];
	
	$address_new = str_replace(',',' ',$address);
	//echo($address_new);
	//echo($cate);
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
	
	#basic_info_frame{float: left; width: 900px; height: 152px;border: 1px solid #e3e7e9; margin-top: 20px; background: white; margin-bottom: 20px;}
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
				        level: 1 // 지도의 확대 레벨
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
						<div id="address_title"><?echo $address_new?></div>
						<div id="like_frame_bt">
							<img src="/landbaksa/images/star_bt.png" id="like_bt">
						</div>
					</div>
					<div id="like_frame_2"></div>
					<div id="like_frame_3">
						<div id="regdate_frame">
							<div id="regdate_frame_title1">최근 갱신일</div>
							<div id="regdate_frame_title2">17.07.29</div>
						</div>
						<div id="sellprice_frame">
							<div id="sellprice_frame_title">매매가</div>
							<div id="sellprice">340,000,000</div>
							<div id="sellprice_frame_title2">원</div>
						</div>
					</div>
				</div>
				<!-- 매매,정보 -->
				<div id="basic_info_frame">
					
					<div class="basic_info_frame_title">공시지가</div>
					<div class="basic_info_frame_content">250,000,000원</div>
					<div class="basic_info_frame_title">실거래가</div>
					<div class="basic_info_frame_content">540,000,000원</div>
					<div class="info_frame_line"></div>
					<div class="basic_info_frame_title">대출금</div>
					<div class="basic_info_frame_content">100,000,000원</div>
					<div class="basic_info_frame_title">보증금</div>
					<div class="basic_info_frame_content">100,000,000원</div>
					<div class="info_frame_line"></div>
					<div class="basic_info_frame_title">월세</div>
					<div class="basic_info_frame_content">1,600,000원</div>
					<div class="basic_info_frame_title">수익률</div>
					<div class="basic_info_frame_content">6.3%</div>
				</div>
				
				<!-- 세금,수익률 정보 -->
				<div id="tax_frame">
					<div class="tax_frame_title">예상비용</div>
					<div class="tax_frame_title2">양도소득세</div>
					<div class="tax_frame_content">35,000,000원</div>
					<div class="tax_frame_title2">취득세</div>
					<div class="tax_frame_content">15,000,000원</div>
					<div class="tax_frame_title2">중계수수료</div>
					<div class="tax_frame_content">3,000,000원</div>
					<div class="tax_frame_title2">임대소득세</div>
					<div class="tax_frame_content">13,000,000원</div>
				</div>
				
				<div id="interest_frame">
					<div class="tax_frame_title">임대업 수익가치</div>
					<div class="interest_frame_title2">연 7% 수익률 기준 월세 예상액</div>
					<div class="interest_frame_content">1,700,000원</div>
					<div class="interest_frame_title2">연 6% 수익률 기준 월세 예상액</div>
					<div class="interest_frame_content">1,500,000원</div>
					<div class="interest_frame_title2">연 5% 수익률 기준 월세 예상액</div>
					<div class="interest_frame_content">1,300,000원</div>
					<div class="interest_frame_title2">연 4% 수익률 기준 월세 예상액</div>
					<div class="interest_frame_content">1,100,000원</div>
				</div>
				
				<!-- 공시지가 성장률 -->
				<div id="basic_price_state_frame">
					<div class="basic_price_state_frame_title">공시지가 성장률</div>
					<div id="basic_price_state_graph">
					</div>
				</div>
				
				<!-- 실거래가 성장률 -->
				<div id="real_price_state_frame">
					<div class="basic_price_state_frame_title">실거래가 성장률</div>
					<div id="real_price_state_graph">
					</div>
				</div>
				
				<!-- 에너지 사용량 성장률 -->
				<div id="energy_state_frame">
					<div class="basic_price_state_frame_title">에너지 사용량 분석</div>
					<div id="energy_state_graph">
					</div>
				</div>
				
				 
			</div>
		</div>
		<div id="bottom_wrap">
			
		</div>
	</div>	
	
</body>	
</HTML>