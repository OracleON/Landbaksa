<script>
function logoutmangonote()
 {
 	 setCookie('userid', '', -1);
 	 setCookie('username', '', -1);
 	 setCookie('shopcode', '', -1);
 	 setCookie('shopname', '', -1);
 	 setCookie('shopcheck', '', -1);
 	 setCookie('regdate', '', -1);
	 location.href = "http://mangonote.co.kr/mangonote/web/mangonotemall/signup/mangonote_login.php";
 }
 
 function setCookie(cName, cValue, cDay)
 {
      var expire = new Date();
      expire.setDate(expire.getDate() + cDay);
      cookies = cName + '=' + escape(cValue) + '; path=/ '; // 한글 깨짐을 막기위해 escape(cValue)를 합니다.
      if(typeof cDay != 'undefined') cookies += ';expires=' + expire.toGMTString() + ';';
      document.cookie = cookies;
 }
 
 
</script>

<style>
	#main_header{
		background: #ffffff;
		width: 1000px;
		height: 75px;
		float: left;
	}
	#hearder_frame_left{float: left; width: 600px; height: 75px;}
	#hearder_frame_right{float: left; width: 400px; height: 75px;}
	#hearder_frame_logo{float: left; width: 150px; height: 75px; line-height: 75px; color: black; font-size: 17px; font-weight: 800; text-align: center;}
	.hearder_frame_menu{float: left; width: 100px; height: 75px; line-height: 75px; color: #8c8d90; font-size: 15px; font-weight: 200; text-align: center;}
	.hearder_frame_menu_select{float: left; width: 100px; height: 75px; line-height: 75px; color: #0068b7; font-size: 15px; font-weight: 500; text-align: center;}
	#hearder_frame_login_bt{float: right; width: 80px; height: 40px; line-height: 40px; text-align: center; color: #0068b7; font-size: 13px; border: 1px solid #0068b7; border-radius: 5px; margin-top: 15px; margin-right: 30px;}
	a{text-decoration: none; color: inherit; }
</style>

<script>


function mangonote_search()
{
	var searchtext = $('#serchtitle').val();
	if(searchtext.length < 1)
	{
		alert("검색어를 입력하세요.");
	}else
	{
		document.location.href='/mangonote/web/mangonotemall/main/mangonotemall_searchResult.php?searchtext='+searchtext; 
	}
	
}

function show_nomember_msg()
{
	alert("로그인부터 하셔야 합니다.");
	location.href = "http://mangonote.co.kr/mangonote/web/mangonotemall/signup/mangonote_login.php";
	
	//var templogoutmsg = document.getElementById('nonmember_msg_div');
	
	//alert(templogoutmsg);
	
	//$('#nonmember_msg_div22').css({"background-color":"black","opacity":"1.0","display":"block"});
	
	//templogoutmsg.setAttribute("style", "display: block;");
}
function close_nomember_msg()
{
	//$('#nonmember_msg_div22').css({"background-color":"black","display":"none"});
}


</script>

<?
		if(isset($_COOKIE["userid"]))
		{
				$userid = $_COOKIE["userid"];
				$username = $_COOKIE["username"];
				$shopcode = $_COOKIE["shopcode"];
				$shopname = $_COOKIE["shopname"];
				$shopimage = $_COOKIE["shopimage"];
				$location = $_COOKIE["location"];
				$phonenum = $_COOKIE["phonenum"];
				$hpnum = $_COOKIE["hpnum"];
				$shopcheck = $_COOKIE["shopcheck"];
				$sendmethod = $_COOKIE["sendmethod"];
				$autodeliveryok = $_COOKIE["autodeliveryok"];
				$userGrade = $_COOKIE["userGrade"];
				$transcompany = $_COOKIE["transcompany"];	

		}
		
		//echo "id:".$userid;
		if($userid == null)
		{
			 //echo "아이디 없다.".$userid;
			 //echo("<script language='javascript'>show_nomember_msg()</script>"); 
		}else
		{
			//echo "아이디 있다.".$userid;
			// echo("<script language='javascript'>close_nomember_msg()</script>"); 
		}
		
		
?>


	
<div  id="main_header">
	<div id="hearder_frame_left">
		<div id="hearder_frame_logo">
			<a href="/landbaksa/web_code/search.php">땅꾼박사</a>
		</div>
		<div id="hearder_frame_menu1" class="hearder_frame_menu">
			<a href="/landbaksa/web_code/search.php">검색</a>
		</div>
		<div id="hearder_frame_menu2" class="hearder_frame_menu">
			<a href="/landbaksa/web_code/search_detail.php">물건비교</a>
		</div>
		<div id="hearder_frame_menu3" class="hearder_frame_menu">
			<a href="/landbaksa/web_code/search_my.php">지난검색</a>
		</div>
		<div id="hearder_frame_menu4" class="hearder_frame_menu">
			<a href="/landbaksa/web_code/search_live.php">실시간검색</a>
		</div>
	</div>
	<div id="hearder_frame_right">
		<div id="hearder_frame_login_bt">
			로그인
		</div>
	</div>	
	
</div>






