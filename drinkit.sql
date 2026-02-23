CREATE TABLE Kayttaja(
kaytaja_id INT AUTO_INCREMENT,
käyttäjätunnus VARCHAR(20) NOT NULL,
salasana VARCHAR(220) NOT NULL,
sähköpostiosoite VARCHAR(100),
rooli VARCHAR(10),
PRIMARY KEY(kaytaja_id)
);

CREATE TABLE resepti(
drinkki_id INT AUTO_INCREMENT,
nimi VARCHAR(20),
valmistusohje TEXT,
drinkkilaji VARCHAR(20),
hyvaksytty TINYINT(1) NOT NULL DEFAULT 0,
PRIMARY KEY(drinkki_id)
);

CREATE TABLE ainesosa(
ainesosa_id INT AUTO_INCREMENT,
nimi VARCHAR (20),
PRIMARY KEY(ainesosa_id)
);

CREATE TABLE drinkkiaines(
drinkki_id INT,
ainesosa_id INT,
maara INT,
FOREIGN KEY (drinkki_id) REFERENCES resepti(drinkki_id),
FOREIGN KEY (ainesosa_id) REFERENCES ainesosa(ainesosa_id)
);