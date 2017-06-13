<?php
/**
 * Created by PhpStorm.
 * User: M.Azadi <mohammad.azadi@yahoo.com>
 * Date: 3/14/2017
 * Time: 10:35 AM
 */
include( '../tel.php' );

$data     = json_decode( file_get_contents( 'data.json' ), JSON_UNESCAPED_UNICODE );
$bot_id   = isset($_GET["token"]) ? $_GET["token"] : '347410843:AAEo0VFk8TYkvkW9VT-3yvmBvV42avlebmQ';
$telegram = new Telegram( $bot_id );
if(isset( $_GET["chatid"] )){

	$content = array(
		'chat_id' => $_GET["chatid"],
		'text'    => $_GET["text"]
	);
	$telegram->sendMessage( $content );
}else{
	$result  = $telegram->getData();
	$text    = $result["message"]["text"];
	$chat_id = $result["message"]["chat"]["id"];

	$data[] = $chat_id;
	if($text == '/start'){
		$content = array(
			'chat_id' => $chat_id,
			'text'    => "
			سلام، خوش آمدید!
			کد تلگرامی شما:
			". $chat_id ."
			"
		);
		$telegram->sendMessage( $content );
	}
	/*else{
		if(isset( $data[$text] ) && $data[$text] == $chat_id) {
			$content = array(
				'chat_id' => $chat_id,
				'text'    => "
			این کد قبلا ثبت شده است.
			"
			);
		}else{
			$content = array(
				'chat_id' => $chat_id,
				'text'    => "
			کد شما با موفقیت ثبت شد.
			"
			);
			$data[$text] = $chat_id;
		}
		$telegram->sendMessage( $content );
	}*/
}
file_put_contents( 'data.json', json_encode( $data, JSON_UNESCAPED_UNICODE ) );