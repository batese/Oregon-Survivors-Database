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

		<title>Symptom Search Results</title>

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
			<h1>Symptom Search Results</h1>
			<?php
			
				include 'databaseConnect.php';

				if (empty($_GET['symptom'])) { // No symptom selected
					// Prepares select statement
					if(!($stmt = $mysqli->prepare("SELECT id, name, description FROM osdb_symptoms "))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
				} else if (!empty($_GET['symptom'])) { // Symptom selected
					// Prepares select statement
					if(!($stmt = $mysqli->prepare("SELECT id, name, description FROM osdb_symptoms WHERE id=?"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					// Binds search parameters
					if(!($stmt->bind_param("i", $_GET['symptom']))){
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					}
				}
				
				// Executes statement
				if(!$stmt->execute()){
					echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}

				// Binds results to variables
				if(!$stmt->bind_result($id, $name, $description)){
					echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}

				class SymptomLine {
					public $id;
					public $name;
					public $description;
				}
				
				$symptomLines = array();
				
				// Gets the results
				while($stmt->fetch()){
					$thisLine = new SymptomLine();
					$thisLine->id = $id;
					$thisLine->name = $name;
					$thisLine->description = $description;
					$symptomLines[] = $thisLine;
				}
				
				if (count($symptomLines) > 0) {
					echo "<table class=\"table table-bordered\">
							<thead>
								<tr>
									<th>Name</th>
									<th>Description</th>
									<th>Update</th>
									<th>Remove</th>
								</tr>
							</thead>";
					for ($x = 0; $x < count($symptomLines); $x++) {
						echo "<tr><td>" . $symptomLines[$x]->name . "</td><td>" . $symptomLines[$x]->description . "</td><td>";
						echo "<form action=\"updateSymptomResult3.php\" method =\"POST\">
							<input type=\"hidden\" name=\"id\" value=\"" . $symptomLines[$x]->id . "\">
							<input type=\"hidden\" name=\"name\" value=\"" . $symptomLines[$x]->name . "\">
							<input type=\"hidden\" name=\"description\" value=\"" . $symptomLines[$x]->description . "\">
							<input type=\"submit\" id=\"update\" value=\"Update Entry\"></form></td><td>";
						echo "<form action=\"updateSymptomResult2.php\" method =\"POST\">
							<input type=\"hidden\" name=\"id\" value=\"" . $symptomLines[$x]->id . "\">
							<input type=\"submit\" id=\"remove\" value=\"Remove Entry\"></form></td></tr>";
					}
					echo "</table>";
				} else {
					echo "Your search returned 0 results. <a href=\"updateSymptom.php\">Go back and search again.";
				}
				$stmt->close();
			?>
		</div><!-- /.container -->

		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="scripts/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>