<?php
/**
 * Created by PhpStorm.
 * User: M.Azadi <mohammad.azadi@yahoo.com>
 * Date: 2/28/2017
 * Time: 1:16 PM
 */

if(isset($_GET["file"])){
	echo $_GET["file"] . ' deleted';
	unlink($_GET["file"]);
	printHtml();
}else {
	printHtml();
}

function printHtml(){
	$files = glob('../uploads/*');
	$select = '
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>delete pic</title>
	<link rel="stylesheet" href="styles/fonts.css" />
	<link rel="stylesheet" href="styles/bootstrap.css" />
	<link rel="stylesheet" href="styles/style.css"  />
	
	<script type="text/javascript" src="scripts/jquery-2.1.4.min.js"></script>
	<script type="text/javascript" src="scripts/bootstrap.min.js"></script>
	<script type="text/javascript" src="scripts/bootstrap-select.js"></script>
</head>
<body>
<style>
.dropdown-menu.open, .dropdown-menu.open ul {
    width: 500px;
    height: 500px;
}

.dropdown-menu.open ul {
    top: 0;
}

.selected span.glyphicon.glyphicon-ok.check-mark {
    color: green;
}
</style>
<form action="/editor/delete.php"><select class="selectpicker" name="file">';
	foreach ($files as $file){
		$select .= '<option value="'.$file.'" data-content="<img src=\''.$file.'\' style=\'height: 200px;width: 200px;\' />">
 =>  '.$file.'
</option>';
	}
	$select .= '</select>
<input type="submit" value="delete" />
<script>
$(function(){
    $(\'select.selectpicker\').selectpicker();
});
</script>
</form></body></html>';

	print_r( $select );
}