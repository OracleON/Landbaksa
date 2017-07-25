<HTML>
<head>
	<? include $_SERVER["DOCUMENT_ROOT"]."/landbaksa/database/inc_dbconnect.php"; ?>
	<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

</head>

<script>
	$(function (){
		$("#hearder_frame_menu2").attr('class','hearder_frame_menu_select');
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
</script>

<style>
	#wrap{width: 1000px; height: auto; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	#top_warp{width: 1000px; height: auto; margin-left: auto; margin-right: auto;}
	#body_wrap{width: 1000px; height: auto; margin-left: auto; margin-right: auto;}
	#bottom_wrap{width: 1000px; height: 80px; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	
	#body_warp_frame{width: 1000px; height: auto; min-height: 500px; text-align: center; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	
</style>


<body bgcolor="#f0f0f0">
	
	<div id="wrap">
		
		<div id='top_warp'>
			<?php include_once $_SERVER["DOCUMENT_ROOT"]."/landbaksa/web_code/common_top.php"; ?>
		</div>
		<div id="body_wrap">
			<div id="body_warp_frame">
				비교검색 페이지  
			</div>
		</div>
		<div id="bottom_wrap">
			
		</div>
	</div>	
	
</body>	
</HTML>