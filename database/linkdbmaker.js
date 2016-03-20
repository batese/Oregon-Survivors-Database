var fs = require('fs');

var maxCid = 14, maxPid = 250, maxSid = 27, maxTid = 11, maxDid = 31;

function contains(a, num) {
    for (var i = 0; i < a.length; i++) {
        if (a[i] === num) {
            return true;
        }
    }
    return false;
}

var perSpecFile = fs.createWriteStream("INSERT INTO osdb_personnel_specialties.txt", {
  flags: "a",
  encoding: "utf8",
  mode: 0744
});

for (z = 1; z < (maxPid + 1); z++) {
	var numSpecs = Math.floor(Math.random() * (5)) + 1;
	var mySpecs = [];
	for (k = 0; k < numSpecs; k++) {
		var attributes = ["pid", "tid"];
		var someString = "INSERT INTO " + "osdb_personnel_specialties" + " (";
		for (y = 0; y < attributes.length; y++) {
			if (y > 0) {
				someString += ", "
			}
			someString += attributes[y];
		}
		someString += ") VALUES (";
	
		var newPid = z;
		do {
			var newTid = Math.floor(Math.random() * (maxTid)) + 1;
		} while (contains(mySpecs, newTid) == true);
		mySpecs.push(newTid);
	
		someString += newPid + ", " + newTid + ");";
	
		perSpecFile.write(someString + "\r\n");
	}
}

var clinSpecFile = fs.createWriteStream("INSERT INTO osdb_clinic_specialties.txt", {
  flags: "a",
  encoding: "utf8",
  mode: 0744
});

for (z = 1; z < (maxCid + 1); z++) {
	var numSpecs = Math.floor(Math.random() * (4)) + 1;
	var mySpecs = [];
	for (k = 0; k < numSpecs; k++) {
		var attributes = ["cid", "tid"];
		var someString = "INSERT INTO " + "osdb_clinic_specialties" + " (";
		for (y = 0; y < attributes.length; y++) {
			if (y > 0) {
				someString += ", "
			}
			someString += attributes[y];
		}
		someString += ") VALUES (";
	
		var newCid = z;
		do {
			var newTid = Math.floor(Math.random() * (maxTid)) + 1;
		} while (contains(mySpecs, newTid) == true);
		mySpecs.push(newTid);
	
		someString += newCid + ", " + newTid + ");";
	
		clinSpecFile.write(someString + "\r\n");
	}
}

var disSymFile = fs.createWriteStream("INSERT INTO osdb_disease_symptoms.txt", {
  flags: "a",
  encoding: "utf8",
  mode: 0744
});

for (z = 1; z < (maxDid + 1); z++) {
	var numSymps = Math.floor(Math.random() * (14)) + 1;
	var mySymps = [];
	for (k = 0; k < numSymps; k++) {
		var attributes = ["sid", "did"];
		var someString = "INSERT INTO " + "osdb_disease_symptoms" + " (";
		for (y = 0; y < attributes.length; y++) {
			if (y > 0) {
				someString += ", "
			}
			someString += attributes[y];
		}
		someString += ") VALUES (";
	
		var newDid = z;
		do {
			var newSid = Math.floor(Math.random() * (maxSid)) + 1;
		} while (contains(mySymps, newSid) == true);
		mySymps.push(newSid);
	
		someString += newSid + ", " + newDid + ");";
	
		disSymFile.write(someString + "\r\n");
	}
}