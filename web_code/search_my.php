<HTML>
<head>
	<? include $_SERVER["DOCUMENT_ROOT"]."/landbaksa/database/inc_dbconnect.php"; ?>
	<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

</head>

<script>
	$(function (){
		$("#hearder_frame_menu3").attr('class','hearder_frame_menu_select');
		
		<?
		if(isset($_COOKIE["userid"]))
		{
				$userid = $_COOKIE["userid"];
				$username = $_COOKIE["username"];	
		}
		if($userid == null)
		{
		?>
		alert('로그인이 필요합니다.\n로그인 페이지로 이동합니다.');
		location.href = "http://pianoontest.cafe24.com/landbaksa/web_code/login.php?cate=1";

		<?
		}
		?>
	});
	
	function goResult(tempseq)
	{
		location.href = "http://pianoontest.cafe24.com/landbaksa/web_code/result.php?cate=2&history_seq="+tempseq;
	}
	
</script>

<style>
	#wrap{width: 1000px; height: auto; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	#top_warp{float: left; width: 1000px; height: auto; margin-left: auto; margin-right: auto;}
	#body_wrap{float: left;width: 1000px; height: auto; margin-left: auto; margin-right: auto;}
	#bottom_wrap{float: left;width: 1000px; height: 80px; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	
	#body_warp_frame{float: left; width: 1000px; height: auto; min-height: 500px; text-align: center; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	
	#mysearch_wrap{float: left; width: 800px; height: auto; min-height: 500px; margin-left: 100px; margin-top: 30px; text-align: left;}
	#mysearch_frame_title{float: left; width: 100%; height: 30px; line-height: 30px;  color: #2e323e; font-size: 18px; font-weight: 700; margin-left: 10px; margin-bottom: 1px; text-align: left; padding-left: 10px;}
	#mysearch_frame{float: left; width: 100%; height: 450px; border: 1px solid #e3e7e9; background: white; margin-top: 10px; overflow: auto;}
	
	#mysearch_frame_header{float: left; width: 800px; height: 40px; border-bottom: 1px solid #d6dbdd;}
	#search_date{float: left; width: 100px; height: 40px; line-height: 40px; text-align: center; color: #8c8e90; font-size: 14px;}
	#search_type{float: left; width: 100px; height: 40px; line-height: 40px; text-align: center; color: #8c8e90; font-size: 14px;}
	#search_address{float: left; width: 360px; height: 40px; line-height: 40px; text-align: center; color: #8c8e90; font-size: 14px;}
	#search_gong{float: left; width: 120px; height: 40px; line-height: 40px; text-align: center; color: #8c8e90; font-size: 14px;}
	#search_sil{float: left; width: 120px; height: 40px; line-height: 40px; text-align: center; color: #8c8e90; font-size: 14px;}
	
	.mysearch_frame_line{float: left; width: 800px; height: 45px; border-bottom: 1px solid #d6dbdd;}
	.search_date{float: left; width: 100px; height: 45px; line-height: 45px; text-align: center; color: #2e343e; font-size: 13px;}
	.search_type{float: left; width: 100px; height: 45px; line-height: 45px; text-align: center; color: #2e343e; font-size: 13px;}
	.search_address{float: left; width: 360px; height: 45px; line-height: 45px; text-align: center; color: #2469ca; font-size: 13px; text-align: left;}
	.search_gong{float: left; width: 120px; height: 45px; line-height: 45px; text-align: center; color: #2e343e; font-size: 13px;}
	.search_sil{float: left; width: 120px; height: 45px; line-height: 45px; text-align: center; color: #2e343e; font-size: 13px;}
</style>


<body bgcolor="#f0f0f0">
	
	<div id="wrap">
		
		<div id='top_warp'>
			<?php include_once $_SERVER["DOCUMENT_ROOT"]."/landbaksa/web_code/common_top.php"; ?>
		</div>
		<div id="body_wrap">
			<div id="body_warp_frame">
				<div id="mysearch_wrap">
					<div id="mysearch_frame_title">관심감정 정보</div>
					<div id="mysearch_frame">
						<div id="mysearch_frame_header">
							<div id="search_date">감정일자</div>
							<div id="search_type">감정종류</div>
							<div id="search_address">주소</div>
							<div id="search_gong">공시지가</div>
							<div id="search_sil">실거래가</div>
						</div>
						<?
							$search_history = mysql_query("SELECT * FROM landbaksa_search_history WHERE userid='$userid' AND like_yn='y' ORDER BY seq DESC");
							while($search_row = mysql_fetch_array($search_history))
							{
								if($search_row['type'] == 'info')
								{
									$search_type = '상세검색';
								}else{
									$search_type = '일반검색';
								}
								echo '<div class="mysearch_frame_line">
										<div class="search_date">'.substr($search_row['regdate'], 0,10).'</div>
										<div class="search_type">'.$search_type.'</div>
										<a href="javascript:goResult('.$search_row['seq'].')">
											<div class="search_address">'.$search_row['address'].'</div>
										</a>
										<div class="search_gong">'.number_format($search_row['gong_price']).'원</div>
										<div class="search_sil">'.number_format($search_row['sil_price']).'원</div>
									</div>';
							}
						?>
						
						
					</div> 
				</div>
			</div>
		</div>
		<div id="bottom_wrap">
			
		</div>
	</div>	
	
</body>	
</HTML>