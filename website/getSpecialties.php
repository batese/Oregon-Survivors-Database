<?php
/*
 * File: getLocations.php
 * Author: Elliot Bates
 * Email: batese@oregonstate.edu
 * Date: 
 * Description:
 */

$mysqli2 = new mysqli("oniddb.cws.oregonstate.edu","batese-db","DZsmOXIzdGETDDfm","batese-db");
if(!$mysqli2 || $msqli2->connect_errno){
	echo "Connection error " . $mysqli2->connect_errno . " " . $mysqli2->connect_error;
}
 
// Prepares select statement
if(!($stmt = $mysqli2->prepare("SELECT id, name FROM osdb_disease_types ORDER BY name ASC"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

// Executes statement
if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli2->connect_errno . " " . $mysqli2->connect_error;
}

// Binds results to variables
if(!$stmt->bind_result($id, $name)){
	echo "Bind failed: "  . $mysqli2->connect_errno . " " . $mysqli2->connect_error;
}

// Gets the results
while($stmt->fetch()){
	echo "<option value=\"" . $id . "\">". $name . "</option>";
}

$stmt->close();

?>