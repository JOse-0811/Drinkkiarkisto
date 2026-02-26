<?php require 'paasysivulle.php'; ?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>AinensLista</title>
    <link rel="stylesheet" href="muntyyli.css">
    <link rel="stylesheet" href="navi.css">
</head>
<body class="aines">
<!--Navikointi-->
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
    	<h2>Lisää aines</h2>
<!--Napit-->
    <form method="post">
        <input type="text" id="nimi" name="nimi" placeholder="Kirjoita aines">
        <button type="submit" name="lisaa">Lisää</button>
    </form>
    <?php
    //Päästä sivulle vain rooli 1 tai 0


    $viesti = "";

    //lisäys ja tarkistus
    if (isset($_POST['lisaa'])) {

        $aines = trim($_POST['nimi']);

        // tekstikenttä ei saa olla tyhjä
        if (empty($aines)) {
            $viesti = "Lisää kenttään aines.";
        } 
        else {
            // tarkistus onko aines jo lisätty
            $stmt = $yhteys->prepare("SELECT ainesosa_id FROM ainesosa WHERE nimi = ?");
            $stmt->bind_param("s", $aines);
            $stmt->execute();
            $tulos = $stmt->get_result();

            if ($tulos->num_rows > 0) {
                //aines on jo
                $viesti = "Aines on lisätty aiemmin.";
            } 
            else {
                // uusi aines lisäys
                $stmt = $yhteys->prepare("INSERT INTO ainesosa (nimi) VALUES (?)");
                $stmt->bind_param("s", $aines);
                $stmt->execute();
                $viesti = "Aines lisätty onnistuneesti.";
            }
        }
    }

    //Viesti käytäjälle näkyy
    if (!empty($viesti)) {
        echo "<p>$viesti</p>";
    }

    //kaikki ainekset tulostetaan
    echo "<h3>Ainekset</h3>";
    echo "<ul>";

    $tulos = $yhteys->query("SELECT nimi FROM ainesosa ORDER BY nimi");
    while ($rivi = $tulos->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($rivi['nimi']) . "</li>";
    }

    echo "</ul>";
    ?>

</body>
</html>