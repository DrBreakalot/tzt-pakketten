<?php
require_once('helper/userhelper.php');
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
                <?php echo getMenu(); ?>
            </ul>
            <div class="midden">
                <div id="registratieGereed">
                Uw registratie is klaar! U kunt nu <a href="inlogscherm.php" title="inlogscherm">inloggen!</a>
                <!--<link id="naarInlogscherm" type="submit" value="Inloggen!"/>-->
                <a href="inlogscherm.php">
                    <input id="InloggenRegistratieGereed" type="button" value="Inloggen" />
                </a>
                </div>
            </div>
        </div>
    </body>
</html>