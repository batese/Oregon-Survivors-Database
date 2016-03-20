var numPeople = 10000;
var peopleFile = "INSERT INTO osdb_people.txt";



var fs = require('fs');

var nameFiles = ["boys_name.txt", "girls_name.txt", "surnames.txt"];
var freqFiles = ["boys_name_freq.txt", "girls_name_freq.txt", "surnames_freq.txt"];
var nameVarNames = ["boyNames", "girlNames", "surnames"];
var freqVarNames = ["boyFreqs", "girlFreqs", "surnameFreqs"];

/*
for (x = 0; x < nameFiles.length; x++) {
	var (nameVarNames[x]) = fs.readFileSync(nameFiles[x]).toString().split("\n");
	var (freqVarNames[x]) = fs.readFileSync(freqFiles[x]).toString().split("\n");
}
*/

var boyNames = fs.readFileSync("boys_name.txt").toString().split("\n");
var girlNames = fs.readFileSync("girls_name.txt").toString().split("\n");
var surnames = fs.readFileSync("surnames.txt").toString().split("\n");
var boyFreqs = fs.readFileSync("boys_name_freq.txt").toString().split("\n");
var girlFreqs = fs.readFileSync("girls_name_freq.txt").toString().split("\n");
var surnameFreqs = fs.readFileSync("surnames_freq.txt").toString().split("\n");
var popFreqs = fs.readFileSync("settlement_pops.txt").toString().split("\n");

/*var nameString = new String(boyNames[0]);
var freqString = new String(boyFreqs[0]);
var someString = nameString + " " + freqString;*/

   /*
for(i in boyNames) {
	var someString = boyNames[i] + "  " + boyFreqs[i] + "";
    console.log(someString);
}*/

function fixNames (array) {
	for (x = 0; x < array.length; x++) {
		var nameString = new String();
		//console.log("Length:" + array[x].length + " " + array[x]);
		//nameString = array[x].slice(0, (array[x].length - 2));
		for (y = 0; y < array[x].length; y++) {
			var charCode = array[x].charCodeAt(y);
			var newLetter;
			if (y > 0) {
				if (charCode < 65 || charCode > 90) { //not a letter
					//console.log("Non capital found");
					break;
				} else {
					newLetter = String.fromCharCode(charCode + 32);
					//console.log(newLetter);
				}
			} else {
				newLetter = array[x][y];
			}
			nameString += newLetter;
		}
		//console.log(nameString);
		array[x] = nameString;
	}
	return array;
}

function fixFreqs (array) {
	var freqs = [];
	var cumFreqs = [];
	for (x = 0; x < array.length; x++) {
		var freq = parseFloat(array[x]);
		freqs[x] = freq;
	}
	var cumFreq = 0;
	for (x = 0; x < array.length; x++) {
		cumFreq += freqs[x];
		cumFreqs[x] = cumFreq;
	}
	return cumFreqs;
}

boyNames = fixNames(boyNames);
girlNames = fixNames(girlNames);
surnames = fixNames(surnames);
boyFreqs = fixFreqs(boyFreqs);
girlFreqs = fixFreqs(girlFreqs);
surnameFreqs = fixFreqs(surnameFreqs);
popFreqs = fixFreqs(popFreqs);
//console.log("Fixed Stuff");

/*for (x = 0; x < 10; x++) {
	console.log(boyNames[x] + "\t" + boyFreqs[x] + "\t" + girlNames[x] + "\t" + girlFreqs[x] + "\t" + surnames[x] + "\t" + surnameFreqs[x])
}*/

function getName (names, freqs) {
	if (names.length != freqs.length) {
		console.log("ERROR: Names != Frequencies");
		return NULL;
	}
	var num = Math.random() * freqs[freqs.length - 1];
	//console.log("num: " + num + "\tmax: " + freqs[freqs.length - 1]);
	var name;
	for (x = 0; x < freqs.length; x++) {
		if (num < freqs[x]) {
			name = names[x];
			break;
		}
	}
	return name;
}

function getDob () {
	var minYear = 1945;
	var maxYear = 2035;
	var yearNum = Math.floor(Math.random() * (maxYear - minYear + 1)) + minYear;
	var monthNum = Math.floor(Math.random() * 12) + 1;
	var maxDay
	if (month == 9 || month == 4 || month == 6 || month == 11) {
		maxDay = 30;
	} else if (month == 2) {
		maxDay = 28;
	} else {
		maxDay = 31;
	}
	var dayNum = Math.floor(Math.random() * maxDay) + 1;
	var year = yearNum.toString();
	var month = monthNum.toString();
	var day = dayNum.toString();
	if (month.length == 1) {
		month = "0" + month;
	}
	if (day.length == 1) {
		day = "0" + day;
	}
	var date = year + month + day;
	return date;
}

function getGender () {
	var gender;
	var num = Math.random()
	if (num > 0.5)
		gender = "Male"
	else
		gender = "Female"
	return gender;
}

function getHome (array) {
	var maxHome = 33;
	var home;
	var num = Math.random() * array[(array.length) - 1];
	//console.log("num: " + num + "\tmax: " + array[array.length - 1]);
	for (k= 0; k < array.length; k++) {
		if (num < array[k]) {
			home = k;
			break;
		}
	}
	return home + 1;
}

/*
var statCheck = [];
for (d = 0; d < 33; d++) {
	statCheck[d] = 0;
}
for (f = 0; f < 10000; f++) {
	var home = getHome(popFreqs);
	statCheck[home]++;
}
for (s = 0; s < 33; s++) {
	console.log(s + ": " + statCheck[s]);
}*/

function getSocsec () {
	var nm1 = Math.floor(Math.random() * 999) + 1;
	var nm2 = Math.floor(Math.random() * 99) + 1;
	var nm3 = Math.floor(Math.random() * 9999) + 1;
	var st1 = nm1.toString();
	while (st1.length < 3)
		st1 = "0" + st1;
	var st2 = nm2.toString();
	while (st2.length < 2)
		st2 = "0" + st2;
	var st3 = nm3.toString();
	while (st3.length < 4)
		st3 = "0" + st3;
	var socsec = st1 + "-" + st2 + "-" + st3;
	return socsec;
}

var numEntries = numPeople;
var dbName = "osdb_people";
var attributes = ["fname", "mname", "lname", "dob", "socsec", "gender", "home"];

//Set our log file as a writestream variable with the 'a' flag 
var logFile = fs.createWriteStream(peopleFile, {
  flags: "a",
  encoding: "utf8",
  mode: 0744
});

for (z = 0; z < numEntries; z++) {
	var gender = getGender();
	var someString = "INSERT INTO " + dbName + " (";
	for (y = 0; y < attributes.length; y++) {
		if (y > 0) {
			someString += ", "
		}
		someString += attributes[y];
	}
	someString += ") VALUES (\""
	var fname, mname, lname = getName(surnames, surnameFreqs);
	if (gender == "Male") {
		fname = getName(boyNames, boyFreqs);
		mname = getName(boyNames, boyFreqs);
	} else {
		fname = getName(girlNames, girlFreqs);
		mname = getName(girlNames, girlFreqs);
	}
	someString += fname + "\", \"" + mname + "\", \"" + lname + "\", ";
	var dob = getDob();
	someString += "\"" + dob + "\", ";
	var socsec = getSocsec();
	var home = getHome(popFreqs);
	someString += "\"" + socsec + "\", \"" + gender + "\", \"" + home + "\");";
	
	logFile.write(someString + "\r\n");
}