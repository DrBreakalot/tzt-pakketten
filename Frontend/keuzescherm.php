<?php
require_once('helper/userhelper.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="stijl.css" />
        <title>Maak uw keuze  -  TZT</title>
    </head>
    <body>
        <div>
            <div id="logo">
                <ul>
                    <li><a href="logo" title="logo"><IMG SRC="Logo TZT.png" ALT="logo TZT" WIDTH="200" HEIGHT="74"></a></li>

                </ul>

            </div>
            <span id="welkom">
                <a href=""></a>
                Welkom username!
            </span>

            <span id="rcorners2">
                <a href=""><img src="magnifier_16.png" width="16" height="16"></a>
                search
            </span>
        </div>
        <div id="menu">
            <ul>
                <?php echo getMenu(); ?>
            </ul>
            <span class="midden">

                hier komt een afbeelding

                <form id="InlogForm" method="get" action="verwerk.php">

                    Gebruikersnaam: <input type="text" name="voornaam"><br/>
                    <br/>
                    Wachtwoord: <input type="text" name="achternaam"><br/>
                    <br/>
                    Wachtwoord vergeten? Klik hier<br/>
                    <br/>
                    <input type="submit" value="Inloggen">
                </form>
            </span>




        </div>
        <?php
        // put your code here
        ?>
    </body>
</html>
