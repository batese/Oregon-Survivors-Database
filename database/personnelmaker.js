var fs = require('fs');

var pids = fs.readFileSync("potmedperpids.txt").toString().split("\n");

function fixnums (array) {
	var myPids = [];
	for (x = 0; x < array.length; x++) {
		var myPid = parseInt(array[x]);
		myPids[x] = myPid;
	}
	return myPids;
}

pids = fixnums(pids);
var sids = [];
var sid = 1;
var prevPid = 1;

for (x = 0; x < pids.length; x++) {
	var thisPid = pids[x];
	if (thisPid < prevPid) {
		sid++;
	}
	sids[x] = sid;
	prevPid = thisPid;
}

/*
for (x = 0; x < sids.length; x++) {
	console.log(sids[x] + "\t" + pids[x]);
}*/

var numPer = 250;

var logFile = fs.createWriteStream("INSERT INTO osdb_medical_personnel.txt", {
  flags: "a",
  encoding: "utf8",
  mode: 0744
});

var dbName = "osdb_medical_personnel";
var attributes = ["pid", "cid"];

for (z = 0; z < numPer; z++) {
	var someString = "INSERT INTO " + dbName + " (";
	for (y = 0; y < attributes.length; y++) {
		if (y > 0) {
			someString += ", "
		}
		someString += attributes[y];
	}
	someString += ") VALUES (";
	
	var selector = Math.floor(Math.random() * (pids.length));
	var newPid = pids[selector];
	var newCid = sids[selector];
	
	if (newCid > 14) {
		newCid = "NULL";
	}
	
	someString += newPid + ", " + newCid + ");";
	
	logFile.write(someString + "\r\n");
}