<?php
/**
 * Created by PhpStorm.
 * User: M.Azadi <mohammad.azadi@yahoo.com>
 * Date: 2/5/2017
 * Time: 1:28 PM
 *
 * @param $whatDo
 * @param $pat_id
 * @param null $c_id
 * @param null $textss
 * @param null $namesCount
 *
 * @return bool|mysqli_result
 */

function DBFunctions($whatDo, $pat_id, $c_id = null, $textss = null, $namesCount = null){
	include_once "config.php";
	global $CFG;


	switch ($whatDo){
		case "namesCount":

			$query2 = "select `namesCount` from patterns WHERE p_id='$pat_id' ";
			$sendBack = mysqli_query($CFG->conn, $query2);
			$sendBack = mysqli_fetch_array( $sendBack );
			return $sendBack[0];
			break;

		case "writeDB":
			$query = "INSERT INTO requests (chat_id, p_id, texts) 
					VALUES ($c_id, '$pat_id', '$textss')";
			$result = mysqli_query($CFG->conn, $query);


			$query2 = "select COUNT(*) from requests WHERE done=0";
			$sendBack = mysqli_query($CFG->conn, $query2);
			$sendBack = mysqli_fetch_array( $sendBack );

			return $sendBack[0];
			break;

		case "writePattern":

			$query = "INSERT INTO patterns (p_id, namesCount) 
					VALUES ('$pat_id', $namesCount)";

			$result = mysqli_query($CFG->conn, $query);

			return $result;
	}

	return null;
};