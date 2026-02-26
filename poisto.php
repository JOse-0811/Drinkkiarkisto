<?php require 'paasyadmi.php'; ?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poista resepti</title>
    <link rel="stylesheet" href="muntyyli.css">
    <link rel="stylesheet" href="navi.css">
</head>
<body class="poisto">
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
    <br>
    <br>
    <ul class="poistolaatikko">
    <h2>Poista drinkki</h2>
    <?php
    
    if(isset($_POST["poista"])){
        $poisto= intval($_POST['poista']);
        //kun poisto nappia painetaan, poistetaan drinkki kokonaan
        $yhteys->query("DELETE FROM drinkkiaines WHERE drinkki_id = '$poisto'");

        $poistosql = "DELETE FROM resepti WHERE drinkki_id = '$poisto'";
        if($yhteys->query($poistosql)) {
            echo "<p class='ilmoitus'>Resepti poistettu!</p>";
            header("refresh:3");
        }
        else {
            echo "<p class='ilmoitus'>Virhe poiston yhteydessä </p>";
            header("refresh:3");
        }
    }
        //haetaan drinkkejä joiden hyväksyntä arvo on 1
        $sql = $yhteys->query("SELECT drinkki_id, nimi FROM resepti WHERE hyvaksytty=1 ORDER BY nimi");

        while ($rivi = $sql->fetch_assoc()) {
            $id=$rivi['drinkki_id'];
            $nimi=htmlspecialchars($rivi['nimi']);
            //tulostetaan sivustolle
            echo "<li>";
            echo "$nimi";
            echo "<form method='POST'>";
            echo "<button class=nappip type='submit' name='poista' value='$id'>Poista</button>";
            echo "</form>";
            echo "<br>";
            echo "</li>";
        }
    ?>
    </ul>
</body>
</html>