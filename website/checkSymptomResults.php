<!-- 
Author: Elliot Alexander Bates
email: batese@oregonstate.edu
description: This page is part of a database project for CS340 (Intorduction to databases) at Oregon State University
last date modified:12/03/2016
-->

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta name="description" content="This page is part of a database project for CS340 (Intorduction to databases) at Oregon State University">
		<meta name="author" content="Elliot Bates (batese@oregonstate.edu)">

		<title>Symptom Check Results</title>

		<!-- Bootstrap core CSS -->
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="styles/styles.css" rel="stylesheet">
	</head>

	<body>
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">Oregon Surivors Database</a>
				</div>
				<div id="navbar" class="collapse navbar-collapse">
				  <ul class="nav navbar-nav">
					<li class="active"><a href="index.html">Home</a></li>
					<li><a href="checkSymptoms.php">Symptom Checker</a></li>
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">Search Database
						<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="peopleSearch.php">People</a></li>
							<li><a href="personnelSearch.php">Medical Personnel</a></li>
							<li><a href="clinicSearch.php">Clinics</a></li>
							<li><a href="settlementSearch.php">Settlements</a></li>
							<li><a href="diseaseSearch.php">Diseases</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">Add Entries
						<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="addPeople.php">People</a></li>
							<li><a href="addPersonnel.php">Medical Personnel</a></li>
							<li><a href="addClinic.php">Clinics</a></li>
							<li><a href="addSettlement.php">Settlements</a></li>
							<li><a href="addDisease.php">Diseases</a></li>
							<li><a href="addDiseaseType.php">Disease Types</a></li>
							<li><a href="addSymptom.php">Symptoms</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">Update/Remove Entries
						<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="updatePeople.php">People</a></li>
							<li><a href="updatePersonnel.php">Medical Personnel</a></li>
							<li><a href="updateClinic.php">Clinics</a></li>
							<li><a href="updateSettlement.php">Settlements</a></li>
							<li><a href="updateDisease.php">Diseases</a></li>
							<li><a href="updateDiseaseType.php">Disease Types</a></li>
							<li><a href="updateSymptom.php">Symptoms</a></li>
						</ul>
					</li>
				  </ul>
				</div><!--/.nav-collapse -->
			</div>
		</nav>

		<div class="container">
			<h1>Symptom Checker Results</h1>
			<div>
				<h2>Search Results</h2>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Disease Name</th>
							<th>Disease Description</th>
							<th>Disease Type</th>
							<th>Percentage Match</th>
						</tr>
					</thead>
					<?php
					
						include 'databaseConnect.php';
						
						$selectStmt = "SELECT d.name, d.description, dt.name, COUNT(ds.sid) FROM osdb_disease_types dt
										INNER JOIN osdb_diseases d ON d.disease_type = dt.id
										INNER JOIN osdb_disease_symptoms ds ON ds.did = d.id
										INNER JOIN osdb_symptoms s ON s.id = ds.sid WHERE"; // Declare string without WHERE clauses
						$counter = 0;
						$paramString = "";
						if (isset($_GET['symptoms'])) { // Dynamically add where clauses
							foreach($_GET['symptoms'] as $symptom) {
								if ($counter == 0) {
									$selectStmt .= " s.id = ?";
								} else {
									$selectStmt .= " OR s.id = ?";
								}
								$counter = $counter + 1;
								$paramString .= "i"; // Build string for binding parameters
							}
						}
						$selectStmt .= " GROUP BY ds.did ORDER BY COUNT(ds.sid) DESC";
						
						//echo "<tr><td>" . $selectStmt . "</td></tr>";
						//echo "<tr><td>" . $paramString . "</td></tr>";
						

						
						// Prepares select statement
						if(!($stmt = $mysqli->prepare($selectStmt))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}
						
						$params = array(); // Creates parameters array
						function setarray(&$myarray, &$values) { // Takes reference to array and reference to values to be added to array
							foreach ($values as $key => &$value) {
								$myarray[] =& $value; // Sets values in array to references to values in other array
							}
						}
						setarray($params, $_GET['symptoms']);
						
						// Binds search parameters
						if(!(call_user_func_array(array($stmt, "bind_param"), array_merge(array($paramString), $params)))){ // Merge parameter declaration (iii..) with parameters reference array and bind
							echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
						}
						
						// Executes statement
						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
						// Binds results to variables
						if(!$stmt->bind_result($name, $description, $type, $count)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
						class DiseaseLine {
							public $name;
							public $description;
							public $type;
							public $percent;
						}
						
						$diseaseLines = array();
						
						// Gets the results
						while($stmt->fetch()){
							$percent = ($count/$counter) * 100;
							$thisLine = new DiseaseLine();
							$thisLine->name = $name;
							$thisLine->description = $description;
							$thisLine->type = $type;
							$thisLine->percent = $percent;
							$diseaseLines[] = $thisLine;
							/*echo "<tr><td>" . $name . "</td><td>" . $description . "</td><td>" . $type . "</td><td>" . $percent . "</td><td>";
							// echo matching symptoms
							echo "</td><td>";
							// echo non matched symptoms
							echo "</td></tr>";*/
						}
						
						foreach ($diseaseLines as &$line) {
							echo "<tr><td>" . $line->name . "</td><td>" . $line->description . "</td><td>" . $line->type . "</td><td>" . $line->percent . "</td></tr>";
						}

						$stmt->close();
					
					?>
				</table>
			</div>
		</div><!-- /.container -->

		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="scripts/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>
