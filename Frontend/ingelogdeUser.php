<?php
require_once 'helper/authhelper.php';

if (array_key_exists('loguit', $_GET)) {
    if ($_GET['loguit']) {
        logout();
        header('Location: index.php');
    }
}

$user = requireUser();
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
                Welkom <?php echo $user['name'] ?>!
            </span>
        </div>
        <div id="menu">
            <ul>
                <li><a href="index.php" title="Home">Home</a></li>
                <li><a href="onze diensten" title="Onze diensten">Onze diensten</a></li>
                <li><a href="mijn pakket" title="Mijn Pakket">Mijn Pakket</a></li>
                <li><a href="contact.php" title="Contact">Contact</a></li>
                <li><a href="treinkoerierStart.php" title="Treinkoerier">Treinkoerier</a></li>
                <li><a href="ingelogdeUser.php?loguit=true" title="Uitloggen">Uitloggen</a></li>
            </ul>
            <div id="midden">
                <a href="aanbiedenpakket.php">
                    <input id="pakketVersturen" type="button" value="Een pakket versturen" />
                </a>
                <a href="Registratieformulier.php">
                    <input id="gegevensWijzigen" type="button" value="Mijn gegevens wijzigen" />
                </a>
                </a>

            </div>
        </div>
        <?php
        // put your code here
        ?>

    </body>
</html>


