<?php
/**
 * Created by PhpStorm.
 * User: M.Azadi <mohammad.azadi@yahoo.com>
 * Date: 3/6/2017
 * Time: 12:50 PM
 */

include( '../tel.php' );
$bot_id   = '246405229:AAG3s1km9QMrl3sH5xy3AFsaz9WUssNOV5g';
$telegram = new Telegram( $bot_id );
$data     = json_decode( file_get_contents( 'data.json' ), JSON_UNESCAPED_UNICODE );

if($_GET["func"] == "sendMessage"){
	$content = array(
		'chat_id' => $_GET["c_id"],
		'text'    => $_GET["text"],
	);
	$telegram->sendMessage( $content );

	echo 'sended';
	$i = 0;
	foreach ($data["all_users"][$_GET["c_id"]]["contact_texts"] as $arr){
		echo intval($arr["id"]) .' => '. intval( $_GET["id"] ) .' => '. $i;
		if(intval($arr["id"]) == intval( $_GET["id"] )){
			$data["all_users"][$_GET["c_id"]]["contact_texts"][$i]["id"] = false;
			file_put_contents( 'data.json', json_encode( $data, JSON_UNESCAPED_UNICODE ) );
			return;
		}
		$i++;
	}


}