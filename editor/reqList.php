<?php
/**
 * Created by PhpStorm.
 * User: M.Azadi <mohammad.azadi@yahoo.com>
 * Date: 2/6/2017
 * Time: 12:59 PM
 */

include_once "../config.php";
global $CFG;

$query = "select * from requests WHERE done=0";
$allreq = mysqli_query($CFG->conn, $query);
$data     = json_decode( file_get_contents( '../functions/data.json' ), JSON_UNESCAPED_UNICODE );


echo '
    <link rel="stylesheet" href="styles/fonts.css" />
    <link rel="stylesheet" href="styles/style.php"/>
	<link rel="stylesheet" href="styles/bootstrap.css" />
    <link rel="stylesheet" href="styles/style.css"  />
    
    <script type="text/javascript" src="scripts/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="scripts/html2canvas.js"></script>
';


foreach ($allreq as $request){
	$texts = json_decode(  $request["texts"]  );
	$htmlTexts = '';
	foreach ($texts as $text){
		$htmlTexts .= '
			<div class="text">
			'. $text .'
			</div>
		';
	}
	$name = isset($data["all_users"][$request["chat_id"]]["names"]) ? $data["all_users"][$request["chat_id"]]["names"] : "";
	$imgHtml = '<img src="../FinalImages/' . trim( $request["p_id"] ) . '.png" />';
	echo '
	<div class="one-request">
        <div class="pat-img pic-cont">
            '. $imgHtml .'
        </div>
        <div class="details">
            <div class="chat_id">
                <div><h2>'. $name .'</h2></div>
	            <span>آیدی کاربر: </span>
	            <span>'. $request["chat_id"] .'</span>
	        </div>
	        <div class="req-texts">
	            <span>متن های روی عکس: </span>
	            '.$htmlTexts.'
	        </div>
	        <div class="do-this-link">
				<a target="_blank" href="/editor/picReq.php?id='. $request["id"] .'&chat_id='. $request["chat_id"] .'&file='. trim( $request["p_id"] ) .'&names='. preg_replace( '/"/', "'", json_encode( $texts, JSON_UNESCAPED_UNICODE )) .'">
				مشاهده و ارسال عکس به کاربر
				</a>        
	        </div>
        </div>
    </div>
	';
}






$query = "select * from requests WHERE done=1";
$allreq = mysqli_query($CFG->conn, $query);


echo '<div><h1 style="color: red;text-align: center">درخواست های انجام شده</h1></div>';

foreach ($allreq as $request){
	$texts = json_decode(  $request["texts"]  );
	$htmlTexts = '';
	foreach ($texts as $text){
		$htmlTexts .= '
			<div class="text">
			'. $text .'
			</div>
		';
	}

	$name = isset($data["all_users"][$request["chat_id"]]["names"]) ? $data["all_users"][$request["chat_id"]]["names"] : "";
	$imgHtml = '<img src="../FinalImages/' . trim( $request["p_id"] ) . '.png" />';
	echo '
	<div class="one-request ended">
        <div class="pat-img pic-cont">
            '. $imgHtml .'
        </div>
        <div class="details">
            <div class="chat_id">
                <div><h2>'. $name .'</h2></div>
	            <span>آیدی کاربر: </span>
	            <span>'. $request["chat_id"] .'</span>
	        </div>
	        <div class="req-texts">
	            <span>متن های روی عکس: </span>
	            '.$htmlTexts.'
	        </div>
	        <div class="do-this-link">
				<a target="_blank" href="/editor/picReq.php?id='. $request["id"] .'&chat_id='. $request["chat_id"] .'&file='. trim( $request["p_id"] ) .'&names='. preg_replace( '/"/', "'", json_encode( $texts, JSON_UNESCAPED_UNICODE )) .'">
				مشاهده و ارسال عکس به کاربر
				</a>        
	        </div>
        </div>
    </div>
	';
}