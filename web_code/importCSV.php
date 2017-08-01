<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>


<?php
    include $_SERVER["DOCUMENT_ROOT"]."/landbaksa/database/inc_dbconnect.php";

    $csv = "/home/hosting_users/pianoontest/www/landbaksa/csvFiles/2017_price_standard_0.csv";

    $kakaoBaseURL = "https://dapi.kakao.com/v2/local/search/address.json?";
    $kakaoAPIKey = "KakaoAK 9e89781701ec5a128fdf028cac50be7a";

    $url = $kakaoBaseURL.'query='.'서울특별시 종로구 청운동 3-52';

    echo $url;

    // curl 세션 초기화
    $ch = curl_init(); //파라미터:url -선택사항
    // curl 옵션 세팅
    curl_setopt($ch,CURLOPT_URL, $url); //여기선 url을 변수로
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_NOSIGNAL, 1);
    curl_setopt($ch,CURLOPT_HTTPHEADER, array("Authorization : ".$kakaoAPIKey));
    // curl 실행
    $data = curl_exec($ch);
    // curl 세션 종료
    curl_close($ch);

    var_dump($data);

    /*
    $lines = file($csv); // member.csv 파일 전체를 배열로 읽어들임
    $count = count($lines); // 파일의 라인 수

    for($i = 1; $i < $count; $i++) {
        // csv 파일의 두번째 라인부터 DB에 입력해야 하므로 $i = 1
        $str = explode(",", rtrim($lines[$i], "rn"));

        $pnu = mysql_escape_string($str[0]);
        $sigunCd = mysql_escape_string($str[1]);
        $dongCd = mysql_escape_string($str[2]);
        $jibun_type = mysql_escape_string($str[3]);
        $bun = mysql_escape_string($str[4]);
        $ji = mysql_escape_string($str[5]);
        $siname = mysql_escape_string($str[6]);
        $guname = mysql_escape_string($str[7]);
        $munname = mysql_escape_string($str[8]);
        $dongname = mysql_escape_string($str[9]);
        $jimog = mysql_escape_string($str[11]);
        $size = mysql_escape_string($str[12]);
        $usename1 = mysql_escape_string($str[13]);
        $usename2 = mysql_escape_string($str[14]);
        $around = mysql_escape_string($str[16]);
        $doro = mysql_escape_string($str[19]);
        $gong_price = mysql_escape_string($str[20]);
//        $x_position =
//        $y_position =

        $sql = "INSERT INTO landbaksa_gongprice_standard SET year='2017', pnu='$pnu', regdate=NOW()";
        $result = mysql_query($sql);

        if(!$result) { // 쿼리 에러 시 다음 라인
            continue;
        }
    }*/
?>


</body>
</html>
