<?php

require_once('helper/authhelper.php');

$heeftGedrukt = FALSE;
$foutWachtwoord = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $heeftGedrukt = TRUE;
    if (
        isset($_POST["gebruikersnaam"]) && (strlen($_POST["gebruikersnaam"]) > 0)
        && isset($_POST["wachtwoord"]) && (strlen($_POST["wachtwoord"]) > 0)
    ) {
        if (login($_POST['gebruikersnaam'], $_POST['wachtwoord'])) {
            header('Location: ingelogdeUser.php'); /* Stuur de browser naar ingelogdeUser.php */
        } else {
            $foutWachtwoord = true;
        }
    }
}
?>

<!DOCTYPE html>
<html>


    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="stijl.css"/>
        <title>Home TZT</title>
    </head>
    <body>
        <div>
            <div id="logo">
                <ul>
                    <li><a href="index.php" title="logo"><IMG SRC="Logo TZT.png" ALT="logo TZT" WIDTH="200" HEIGHT="74"></a>
                    </li>

                </ul>
            </div>
        </div>
        <div id="menu">
            <ul>
                <li><a href="index.php" title="Home">Home</a></li>
                <li><a href="onze diensten" title="Onze diensten">Onze diensten</a></li>
                <li><a href="mijn pakket" title="Mijn Pakket">Mijn Pakket</a></li>
                <li><a href="contact.php" title="Contact">Contact</a></li>
                <li><a href="treinkoerierStart.php" title="Treinkoerier">Treinkoerier</a></li>

            </ul>
            <span id="inlogscherm">

                <form id="InlogForm" method="post" action="inlogscherm.php">

                    <label for "gebruikersnaam">Gebruikersnaam:</label>
                    <input type="text" name="gebruikersnaam" value="<?php if ($heeftGedrukt == TRUE) {
                        print_r($_POST["gebruikersnaam"]);
                    } ?>">
                    <?php if ($heeftGedrukt == TRUE && (!isset($_POST["gebruikersnaam"]) || (strlen($_POST["gebruikersnaam"]) == 0))) {
                        ?><span id="foutmelding"><?php print ("Dit is een verplicht veld!") ?>
                        </span><?php } ?> <br/><br/>

                    <label for "wachtwoord">Wachtwoord:</label>
                    <input type="text" name="wachtwoord" value="<?php if ($heeftGedrukt == TRUE) {
                        print_r($_POST["wachtwoord"]);
                    } ?>">
                    <?php if ($heeftGedrukt == TRUE && (!isset ($_POST["wachtwoord"]) || (strlen($_POST["wachtwoord"]) == 0))) {

                        ?><span id="foutmelding"><?php print ("Dit is een verplicht veld!") ?>
                        </span><?php } ?><br/><br/>
                    <?php if ($foutWachtwoord) {
                        echo '<span id = "foutwachtwoord" > Dit is een onbekende combinatie van gebruikersnaam en wachtwoord </span ><br />';
                    } ?>
                    <span id="tekstWachtwoordVergeten" href="" method="get" action="verwerk1.php">
                        Wachtwoord vergeten? Klik hier</span><br/>
                    <br/>
                    <input id="inloggen2" type="submit" value="Inloggen">
                </form>
            </span>
        </div>
    </body>
</html>

