<HTML>
<head>
	<? include $_SERVER["DOCUMENT_ROOT"]."/landbaksa/database/inc_dbconnect.php"; ?>
	<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

</head>

<script>
	
	
	
	
	
	$(function (){
		$("#hearder_frame_menu1").attr('class','hearder_frame_menu_select');
		
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
	
		
		$('#search_address_input').on('click',function(){
			/*var tempaddress = $('#address').val();
			if(tempaddress =='')
			{
				//alert('주소 API호출');
				sample6_execDaumPostcode();
			}else
			{
				
			}
			*/
			$('#address').val('');
			$('#lawcode').val('');
			$('#jibun').val('');
			$('#apartment').val('');
			sample6_execDaumPostcode();
		});
		
		$('#search_bt').on('click',function(){
			var tempaddress = $('#address').val();
			
			if(tempaddress == '')
			{
				alert('주소를 입력하세요.');
			}else
			{
				var tempaddress2 = tempaddress.replace('(', '');
				tempaddress2 = tempaddress2.replace(')', '');
				var Array_address = tempaddress2.replace(',', '').split(' ');
				//alert(Array_address[0]+'/'+Array_address[1]+'/'+Array_address[2]);
				
				//alert('감정시작!!');
				var tempuserid = '<?echo $userid?>';
				var lawcode = $('#lawcode').val();
				var jibun = $('#jibun').val();
				var apartment = $('#apartment').val();
				
				var historyseq ='';
				
				$('#background_layer').fadeIn();
				$('#loading').fadeIn();
				
				var getparams = {userid:tempuserid,username:'<?echo $username?>',law_code:lawcode,ji_bun:jibun,apartment_type:apartment,search_type:'address',address:tempaddress};
						//alert('getparams 준비.'+getparams);
				$.getJSON("CollectInfo.php",getparams,function(data){	
					$.each(data, function(key, value){
							if(key == "history_seq")
							{
								historyseq = value;
							}
							
							if(key == "signupJson")
							{
								if(value == "insertok")
								{
									changeok = "ok";
									//alert('로그인 성공입니다.');
									$('#output').empty().append('온라인 감정 성공 입니다.');
									$('#output2').empty().append('분석결과 페이지로 이동합니다.');
									
									setTimeout(function(){
												//$('#loading').fadeOut();
												//$("#loding").popup("close");
												//$('#loading').hide();
												
												location.href = "http://pianoontest.cafe24.com/landbaksa/web_code/result.php?cate=1&address="+Array_address+"&lawcode="+lawcode+"&jibun="+jibun+"&apartment="+apartment+"&searchtype=address&history_seq="+historyseq;
												 }, 1000);
								}
								if(value == "no")
								{
									changeok = "not";	
									$('#output2').empty().append('감정정보가 부족합니다.');
									//$('#loading').hide();
									setTimeout(function(){
												alert('감정에 실패하였습니다..');
												$('#background_layer').fadeOut();
												$('#loading').fadeOut();
												 }, 1000);
									
								}
	
								if(value == "error")
								{
									changeok = "not";
									$('#output').append('DATA BASE ERROR!!!!.');
									setTimeout(function(){
												$('#loading').fadeOut();
												$('#background_layer').fadeOut();
												//$("#loding").popup("close");
												//$('#loading').hide();
												 }, 1000);
								}
								
							}
							
					});
					
					if(changeok =="ok")
					{
						//location.href = "http://pianoontest.cafe24.com/landbaksa/web_code/result.php?cate=1&address="+Array_address+"&lawcode="+lawcode+"&jibun="+jibun+"&apartment="+apartment;
						
					}else{
						//alert('감정에 실패하였습니다..');
						//$('#background_layer').fadeOut();
						//$('#loading').fadeOut();
					}	
				});	
				
				
			}
			
		});
	});
