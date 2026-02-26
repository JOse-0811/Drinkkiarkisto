<?php require 'paasyadmi.php'; ?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hyväksy reseptit</title>
    <link rel="stylesheet" href="muntyyli.css">
    <link rel="stylesheet" href="navi.css">
</head>
<body class="hyvaksy">
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
    <ul class="hyvaksyl">
    <h2 class="o">Hyväksy tai hylkää</h2>
    <?php
        //hyväksytty nappi
        if(isset($_POST['hyvaksy'])){
            $hyvaksy= intval($_POST['hyvaksy']);
            
            // päivittää reseptin hyväksytyksi (hyvaksytty = 1)
            if($yhteys->query("UPDATE resepti SET hyvaksytty = 1 WHERE drinkki_id='$hyvaksy'")){
                echo "<p class='h'>Resepti hyväksytty!</p>";
                header("refresh:3");
            }
            else {
                echo "<p class='h'>Virhe hyväksynnän yhteydessä </p>";
                header("refresh:3");
            }
        }
        //hylätty nappi
        elseif(isset($_POST['hylkaa'])){
            $hylkaa= intval($_POST['hylkaa']);
            // kun hylkää nappia painetaan, resepti poistetaan tietokannasta
            $yhteys->query("DELETE FROM drinkkiaines WHERE drinkki_id='$hylkaa'");

            $hylkaasql="DELETE FROM resepti WHERE drinkki_id='$hylkaa'";
            if($yhteys->query($hylkaasql)){
                echo "<p class='h'>Resepti hylätty!</p>";
                header("refresh:3");
            }
            else {
                echo "<p class='h'>Virhe hylkäämisen yhteydessä </p>";
                header("refresh:3");
            }
        }
        
        $sql= $yhteys->query("SELECT nimi, hyvaksytty, drinkki_id FROM resepti WHERE hyvaksytty=0 ORDER BY nimi");
        while ($rivi = $sql->fetch_assoc()) {
            $nappula=$rivi['drinkki_id'];
            
            $nimi=htmlspecialchars($rivi['nimi']);
            // Tulostetaan hyväksyntää odottavat reseptit sivulle
            echo "<li>";
            echo "<p class=p>$nimi</p>";
            echo "<form method='POST'>";
            echo "<button class=painah type='submit' name='hyvaksy' value='$nappula'>hyväksy</button>";
            echo "<button class=painav type='submit' name='hylkaa' value='$nappula'>hylkää</button>";
            echo "</form>";
            echo "<br>";
            echo "</li>";
        }
    ?>
    </ul>
</body>
</html>