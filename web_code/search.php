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
		
		$('#search_address_input').on('click',function(){
			var tempaddress = $('#address').val();
			if(tempaddress =='')
			{
				//alert('주소 API호출');
				sample6_execDaumPostcode();
			}else
			{
				
			}
			
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
				
				location.href = "http://pianoontest.cafe24.com/landbaksa/web_code/result.php?cate=1&address="+Array_address;
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

                // 커서를 상세주소 필드로 이동한다.
                document.getElementById('address').focus();
            }
        }).open();
    }
</script>

<style>
	#wrap{width: 1000px; height: auto; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	#top_warp{width: 1000px; height: auto; margin-left: auto; margin-right: auto;}
	#body_wrap{float: left; width: 1000px; height: auto; margin-left: auto; margin-right: auto;}
	#bottom_wrap{width: 1000px; height: 80px; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	
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
</style>


<body bgcolor="#f0f0f0">
	
	<div id="wrap">
		
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
			</div>
			
		</div>
		<div id="bottom_wrap">
			
		</div>
	</div>	
	
</body>	
</HTML>