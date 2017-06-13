<?php
/**
 * Created by PhpStorm.
 * User: M.Azadi <mohammad.azadi@yahoo.com>
 * Date: 3/6/2017
 * Time: 12:44 PM
 */


$data     = json_decode( file_get_contents( 'data.json' ), JSON_UNESCAPED_UNICODE );

$pri_Html = '
    <!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>ارسال پاسخ</title>
    <link rel="stylesheet" href="../editor/styles/style.php"/>
	<link rel="stylesheet" href="../editor/styles/fonts.css" />
	<link rel="stylesheet" href="../editor/styles/bootstrap.css" />
	<link rel="stylesheet" href="../editor/styles/style.css"  />

	<script type="text/javascript" src="../editor/scripts/jquery-2.1.4.min.js"></script>


</head>
<body>

<iframe name="sendText" id="sendText" class="sendText"></iframe>
';

foreach ($data["all_users"] as $c_id => $users){
	foreach ($users["contact_texts"] as $messages){
		if($messages["id"] != false){
			$pri_Html .= '<div class="one-message">
    <div class="mess-name">'. $messages["name"] .'</div>
    <div class="mess-text">'. $messages["text"] .'</div>
    <div class="mess-ans">
        <form target="sendText" action="/functions/telFuncs.php" method="get">
            <input name="c_id" value="'.$c_id.'" type="hidden">
            <input name="func" value="sendMessage" type="hidden">
            <input name="id" value="'. $messages["id"] .'" type="hidden">
            <textarea height="300" class="form-control" name="text"></textarea>
            <br />
            <input type="submit" value="ارسال" class="btn btn-primary">
        </form>
    </div>
    
</div>';
		}
	}
}

echo $pri_Html . '</body></html>';