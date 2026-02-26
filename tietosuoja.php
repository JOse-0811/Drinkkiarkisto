<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Tietosuoja</title>
    <link rel="stylesheet" href="muntyyli.css">
</head>
<body class="tietosuoja">
    <?php
    include_once("yhteys.php");
    if (!isset($_SESSION['rooli'])) {
        include "naviGuest.php";
    }
    elseif($_SESSION['rooli']==1){
        include "naviAdmin.php";
    }
    else {
            include "naviUser.php";
    }  
    ?>
    <h3>Tietosuojaseloste - drinkkiarkiston käytäjälle</h3>
    <?php

    echo "<h4>Rekisterin nimi</h4>";

    echo "<p class= 'teksti'>Drinkkiarkiston käyttäjärekisteri.</p>";

    echo "<h4>Rekisterinpitäjä</h4>";

    echo "<p class= 'teksti'>Espoon seudun koulutuskuntayhtymä Omnia <br> Upseerinkatu 11, 02600 Espoo</p>";

    echo "<h4>Yhteyshenkilö</h4>";

    echo "<p class= 'teksti'>Josette Collin <br>000000000 <br>josette.collin@omnia.fi</p>";

    echo "<h4>Henkilötietojen käsittelyn tarkoitus</h4>";

    echo "<p class= 'teksti'>Drinkkiarkisto on ohjelmistokehittäjän ammattitutkinnon loppuprojekti. <br>Henkilötietoja käsitellään käyttäjien rekisteröintiä ja kirjautumista varten.</p>";

    echo "<h4>Kerättävät tiedot</h4>";

    echo "<p class= 'teksti'>käyttäjätunnus <br>salasana (hashattuna) <br>sähköpostiosoite <br>käyttäjärooli</p>";

    echo "<h4>Tietojen luovutus ja suojaus</h4>";

    echo "<p class= 'teksti'>Tietoja ei luovuteta ulkopuolisille. <br>Tiedot on suojattu ja salasana tallennetaan salattuna.</p>";

    echo "<h4>Rekisteröidyn oikeudet</h4>";

    echo "<p class= 'teksti'>Käyttäjällä on oikeus tarkistaa, korjata ja poistaa omat tietonsa ottamalla yhteyttä rekisterinpitäjään.</p>";

    ?>
</body>
</html>