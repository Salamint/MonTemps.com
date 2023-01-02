DROP TABLE IF EXISTS "Courses";
DROP TABLE IF EXISTS "Utilisateurs";
DROP TABLE IF EXISTS "Temps";

CREATE TABLE Courses (
	"id" TEXT UNIQUE NOT NULL,
	"type" TEXT NOT NULL,
	"ouverture" TEXT NOT NULL,
	CONSTRAINT PK_Courses PRIMARY KEY ("id")
);

CREATE TABLE Utilisateurs (
	"email" TEXT UNIQUE NOT NULL,
	"password" TEXT NOT NULL,
	"nickname" TEXT NOT NULL,
	"age" INT NOT NULL,
	CONSTRAINT PK_Utilisateurs PRIMARY KEY ("email"),
	CHECK("email" LIKE "%@%")
);

CREATE TABLE Temps (
	"email" TEXT,
	"id" TEXT,
	"temps" INT,
	CONSTRAINT PK_Temps PRIMARY KEY ("email", "id", "temps"),
	CONSTRAINT FK_TempsCourse FOREIGN KEY (id) REFERENCES Courses(id),
	CONSTRAINT FK_TempsUtilisateur FOREIGN KEY (email) REFERENCES Utilisateurs(email)
);

INSERT INTO Courses VALUES
("0", "Marathon", "20 Septembre 2022"),
("1", "Equitation", "2 Janvier 2021"),
("2", "Natation", "27 Juin 2017"),
("3", "100 M", "3 Janvier 2023"),
("4", "Velo", "19 Mai 2019");

INSERT INTO Utilisateurs VALUES
("malefoy.drago@poudlard.gouv.fr", "UtDe6sVL", "Drago Malefoy", 43),
("potter.harry@poudlard.gouv.fr", "p0ud1@rd", "Harry Potter", 43),
("dumbledore.albus@poudlard.gouv.fr", "VgLBVM6d", "Albus Dumbledore", 143),
("granger.hermione@poudlard.gouv.fr", "p5PQu3Ag", "Hermione Granger", 44),
("rogue.severus@poudlard.gouv.fr", "5oU9YbfK", "Severus Rogue", 63),
("naso.solido@jojo.fandom.com", "CMvZ9wft", "Diavolo", 43),
("bucciarati.bruno@jojo.fandom.com", "B5s3iBcP", "Bruno Bucciarati", 43),
("pucci.enrico@jojo.fandom.com", "ChuEo6MD", "Enrico Pucci", 43),
("kujo.jotaro@jojo.fandom.com", "12345", "Jotaro Kujo", 43),
("zeppeli.gyro@jojo.fandom.com", "8GhtFkvK", "Gyro Zeppeli", 43);

INSERT INTO Temps VALUES
("malefoy.drago@poudlard.gouv.fr", "0", 763),
("malefoy.drago@poudlard.gouv.fr", "1", 648),
("malefoy.drago@poudlard.gouv.fr", "2", 32),
("potter.harry@poudlard.gouv.fr", "1", 642),
("potter.harry@poudlard.gouv.fr", "2", 37),
("potter.harry@poudlard.gouv.fr", "3", 15),
("dumbledore.albus@poudlard.gouv.fr", "2", 46),
("dumbledore.albus@poudlard.gouv.fr", "3", 17),
("dumbledore.albus@poudlard.gouv.fr", "4", 3689),
("granger.hermione@poudlard.gouv.fr", "3", 14),
("granger.hermione@poudlard.gouv.fr", "4", 3798),
("granger.hermione@poudlard.gouv.fr", "0", 684),
("rogue.severus@poudlard.gouv.fr", "4", 3832),
("rogue.severus@poudlard.gouv.fr", "0", 802),
("rogue.severus@poudlard.gouv.fr", "1", 653),
("naso.solido@jojo.fandom.com", "0", 653),
("naso.solido@jojo.fandom.com", "1", 765),
("naso.solido@jojo.fandom.com", "2", 34),
("bucciarati.bruno@jojo.fandom.com", "1", 775),
("bucciarati.bruno@jojo.fandom.com", "2", 41),
("bucciarati.bruno@jojo.fandom.com", "3", 19),
("pucci.enrico@jojo.fandom.com", "2", 29),
("pucci.enrico@jojo.fandom.com", "3", 15),
("pucci.enrico@jojo.fandom.com", "4", 3805),
("kujo.jotaro@jojo.fandom.com", "3", 18),
("kujo.jotaro@jojo.fandom.com", "4", 3753),
("kujo.jotaro@jojo.fandom.com", "0", 684),
("zeppeli.gyro@jojo.fandom.com", "4", 3721),
("zeppeli.gyro@jojo.fandom.com", "0", 732),
("zeppeli.gyro@jojo.fandom.com", "1", 630);
