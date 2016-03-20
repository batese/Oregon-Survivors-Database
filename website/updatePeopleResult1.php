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

		<title>Search Results</title>

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
			<h1>Search Results</h1>
			<?php
				include 'databaseConnect.php';

				$firstName = "%" . $_GET['firstName'] . "%";
				$middleName = "%" . $_GET['middleName'] . "%";
				$surname = "%" . $_GET['surname'] . "%";
				
				if (empty($_GET['gender']) && empty($_GET['location'])) { // Neither gender or location are set
					// Prepares select statement
					if(!($stmt = $mysqli->prepare("SELECT p.id, p.fname, p.mname, p.lname, p.dob, p.socsec, p.gender, s.name, s.id, mp.id FROM osdb_people p 
													INNER JOIN osdb_settlements s ON s.id = p.home
													LEFT JOIN osdb_medical_personnel mp ON mp.pid = p.id
													WHERE p.fname LIKE ? 
													AND p.mname LIKE ? 
													AND p.lname LIKE ? 
													AND p.dob > ? 
													AND p.dob < ?"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					// Binds search parameters
					if(!($stmt->bind_param("sssss", $firstName, $middleName, $surname, $_GET['dobafter'], $_GET['dobbefore']))){
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					}
				} else if (empty($_GET['gender']) && !empty($_GET['location'])) { // Only gender is set
					// Prepares select statement
					if(!($stmt = $mysqli->prepare("SELECT p.id, p.fname, p.mname, p.lname, p.dob, p.socsec, p.gender, s.name, s.id, mp.id FROM osdb_people p 
													INNER JOIN osdb_settlements s ON s.id = p.home
													LEFT JOIN osdb_medical_personnel mp ON mp.pid = p.id
													WHERE p.fname LIKE ? 
													AND p.mname LIKE ? 
													AND p.lname LIKE ? 
													AND p.dob > ? 
													AND p.dob < ? 
													AND p.home = ?"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					// Binds search parameters
					if(!($stmt->bind_param("sssssi", $firstName, $middleName, $surname, $_GET['dobafter'], $_GET['dobbefore'], $_GET['location']))){
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					}
				} else if (!empty($_GET['gender']) && empty($_GET['location'])) { // Only location is set
					// Prepares select statement
					if(!($stmt = $mysqli->prepare("SELECT p.id, p.fname, p.mname, p.lname, p.dob, p.socsec, p.gender, s.name, s.id, mp.id FROM osdb_people p 
													INNER JOIN osdb_settlements s ON s.id = p.home
													LEFT JOIN osdb_medical_personnel mp ON mp.pid = p.id
													WHERE p.fname LIKE ? 
													AND p.mname LIKE ? 
													AND p.lname LIKE ? 
													AND p.dob > ? 
													AND p.dob < ? 
													AND p.gender = ?"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					// Binds search parameters
					if(!($stmt->bind_param("ssssss", $firstName, $middleName, $surname, $_GET['dobafter'], $_GET['dobbefore'], $_GET['gender']))){
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					}
				} else if (!empty($_GET['gender']) && !empty($_GET['location'])) { // Both gender and location are set
					// Prepares select statement
					if(!($stmt = $mysqli->prepare("SELECT p.id, p.fname, p.mname, p.lname, p.dob, p.socsec, p.gender, s.name, s.id, mp.id FROM osdb_people p 
													INNER JOIN osdb_settlements s ON s.id = p.home
													LEFT JOIN osdb_medical_personnel mp ON mp.pid = p.id
													WHERE p.fname LIKE ? 
													AND p.mname LIKE ? 
													AND p.lname LIKE ? 
													AND p.dob > ? 
													AND p.dob < ? 
													AND p.home = ? 
													AND p.gender = ?"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					// Binds search parameters
					if(!($stmt->bind_param("sssssis", $firstName, $middleName, $surname, $_GET['dobafter'], $_GET['dobbefore'], $_GET['location'], $_GET['gender']))){
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					}
				}
				
				// Executes statement
				if(!$stmt->execute()){
					echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}

				// Binds results to variables
				if(!$stmt->bind_result($id, $fname, $mname, $lname, $dob, $socsec, $gender, $home, $homeId, $medId)){
					echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}

				class PeopleLine {
					public $id;
					public $fname;
					public $mname;
					public $lname;
					public $dob;
					public $socsec;
					public $gender;
					public $home;
					public $homeId;
					public $medId;
				}
				
				$peopleLines = array();
				
				// Store results
				while($stmt->fetch()){
					$thisLine = new PeopleLine();
					$thisLine->id = $id;
					$thisLine->fname = $fname;
					$thisLine->mname = $mname;
					$thisLine->lname = $lname;
					$thisLine->dob = $dob;
					$thisLine->socsec = $socsec;
					$thisLine->gender = $gender;
					$thisLine->home = $home;
					$thisLine->homeId = $homeId;
					$thisLine->medId = $medId;
					$peopleLines[] = $thisLine;
				}
				
				if (count($peopleLines) > 0) {
					echo "<table class=\"table table-bordered\">
							<thead>
								<tr>
									<th>First Name</th>
									<th>Middle Name</th>
									<th>Surname</th>
									<th>Location</th>
									<th>DOB</th>
									<th>Gender</th>
									<th>Social Security</th>
									<th>Update</th>
									<th>Remove</th>
								</tr>
							</thead>";
					for ($x = 0; $x < count($peopleLines); $x++) {
						echo "<tr><td>" . $peopleLines[$x]->fname . "</td><td>" . $peopleLines[$x]->mname . "</td><td>" . $peopleLines[$x]->lname . "</td><td>" . 
								$peopleLines[$x]->home . "</td><td>" . $peopleLines[$x]->dob . "</td><td>" . $peopleLines[$x]->gender . "</td><td>" . 
								$peopleLines[$x]->socsec . "</td><td>";
						echo "<form action=\"updatePeopleResult3.php\" method =\"POST\">
								<input type=\"hidden\" name=\"id\" value=\"" . $peopleLines[$x]->id . "\">
								<input type=\"hidden\" name=\"fname\" value=\"" . $peopleLines[$x]->fname . "\">
								<input type=\"hidden\" name=\"mname\" value=\"" . $peopleLines[$x]->mname . "\">
								<input type=\"hidden\" name=\"lname\" value=\"" . $peopleLines[$x]->lname . "\">
								<input type=\"hidden\" name=\"homeId\" value=\"" . $peopleLines[$x]->homeId . "\">
								<input type=\"hidden\" name=\"dob\" value=\"" . $peopleLines[$x]->dob . "\">
								<input type=\"hidden\" name=\"gender\" value=\"" . $peopleLines[$x]->gender . "\">
								<input type=\"hidden\" name=\"socsec\" value=\"" . $peopleLines[$x]->socsec . "\">
								<input type=\"submit\" id=\"update\" value=\"Update Entry\"></form>";
						echo "</td><td>";
						echo "<form action=\"updatePeopleResult2.php\" method =\"POST\">
								<input type=\"hidden\" name=\"id\" value=\"" . $peopleLines[$x]->id . "\">
								<input type=\"submit\" id=\"remove\" value=\"Remove Entry\"></form>";
						echo "</td></tr>";
					}
					echo "</table>";
				} else {
						echo "Your search returned 0 results. <a href=\"peopleSearch.php\">Go back and search again.";
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