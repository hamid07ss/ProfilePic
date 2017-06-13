<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ERROR);
include( 'tel.php' );
include( 'writeDB.php' );
include( 'CheckText.php' );

$bot_id   = '246405229:AAFxEfNiaDGd5IOq2fBWnm5R_dBHtAAWQtI';
$telegram = new Telegram($bot_id);
do{
	$req = $telegram->getUpdates();
	for ($i = 0; $i < $telegram->UpdateCount(); $i++) {
		// You NEED to call serveUpdate before accessing the values of message in Telegram Class
		$telegram->serveUpdate($i);
		$result  = $telegram->getData();
		$text = "";
		if(isset( $result["message"]["text"] )){
			$text    = $result["message"]["text"];
		}
		$chat_id = $result["message"]["chat"]["id"];
		CheckText($text, $chat_id, $telegram);
	}
}while (true);