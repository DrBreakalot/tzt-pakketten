<?php
require_once 'helper/userhelper.php';
if (getUser()) {
    header('Location: ingelogdeUser.php');
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
            </span>
        </div>
        <div id="menu">  
            <ul>  
                <li><a href="index.php" title="Home">Home</a></li>  
                <li><a href="onzediensten.php" title="Onze diensten">Onze diensten</a></li>  
                <li><a href="mijn pakket" title="Mijn Pakket">Mijn Pakket</a></li>  
                <li><a href="contact.php" title="Contact">Contact</a></li> 
                <li><a href="treinkoerierStart.php" title="Treinkoerier">Treinkoerier</a></li>
            </ul>
            <div id="midden">


                <a href="inlogscherm.php">
                    <input id="Inloggen" type="button" value="Inloggen" />
                </a>
                <a href="Registratieformulier.php">
                    <input id="Registreren" type="button" value="Registreren" />
                </a>
                </a>

            </div>
        </div>
    </body>
</html>
