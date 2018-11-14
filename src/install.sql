DROP TABLE IF EXISTS wcf1_siraca_race;
CREATE TABLE wcf1_siraca_race (
	raceID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(255) NOT NULL,
    startTime INT(10) NOT NULL,
    availableSlots INT(10) NOT NULL
);

DROP TABLE IF EXISTS wcf1_siraca_participation;
CREATE TABLE wcf1_siraca_participation (
	participationID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	raceID INT(10) NOT NULL,
	userID INT(10) NOT NULL,
	type TINYINT NOT NULL,
    /* ListType */
    listType TINYINT NOT NULL,
    /* Local position in list (titular and waiting list are separated) */
    position INT(10) NOT NULL,
    registrationTime INT(10) NOT NULL,
    /* Can be null */
    presenceTime INT(10)
);