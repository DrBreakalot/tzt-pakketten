<?php

require_once 'helper/userhelper.php';
require_once 'helper/addresshelper.php';

$heeftGepost = FALSE;
$error = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $heeftGepost = TRUE;
    if (
        isset($_POST["voornaam"]) && (strlen($_POST["voornaam"]) > 0)
        && isset($_POST["achternaam"]) && (strlen($_POST["achternaam"]) > 0)
        && isset($_POST["straat"]) && (strlen($_POST["straat"]) > 0)
        && isset($_POST["huisnummer"]) && (strlen($_POST["huisnummer"]) > 0)
        && isset($_POST["postcode"]) && (strlen($_POST["postcode"]) > 0)
        && isset($_POST["woonplaats"]) && (strlen($_POST["woonplaats"]) > 0)
        && isset($_POST["e-mailadres"]) && (strlen($_POST["e-mailadres"]) > 0)
        && isset($_POST["wachtwoord"]) && (strlen($_POST["wachtwoord"]) > 0)
    ) {

        $response = registerCustomer($_POST['voornaam'] . ' ' . $_POST['achternaam'], $_POST['e-mailadres'], $_POST['wachtwoord'], createAddress($_POST['straat'] . ' ' . $_POST['huisnummer'], $_POST['woonplaats'], $_POST['postcode']));
        if ($response['status'] === 201) {
            header('Location: registreer.php'); /* Stuur de browser naar registreer.php */
        } else if ($response['status'] === 409) {
            $error = 'Er bestaat al een gebruiker met dit emailadres';
        } else {
            $error = $response['body']['error'];
        }

    }
}
?>
<!DOCTYPE html>
<html>



    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="stijl.css" />
        <title>Home TZT</title>
    </head>
    <body>
        <div>
            <div id="logo">
                <ul>  
                    <li><a href="index.php" title="logo"><IMG SRC="Logo TZT.png" ALT="logo TZT" WIDTH="200" HEIGHT="74"></a></li>  

                </ul>  

            </div>
            <span id="welkom">
                <a href=""></a>
                Registratieformulier
            </span>
        </div>
        <div id="menu">  
            <ul>  
                <li><a href="index.php" title="Home">Home</a></li>  
                <li><a href="onze diensten" title="Onze diensten">Onze diensten</a></li>  
                <li><a href="mijn pakket" title="Mijn Pakket">Mijn Pakket</a></li>  
                <li><a href="contact.php" title="Contact">Contact</a></li>
                <li><a href="treinkoerierStart.php" title="Treinkoerier">Treinkoerier</a></li>

            </ul>
            <div id="middenRegistratieScherm">

                <?php
                if ($error) {
                    echo '<span id="foutmelding">' . $error . '</span>';
                }
                ?>

                <form id="registratieForm" method="post" action="registratieformulier.php">
                    
                    <label for "voornaam">Naam</label>
                    <input type="text" name="voornaam" value="<?php if ($heeftGepost == TRUE) {
                            print_r($_POST["voornaam"]);
                        }?>">
                        <?php if ($heeftGepost == TRUE && (!isset($_POST["voornaam"])|| (strlen($_POST["voornaam"]) == 0))){
                            ?><span id = "foutmelding"><?php print ("Dit is een verplicht veld!") ?>
                            </span><?php } ?> <br/><br/>
                            
                    <label for "achternaam">Achternaam</label>
                    <input type="text" name="achternaam" value="<?php if ($heeftGepost == TRUE) {
                            print_r($_POST["achternaam"]);
                        }?>">
                        <?php if ($heeftGepost == TRUE && (!isset($_POST["achternaam"])|| (strlen($_POST["achternaam"]) == 0))){
                            ?><span id = "foutmelding"><?php print ("Dit is een verplicht veld!") ?>
                            </span><?php } ?> <br/><br/>
                    
                    <label for "straat">Straat</label>
                    <input type="text" name="straat" value="<?php if ($heeftGepost == TRUE) {
                            print_r($_POST["straat"]);
                        }?>">
                        <?php if ($heeftGepost == TRUE && (!isset($_POST["straat"])|| (strlen($_POST["straat"]) == 0))){
                            ?><span id = "foutmelding"><?php print ("Dit is een verplicht veld!") ?>
                            </span><?php } ?> <br/><br/>
                    
                    <label for "huisnummer">Huisnummer</label>
                    <input type="text" name="huisnummer" value="<?php if ($heeftGepost == TRUE) {
                            print_r($_POST["huisnummer"]);
                        }?>">
                        <?php if ($heeftGepost == TRUE && (!isset($_POST["huisnummer"])|| (strlen($_POST["huisnummer"]) == 0))){
                            ?><span id = "foutmelding"><?php print ("Dit is een verplicht veld!") ?>
                            </span><?php } ?> <br/><br/>
                    
                    <label for "postcode">Postcode</label>
                    <input type="text" name="postcode" value="<?php if ($heeftGepost == TRUE) {
                            print_r($_POST["postcode"]);
                        }?>">
                        <?php if ($heeftGepost == TRUE && (!isset($_POST["postcode"])|| (strlen($_POST["postcode"]) == 0))){
                            ?><span id = "foutmelding"><?php print ("Dit is een verplicht veld!") ?>
                            </span><?php } ?> <br/><br/>
                    
                    <label for "woonplaats">Woonplaats</label>
                    <input type="text" name="woonplaats" value="<?php if ($heeftGepost == TRUE) {
                            print_r($_POST["woonplaats"]);
                        }?>">
                        <?php if ($heeftGepost == TRUE && (!isset($_POST["woonplaats"])|| (strlen($_POST["woonplaats"]) == 0))){
                            ?><span id = "foutmelding"><?php print ("Dit is een verplicht veld!") ?>
                            </span><?php } ?> <br/><br/>
                    
                    <label for "e-mailadres">E-mailadres</label>
                    <input type="text" name="e-mailadres" value="<?php if ($heeftGepost == TRUE) {
                            print_r($_POST["e-mailadres"]);
                        }?>">
                        <?php if ($heeftGepost == TRUE && (!isset($_POST["e-mailadres"])|| (strlen($_POST["e-mailadres"]) == 0))){
                            ?><span id = "foutmelding"><?php print ("Dit is een verplicht veld!") ?>
                            </span><?php } ?> <br/><br/>
                    
                    <label for "wachtwoord">Wachtwoord</label>
                    <input type="password" name="wachtwoord" value="<?php if ($heeftGepost == TRUE) {
                            print_r($_POST["wachtwoord"]);
                        }?>">
                        <?php if ($heeftGepost == TRUE && (!isset($_POST["wachtwoord"])|| (strlen($_POST["wachtwoord"]) == 0))){
                            ?><span id = "foutmelding"><?php print ("Dit is een verplicht veld!") ?>
                            </span><?php } ?> <br/><br/>
                    
                    <input id="registreren2" type="submit" value="Registreer!">
                </form>
            </div>

        </div>
    </body>
</html>


