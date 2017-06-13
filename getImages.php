<?php
/**
 * Created by PhpStorm.
 * User: M.Azadi <mohammad.azadi@yahoo.com>
 * Date: 1/18/2017
 * Time: 4:47 PM
 */

$files = glob( dirname(__FILE__) . '/uploads/*.{jpg,png,gif,jpeg}', GLOB_BRACE);
$n_files = [];
foreach ($files as $file){
	$n_files["upload"][] = 'http://78.47.129.34/uploads/' . basename($file);
}

$files = glob( dirname(__FILE__) . '/FinalImages/*.png', GLOB_BRACE);
foreach ($files as $file){
	$n_files["final"][] = 'http://78.47.129.34/FinalImages/' . basename($file);
}

echo json_encode( $n_files );