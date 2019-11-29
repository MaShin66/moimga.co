<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/******************************************************
 * CURL PATH
 ******************************************************/
//	$CURL_PATH = "C:\curl\curl";
$CURL_PATH = "/usr/bin/curl";

/******************************************************
 * ID		: 다날에서 제공해 드린 CPID
 * PWD		: 다날에서 제공해 드린 CPPWD
 * ORDERID 	: CP 주문정보
 ******************************************************/
$ID  = "B010037917";
$PWD = "pCofnWoasL";
$ORDERID = 0;

/******************************************************
 * CHARSET ( UTF-8:Default or EUC-KR )
 ******************************************************/
//$CHARSET = "EUC-KR";
$CHARSET = "UTF-8";

/******************************************************
 * CallTrans
 *    - 다날 서버와 통신하는 함수입니다.
 *    - $Debug가 true일경우 웹브라우져에 debugging 메시지를 출력합니다.
 ******************************************************/
function CallTrans($REQ_DATA,$Debug=false){

    $DN_SERVICE_URL = "https://uas.teledit.com/uas/";
    $DN_CONNECT_TIMEOUT = "5";
    $DN_TIMEOUT = "30";
    $CHARSET = "UTF-8";

    $REQ_STR = data2str($REQ_DATA);
//    print_r($REQ_STR);

    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_POST,1 );
    curl_setopt( $ch,CURLOPT_SSLVERSION,0 );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER,0 );
    curl_setopt( $ch,CURLOPT_CONNECTTIMEOUT,$DN_CONNECT_TIMEOUT );
    curl_setopt( $ch,CURLOPT_TIMEOUT,$DN_TIMEOUT );
    curl_setopt( $ch,CURLOPT_URL,$DN_SERVICE_URL );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, array("Content-type:application/x-www-form-urlencoded;charset=".$CHARSET));
    curl_setopt( $ch,CURLOPT_POSTFIELDS,$REQ_STR );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER,1 );
    curl_setopt( $ch,CURLINFO_HEADER_OUT,1 );

    $RES_STR = curl_exec($ch);
    if( ($CURL_VAL=curl_errno($ch)) != 0 )
    {
        $RES_STR = "RETURNCODE=-1&RETURNMSG=NETWORK ERROR(" . htmlspecialchars($CURL_VAL) . ":" . htmlspecialchars(curl_error($ch)) . ")";
    }

    if( $Debug )
    {
        echo "REQ[" . $REQ_STR . "]<BR>";
        echo "RET[" . htmlspecialchars($CURL_VAL) . ":" . htmlspecialchars(curl_error($ch)) . "]<BR>";
        echo "RES[" . htmlspecialchars(urldecode($RES_STR)) . "]<BR>";
        echo "<BR>" . htmlspecialchars(print_r(curl_getinfo($ch)));
        exit();
    }
    curl_close($ch);

    return str2data($RES_STR);
}

function str2data($str){

    $in = "";

    if((string)$str == "Array"){
        for($i=0; $i<count($str);$i++){
            $in .= $str[$i];
        }
    }else{
        $in = $str;
    }

    $pairs = explode("&", $in);

    foreach($pairs as $line){
        $parsed = explode("=", $line, 2);

        if(count($parsed) == 2){
            $data[$parsed[0]] = $parsed[1];
        }
    }

    return $data;
}

function data2str($data){

    $pairs = array();
    foreach($data as $key => $value){
        array_push($pairs, $key . '=' . urlencode($value));
    }

    return implode('&', $pairs);
}

function MakeFormInput($arr,$ext=array(),$Prefix=""){

    $PreLen = strlen(trim($Prefix));
    $keys = array_keys($arr);

    for($i=0;$i<count($keys);$i++){

        $key = $keys[$i];
        if( trim($key) == "" ) continue;

        if( !in_array($key,$ext) && substr($key,0,$PreLen) == $Prefix ){
            echo( "<input type=\"hidden\" name=\"".htmlspecialchars($key)."\" value=\"".htmlspecialchars($arr[$key])."\">\n" );
        }
    }
}

function MakeAddtionalInput($Trans,$HTTPVAR,$Names) {

    while( $name=array_pop($Names) )
    {
        $Trans[$name] = trim($HTTPVAR[$name]);
    }

    return $Trans;
}

function GetBgColor($BgColor) {

    $Color = 0;

    if( intval($BgColor) > 0 && intval($BgColor) < 11 )
    {
        $Color = $BgColor;
    }

    return sprintf( "%02d",$Color );
}

function GetRandom( $nMin,$nMax )
{
    mt_srand();
    return mt_rand( $nMin,$nMax );
}

?>