</script>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
    function sample6_execDaumPostcode() {
        new daum.Postcode({
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = ''; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수
                var bcode = '';//법정동코드
                var bunji = ''; //번지
                var apart_type =''; //아파트여부
				
				apart_type = data.apartment;
				//bunji = data.jibunAddressEnglish.split(",");
				var tempbunji = data.jibunAddress.split(" ");
				bunji = tempbunji[tempbunji.length-1];
				
				//alert(tempbunji[2]+tempbunji[3]);
				bcode = data.bcode;
				
                // 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                    fullAddr = data.roadAddress;

                } else { // 사용자가 지번 주소를 선택했을 경우(J)
                    fullAddr = data.jibunAddress;
                }

                // 사용자가 선택한 주소가 도로명 타입일때 조합한다.
                if(data.userSelectedType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                //document.getElementById('sample6_postcode').value = data.zonecode; //5자리 새우편번호 사용
                document.getElementById('address').value = fullAddr;
                document.getElementById('lawcode').value = bcode;
                document.getElementById('jibun').value = bunji;
                document.getElementById('apartment').value = apart_type;
                

                // 커서를 상세주소 필드로 이동한다.
                document.getElementById('address').focus();
            }
        }).open();
    }
</script>

<style>
	#wrap{width: 1000px; height: auto; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	#top_warp{float: left;width: 1000px; height: auto; margin-left: auto; margin-right: auto;}
	#body_wrap{float: left; width: 1000px; height: auto; margin-left: auto; margin-right: auto;}
	#bottom_wrap{float: left;width: 1000px; height: 80px; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	
	#body_warp_frame{float: left; width: 1000px; height: auto; min-height: 500px; text-align: center; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	
	#search_frame{float: left;
		width: 600px;
		height: 50px;
		margin-left: 200px;
		margin-top: 200px;
		margin-bottom: 200px;
	}
	.search_address_input{
		float: left;
		width: 500px;
		height: 40px;
		background: #ffffff;
		border: 1px solid #d6dbdd;
		border-bottom-left-radius: 5px;
		border-top-left-radius: 5px;
		color: #8c8e90;
		padding-left: 15px;
		font-size: 15px;
		line-height: 40px; text-align: left;
	}
	#search_bt{
		float: left;
		width: 80px;
		height: 40px;
		background: #0068b7;
		color: #ffffff;
		font-size: 16px;
		font-weight: 400;
		text-align: center;
		line-height: 40px;
		border-bottom-right-radius: 5px;
		border-top-right-radius: 5px;
	}
	#search_address_frame{float: left;
		width: 600px;
		height: 300px;
		margin-left: 200px;
		margin-top: 0px;
		margin-bottom: 100px;
		background: blue;
	}
	
	#search_data_frame{
		float: left;
		width: 300px;
		height:40px;
		border: 0px solid red;
	}
	.hidden_input{
		display: none;
	}
	
	#loading{
	 		z-index: 10;
	 		height:100px;
			width:400px;
			background:white;
			border:1px #0068b7 solid;
			display:none;
			position:absolute;
			margin-left:300px;
			margin-top: 250px;
			border-radius: 5px;
			}
	 #loading > p{color:#FFF}
	 #output{
		 width: 100%;
		 height: 20px; line-height: 20px;
		 text-align: center;
		 color: #0068b7;
		 font-size: 13px;
		 margin-top: 30px;
	 }
	  #output2{
		  width: 100%;
		 height: 20px; line-height: 20px;
		 text-align: center;
		 color: #0068b7;
		 font-size: 13px;
	 }
	 #background_layer
	 {
		 position:absolute;
		 z-index: 9;
		 width: 100%;
		 height: 100%;
		 background: #0068b7;
		 opacity: 0.3;
		 left: 0px;
		 top: 0px;
		 display:none;
	 }
	 
</style>


<body bgcolor="#f0f0f0">
	
	<div id="wrap">
		
		<div id="background_layer"></div>
		<div id="loading" data-rel="popup" data-position-to="window">
			
			<div id="output">온라인 감정중...</div>
			<div id="output2">공시지가,실거래가,에너지사용량 분석중</div>
		</div>
		
		<div id='top_warp'>
			<?php include_once $_SERVER["DOCUMENT_ROOT"]."/landbaksa/web_code/common_top.php"; ?>
		</div>
		<div id="body_wrap">
			<div id="body_warp_frame">
				<div id="search_frame">
					<div id="search_address_input">
						<input type="text" id="address" class="search_address_input" placeholder="주소를 입력하세요.">
						
					</div>
					<div id="search_bt">
						감정시작
					</div>
				</div>
				<div id="search_data_frame">
					<input type="text" id="lawcode" class="hidden_input">
					<input type="text" id="jibun" class="hidden_input">
					<input type="text" id="apartment" class="hidden_input">
				</div>				
			</div>
			
		</div>
		<div id="bottom_wrap">
			
		</div>
	</div>	
	
</body>	
</HTML>