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
		
		$("#userid").focusout(function() {
			  	UseridCheck();
		});
		$("#pwd2").focusout(function() {
		  		passwordCheck();
		});
		
	});
	
	function UseridCheck()
	{
		var tempuserid = $("#userid").val();  	
		  	
		$.ajax({
              type: "POST",
              url: "useridCheck.php",
              data: ({userid: tempuserid}),
              cache: false,
              dataType: "json",
              success:function(msg) 
			  {
				   $("#resultLog").html(msg.message);
				   if(msg.signupJson == 'no')
				   {
				   	   jQuery("#userid").val('');
					   jQuery("#userid").focus();
					   $('#useridcheckbox').attr('checked',false);
				   }else
				   {
					   $('#useridcheckbox').attr('checked',true);
				   }
				   
			  },
			  error: function(xhr, status, errorThrown) 
			  {
			  	$("#resultLog").html(msg.message);
			  }
           });
			
	}
	
	function passwordCheck()
	{
		var temppwd1 = $("#pwd1").val();
		var temppwd2 = $("#pwd2").val();
		if(temppwd1.length < 4 || temppwd2.length < 4)
		{
			alert("비밀번호는 4자리이상 입니다.");
			jQuery("#pwd1").val('');
			jQuery("#pwd1").focus();
		}
		if(temppwd1.toString() != temppwd2.toString())
		{
			alert("비밀번호 확인이 다릅니다.");
			 jQuery("#pwd1").val('');
			 jQuery("#pwd2"),val('');
			 jQuery("#pwd1").focus();
		}
	}
		
	function singup()
	{
		var tempuserid = $('#userid').val();
		var temppassword1 = $('#pwd1').val();
		var temppassword2 = $('#pwd2').val();
		var tempusername = $('#username').val();
		
	    if(tempuserid.length < 1)
		{
			alert('아이디를  확인해주세요');
			$('#userid').focus();
			return false;
		}else if(tempusername.length < 1)
		{
			alert('이름을  확인해주세요');
			$('#username').focus();
			return false;
		}
		else if(temppassword1.length < 4)
		{
			alert('비밀번호를 확인해주세요');
			$('#pwd1').focus();
			return false;
		}else if(temppassword2.length < 4)
		{
			alert('비밀번호를 확인해주세요');
			$('#pwd2').focus();
			return false;
		}else{
			
			$('#background_layer').fadeIn();
			$('#loading').fadeIn();
			
			var getparams = {userid:tempuserid,pwd:temppassword1,username:tempusername};
					//alert('getparams 준비.'+getparams);
			$.getJSON("insert_singup.php",getparams,function(data){	
				$.each(data, function(key, value){
						if(key == "signupJson")
						{
							if(value == "insertok")
							{
								changeok = "ok";
								//alert('로그인 성공입니다.');
								$('#output').empty().append('회원가입 성공 입니다.');
								$('#output2').empty().append('로그인 페이지로 이동합니다.');
								
								setTimeout(function(){
											//$('#loading').fadeOut();
											//$("#loding").popup("close");
											//$('#loading').hide();
											
											location.href = "http://pianoontest.cafe24.com/landbaksa/web_code/login.php?cate=1";
											 }, 3000);
							}
							if(value == "no")
							{
								changeok = "not";	
								$('#output').append('정보가 정확하지 않아 실패하였습니다!!');
								//$('#loading').hide();
								setTimeout(function(){
											$('#loading').fadeOut();
											//$("#loding").popup("close");
											//$('#loading').hide();
											 }, 100);
								
							}

							if(value == "error")
							{
								changeok = "not";
								$('#output').append('DATA BASE ERROR!!!!.');
								setTimeout(function(){
											$('#loading').fadeOut();
											//$("#loding").popup("close");
											//$('#loading').hide();
											 }, 100);
							}
							
						}
						
				});
				
				if(changeok =="ok")
				{
					
					
				}else{
					alert('회원가입이 실패하였습니다..');
					$('#background_layer').fadeOut();
					$('#loading').fadeOut();
				}	
			});	
					
		}
	}
</script>

<style>
	#wrap{width: 1000px; height: auto; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	#top_warp{width: 1000px; height: auto; margin-left: auto; margin-right: auto;}
	#body_wrap{width: 1000px; height: auto; margin-left: auto; margin-right: auto; float: left;}
	#bottom_wrap{width: 1000px; height: 80px; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	
	#body_warp_frame{float: left; width: 1000px; height: auto; min-height: 500px; text-align: center; margin-left: auto; margin-right: auto; background: #f8f8f8;}
	
	#login_input_frame{float: left; width: 400px; height: 220px; margin-top: 100px; margin-left: 300px;}
	.id_pwd_input{float:left; width: 300px; height: 40px; margin-bottom: 10px; margin-left: 50px; padding-left: 10px;
		font-size: 15px;
		
	}
	
	#login_bt_frame_signup{float: left; width: 400px; height: 80px; margin-top:0px; margin-left: 300px; border: 0px solid black;}
	#signup_bt{float: left; width: 130px; height: 40px; line-height: 40px; border: 1px solid #0068b7; border-radius: 5px; text-align: center;
		color:white; background: #0068b7; font-size: 14px; font-weight: 300;
		margin-left: 216px;
	}
	
	#resultLog{float: left; width: 300px; height: 20px; line-height: 20px; color:#0068b7; font-size: 12px; margin-top: -10px; font-weight: 100;
		text-align: left;
		margin-left: 50px;
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
			
			<div id="output"></div>
			<div id="output2"></div>
		</div>
		
		<div id='top_warp'>
			<?php include_once $_SERVER["DOCUMENT_ROOT"]."/landbaksa/web_code/common_top.php"; ?>
		</div>
		<div id="body_wrap">
			<div id="body_warp_frame">
				<div id="login_input_frame">
					<input type="text" id="userid" name="userid" class="id_pwd_input" placeholder="아이디를 입력하세요.">
					<div id="resultLog"></div>
				    <input type="checkbox" id='useridcheckbox' name='useridcheckbox' disabled="true">
					<input type="text" id="pwd1" name"pwd1"  class="id_pwd_input" placeholder="비밀번호를 입력하세요.">
					<input type="pwd" id="pwd2" name"pwd2"  class="id_pwd_input" placeholder="비밀번호 확인.">
					<input type="text" id="username" name"username"  class="id_pwd_input" placeholder="이름을 입력하세요.">
				</div>
				<div id="login_bt_frame_signup">
					<a href="javascript:singup()">
						<div id="signup_bt">
							회원가입
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