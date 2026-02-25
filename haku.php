<?php require 'paasysivulle.php'; ?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haku</title>
    <link rel="stylesheet" href="muntyyli.css">
    <link rel="stylesheet" href="navi.css">
</head>
<body class="haku">
    <!--Navikointi-->
    <?php 
        session_start();
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
<!--haku laatikot-->
    <form class="laatikko" method="GET">
        <input type="text" id="haku" name="haku" placeholder="Haku"><br><br>
        <label><input type="radio" name="tyyppi" value="nimi">Nimi</label>
        <br>
        <label><input type="radio" name="tyyppi" value="aines">Aines</label>
        <br><br>
        <input type="submit" name="painike">
    </form>
    <br>
    <br>
    <?php 
    if (isset($_GET['painike'])) {

        $haku = trim($_GET['haku']);
        $tyyppi = $_GET['tyyppi'] ?? "nimi";

        // Haku tyhjänä
        if ($haku === "") {

            $sql ="
            SELECT r.drinkki_id, r.nimi, r.drinkkilaji, r.valmistusohje,
                a.nimi AS aines, da.maara
            FROM resepti r
            LEFT JOIN drinkkiaines da ON r.drinkki_id = da.drinkki_id
            LEFT JOIN ainesosa a ON da.ainesosa_id = a.ainesosa_id
            WHERE hyvaksytty=1
            ORDER BY r.nimi
            ";

            $stmt = $yhteys->prepare($sql);
            $stmt->execute();

        }
        // haku nimellä
        elseif ($tyyppi === "nimi") {

            $sql = "
            SELECT r.drinkki_id, r.nimi, r.drinkkilaji, r.valmistusohje,
                a.nimi AS aines, da.maara
            FROM resepti r
            LEFT JOIN drinkkiaines da ON r.drinkki_id = da.drinkki_id
            LEFT JOIN ainesosa a ON da.ainesosa_id = a.ainesosa_id
            WHERE r.nimi LIKE ? 
                AND hyvaksytty=1
            ORDER BY r.nimi
            ";

            $hakusana = "%" . $haku . "%";
            $stmt = $yhteys->prepare($sql);
            $stmt->bind_param("s", $hakusana);
            $stmt->execute();

        }
        // Haku ainesosalla
        else {

            $sql = "
            SELECT r.drinkki_id, r.nimi, r.drinkkilaji, r.valmistusohje,
                a.nimi AS aines, da.maara
            FROM resepti r
            LEFT JOIN drinkkiaines da ON r.drinkki_id = da.drinkki_id
            LEFT JOIN ainesosa a ON da.ainesosa_id = a.ainesosa_id
            WHERE a.nimi LIKE ? 
                AND hyvaksytty=1
            ORDER BY r.nimi
            ";

            $hakusana = "%" . $haku . "%";
            $stmt = $yhteys->prepare($sql);
            $stmt->bind_param("s", $hakusana);
            $stmt->execute();
        }

        // Tulostetaan
        $tulos = $stmt->get_result();
        $edellinen = "";
        $edellinenOhje = "";

        while ($rivi = $tulos->fetch_assoc()) {
            //tulostaa edellisen reseptin ohjeet kun nimi vaihtuu
            if ($edellinen !== "" && $edellinen !== $rivi['nimi']) {
                echo "<strong>Valmistusohje:</strong><br>";
                echo nl2br(htmlspecialchars($edellinenOhje));
                echo "<hr>";
            }

            if ($edellinen !== $rivi['nimi']) {
                //tulostaa nimen, juoman ja einesosien ainesosien otsikkot
                echo "<strong>Nimi:</strong> " . htmlspecialchars($rivi['nimi']) . "<br>";
                echo "<strong>Juomalaji:</strong> " . htmlspecialchars($rivi['drinkkilaji']) . "<br>";
                echo "<strong>Ainesosat:</strong><br>";

                $edellinen = $rivi['nimi'];
                $edellinenOhje = $rivi['valmistusohje'];
            }
            //tulostaa ainesosan ja määrän
            if (!empty($rivi['aines'])) {
                echo "&nbsp;&nbsp;" . htmlspecialchars($rivi['aines']);
                if (!empty($rivi['maara'])) {
                    echo " " . htmlspecialchars($rivi['maara']);
                }
                echo "<br>";
            }
        }

        // viimeinen ohje
        if ($edellinen !== "") {
            echo "<strong>Valmistusohje:</strong><br>";
            echo nl2br(htmlspecialchars($edellinenOhje));
        }
    }
    ?>
</body>
</html>