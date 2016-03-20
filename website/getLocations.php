<?php
/*
 * File: getLocations.php
 * Author: Elliot Bates
 * Email: batese@oregonstate.edu
 * Date: 
 * Description:
 */
 
error_reporting(E_ALL);
ini_set('display_errors','On');

include 'databaseConnect.php';

// Prepares select statement
if(!($stmt = $mysqli->prepare("SELECT id, name FROM osdb_settlements ORDER BY name ASC"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

// Executes statement
if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

// Binds results to variables
if(!$stmt->bind_result($id, $name)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

// Gets the results
while($stmt->fetch()){
	echo "<option value=\"" . $id . "\">". $name . "</option>";
}

$stmt->close();

?>

<?php
/*

$cities = array("Portland", "Salem", "Eugene");
echo '<html>';
foreach ($cities as $city) {
	echo '<option>' . $city . '</option>';
}



*/

?>