<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>AinensLista</title>
    <link rel="stylesheet" href="muntyyli.css">
    <link rel="stylesheet" href="navi.css">
</head>
<body class="rekisteri">
        <?php 
        //navikointi ja yhteys
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
	<h2>Rekistöröidy drinkkiarkiston käytähäksi</h2>
<!--laatikot -->
    <form method="post">
        <input type="text" id="kaytaja" name="kaytaja" placeholder="Käytäjätunnus"><br><br>
        <input type="password" id="salasana" name="salasana" placeholder="salasana"><br><br>
        <input type="text" id="sahkoposti" name="sahkoposti" placeholder="sähköpostiosoite"><br><br>
        <button type="submit" name="rekisteri">rekistöröidy</button>
    </form>
    <br>
    <p>Rekisteröityessäsi drinkkiarkiston käyttäjäksi hyväksyt henkilötietojesi käsitelyehdot.</p>
    <p>Lue täältä <a href="tietosuoja.php">drinkkiarkiston tietosuojaseloste</a>.</p>
    <?php


        if (isset($_POST["rekisteri"])) {

            $kaytanimi= $_POST['kaytaja'];
            $salasana= $_POST['salasana'];
            $sahkoposti= $_POST['sahkoposti'];
            // Salasana hashataan PASSWORD_DEFAULT-algoritmilla (eri hash joka kerta)
            $salattu= password_hash($salasana, PASSWORD_DEFAULT);
            
            // Tarkistaa, että kaikki lomakkeen kentät on täytetty
            if (empty($kaytanimi) || empty($salattu) || empty($sahkoposti)) {
                echo "<p class= 'virhe'>Täytä kaikki kentät</p>";
            }
            //tarkistaa, onko käyttäjä jo olemassa
            else{
                $haku= "SELECT * FROM kayttaja WHERE käyttäjätunnus = '$kaytanimi'";
                $tulos= $yhteys -> query($haku);

                if ($tulos -> num_rows > 0){
                    echo "<p class='virhe'>käyttäjä on jo olemassa</p>";
                }
                else{
                    $lisays= "INSERT INTO kayttaja (käyttäjätunnus, salasana, sähköpostiosoite, rooli)VALUES ('$kaytanimi', '$salattu', '$sahkoposti', '0')";
                    $tulos= $yhteys -> query($lisays);
                
                if ($tulos == TRUE){
                    echo "<p class= 'onnistu'>rekisteröinti onnistui</p>";
                }
                else{
                    echo "<p class= 'virhe'>Rekisteröinti epäonnistui</p>";
                }
                }
            }

        }
        $yhteys-> close();

    ?>
</body>
</html>