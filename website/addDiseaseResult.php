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

		<title>Add Disease Result</title>

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
			<h1>Add Disease Result</h1>
				<?php
					include 'databaseConnect.php';
					
					if (!empty($_POST['name']) && !empty($_POST['description'])) {
						if(!($stmt = $mysqli->prepare("INSERT INTO osdb_diseases(name, description, disease_type) VALUES (?,?,?)"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}
						if(!($stmt->bind_param("ssi", $_POST['name'], $_POST['description'], $_POST['type']))){
							echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
						}
						if(!$stmt->execute()){
							echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
						} else {
							echo "Added " . $stmt->affected_rows . " rows to osdb_diseases.<br>";
						}
						$stmt->close();
						$count = 0;
						foreach($_POST['symptoms'] as $symptom) {
							if(!($stmt = $mysqli->prepare("INSERT INTO osdb_disease_symptoms(sid, did) VALUES (?,(SELECT MAX(id) FROM osdb_diseases
																													WHERE name = ?))"))){
								echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
							}
							if(!($stmt->bind_param("is", $symptom, $_POST['name']))){
								echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
							}
							if(!$stmt->execute()){
								echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
							} else {
								$count++;
							}
							$stmt->close();
						}
						if ($count > 0) {
							echo "Added " . $count . " rows to osdb_disease_symptoms.<br>";
						}
					} else {
						echo "Please <a href=\"addDisease.php\">go back</a> and fill in the name and description.";
					}
				?>
			</table>
		</div><!-- /.container -->


		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="scripts/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>
