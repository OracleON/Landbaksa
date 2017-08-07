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
	});
	
	function logincheck()
	{
		var tempuserid = $('#userid').val();
		var temppassword = $('#pwd').val();
		
		if(tempuserid.length < 1)
		{
			alert('아이디를  확인해주세요');
			$('#userid').focus();
			return false;
		}
		else if(temppassword.length < 4)
		{
			alert('비밀번호를 확인해주세요');
			$('#pwd').focus();
			return false;
		}else
		{
			
			var getparams = {userid:tempuserid,pwd:temppassword};
					//alert('getparams 준비.'+getparams);
			$.getJSON("logincheck.php",getparams,function(data){	
				$.each(data, function(key, value){
						if(key == "signupJson")
						{
							if(value == "accountok")
							{
								alert('로그인 성공입니다.');			
								location.href = "http://pianoontest.cafe24.com/landbaksa/web_code/search.php?cate=1";
											 
							}
							if(value == "notsignup")
							{
								
								alert('로그인 실패입니다.');	
								$('#userid').val('');
								$('#pwd').val('');
							}

							
							
						}
						
				});
			});	
		}
	}
</script>

<style>
	#wrap{width: 1000px; height: auto; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	#top_warp{width: 1000px; height: auto; margin-left: auto; margin-right: auto;}
	#body_wrap{width: 1000px; height: auto; margin-left: auto; margin-right: auto;}
	#bottom_wrap{width: 1000px; height: 80px; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	
	#body_warp_frame{width: 1000px; height: auto; min-height: 500px; text-align: center; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	
	#login_input_frame{float: left; width: 400px; height: 100px; margin-top: 150px; margin-left: 300px;}
	.id_pwd_input{float:left; width: 300px; height: 40px; margin-bottom: 10px; margin-left: 50px; padding-left: 10px;
		font-size: 15px;
		
	}
	
	#login_bt_frame_login{float: left; width: 400px; height: 80px; margin-top:0px; margin-left: 300px;}
	#signup_bt{float: left; width: 130px; height: 40px; line-height: 40px; border: 1px solid #0068b7; border-radius: 5px; text-align: center;
		color #0068b7; background: white; font-size: 14px; font-weight: 300;
		margin-left: 116px;
	}
	#login_bt{float: left; width: 80px; height: 40px; line-height: 40px; border: 1px solid #0068b7; border-radius: 5px;
		text-align: center; color: white; background: #0068b7; font-size: 14px; font-weight: 300;
		margin-left: 20px;
	}

</style>


<body bgcolor="#f0f0f0">
	
	<div id="wrap">
		
		<div id='top_warp'>
			<?php include_once $_SERVER["DOCUMENT_ROOT"]."/landbaksa/web_code/common_top.php"; ?>
		</div>
		<div id="body_wrap">
			<div id="body_warp_frame">
				<div id="login_input_frame">
					<input type="text" id="userid" name="userid" class="id_pwd_input" placeholder="아이디를 입력하세요.">
					<input type="password" id="pwd" name="pwd"  class="id_pwd_input" placeholder="비밀번호를 입력하세요.">
				</div>
				<div id="login_bt_frame_login">
					<a href="signup.php">
						<div id="signup_bt">
							회원가입
						</div>
					</a>
					<a href="javascript:logincheck()">
						<div id="login_bt">
							로그인
						</div>
					</a>
				</div> 
			</div>
		</div>
		<div id="bottom_wrap">
			
		</div>
	</div>	
	
</body>	
</HTML>