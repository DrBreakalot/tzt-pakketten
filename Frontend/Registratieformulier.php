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

            <span id="rcorners2">
                <a href=""><img src="magnifier_16.png" width="16" height="16"></a>
                search
            </span>
        </div>
        <div id="menu">  
            <ul>  
                <li><a href="index.php" title="Home">Home</a></li>  
                <li><a href="onze diensten" title="Onze diensten">Onze diensten</a></li>  
                <li><a href="mijn pakket" title="Mijn Pakket">Mijn Pakket</a></li>  
                <li><a href="contact" title="Contact">Contact</a></li> 

            </ul>
            <span id="middenRegistratieScherm">
                <form id="registratieForm" method="post" action="registreer.php">
                    <label for "naam">Naam</label>
                    <input type="text" name="voornaam"><br/><br/>
                    <label for "achternaam">Achternaam</label>
                    <input type="text" name="achternaam"><br/><br/>
                    <label for "gebruikersnaam">Gebruikersnaam</label>
                    <input type="text" name="plaatsnaam"><br/><br/>
                    <label for "wachtwoord">Wachtwoord</label>
                    <input type="text" name="achternaam"><br/><br/>
                    <label for "postcode">Postcode</label>
                    <input type="text" name="huisnummer"><br/><br/>
                    <label for "huisnummer">Huisnummer</label>
                    <input type="text" name="huisnummer"><br/><br/>
                    <label for "e-mailadres">E-mailadres</label>
                    <input type="text" name="e-mailadres"><br/><br/>
                    <label for "telefoonnummer">Telefoonnummer</label>
                    <input type="text" name="telefoonnummer"><br/><br/>
                    <input id="registreren2" type="submit" value="Registreer!">
                </form>
            </span>







        </div>



        <?php
        // put your code here
        ?>

    </body>
</html>


