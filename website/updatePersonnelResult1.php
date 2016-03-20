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

		<title>Personnel Search Results</title>

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
			<h1>Medical Personnel Search Results</h1>
				<?php
					include 'databaseConnect.php';

					$firstName = "%" . $_GET['firstName'] . "%";
					$surname = "%" . $_GET['surname'] . "%";
					
					if (empty($_GET['specialty']) && empty($_GET['location'])) { // Neither gender or location are set
						// Prepares select statement
						if(!($stmt = $mysqli->prepare("SELECT mp.id, p.fname, p.mname, p.lname, c.name, dt.name, s.name, p.dob, p.gender, c.id, s.id FROM osdb_people p
														INNER JOIN osdb_settlements s ON s.id = p.home
														INNER JOIN osdb_medical_personnel mp ON mp.pid = p.id
														LEFT JOIN osdb_clinics c ON c.id = mp.cid
														LEFT JOIN osdb_personnel_specialties ps ON ps.pid = mp.id
														LEFT JOIN osdb_disease_types dt ON dt.id = ps.tid
														WHERE fname LIKE ? AND lname LIKE ?
														ORDER BY mp.id ASC"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}
						// Binds search parameters
						if(!($stmt->bind_param("ss", $firstName, $surname))){
							echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
						}
					} else if (!empty($_GET['specialty']) && empty($_GET['location'])) {
						// Prepares select statement
						if(!($stmt = $mysqli->prepare("SELECT mp.id, p.fname, p.mname, p.lname, c.name, dt.name, s.name, p.dob, p.gender, c.id, s.id FROM osdb_people p
														INNER JOIN osdb_settlements s ON s.id = p.home
														INNER JOIN osdb_medical_personnel mp ON mp.pid = p.id
														LEFT JOIN osdb_clinics c ON c.id = mp.cid
														LEFT JOIN osdb_personnel_specialties ps ON ps.pid = mp.id
														LEFT JOIN osdb_disease_types dt ON dt.id = ps.tid
														WHERE fname LIKE ? AND lname LIKE ? AND dt.id = ?
														ORDER BY mp.id ASC"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}
						// Binds search parameters
						if(!($stmt->bind_param("ssi", $firstName, $surname, $_GET['specialty']))){
							echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
						}						
					} else if (empty($_GET['specialty']) && !empty($_GET['location'])) {
						// Prepares select statement
						if(!($stmt = $mysqli->prepare("SELECT mp.id, p.fname, p.mname, p.lname, c.name, dt.name, s.name, p.dob, p.gender, c.id, s.id FROM osdb_people p
														INNER JOIN osdb_settlements s ON s.id = p.home
														INNER JOIN osdb_medical_personnel mp ON mp.pid = p.id
														LEFT JOIN osdb_clinics c ON c.id = mp.cid
														LEFT JOIN osdb_personnel_specialties ps ON ps.pid = mp.id
														LEFT JOIN osdb_disease_types dt ON dt.id = ps.tid
														WHERE fname LIKE ? AND lname LIKE ? AND s.id = ?
														ORDER BY mp.id ASC"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}
						// Binds search parameters
						if(!($stmt->bind_param("ssi", $firstName, $surname, $_GET['location']))){
							echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
						}						
					} else if (!empty($_GET['specialty']) && !empty($_GET['location'])) {
						// Prepares select statement
						if(!($stmt = $mysqli->prepare("SELECT mp.id, p.fname, p.mname, p.lname, c.name, dt.name, s.name, p.dob, p.gender, c.id, s.id FROM osdb_people p
														INNER JOIN osdb_settlements s ON s.id = p.home
														INNER JOIN osdb_medical_personnel mp ON mp.pid = p.id
														LEFT JOIN osdb_clinics c ON c.id = mp.cid
														LEFT JOIN osdb_personnel_specialties ps ON ps.pid = mp.id
														LEFT JOIN osdb_disease_types dt ON dt.id = ps.tid
														WHERE fname LIKE ? AND lname LIKE ? AND dt.id = ? AND s.id = ?
														ORDER BY mp.id ASC"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}
						// Binds search parameters
						if(!($stmt->bind_param("ssii", $firstName, $surname, $_GET['specialty'], $_GET['location']))){
							echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
						}						
					}
					
					// Executes statement
					if(!$stmt->execute()){
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}

					// Binds results to variables
					if(!$stmt->bind_result($id, $fname, $mname, $lname, $clinic, $specialty, $location, $dob, $gender, $cid, $sid)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}

					class PersonnelLine {
						public $id;
						public $fname;
						public $mname;
						public $lname;
						public $clinic;
						public $specialty;
						public $location;
						public $dob;
						public $gender;
						public $cid;
						public $sid;
					}
					
					$personnelLines = array();
					
					// Gets the results
					while($stmt->fetch()){
						$thisLine = new PersonnelLine();
						$thisLine->id = $id;
						$thisLine->fname = $fname;
						$thisLine->mname = $mname;
						$thisLine->lname = $lname;
						$thisLine->clinic = $clinic;
						$thisLine->specialty = $specialty;
						$thisLine->location = $location;
						$thisLine->dob = $dob;
						$thisLine->gender = $gender;
						$thisLine->cid = $cid;
						$thisLine->sid = $sid;
						$personnelLines[] = $thisLine;
					}
					
					if (count($personnelLines) > 0) {
						echo "<table class=\"table table-bordered\">
								<thead>
									<tr>
										<th>Medical ID</th>
										<th>First Name</th>
										<th>Middle Name</th>
										<th>Surname</th>
										<th>Clinic</th>
										<th>Specialties</th>
										<th>Location</th>
										<th>DOB</th>
										<th>Gender</th>
										<th>Update</th>
										<th>Remove</th>
									</tr>
								</thead>";
						for ($x = 0; $x < count($personnelLines); $x++) {
							if ($x == 0) {
								echo "<tr><td>" . $personnelLines[$x]->id . "</td><td>" . $personnelLines[$x]->fname . "</td><td>" . $personnelLines[$x]->mname . "</td><td>" . 
									$personnelLines[$x]->lname . "</td><td>" . $personnelLines[$x]->clinic . "</td><td><ul><li>" . $personnelLines[$x]->specialty;
							} else {
								if ($personnelLines[$x]->id == $personnelLines[$x-1]->id) {
									echo "</li><li>" . $personnelLines[$x]->specialty;
								} else {
									echo "<tr><td>" . $personnelLines[$x]->id . "</td><td>" . $personnelLines[$x]->fname . "</td><td>" . $personnelLines[$x]->mname . "</td><td>" . 
											$personnelLines[$x]->lname . "</td><td>" . $personnelLines[$x]->clinic . "</td><td><ul><li>" . $personnelLines[$x]->specialty;
								}
							}
							if ($x == (count($personnelLines)-1)) {
								echo "</li></ul></td><td>" . $personnelLines[$x]->location . "</td><td>" . $personnelLines[$x]->dob . "</td><td>" . $personnelLines[$x]->gender . "</td><td>";
								echo "<form action=\"updatePersonnelResult3.php\" method =\"POST\">
										<input type=\"hidden\" name=\"id\" value=\"" . $personnelLines[$x]->id . "\">
										<input type=\"hidden\" name=\"sid\" value=\"" . $personnelLines[$x]->sid . "\">
										<input type=\"submit\" id=\"update\" value=\"Update Entry\"></form>";
								echo "</td><td>";
								echo "<form action=\"updatePersonnelResult2.php\" method =\"POST\">
										<input type=\"hidden\" name=\"id\" value=\"" . $personnelLines[$x]->id . "\">
										<input type=\"submit\" id=\"remove\" value=\"Remove Entry\"></form>";
								echo "</td></tr>";
							} else {
								if ($personnelLines[$x]->id != $personnelLines[$x+1]->id) {
									echo "</li></ul></td><td>" . $personnelLines[$x]->location . "</td><td>" . $personnelLines[$x]->dob . "</td><td>" . $personnelLines[$x]->gender . "</td><td>";
									echo "<form action=\"updatePersonnelResult3.php\" method =\"POST\">
											<input type=\"hidden\" name=\"id\" value=\"" . $personnelLines[$x]->id . "\">
											<input type=\"hidden\" name=\"sid\" value=\"" . $personnelLines[$x]->sid . "\">
											<input type=\"submit\" id=\"update\" value=\"Update Entry\"></form>";
									echo "</td><td>";
									echo "<form action=\"updatePersonnelResult2.php\" method =\"POST\">
											<input type=\"hidden\" name=\"id\" value=\"" . $personnelLines[$x]->id . "\">
											<input type=\"submit\" id=\"remove\" value=\"Remove Entry\"></form>";
									echo "</td></tr>";
								}
							}
						}
						echo "</table>";
					} else {
						echo "Your search returned 0 results. <a href=\"updatePersonnel.php\">Go back and search again.";
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