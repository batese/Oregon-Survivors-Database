SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `osdb_clinic_specialties`;
DROP TABLE IF EXISTS `osdb_personnel_specialties`;
DROP TABLE IF EXISTS `osdb_disease_symptoms`;
DROP TABLE IF EXISTS `osdb_medical_personnel`;
DROP TABLE IF EXISTS `osdb_clinics`;
DROP TABLE IF EXISTS `osdb_diseases`;
DROP TABLE IF EXISTS `osdb_symptoms`;
DROP TABLE IF EXISTS `osdb_disease_types`;
DROP TABLE IF EXISTS `osdb_people`;
DROP TABLE IF EXISTS `osdb_settlements`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE osdb_settlements (
	id			int		NOT NULL  AUTO_INCREMENT,
	name			varchar(255)	NOT NULL,
	longitude		float		NOT NULL,
	latitude		float		NOT NULL,
	PRIMARY KEY (id)
)engine=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE osdb_people (
	id			int		NOT NULL  AUTO_INCREMENT,
	fname			varchar(255)	NOT NULL,
	mname			varchar(255),
	lname			varchar(255)	NOT NULL,
	dob			date		NOT NULL,
	socsec			varchar(35),
	gender			varchar(35)	NOT NULL,
	home			int,
	PRIMARY KEY (id),
	CONSTRAINT fk_people_home FOREIGN KEY (home) REFERENCES osdb_settlements (id) ON DELETE RESTRICT ON UPDATE CASCADE
)engine=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE osdb_clinics (
	id			int		NOT NULL  AUTO_INCREMENT,
	name			varchar(255)	NOT NULL,
	location		int		NOT NULL,
	address1		varchar(255),
	address2		varchar(255),
	PRIMARY KEY (id),
	CONSTRAINT fk_clinics_location FOREIGN KEY (location) REFERENCES osdb_settlements (id) ON DELETE RESTRICT ON UPDATE CASCADE
)engine=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE osdb_disease_types (
	id			int		NOT NULL  AUTO_INCREMENT,
	name			varchar(255)	NOT NULL,
	PRIMARY KEY (id)
)engine=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE osdb_medical_personnel (
	id			int		NOT NULL  AUTO_INCREMENT,
	pid			int		NOT NULL,
	cid			int,
	PRIMARY KEY (id),
	CONSTRAINT fk_medical_personnel_pid FOREIGN KEY (pid) REFERENCES osdb_people (id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_medical_personnel_cid FOREIGN KEY (cid) REFERENCES osdb_clinics (id) ON DELETE SET NULL ON UPDATE CASCADE
)engine=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE osdb_diseases (
	id			int		NOT NULL  AUTO_INCREMENT,
	name			varchar(255)	NOT NULL,
	description		text		NOT NULL,
	disease_type		int		NOT NULL,
	PRIMARY KEY (id),
	CONSTRAINT fk_diseases_disease_type FOREIGN KEY (disease_type) REFERENCES osdb_disease_types (id) ON DELETE RESTRICT ON UPDATE CASCADE
)engine=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE osdb_symptoms (
	id			int		NOT NULL  AUTO_INCREMENT,
	name			varchar(255)	NOT NULL,
	description		text		NOT NULL,
	PRIMARY KEY (id)
)engine=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE osdb_disease_symptoms (
	sid			int		NOT NULL,
	did			int		NOT NULL,
	PRIMARY KEY (sid, did),
	CONSTRAINT fk_disease_symptoms_sid FOREIGN KEY (sid) REFERENCES osdb_symptoms (id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_disease_symptoms_did FOREIGN KEY (did) REFERENCES osdb_diseases (id) ON DELETE CASCADE ON UPDATE CASCADE
)engine=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE osdb_clinic_specialties (
	cid			int		NOT NULL,
	tid			int		NOT NULL,
	PRIMARY KEY (cid , tid),
	CONSTRAINT fk_clinic_specialties_cid FOREIGN KEY (cid) REFERENCES osdb_clinics (id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_clinic_specialties_tid FOREIGN KEY (tid) REFERENCES osdb_disease_types (id) ON DELETE CASCADE ON UPDATE CASCADE
)engine=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE osdb_personnel_specialties (
	pid			int		NOT NULL,
	tid			int		NOT NULL,
	PRIMARY KEY (pid , tid),
	CONSTRAINT fk_personnel_specialties_pid FOREIGN KEY (pid) REFERENCES osdb_medical_personnel (id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_personnel_specialties_tid FOREIGN KEY (tid) REFERENCES osdb_disease_types (id) ON DELETE CASCADE ON UPDATE CASCADE
)engine=InnoDB DEFAULT CHARSET=utf8;