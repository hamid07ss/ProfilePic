<?php
/**
 * Created by PhpStorm.
 * User: M.Azadi <mohammad.azadi@yahoo.com>
 * Date: 2/13/2017
 * Time: 1:43 PM
 */

include( '../tel.php' );

$chat_id  = '@ProfilePic_free';
$bot_id   = '246405229:AAG3s1km9QMrl3sH5xy3AFsaz9WUssNOV5g';
$telegram = new Telegram( $bot_id );
$result  = $telegram->getData();

$content = array(
	'chat_id' => $chat_id,
	'photo' => $_POST["img"],
	'caption' => $_POST["descs"]
);
$telegram->sendPhoto( $content );