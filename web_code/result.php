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
	#top_warp{width: 1000px; height: auto; margin-left: auto; margin-right: auto;}
	#body_wrap{width: 1000px; height: auto; min-height: 1000px; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	#bottom_wrap{width: 1000px; height: 80px; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	
	#body_warp_frame{float: left; width: 900px; height: auto; min-height: 500px; text-align: center; background:#f8f8f8; margin-left: 50px; margin-top: 30px;}
	
	#address_info_frame{float: left; width: 900px; height: 30px; line-height: 30px; color:#2e323e; font-size: 16px; font-weight: 600; text-align: left; margin-bottom: 10px;}
	
	#like_frame{float: left; width: 900px; height: 100px; border: 1px solid #e3e7e9; margin-top: 10px; background: white;}
	
	#like_frame_1{float: left; width: 900px; height: 50px; }
	#address_title{float: left; width: 800px; height: 50px; line-height: 50px; color: #2e323e; font-size: 16px; font-weight: 600; text-align: left; padding-left: 30px;}
	#like_frame_bt{float: left; width: 70px;height: 50px;}
	#like_bt{width: 50px; height: 50p;}
	#like_frame_2{float: left; width: 900px; height: 1px; background: #e3e7e9;}
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
				<div id="map" style="width:900px;height:350px;"></div>
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
						
					</div>
				</div>
				<!-- 매매,정보 -->
				
				<!-- 세금,수익률 정보 -->
				
				 
			</div>
		</div>
		<div id="bottom_wrap">
			
		</div>
	</div>	
	
</body>	
</HTML>