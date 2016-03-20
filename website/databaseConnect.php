<?php
/*
 * File: databaseConnect.php
 * Author: Elliot Bates
 * Email: batese@oregonstate.edu
 * Date: 01/03/2016
 * Description:
 */

//error_reporting(E_ALL);
//ini_set('display_errors','On');

//Connects to the database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","batese-db","DZsmOXIzdGETDDfm","batese-db");
if(!$mysqli || $msqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
?>