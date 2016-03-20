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

		<title>Update Disease</title>

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
			<h1>Update Disease</h1>
			<form action="updateDiseaseResult4.php" method ="POST" role="form" >
				<fieldset>
					<legend>Update Options</legend>
					<div class="form-group">
						<label for="name">Name:</label>
						<input type="text" class="form-control" name="name" id="name" <?php echo "value=\"" . $_POST['name'] . "\""; ?>>
					</div>
					<div class="form-group">
						<label for="description">Description:</label>
						<input type="text" class="form-control" name="description" id="description" <?php echo "value=\"" . $_POST['description'] . "\""; ?>>
					</div>
					<div class="form-group">
						<label for="type">Disease Type:</label>					
						<select class="form-control" name = "type" id="type">
							<?php 
								include 'databaseConnect.php';
								
								//Get current
								if(!($stmt = $mysqli->prepare("SELECT dt.id, dt.name FROM osdb_disease_types dt
																INNER JOIN osdb_diseases d ON d.disease_type = dt.id
																WHERE d.id = ?"))){
									echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
								}
								// Binds search parameters
								if(!($stmt->bind_param("i", $_POST['id']))){
									echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
								}
								// Executes statement
								if(!$stmt->execute()){
									echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
								// Binds results to variables
								if(!$stmt->bind_result($id1, $name1)){
									echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
								// Gets the results
								while($stmt->fetch()){
									echo "<option value=\"" . $id1 . "\">". $name1 . "</option>";
								}
								$stmt->close();
								
								//Get all
								if(!($stmt = $mysqli->prepare("SELECT id, name FROM osdb_disease_types ORDER BY name ASC"))){
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
						</select>
					</div>
					<div class="form-group">
						<label for="symptomTable">Select Disease Symptoms:</label>
						<table class="table table-bordered" name="symptomTable">
							<thead>
								<tr>
									<th></th>
									<th>Symptom</th>
									<th>Description</th>
								</tr>
							</thead>
							<?php
								if(!($stmt = $mysqli->prepare("SELECT s.id FROM osdb_symptoms s
																INNER JOIN osdb_disease_symptoms ds ON ds.sid = s.id
																INNER JOIN osdb_diseases d ON d.id = ds.did
																WHERE d.id = ?"))){
									echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
								}
								if(!($stmt->bind_param("i", $_POST['id']))){
									echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
								}
								// Executes statement
								if(!$stmt->execute()){
									echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
								// Binds results to variables
								if(!$stmt->bind_result($sid)){
									echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
								$symptoms = array();
								// Gets the results
								while($stmt->fetch()){
									$symptoms[] = $sid;
								}
								$stmt->close();
								
								if(!($stmt = $mysqli->prepare("SELECT id, name, description FROM osdb_symptoms"))){
									echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
								}
								// Executes statement
								if(!$stmt->execute()){
									echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
								// Binds results to variables
								if(!$stmt->bind_result($id, $name, $description)){
									echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
								// Gets the results
								while($stmt->fetch()){
									echo "<tr><td><input type=\"checkbox\" name=\"symptoms[]\" value=\"" . $id . "\"";
									if (in_array($id, $symptoms)) {
										echo " checked";
									}
									echo "></td><td>" . $name . "</td><td>" . $description . "</td></tr>";
								}
								$stmt->close();
							?>
						</table>
					</div>
					<input type="hidden" name="id" <?php echo "value=\"" . $_POST['id'] . "\""; ?>>
					<button type="submit" class="btn btn-default">Update</button>
				</fieldset>
			</form>
		</div><!-- /.container -->

		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="scripts/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>