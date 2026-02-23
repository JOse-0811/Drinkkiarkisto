<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muokaa resepti</title>
    <link rel="stylesheet" href="muntyyli.css">
    <link rel="stylesheet" href="navi.css">
</head>
<body>
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

<ul>
    <h2>Muokaa Reseptejä</h2>
    <?php
    $valittu=null;
 if (isset($_POST["talenna"])) {
            $id = intval($_POST['id']);
            $nimi= $yhteys->real_escape_string($_POST['nimi']);
            $laji= $yhteys->real_escape_string($_POST['juoma']);
            $maara= $_POST['maara'];
            $ohje= $yhteys->real_escape_string($_POST['ohjeet']);
            $aines= $_POST['aines'];

                
            $yhteys->query("UPDATE resepti SET nimi='$nimi', drinkkilaji='$laji', valmistusohje='$ohje' WHERE drinkki_id=$id");

            $yhteys->query("DELETE FROM drinkkiaines WHERE drinkki_id=$id");

            for($i=0;$i<count($aines);$i++){
                if(!empty($aines[$i]) && !empty($maara[$i])){
                    $yhteys->query("INSERT INTO drinkkiaines (drinkki_id, ainesosa_id, maara) VALUES ($id, $aines, '$maara')");
                }
            }
            
        $valittu=$id;
    }
    ?>
<!--valitse drinkki-->
    <ul class="vasen">
        <?php
        $sql=$yhteys->query("SELECT nimi, drinkki_id FROM resepti ORDER BY nimi");

        while ($rivi = $sql->fetch_assoc()) {
        $id=$rivi['drinkki_id'];
        $nimi=htmlspecialchars($rivi['nimi']);
        echo "<form method='POST'>";
        echo $nimi;
        echo "<input type='hidden' name='id' value='$id'>";
        echo "<button type='submit' name='valitse'>Muokaa</button>";
        echo "</form>";

        }
        ?>
    </ul>
<!--muokaa reseptiä-->
<ul class="oikea">
<?php if($valittu): ?>
    <?php
        if($valittu){
            $tiedot = $yhteys
            ->query("SELECT nimi, drinkkilaji, valmistusohje FROM resepti WHERE drinkki_id=$valittu")
            ->fetch_assoc();
        }
        $teito=htmlspecialchars($tiedot['nimi']);

            $aineset = [];
            $tulos = $yhteys->query("SELECT ainesosa_id, nimi FROM ainesosa");
            while ($rivi = $tulos->fetch_assoc()) {
            $aineset[] = $rivi;
            }
        ?>
            <form method="post">
            <input type="hidden" name="id" value="<?= $valittu ?>">
            <input class="nimi" type="text" name="nimi" value="<?= htmlspecialchars($tiedot['nimi']) ?>">
            <br>
            <br>
            <input class="laji" type="text" name="juoma" value="<?= htmlspecialchars($tiedot['drinkkilaji']) ?>">
            <br>
            <br>
            <strong>Raaka-aines:</strong>
            <strong class="maara">Raaka aineen määrä.</strong><br>

            <!-- 3 raaka-aineriviä -->
            <?php for ($i = 0; $i < 3; $i++): ?>

            <select name="aines[]">
                <option value="">valitse</option>

                <?php foreach ($aineset as $a): ?>
                    <option value="<?= $a['ainesosa_id'] ?>">
                        <?= $a['nimi'] ?>
                    </option>
                <?php endforeach; ?>

            </select>

            <input class="maara" type="text" name="maara[]" placeholder="esim. 4 cl"><br><br>

            <?php endfor; ?>

            <!--Ohjeet-->
            <textarea class="ohje" name="ohjeet"><?= htmlspecialchars($tiedot['valmistusohje']) ?></textarea>
            <br>
            <br>
            <!--painike-->
            <button class="sivu" type="submit" name="talenna">Tallenna</button>
            </form>
    <?php endif; ?>
    </ul>
</ul>
</body>
</html>