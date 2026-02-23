<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Käytäjän poisto</title>
    <link rel="stylesheet" href="muntyyli.css">
    <link rel="stylesheet" href="navi.css">
</head>
<body class="kautajapois">

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
<ul class="poisk">
    <h2>Poista käyttäjä</h2>
    <?php
    if(isset($_POST["poista"])){
        $poisto= intval($_POST['poista']);
        //poistetaan käytäjä kokonaan
        $poistosql ="DELETE FROM kayttaja WHERE kaytaja_id = '$poisto'";
        if($yhteys->query($poistosql)) {
            echo "<p class='ilmoitus'>Käyttäjä poistettu!</p>";
            header("refresh:3");
        }
        else {
            echo "<p class='ilmoitus'>Virhe käyttäjän poiston yhteydessä </p>";
            header("refresh:3");
        }
    }
        //haetaan käytäjiä joiden rooli on 0
        $sql = $yhteys->query("SELECT kaytaja_id, käyttäjätunnus FROM kayttaja WHERE rooli=0 ORDER BY käyttäjätunnus");

        while ($rivi = $sql->fetch_assoc()) {
            $id=$rivi['kaytaja_id'];
            $nimi=htmlspecialchars($rivi['käyttäjätunnus']);
            //printataan sivustolle
            echo "<li>";
            echo "$nimi";
            echo "<form method='POST'>";
            echo "<button class=nappikp type='submit' name='poista' value='$id'>Poista</button>";
            echo "</form>";
            echo "<br>";
            echo "</li>";
        }
    ?>
</ul>
</body>
</html>