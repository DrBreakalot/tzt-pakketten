<?php
require_once('helper/userhelper.php');

$heeftGedrukt = FALSE;
if ($_SERVER["REQUEST_METHOD"] == "POST"){
     $heeftGedrukt = TRUE;
     if (
                       isset($_POST["gebruikersnaam"]) && (strlen($_POST["gebruikersnaam"]) > 0)
                       && isset($_POST["wachtwoord"]) && (strlen($_POST["wachtwoord"]) > 0)){
          header('Location: ingelogdeUser.php'); /* Stuur de browser naar ingelogdeUser.php */
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
                Inloggen Treinkoerier
            </span>
        </div>
        <div id="menu">  
            <ul>
                <?php echo getMenu(); ?>

            </ul>
            <div id="inlogscherm" class="midden">

                <form id="InlogForm" method="post" action="inlogschermtreinkoerier.php">
                    
                    <label for "gebruikersnaam">Gebruikersnaam:</label>
                    <input type="text" name="gebruikersnaam" value="<?php if ($heeftGedrukt == TRUE) {
                        print_r($_POST["gebruikersnaam"]);
                    }?>">
                    <?php if ($heeftGedrukt == TRUE && (!isset($_POST["gebruikersnaam"]) || (strlen($_POST["gebruikersnaam"]) == 0))) {
                        ?><span id="foutmelding"><?php print ("Dit is een verplicht veld!") ?>
                    </span><?php } ?> <br/><br/>
                    
                    <label for "wachtwoord">Wachtwoord:</label>
                    <input type="password" name="wachtwoord" value="<?php if ($heeftGedrukt == TRUE) {
                        print_r($_POST["wachtwoord"]);
                    }?>">
                    <?php if ($heeftGedrukt == TRUE && (!isset ($_POST["wachtwoord"]) || (strlen($_POST["wachtwoord"]) == 0))) {
                        ?><span id="foutmelding"><?php print ("Dit is een verplicht veld!") ?>
                    </span><?php } ?><br/><br/>

                    <span id="tekstWachtwoordVergeten" href="">
                        Wachtwoord vergeten? Klik hier</span><br/>
                    <br/>
                    <input id="inloggen2" type="submit" value="Inloggen">
                </form>
            </div>
        </div>
    </body>
</html>

