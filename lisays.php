<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lisäys</title>
    <link rel="stylesheet" href="muntyyli.css">
    <link rel="stylesheet" href="navi.css">
</head>
<body class="lisays">
<!--navikointi-->
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
            
        $aineset = [];
        $tulos = $yhteys->query("SELECT ainesosa_id, nimi FROM ainesosa");
        while ($rivi = $tulos->fetch_assoc()) {
            $aineset[] = $rivi;
        }
    ?>
    <br>
    <br>
    <form class="laatikkol" action="" method="post">
        <br>
        <h4>Ehdota drinkkiä</h4>
        <input class="sivu" type="text" id="nimi" name="nimi" placeholder="Nimi"><br><br>
        <input class="sivu" type="text" id="juoma" name="juoma" placeholder="Juomalaji"><br><br>
        <table >
            <tbody id="taulu" class="taulu">
                <tr class="raaka">
                    <th>Raaka-aines:</th>
                    <th>Raaka aineen määrä.</th>
                </tr>
                <tr id="kopio">
                    <td id="ainel">
                        <select  name="aines[]" >
                            <option value="">valitse</option>

                            <?php foreach ($aineset as $a): ?>
                                <option value="<?= $a['ainesosa_id'] ?>">
                                    <?= $a['nimi'] ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </td>
                    <td id="maaral">
                        <input class="maara" type="text" name="maara[]"  placeholder="esim. 4 cl"><br><br>
                    </td>
                    <td >
                        <button id="add" class="testi" type="button">+</button>
                    </td>
                </tr>
            </tbody>
        </table>

<!-- 3 raaka-aineriviä -->

<!--Ohjeet-->
        <textarea class="sivu" name="ohjeet" placeholder="Ohjeet"></textarea><br><br>
<!--painike-->
        <button class="nappi" type="submit" name="uusiaines">Lisää resepti</button>
        <br>
        <br>
    </form>
    <?php
        if (isset($_POST["uusiaines"])) {

            $nimi= $_POST['nimi'];
            $laji= $_POST['juoma'];
            $maara= $_POST['maara'];
            $ohje= $_POST['ohjeet'];
            $aines= $_POST['aines'];
            //tarkistaa ettei kentät ole tyhjiä
            if(empty($nimi) || empty($laji) || empty($ohje)){
                echo "<p class='tvirhe'>kenttät pitää täytää</p>";
                
            } else {
            //tarkistaa onko jo olemassa
            $stmt = $yhteys->prepare("SELECT drinkki_id FROM resepti WHERE nimi = ?");
            $stmt->bind_param("s", $nimi);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows > 0) {
            //resepti on jo
                echo "<p class='tvirhe'>resepti on lisätty aiemmin.</p>";
            
                
            } else {
            $onkomaara= FALSE;
            // Varmistaa, että ainakin yksi raaka-aine ja määrä on täytetty
            foreach($maara as $m){
                if(!empty(trim($m))){
                    $onkomaara= TRUE;
                    break;
                }
            }
            if(!$onkomaara){
                echo "<p class='tvirhe'>Ainakin yksi raaka aine ja maara pitää täytää</p>";
               
            
            }
            // Asetetaan hyväksytty-arvo käyttäjän roolin mukaan (admin = 1, muu = 0)
            if (isset($_SESSION['rooli']) && $_SESSION['rooli'] === 'admin') {
                $hyvaksytty = 1;
            } else {
                $hyvaksytty = 0;
            }
            //lisää resepti
            $stmt = $yhteys->prepare(
                "INSERT INTO resepti (nimi, valmistusohje, drinkkilaji, hyvaksytty) VALUES (?, ?, ?, ?)"
            );
            
            $stmt->bind_param("sssi", $nimi, $ohje, $laji, $hyvaksytty);
            $stmt->execute();
            
            $drinkki_id= $yhteys-> insert_id;

            //lisää raaka aineet
            for ($i = 0; $i < count($aines); $i++) {
                if (!empty($aines[$i]) && !empty(trim($maara[$i]))) {

                    $stmt2 = $yhteys->prepare(
                        "INSERT INTO drinkkiaines (drinkki_id, ainesosa_id, maara)
                        VALUES (?, ?, ?)"
                    );
                    $stmt2->bind_param("iis", $drinkki_id, $aines[$i], $maara[$i]);
                    $stmt2->execute();
                }
            }
            echo "<p class='tonnistu'>Uusi resepti lisätty.</p>";
            }
            }
        }
    ?>
    <script src="lisaus.js"></script>
</body>
</html>