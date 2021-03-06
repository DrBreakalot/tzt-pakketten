<?php
require_once('helper/userhelper.php');

$heeftGepost = FALSE;
if ($_SERVER["REQUEST_METHOD"] == "POST"){
     $heeftGepost = TRUE;
     if (
                       isset($_POST["voornaam"]) && (strlen($_POST["voornaam"]) > 0) 
                        && isset($_POST["achternaam"]) && (strlen($_POST["achternaam"]) > 0) 
                        && isset($_POST["straat"]) && (strlen($_POST["straat"]) > 0) 
                        && isset($_POST["huisnummer"]) && (strlen($_POST["huisnummer"]) > 0)
                        && isset($_POST["postcode"]) && (strlen($_POST["postcode"]) > 0)
                        && isset($_POST["woonplaats"]) && (strlen($_POST["woonplaats"]) > 0)
                        && isset($_POST["e-mailadres"]) && (strlen($_POST["e-mailadres"]) > 0)
                        && isset($_POST["telefoonnummer"]) && (strlen($_POST["telefoonnummer"]) > 0)
                        && isset($_POST["gebruikersnaam"]) && (strlen($_POST["gebruikersnaam"]) > 0)
                        && isset($_POST["wachtwoord"]) && (strlen($_POST["wachtwoord"]) > 0)){
        
        header('Location: registreer.php'); /* Stuur de browser naar registreer.php */
  
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
                Registratieformulier Treinkoerier
            </span>

        </div>
        <div id="menu">  
            <ul>
                <?php echo getMenu(); ?>
            </ul>
            <div class="midden" id="middenRegistratieScherm">
                <form id="registratieForm" method="post" action="Registratieformulier.php">
                    
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
                    
                    <label for "telefoonnummer">Telefoonnummer</label>
                    <input type="text" name="telefoonnummer" value="<?php if ($heeftGepost == TRUE) {
                            print_r($_POST["telefoonnummer"]);
                        }?>">
                        <?php if ($heeftGepost == TRUE && (!isset($_POST["telefoonnummer"])|| (strlen($_POST["telefoonnummer"]) == 0))){
                            ?><span id = "foutmelding"><?php print ("Dit is een verplicht veld!") ?>
                            </span><?php } ?> <br/><br/>
                    
                    <label for "gebruikersnaam">Gebruikersnaam</label>
                    <input type="text" name="gebruikersnaam" value="<?php if ($heeftGepost == TRUE) {
                            print_r($_POST["gebruikersnaam"]);
                        }?>">
                        <?php if ($heeftGepost == TRUE && (!isset($_POST["gebruikersnaam"])|| (strlen($_POST["gebruikersnaam"]) == 0))){
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




